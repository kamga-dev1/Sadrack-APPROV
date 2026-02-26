const express = require('express');
const router  = express.Router();
const db      = require('../config/db');

// GET /api/articles
router.get('/', async (req, res) => {
  try {
    const [rows] = await db.query(
      `SELECT a.*, f.nom AS fournisseur_nom, c.nom AS categorie_nom
       FROM articles a
       LEFT JOIN fournisseurs f ON f.id_fournisseur = a.id_fournisseur
       LEFT JOIN categories   c ON c.id_categorie   = a.id_categorie
       ORDER BY a.nom`
    );
    res.json({ success: true, data: rows });
  } catch (err) {
    res.status(500).json({ success: false, message: err.message });
  }
});

// GET /api/articles/alertes — articles sous seuil minimum
router.get('/alertes', async (req, res) => {
  try {
    const [rows] = await db.query('SELECT * FROM v_articles_alerte');
    res.json({ success: true, data: rows });
  } catch (err) {
    res.status(500).json({ success: false, message: err.message });
  }
});

// GET /api/articles/:id
router.get('/:id', async (req, res) => {
  try {
    const [rows] = await db.query(
      `SELECT a.*, f.nom AS fournisseur_nom, c.nom AS categorie_nom
       FROM articles a
       LEFT JOIN fournisseurs f ON f.id_fournisseur = a.id_fournisseur
       LEFT JOIN categories   c ON c.id_categorie   = a.id_categorie
       WHERE a.id_article = ?`,
      [req.params.id]
    );
    if (rows.length === 0)
      return res.status(404).json({ success: false, message: 'Article introuvable' });
    res.json({ success: true, data: rows[0] });
  } catch (err) {
    res.status(500).json({ success: false, message: err.message });
  }
});

// POST /api/articles
router.post('/', async (req, res) => {
  const { id_fournisseur, id_categorie, reference, nom, description, unite, prix_achat, stock_actuel, seuil_minimum, delai_approvisionnement } = req.body;
  try {
    const [result] = await db.query(
      `INSERT INTO articles
        (id_fournisseur, id_categorie, reference, nom, description, unite, prix_achat, stock_actuel, seuil_minimum, delai_approvisionnement)
       VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)`,
      [id_fournisseur, id_categorie, reference, nom, description, unite, prix_achat, stock_actuel || 0, seuil_minimum || 0, delai_approvisionnement]
    );
    res.status(201).json({ success: true, id: result.insertId, message: 'Article créé avec succès' });
  } catch (err) {
    res.status(500).json({ success: false, message: err.message });
  }
});

// PUT /api/articles/:id
router.put('/:id', async (req, res) => {
  const { nom, description, unite, prix_achat, seuil_minimum, delai_approvisionnement, statut } = req.body;
  try {
    await db.query(
      `UPDATE articles SET
        nom=?, description=?, unite=?, prix_achat=?,
        seuil_minimum=?, delai_approvisionnement=?, statut=?
       WHERE id_article=?`,
      [nom, description, unite, prix_achat, seuil_minimum, delai_approvisionnement, statut, req.params.id]
    );
    res.json({ success: true, message: 'Article mis à jour' });
  } catch (err) {
    res.status(500).json({ success: false, message: err.message });
  }
});

// DELETE /api/articles/:id
router.delete('/:id', async (req, res) => {
  try {
    await db.query(
      "UPDATE articles SET statut='inactif' WHERE id_article=?",
      [req.params.id]
    );
    res.json({ success: true, message: 'Article désactivé' });
  } catch (err) {
    res.status(500).json({ success: false, message: err.message });
  }
});

module.exports = router;