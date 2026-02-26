<?php
$current_page = basename($_SERVER['PHP_SELF']);
$role = $_SESSION['user']['role'] ?? '';

function isActive(string $page, string $current): string {
    return $page === $current ? 'active' : '';
}
?>

<nav id="sidebar" class="sidebar">
    <div class="sidebar-sticky">

        <!-- MENU PRINCIPAL -->
        <ul class="nav flex-column pt-2">

            <li class="nav-item">
                <a class="nav-link <?= isActive('dashboard.php', $current_page) ?>" href="/php/dashboard.php">
                    <i class="fas fa-gauge-high me-2"></i>
                    Tableau de bord
                </a>
            </li>

            <li class="sidebar-divider">ACHATS</li>

            <li class="nav-item">
                <a class="nav-link <?= isActive('fournisseurs.php', $current_page) ?>" href="/php/fournisseurs.php">
                    <i class="fas fa-building me-2"></i>
                    Fournisseurs
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?= isActive('articles.php', $current_page) ?>" href="/php/articles.php">
                    <i class="fas fa-box me-2"></i>
                    Articles
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?= isActive('commandes.php', $current_page) ?>" href="/php/commandes.php">
                    <i class="fas fa-file-invoice me-2"></i>
                    Commandes
                </a>
            </li>

            <li class="sidebar-divider">LOGISTIQUE</li>

            <li class="nav-item">
                <a class="nav-link <?= isActive('receptions.php', $current_page) ?>" href="/php/receptions.php">
                    <i class="fas fa-truck me-2"></i>
                    Réceptions
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?= isActive('stock.php', $current_page) ?>" href="/php/stock.php">
                    <i class="fas fa-warehouse me-2"></i>
                    Stock
                </a>
            </li>

            <li class="sidebar-divider">FINANCE</li>

            <li class="nav-item">
                <a class="nav-link <?= isActive('paiements.php', $current_page) ?>" href="/php/paiements.php">
                    <i class="fas fa-money-bill-wave me-2"></i>
                    Paiements
                </a>
            </li>

            <?php if ($role === 'admin'): ?>
            <li class="sidebar-divider">ADMINISTRATION</li>

            <li class="nav-item">
                <a class="nav-link <?= isActive('utilisateurs.php', $current_page) ?>" href="/php/utilisateurs.php">
                    <i class="fas fa-users-gear me-2"></i>
                    Utilisateurs
                </a>
            </li>
            <?php endif; ?>

        </ul>
    </div>
</nav>

<!-- CONTENU PRINCIPAL -->
<main class="main-content flex-grow-1">
    <!-- Zone alertes -->
    <div id="alert-zone" class="px-4 pt-3"></div>