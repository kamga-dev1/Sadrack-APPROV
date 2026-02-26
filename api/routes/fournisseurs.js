const express = require('express');
const router  = express.Router();
const db      = require('../config/db');

// GET /api/fournisseurs
router.get('/', async (req, res) => {
  try {
    const [rows] = await db.query(
      'SELECT * FROM fournisseurs ORDER BY nom'
    );
    res.json({ success: true, data: rows });
  } catch (err) {
    res.status(500).json({ success: false, message: err.message });
  }
});

// GET /api/fournisseurs/:id
router.get('/:id', async (req, res) => {
  try {
    const [rows] = await db.query(
      'SELECT * FROM fournisseurs WHERE id_fournisseur = ?',
      [req.params.id]
    );
    if (rows.length === 0)
      return res.status(404).json({ success: false, message: 'Fournisseur introuvable' });
    res.json({ success: true, data: rows[0] });
  } catch (err) {
    res.status(500).json({ success: false, message: err.message });
  }
});

// POST /api/fournisseurs
router.post('/', async (req, res) => {
  const { code, nom, contact_nom, telephone, email, adresse, conditions_paiement, delai_livraison_moyen, note } = req.body;
  try {
    const [result] = await db.query(
      `INSERT INTO fournisseurs 
        (code, nom, contact_nom, telephone, email, adresse, conditions_paiement, delai_livraison_moyen, note)
       VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)`,
      [code, nom, contact_nom, telephone, email, adresse, conditions_paiement, delai_livraison_moyen, note]
    );
    res.status(201).json({ success: true, id: result.insertId, message: 'Fournisseur créé avec succès' });
  } catch (err) {
    res.status(500).json({ success: false, message: err.message });
  }
});

// PUT /api/fournisseurs/:id
router.put('/:id', async (req, res) => {
  const { nom, contact_nom, telephone, email, adresse, conditions_paiement, delai_livraison_moyen, note, statut } = req.body;
  try {
    await db.query(
      `UPDATE fournisseurs SET
        nom=?, contact_nom=?, telephone=?, email=?, adresse=?,
        conditions_paiement=?, delai_livraison_moyen=?, note=?, statut=?
       WHERE id_fournisseur=?`,
      [nom, contact_nom, telephone, email, adresse, conditions_paiement, delai_livraison_moyen, note, statut, req.params.id]
    );
    res.json({ success: true, message: 'Fournisseur mis à jour' });
  } catch (err) {
    res.status(500).json({ success: false, message: err.message });
  }
});

// DELETE /api/fournisseurs/:id
router.delete('/:id', async (req, res) => {
  try {
    await db.query(
      "UPDATE fournisseurs SET statut='inactif' WHERE id_fournisseur=?",
      [req.params.id]
    );
    res.json({ success: true, message: 'Fournisseur désactivé' });
  } catch (err) {
    res.status(500).json({ success: false, message: err.message });
  }
});

module.exports = router;