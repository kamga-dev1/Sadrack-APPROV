<?php
$_cur  = basename($_SERVER['PHP_SELF']);
$_r2   = $_SESSION['user']['role'] ?? '';
$_n2   = htmlspecialchars($_SESSION['user']['nom'] ?? '');
$_i2   = strtoupper(mb_substr($_SESSION['user']['nom'] ?? 'U', 0, 1));
function _si($p,$c){return $p===$c?' on':'';}
?>
<aside class="sidebar" id="sidebar">
  <div class="s-section" style="padding-top:10px;">
    <a href="dashboard.php" class="s-item<?= _si('dashboard.php',$_cur) ?>"><i class="fas fa-chart-line"></i>Tableau de bord</a>
  </div>
  <div class="s-section">
    <span class="s-label">Gestion Achats</span>
    <a href="fournisseurs.php" class="s-item<?= _si('fournisseurs.php',$_cur) ?>"><i class="fas fa-building"></i>Fournisseurs</a>
    <a href="articles.php" class="s-item<?= _si('articles.php',$_cur) ?>"><i class="fas fa-cube"></i>Articles<span class="s-badge" id="side-badge" style="display:none"></span></a>
    <a href="commandes.php" class="s-item<?= _si('commandes.php',$_cur) ?>"><i class="fas fa-file-contract"></i>Commandes</a>
  </div>
  <div class="s-section">
    <span class="s-label">Logistique</span>
    <a href="receptions.php" class="s-item<?= _si('receptions.php',$_cur) ?>"><i class="fas fa-truck-ramp-box"></i>Réceptions</a>
    <a href="stock.php" class="s-item<?= _si('stock.php',$_cur) ?>"><i class="fas fa-warehouse"></i>Stock</a>
  </div>
  <div class="s-section">
    <span class="s-label">Finance</span>
    <a href="paiements.php" class="s-item<?= _si('paiements.php',$_cur) ?>"><i class="fas fa-receipt"></i>Paiements</a>
  </div>
  <?php if($_r2==='admin'): ?>
  <div class="s-section">
    <span class="s-label">Administration</span>
    <a href="utilisateurs.php" class="s-item<?= _si('utilisateurs.php',$_cur) ?>"><i class="fas fa-users-gear"></i>Utilisateurs</a>
  </div>
  <?php endif; ?>
  <div class="s-foot">
    <div class="s-foot-card">
      <div class="s-foot-av"><?= $_i2 ?></div>
      <div><div class="s-foot-name"><?= $_n2 ?></div><div class="s-foot-role"><?= $_r2 ?></div></div>
    </div>
  </div>
</aside>

<div class="page">
<div id="alert-zone"></div>