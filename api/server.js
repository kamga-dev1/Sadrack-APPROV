require('dotenv').config();
const express = require('express');
const cors    = require('cors');
const app     = express();

app.use(cors());
app.use(express.json());

// Routes
app.use('/api/auth', require('./routes/auth'));
app.use('/api/fournisseurs', require('./routes/fournisseurs'));
app.use('/api/articles',     require('./routes/articles'));
app.use('/api/commandes',    require('./routes/commandes'));
app.use('/api/receptions',   require('./routes/receptions'));
app.use('/api/paiements',    require('./routes/paiements'));
app.use('/api/stock',        require('./routes/stocks'));
app.use('/api/dashboard',    require('./routes/dashboard'));
app.use('/api/utilisateurs',  require('./routes/utilisateurs'));

// Test route
app.get('/', (req, res) => {
  res.json({ message: 'API Gestion Approvisionnement opérationnelle ✅' });
});

const PORT = process.env.PORT || 3000;
app.listen(PORT, () => {
  console.log(`✅ API démarrée sur http://localhost:${PORT}`);
});
