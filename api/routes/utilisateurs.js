const express = require('express');
const router  = express.Router();
const bcrypt  = require('bcryptjs');
const db      = require('../config/db');

// GET /api/utilisateurs
router.get('/', async (req, res) => {
  try {
    const [rows] = await db.query(
      `SELECT id_utilisateur, nom, email, role, statut, 
              derniere_connexion, created_at
       FROM utilisateurs 
       ORDER BY nom`
    );
    res.json({ success: true, data: rows });
  } catch (err) {
    res.status(500).json({ success: false, message: err.message });
  }
});

// GET /api/utilisateurs/:id
router.get('/:id', async (req, res) => {
  try {
    const [rows] = await db.query(
      `SELECT id_utilisateur, nom, email, role, statut, 
              derniere_connexion, created_at
       FROM utilisateurs 
       WHERE id_utilisateur = ?`,
      [req.params.id]
    );
    if (rows.length === 0)
      return res.status(404).json({ success: false, message: 'Utilisateur introuvable' });
    res.json({ success: true, data: rows[0] });
  } catch (err) {
    res.status(500).json({ success: false, message: err.message });
  }
});

// POST /api/utilisateurs
router.post('/', async (req, res) => {
  const { nom, email, mot_de_passe, role } = req.body;

  if (!nom || !email || !mot_de_passe || !role)
    return res.status(400).json({ success: false, message: 'Tous les champs sont requis' });

  try {
    // Vérifier si email déjà utilisé
    const [exist] = await db.query(
      'SELECT id_utilisateur FROM utilisateurs WHERE email = ?',
      [email]
    );
    if (exist.length > 0)
      return res.status(400).json({ success: false, message: 'Email déjà utilisé' });

    // Hasher le mot de passe
    const hash = await bcrypt.hash(mot_de_passe, 12);

    const [result] = await db.query(
      `INSERT INTO utilisateurs (nom, email, mot_de_passe, role)
       VALUES (?, ?, ?, ?)`,
      [nom, email, hash, role]
    );
    res.status(201).json({ success: true, id: result.insertId, message: 'Utilisateur créé avec succès' });
  } catch (err) {
    res.status(500).json({ success: false, message: err.message });
  }
});

// PUT /api/utilisateurs/:id
router.put('/:id', async (req, res) => {
  const { nom, email, role, statut } = req.body;
  try {
    await db.query(
      `UPDATE utilisateurs SET nom=?, email=?, role=?, statut=?
       WHERE id_utilisateur=?`,
      [nom, email, role, statut, req.params.id]
    );
    res.json({ success: true, message: 'Utilisateur mis à jour' });
  } catch (err) {
    res.status(500).json({ success: false, message: err.message });
  }
});

// PUT /api/utilisateurs/:id/password
router.put('/:id/password', async (req, res) => {
  const { mot_de_passe } = req.body;
  try {
    const hash = await bcrypt.hash(mot_de_passe, 12);
    await db.query(
      'UPDATE utilisateurs SET mot_de_passe=? WHERE id_utilisateur=?',
      [hash, req.params.id]
    );
    res.json({ success: true, message: 'Mot de passe mis à jour' });
  } catch (err) {
    res.status(500).json({ success: false, message: err.message });
  }
});

// DELETE /api/utilisateurs/:id
router.delete('/:id', async (req, res) => {
  try {
    await db.query(
      "UPDATE utilisateurs SET statut='inactif' WHERE id_utilisateur=?",
      [req.params.id]
    );
    res.json({ success: true, message: 'Utilisateur désactivé' });
  } catch (err) {
    res.status(500).json({ success: false, message: err.message });
  }
});

module.exports = router;