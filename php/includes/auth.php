<?php
/**
 * ════════════════════════════════════════════════════════════════
 *  includes/auth.php  —  Contrôle d'accès centralisé
 *  Application : Gestion d'Approvisionnement
 * ════════════════════════════════════════════════════════════════
 *
 *  RÔLES disponibles (valeurs exactes en base) :
 *    - admin        → accès total
 *    - gestionnaire → gestion opérationnelle (pas les utilisateurs)
 *    - magasinier   → stocks & réceptions uniquement (lecture limitée)
 *
 *  UTILISATION dans chaque page PHP :
 * ────────────────────────────────────────────────────────────────
 *  require_once 'includes/auth.php';
 *
 *  // 1) Vérifier connexion + rôle minimum pour accéder à la page
 *  requireRole(['admin','gestionnaire']);   // redirige si non autorisé
 *
 *  // 2) Récupérer le rôle courant dans PHP
 *  $role = currentRole();   // 'admin' | 'gestionnaire' | 'magasinier'
 *  $uid  = currentUserId();
 *
 *  // 3) Injecter les permissions dans le JS de la page
 *  injectPermissions();     // crée window.PERMS = { canCreate, canEdit, canDelete, canChangeStatut, isAdmin, role }
 *
 *  // 4) Vérifier une permission précise en PHP (pour afficher/cacher du HTML)
 *  if(can('create')) { ... }
 * ────────────────────────────────────────────────────────────────
 */

/* ── Sécurité : ne pas inclure directement ── */
if(!defined('APP_ROOT') && basename($_SERVER['PHP_SELF']) === 'auth.php') {
    http_response_code(403); exit('Accès direct interdit.');
}

/* ════════════════════════════════════════
   MATRICE DES PERMISSIONS PAR RÔLE
   ════════════════════════════════════════
   Chaque rôle possède un tableau de capacités booléennes.
   Ajouter une capacité ici suffit pour qu'elle soit disponible
   partout via can() et window.PERMS en JS.
*/
define('PERMISSIONS', [
    'admin' => [
        'canCreate'       => true,
        'canEdit'         => true,
        'canDelete'       => true,
        'canChangeStatut' => true,
        'canViewAll'      => true,
        'isAdmin'         => true,
    ],
    'gestionnaire' => [
        'canCreate'       => true,
        'canEdit'         => true,
        'canDelete'       => false,   // suppression réservée admin
        'canChangeStatut' => true,
        'canViewAll'      => true,
        'isAdmin'         => false,
    ],
    'magasinier' => [
        'canCreate'       => false,
        'canEdit'         => false,
        'canDelete'       => false,
        'canChangeStatut' => false,
        'canViewAll'      => false,
        'isAdmin'         => false,
    ],
]);

/* ════════════════════════════════════════
   ACCÈS AUX PAGES (liste blanche par page)
   Clé = nom du fichier PHP sans extension
   Valeur = tableau des rôles autorisés
   ════════════════════════════════════════ */
define('PAGE_ROLES', [
    'dashboard'    => ['admin', 'gestionnaire', 'magasinier'],
    'articles'     => ['admin', 'gestionnaire', 'magasinier'],
    'commandes'    => ['admin', 'gestionnaire'],
    'fournisseurs' => ['admin', 'gestionnaire'],
    'paiements'    => ['admin', 'gestionnaire'],
    'receptions'   => ['admin', 'gestionnaire', 'magasinier'],
    'stocks'       => ['admin', 'gestionnaire', 'magasinier'],
    'utilisateurs' => ['admin'],
]);


/* ════════════════════════════════════════
   FONCTIONS UTILITAIRES
   ════════════════════════════════════════ */

/**
 * Retourne le rôle de l'utilisateur connecté.
 * Renvoi 'magasinier' par défaut (rôle le moins privilégié) si absent.
 */
function currentRole(): string {
    return $_SESSION['user']['role'] ?? 'magasinier';
}

/**
 * Retourne l'ID de l'utilisateur connecté.
 */
