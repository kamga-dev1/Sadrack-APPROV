<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Approvisionnement</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Style personnalisé -->
    <link href="/assets/css/style.css" rel="stylesheet">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top" style="background-color: #1A5276;">
    <div class="container-fluid">

        <!-- Logo + Titre -->
        <a class="navbar-brand fw-bold" href="/php/dashboard.php">
            <i class="fas fa-warehouse me-2"></i>
            GestionApprov
        </a>

        <!-- Bouton toggle mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">

                <!-- Alertes stock -->
                <li class="nav-item me-2">
                    <a class="nav-link position-relative" href="/php/articles.php" title="Alertes stock">
                        <i class="fas fa-triangle-exclamation fa-lg text-warning"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="badge-alertes">
                            0
                        </span>
                    </a>
                </li>

                <!-- Utilisateur connecté -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle me-1"></i>
                        <?php echo htmlspecialchars($_SESSION['user']['nom'] ?? 'Utilisateur'); ?>
                        <span class="badge bg-secondary ms-1">
                            <?php echo htmlspecialchars($_SESSION['user']['role'] ?? ''); ?>
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <span class="dropdown-item-text text-muted small">
                                <?php echo htmlspecialchars($_SESSION['user']['email'] ?? ''); ?>
                            </span>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-danger" href="/php/logout.php">
                                <i class="fas fa-right-from-bracket me-2"></i>Déconnexion
                            </a>
                        </li>
                    </ul>
                </li>

            </ul>
        </div>
    </div>
</nav>

<!-- WRAPPER -->
<div class="d-flex" style="margin-top: 56px;">