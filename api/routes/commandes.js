const express = require('express');
const router  = express.Router();
const db      = require('../config/db');

// GET /api/commandes
router.get('/', async (req, res) => {
  try {
    const [rows] = await db.query(
      'SELECT * FROM v_commandes_detail ORDER BY date_commande DESC'
    );
    res.json({ success: true, data: rows });
  } catch (err) {
    res.status(500).json({ success: false, message: err.message });
  }
});

// GET /api/commandes/:id
router.get('/:id', async (req, res) => {
  try {
    const [commande] = await db.query(
      'SELECT * FROM v_commandes_detail WHERE id_commande = ?',
      [req.params.id]
    );
    if (commande.length === 0)
      return res.status(404).json({ success: false, message: 'Commande introuvable' });

    const [lignes] = await db.query(
      `SELECT lc.*, a.nom AS article_nom, a.reference, a.unite
       FROM lignes_commande lc
       JOIN articles a ON a.id_article = lc.id_article
       WHERE lc.id_commande = ?`,
      [req.params.id]
    );
    res.json({ success: true, data: { ...commande[0], lignes } });
  } catch (err) {
    res.status(500).json({ success: false, message: err.message });
  }
});

// POST /api/commandes
router.post('/', async (req, res) => {
  const { id_fournisseur, id_utilisateur, date_commande, date_prevue_livraison, notes, lignes } = req.body;
  const conn = await db.getConnection();
  try {
    await conn.beginTransaction();

    // Générer numéro commande
    const year = new Date().getFullYear();
    const [last] = await conn.query(
      "SELECT COUNT(*) AS total FROM commandes WHERE YEAR(date_commande) = ?",
      [year]
    );
    const numero = `CMD-${year}-${String(last[0].total + 1).padStart(4, '0')}`;

    // Créer la commande
    const [result] = await conn.query(
      `INSERT INTO commandes
        (id_fournisseur, id_utilisateur, numero_commande, date_commande, date_prevue_livraison, notes)
       VALUES (?, ?, ?, ?, ?, ?)`,
      [id_fournisseur, id_utilisateur, numero, date_commande, date_prevue_livraison, notes]
    );
    const id_commande = result.insertId;

    // Insérer les lignes
    for (const ligne of lignes) {
      const total_ht = ligne.quantite * ligne.prix_unitaire;
      await conn.query(
        `INSERT INTO lignes_commande (id_commande, id_article, quantite, prix_unitaire, total_ht)
         VALUES (?, ?, ?, ?, ?)`,
        [id_commande, ligne.id_article, ligne.quantite, ligne.prix_unitaire, total_ht]
      );
    }

    await conn.commit();
    res.status(201).json({ success: true, id: id_commande, numero, message: 'Commande créée avec succès' });
  } catch (err) {
    await conn.rollback();
    res.status(500).json({ success: false, message: err.message });
  } finally {
    conn.release();
  }
});

// PUT /api/commandes/:id/statut
router.put('/:id/statut', async (req, res) => {
  const { statut } = req.body;
  try {
    await db.query(
      'UPDATE commandes SET statut=? WHERE id_commande=?',
      [statut, req.params.id]
    );
    res.json({ success: true, message: 'Statut mis à jour' });
  } catch (err) {
    res.status(500).json({ success: false, message: err.message });
  }
});

module.exports = router;