function currentUserId(): int {
    return (int)($_SESSION['user']['id'] ?? $_SESSION['user']['id_utilisateur'] ?? 0);
}

/**
 * Vérifie si l'utilisateur connecté possède une permission précise.
 * Ex : can('canCreate'), can('canDelete')
 */
function can(string $perm): bool {
    $role  = currentRole();
    $perms = PERMISSIONS[$role] ?? [];
    return (bool)($perms[$perm] ?? false);
}

/**
 * Redirige vers dashboard.php avec un message d'erreur si l'utilisateur
 * n'a pas l'un des rôles requis.
 *
 * @param array $roles  Liste des rôles autorisés. Ex: ['admin','gestionnaire']
 *                      Passer [] ou omettre = juste vérifier la connexion.
 */
function requireRole(array $roles = []): void {
    /* 1. Vérifier la connexion */
    if (empty($_SESSION['user'])) {
        header('Location: login.php');
        exit;
    }
    /* 2. Vérifier le rôle si des restrictions sont définies */
    if (!empty($roles) && !in_array(currentRole(), $roles, true)) {
        /* Stocker un message flash pour l'afficher sur le dashboard */
        $_SESSION['flash_error'] = 'Accès refusé. Vous n\'avez pas les droits pour accéder à cette page.';
        header('Location: dashboard.php');
        exit;
    }
}

/**
 * Vérifie automatiquement les droits en fonction du nom de la page courante.
 * À appeler sans argument, détecte le fichier PHP courant.
 */
function requirePageAccess(): void {
    $page = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
    $allowed = PAGE_ROLES[$page] ?? ['admin']; // par défaut admin only
    requireRole($allowed);
}

/**
 * Injecte un objet JS window.PERMS avec toutes les permissions du rôle courant.
 * À appeler dans le <body> de chaque page, juste après les includes sidebar/header.
 *
 * Génère :
 *   window.PERMS = {
 *     role: "gestionnaire",
 *     canCreate: true,
 *     canEdit: true,
 *     canDelete: false,
 *     canChangeStatut: true,
 *     canViewAll: true,
 *     isAdmin: false
 *   };
 */
function injectPermissions(): void {
    $role  = currentRole();
    $perms = PERMISSIONS[$role] ?? PERMISSIONS['magasinier'];
    $perms['role'] = $role;
    $json  = json_encode($perms, JSON_THROW_ON_ERROR);
    echo "<script>window.PERMS = {$json};</script>\n";
}

/**
 * Retourne une classe CSS 'hidden' si la permission est absente.
 * Pratique pour masquer des boutons HTML directement en PHP.
 * Ex : <button class="btn-gold <?= hiddenUnless('canCreate') ?>">
 */
function hiddenUnless(string $perm): string {
    return can($perm) ? '' : 'perm-hidden';
}

/**
 * Affiche (et vide) le message flash stocké en session.
 * À placer dans le dashboard ou dans le header.
 */
function renderFlash(): void {
    if (!empty($_SESSION['flash_error'])) {
        $msg = htmlspecialchars($_SESSION['flash_error']);
        unset($_SESSION['flash_error']);
        echo <<<HTML
        <div class="flash-error" id="flash-err">
          <i class="fas fa-shield-halved"></i>
          <span>{$msg}</span>
          <button onclick="this.parentElement.remove()"><i class="fas fa-xmark"></i></button>
        </div>
        <style>
          .flash-error{
            display:flex;align-items:center;gap:10px;
            background:#fff0f0;border:1px solid #f8b8b8;border-radius:10px;
            padding:12px 16px;margin-bottom:18px;
            color:#c03030;font-size:.88rem;font-weight:600;
            animation:flashIn .3s ease;
          }
          @keyframes flashIn{from{opacity:0;transform:translateY(-8px);}to{opacity:1;transform:none;}}
          .flash-error button{margin-left:auto;background:none;border:none;cursor:pointer;color:#c03030;font-size:.85rem;padding:2px 6px;}
        </style>
        HTML;
    }
}