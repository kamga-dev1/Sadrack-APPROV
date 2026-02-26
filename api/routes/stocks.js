const express = require('express');
const router  = express.Router();
const db      = require('../config/db');

// GET /api/stock/mouvements
router.get('/mouvements', async (req, res) => {
  try {
    const [rows] = await db.query(
      `SELECT ms.*, a.reference, a.nom AS article_nom, u.nom AS utilisateur_nom
       FROM mouvements_stock ms
       JOIN articles      a ON a.id_article     = ms.id_article
       JOIN utilisateurs  u ON u.id_utilisateur = ms.id_utilisateur
       ORDER BY ms.date_mouvement DESC
       LIMIT 100`
    );
    res.json({ success: true, data: rows });
  } catch (err) {
    res.status(500).json({ success: false, message: err.message });
  }
});

// GET /api/stock/mouvements/:id_article
router.get('/mouvements/:id_article', async (req, res) => {
  try {
    const [rows] = await db.query(
      `SELECT ms.*, a.reference, a.nom AS article_nom, u.nom AS utilisateur_nom
       FROM mouvements_stock ms
       JOIN articles      a ON a.id_article     = ms.id_article
       JOIN utilisateurs  u ON u.id_utilisateur = ms.id_utilisateur
       WHERE ms.id_article = ?
       ORDER BY ms.date_mouvement DESC`,
      [req.params.id_article]
    );
    res.json({ success: true, data: rows });
  } catch (err) {
    res.status(500).json({ success: false, message: err.message });
  }
});

// POST /api/stock/ajustement
router.post('/ajustement', async (req, res) => {
  const { id_article, id_utilisateur, quantite, type_mouvement, motif } = req.body;
  const conn = await db.getConnection();
  try {
    await conn.beginTransaction();

    const [article] = await conn.query(
      'SELECT stock_actuel FROM articles WHERE id_article = ?',
      [id_article]
    );
    if (article.length === 0)
      return res.status(404).json({ success: false, message: 'Article introuvable' });

    const stock_avant = article[0].stock_actuel;
    const stock_apres = type_mouvement === 'entree'
      ? stock_avant + quantite
      : stock_avant - quantite;

    if (stock_apres < 0)
      return res.status(400).json({ success: false, message: 'Stock insuffisant' });

    await conn.query(
      'UPDATE articles SET stock_actuel = ? WHERE id_article = ?',
      [stock_apres, id_article]
    );

    await conn.query(
      `INSERT INTO mouvements_stock
        (id_article, id_utilisateur, type_mouvement, quantite, stock_avant, stock_apres, motif)
       VALUES (?, ?, ?, ?, ?, ?, ?)`,
      [id_article, id_utilisateur, type_mouvement, quantite, stock_avant, stock_apres, motif]
    );

    await conn.commit();
    res.json({ success: true, stock_avant, stock_apres, message: 'Ajustement effectué' });
  } catch (err) {
    await conn.rollback();
    res.status(500).json({ success: false, message: err.message });
  } finally {
    conn.release();
  }
});

module.exports = router;