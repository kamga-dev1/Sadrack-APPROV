const express = require('express');
const router  = express.Router();
const db      = require('../config/db');

// GET /api/receptions
router.get('/', async (req, res) => {
  try {
    const [rows] = await db.query(
      `SELECT r.*, c.numero_commande, f.nom AS fournisseur_nom, u.nom AS receptionnaire
       FROM receptions r
       JOIN commandes    c ON c.id_commande    = r.id_commande
       JOIN fournisseurs f ON f.id_fournisseur = c.id_fournisseur
       JOIN utilisateurs u ON u.id_utilisateur = r.id_utilisateur
       ORDER BY r.date_reception DESC`
    );
    res.json({ success: true, data: rows });
  } catch (err) {
    res.status(500).json({ success: false, message: err.message });
  }
});

// GET /api/receptions/:id
router.get('/:id', async (req, res) => {
  try {
    const [reception] = await db.query(
      `SELECT r.*, c.numero_commande, f.nom AS fournisseur_nom
       FROM receptions r
       JOIN commandes    c ON c.id_commande    = r.id_commande
       JOIN fournisseurs f ON f.id_fournisseur = c.id_fournisseur
       WHERE r.id_reception = ?`,
      [req.params.id]
    );
    if (reception.length === 0)
      return res.status(404).json({ success: false, message: 'Réception introuvable' });

    const [lignes] = await db.query(
      `SELECT lr.*, a.nom AS article_nom, a.reference, a.unite
       FROM lignes_reception lr
       JOIN articles a ON a.id_article = lr.id_article
       WHERE lr.id_reception = ?`,
      [req.params.id]
    );
    res.json({ success: true, data: { ...reception[0], lignes } });
  } catch (err) {
    res.status(500).json({ success: false, message: err.message });
  }
});

// POST /api/receptions
router.post('/', async (req, res) => {
  const { id_commande, id_utilisateur, date_reception, observation, lignes } = req.body;
  const conn = await db.getConnection();
  try {
    await conn.beginTransaction();

    // Vérifier si réception complète ou partielle
    const totalEcart = lignes.reduce((sum, l) => sum + (l.quantite_commandee - l.quantite_recue), 0);
    const statut = totalEcart === 0 ? 'complete' : 'partielle';

    // Créer la réception
    const [result] = await conn.query(
      `INSERT INTO receptions (id_commande, id_utilisateur, date_reception, statut, observation)
       VALUES (?, ?, ?, ?, ?)`,
      [id_commande, id_utilisateur, date_reception, statut, observation]
    );
    const id_reception = result.insertId;

    // Insérer les lignes (le trigger met à jour le stock automatiquement)
    for (const ligne of lignes) {
      await conn.query(
        `INSERT INTO lignes_reception
          (id_reception, id_article, quantite_commandee, quantite_recue, commentaire)
         VALUES (?, ?, ?, ?, ?)`,
        [id_reception, ligne.id_article, ligne.quantite_commandee, ligne.quantite_recue, ligne.commentaire || null]
      );
    }

    // Mettre à jour le statut de la commande
    const nouveauStatut = statut === 'complete' ? 'recue_totale' : 'recue_partielle';
    await conn.query(
      'UPDATE commandes SET statut=? WHERE id_commande=?',
      [nouveauStatut, id_commande]
    );

    await conn.commit();
    res.status(201).json({ success: true, id: id_reception, statut, message: 'Réception enregistrée avec succès' });
  } catch (err) {
    await conn.rollback();
    res.status(500).json({ success: false, message: err.message });
  } finally {
    conn.release();
  }
});

module.exports = router;