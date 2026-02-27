<?php
$current = basename($_SERVER['PHP_SELF']);
$role    = $_SESSION['user']['role'] ?? '';
function sa($page, $cur){ return $page === $cur ? ' active' : ''; }
?>
<!-- ══ SIDEBAR ══ -->
<aside class="sidebar">

  <div class="side-section">
    <a href="/SADRACK-APPROV/php/dashboard.php" class="side-item<?= sa('dashboard.php',$current) ?>">
      <i class="fas fa-gauge-high"></i>Tableau de bord
    </a>
  </div>

  <div class="side-section">
    <span class="side-label">Achats</span>
    <a href="/SADRACK-APPROV/php/fournisseurs.php" class="side-item<?= sa('fournisseurs.php',$current) ?>">
      <i class="fas fa-building"></i>Fournisseurs
    </a>
    <a href="/SADRACK-APPROV/php/articles.php" class="side-item<?= sa('articles.php',$current) ?>">
      <i class="fas fa-box"></i>Articles
      <span class="side-badge" id="side-alert-badge" style="display:none"></span>
    </a>
    <a href="/SADRACK-APPROV/php/commandes.php" class="side-item<?= sa('commandes.php',$current) ?>">
      <i class="fas fa-file-invoice"></i>Commandes
    </a>
  </div>

  <div class="side-section">
    <span class="side-label">Logistique</span>
    <a href="/SADRACK-APPROV/php/receptions.php" class="side-item<?= sa('receptions.php',$current) ?>">
      <i class="fas fa-truck-fast"></i>Réceptions
    </a>
    <a href="/SADRACK-APPROV/php/stock.php" class="side-item<?= sa('stock.php',$current) ?>">
      <i class="fas fa-warehouse"></i>Stock
    </a>
  </div>

  <div class="side-section">
    <span class="side-label">Finance</span>
    <a href="/SADRACK-APPROV/php/paiements.php" class="side-item<?= sa('paiements.php',$current) ?>">
      <i class="fas fa-money-bill-wave"></i>Paiements
    </a>
  </div>

  <?php if ($role === 'admin'): ?>
  <div class="side-section">
    <span class="side-label">Administration</span>
    <a href="/SADRACK-APPROV/php/utilisateurs.php" class="side-item<?= sa('utilisateurs.php',$current) ?>">
      <i class="fas fa-users-gear"></i>Utilisateurs
    </a>
  </div>
  <?php endif; ?>

</aside>

<!-- ══ MAIN ══ -->
<main class="main-content">
<div id="alert-zone"></div>