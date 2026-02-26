const express = require('express');
const router  = express.Router();
const db      = require('../config/db');

// GET /api/paiements
router.get('/', async (req, res) => {
  try {
    const [rows] = await db.query(
      `SELECT p.*, f.nom AS fournisseur_nom, c.numero_commande
       FROM paiements p
       JOIN fournisseurs f ON f.id_fournisseur = p.id_fournisseur
       LEFT JOIN commandes c ON c.id_commande  = p.id_commande
       ORDER BY p.date_echeance ASC`
    );
    res.json({ success: true, data: rows });
  } catch (err) {
    res.status(500).json({ success: false, message: err.message });
  }
});

// GET /api/paiements/urgents
router.get('/urgents', async (req, res) => {
  try {
    const [rows] = await db.query('SELECT * FROM v_paiements_urgents');
    res.json({ success: true, data: rows });
  } catch (err) {
    res.status(500).json({ success: false, message: err.message });
  }
});

// GET /api/paiements/:id
router.get('/:id', async (req, res) => {
  try {
    const [rows] = await db.query(
      `SELECT p.*, f.nom AS fournisseur_nom, c.numero_commande
       FROM paiements p
       JOIN fournisseurs f ON f.id_fournisseur = p.id_fournisseur
       LEFT JOIN commandes c ON c.id_commande  = p.id_commande
       WHERE p.id_paiement = ?`,
      [req.params.id]
    );
    if (rows.length === 0)
      return res.status(404).json({ success: false, message: 'Paiement introuvable' });
    res.json({ success: true, data: rows[0] });
  } catch (err) {
    res.status(500).json({ success: false, message: err.message });
  }
});

// POST /api/paiements
router.post('/', async (req, res) => {
  const { id_fournisseur, id_commande, id_utilisateur, numero_facture,
          date_facture, montant, date_echeance, mode_paiement, notes } = req.body;
  try {
    const [result] = await db.query(
      `INSERT INTO paiements
        (id_fournisseur, id_commande, id_utilisateur, numero_facture,
         date_facture, montant, date_echeance, mode_paiement, notes)
       VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)`,
      [id_fournisseur, id_commande, id_utilisateur, numero_facture,
       date_facture, montant, date_echeance, mode_paiement, notes]
    );
    res.status(201).json({ success: true, id: result.insertId, message: 'Paiement enregistré avec succès' });
  } catch (err) {
    res.status(500).json({ success: false, message: err.message });
  }
});

// PUT /api/paiements/:id/payer
router.put('/:id/payer', async (req, res) => {
  const { date_paiement, reference_paiement, mode_paiement } = req.body;
  try {
    await db.query(
      `UPDATE paiements SET
        statut='paye', date_paiement=?, reference_paiement=?, mode_paiement=?
       WHERE id_paiement=?`,
      [date_paiement, reference_paiement, mode_paiement, req.params.id]
    );
    res.json({ success: true, message: 'Paiement effectué avec succès' });
  } catch (err) {
    res.status(500).json({ success: false, message: err.message });
  }
});

module.exports = router;