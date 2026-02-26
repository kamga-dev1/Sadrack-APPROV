const express = require('express');
const router  = express.Router();
const db      = require('../config/db');

// GET /api/dashboard/kpis
router.get('/kpis', async (req, res) => {
  try {
    const [kpis] = await db.query('SELECT * FROM v_dashboard_kpis');
    res.json({ success: true, data: kpis[0] });
  } catch (err) {
    res.status(500).json({ success: false, message: err.message });
  }
});

// GET /api/dashboard/alertes
router.get('/alertes', async (req, res) => {
  try {
    const [alertes_stock]    = await db.query('SELECT * FROM v_articles_alerte');
    const [alertes_paiements]= await db.query('SELECT * FROM v_paiements_urgents');
    res.json({
      success: true,
      data: {
        stock:    alertes_stock,
        paiements: alertes_paiements
      }
    });
  } catch (err) {
    res.status(500).json({ success: false, message: err.message });
  }
});

// GET /api/dashboard/activite
router.get('/activite', async (req, res) => {
  try {
    const [dernieres_commandes] = await db.query(
      `SELECT * FROM v_commandes_detail
       ORDER BY date_commande DESC LIMIT 5`
    );
    const [derniers_mouvements] = await db.query(
      `SELECT ms.*, a.reference, a.nom AS article_nom
       FROM mouvements_stock ms
       JOIN articles a ON a.id_article = ms.id_article
       ORDER BY ms.date_mouvement DESC LIMIT 5`
    );
    res.json({
      success: true,
      data: {
        commandes:  dernieres_commandes,
        mouvements: derniers_mouvements
      }
    });
  } catch (err) {
    res.status(500).json({ success: false, message: err.message });
  }
});

module.exports = router;