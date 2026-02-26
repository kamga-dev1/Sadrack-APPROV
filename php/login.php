<?php
require_once 'includes/config.php';

if (!empty($_SESSION['user'])) {
    header('Location: dashboard.php');
    exit;
}

$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email']    ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!$email || !$password) {
        $erreur = 'Veuillez remplir tous les champs.';
    } else {
        try {
            $pdo  = new PDO(
                'mysql:host=127.0.0.1;port=3306;dbname=gestion_approvisionnement;charset=utf8mb4',
                'root', 'root',
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
            $stmt = $pdo->prepare('SELECT * FROM utilisateurs WHERE email = ? AND statut = ?');
            $stmt->execute([$email, 'actif']);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['mot_de_passe'])) {
                $_SESSION['user'] = [
                    'id'    => $user['id_utilisateur'],
                    'nom'   => $user['nom'],
                    'role'  => $user['role'],
                    'email' => $user['email'],
                ];
                $pdo->prepare('UPDATE utilisateurs SET derniere_connexion = NOW() WHERE id_utilisateur = ?')
                    ->execute([$user['id_utilisateur']]);
                header('Location: dashboard.php');
                exit;
            } else {
                $erreur = 'Email ou mot de passe incorrect.';
            }
        } catch (Exception $e) {
            $erreur = 'Erreur de connexion à la base de données.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion — GestionApprov</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,900;1,700&family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --ink:       #08101E;
            --ink2:      #0F1E34;
            --ink3:      #162742;
            --gold:      #C8943A;
            --gold2:     #E8B45A;
            --gold3:     #F5CC7A;
            --ivory:     #FAF8F4;
            --ivory2:    #F2EEE6;
            --text:      #1A2640;
            --muted:     #7A8799;
            --border:    #E0D8CC;
            --success:   #2D9E6B;
            --danger:    #C0392B;
            --r:         14px;
        }

        html, body { height: 100%; overflow: hidden; }

        body {
            font-family: 'DM Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            background: var(--ink);
        }

        /* ════════════════════════════════
           PANNEAU GAUCHE
        ════════════════════════════════ */
        .left {
            flex: 1;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 48px 64px 56px;
            overflow: hidden;
        }

        /* Canvas SVG animé en fond */
        .bg-canvas {
            position: absolute;
            inset: 0;
            z-index: 0;
        }

        /* Overlay gradient */
        .left-overlay {
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse 80% 60% at 20% 80%, rgba(200,148,58,0.12) 0%, transparent 60%),
                radial-gradient(ellipse 60% 50% at 85% 10%, rgba(22,39,66,0.9) 0%, transparent 55%),
                linear-gradient(160deg, #08101E 0%, #0D1B30 40%, #091526 100%);
            z-index: 1;
        }

        /* Grain texture overlay */
        .grain {
            position: absolute;
            inset: 0;
            z-index: 2;
            opacity: 0.035;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='1'/%3E%3C/svg%3E");
            background-size: 180px 180px;
        }

        /* Ligne décorative gauche */
        .deco-line {
            position: absolute;
            left: 0; top: 0; bottom: 0;
            width: 1px;
            background: linear-gradient(to bottom,
                transparent 0%,
                rgba(200,148,58,0.6) 30%,
                rgba(232,180,90,0.8) 50%,
                rgba(200,148,58,0.6) 70%,
                transparent 100%
            );
            z-index: 3;
        }

        /* Lignes décoratives horizontales subtiles */
        .h-lines {
            position: absolute;
            inset: 0;
            z-index: 2;
            background-image:
                linear-gradient(rgba(200,148,58,0.03) 1px, transparent 1px);
            background-size: 100% 80px;
        }

        /* Orbe lumineux animé */
        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(60px);
            z-index: 2;
        }

        .orb-1 {
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(200,148,58,0.08) 0%, transparent 70%);
            bottom: -100px; left: -100px;
            animation: orbFloat 8s ease-in-out infinite;
        }

        .orb-2 {
            width: 300px; height: 300px;
            background: radial-gradient(circle, rgba(30,60,120,0.15) 0%, transparent 70%);
            top: 0; right: 0;
            animation: orbFloat 12s ease-in-out infinite reverse;
        }

        @keyframes orbFloat {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(20px, -30px) scale(1.05); }
            66% { transform: translate(-15px, 20px) scale(0.95); }
        }

        /* Particules */
        .particles { position: absolute; inset: 0; z-index: 3; overflow: hidden; }
        .p {
            position: absolute;
            border-radius: 50%;
            opacity: 0;
            animation: particleRise var(--dur,9s) ease-in-out infinite var(--delay,0s);
        }
        .p-dot  { width: 2px; height: 2px; background: var(--gold2); }
        .p-ring {
            border: 1px solid rgba(200,148,58,0.4);
            width: 6px; height: 6px;
            background: transparent;
            animation-name: particleRiseRing;
        }

        @keyframes particleRise {
            0%   { opacity: 0; transform: translateY(0) scale(1); }
            15%  { opacity: var(--op, 0.5); }
            85%  { opacity: var(--op, 0.5); }
            100% { opacity: 0; transform: translateY(-140px) scale(0.2); }
        }

        @keyframes particleRiseRing {
            0%   { opacity: 0; transform: translateY(0) scale(1) rotate(0deg); }
            15%  { opacity: 0.35; }
            85%  { opacity: 0.35; }
            100% { opacity: 0; transform: translateY(-100px) scale(1.5) rotate(180deg); }
        }

        /* Contenu gauche */
        .left-content {
            position: relative;
            z-index: 10;
            display: flex;
            flex-direction: column;
            height: 100%;
            justify-content: space-between;
        }

        /* Logo */
        .logo {
            display: flex;
            align-items: center;
            gap: 14px;
            animation: revealUp 0.9s cubic-bezier(0.16,1,0.3,1) both;
        }

        .logo-mark {
            width: 46px; height: 46px;
            border-radius: 12px;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo-mark::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--gold) 0%, #9A6820 100%);
            opacity: 0.9;
        }

        .logo-mark::after {
            content: '';
            position: absolute;
            inset: 1px;
            border-radius: 11px;
            background: linear-gradient(135deg, rgba(255,255,255,0.15) 0%, transparent 50%);
        }

        .logo-mark i {
            position: relative;
            z-index: 1;
            color: #fff;
            font-size: 1.15rem;
        }

        .logo-name {
            font-family: 'Playfair Display', serif;
            font-size: 1.45rem;
            font-weight: 700;
            color: #fff;
            letter-spacing: -0.3px;
        }

        .logo-name em {
            color: var(--gold2);
            font-style: normal;
        }

        /* Hero text */
        .hero {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 40px 0;
        }

        .hero-tag {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 28px;
            animation: revealUp 0.9s 0.1s cubic-bezier(0.16,1,0.3,1) both;
        }

        .hero-tag-dot {
            width: 6px; height: 6px;
            border-radius: 50%;
            background: var(--gold);
            animation: blink 2s ease-in-out infinite;
        }

        @keyframes blink {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.4; transform: scale(0.7); }
        }

        .hero-tag-text {
            font-family: 'DM Mono', monospace;
            font-size: 0.65rem;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: rgba(200,148,58,0.8);
        }

        .hero-headline {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2.8rem, 4vw, 4.2rem);
            font-weight: 900;
            line-height: 1.0;
            color: #fff;
            letter-spacing: -2px;
            margin-bottom: 24px;
            animation: revealUp 0.9s 0.2s cubic-bezier(0.16,1,0.3,1) both;
        }

        .hero-headline .line-gold {
            display: block;
            color: transparent;
            -webkit-text-stroke: 1.5px rgba(200,148,58,0.6);
        }

        .hero-headline .line-italic {
            font-style: italic;
            color: var(--gold2);
        }

        .hero-desc {
            font-size: 0.9rem;
            color: rgba(255,255,255,0.38);
            line-height: 1.9;
            font-weight: 300;
            max-width: 400px;
            margin-bottom: 48px;
            animation: revealUp 0.9s 0.3s cubic-bezier(0.16,1,0.3,1) both;
        }

        /* Stats */
        .stats {
            display: flex;
            gap: 0;
            border: 1px solid rgba(200,148,58,0.15);
            border-radius: 16px;
            overflow: hidden;
            background: rgba(255,255,255,0.02);
            backdrop-filter: blur(20px);
            animation: revealUp 0.9s 0.4s cubic-bezier(0.16,1,0.3,1) both;
        }

        .stat {
            flex: 1;
            padding: 22px 28px;
            position: relative;
            transition: background 0.3s;
        }

        .stat + .stat {
            border-left: 1px solid rgba(200,148,58,0.12);
        }

        .stat:hover { background: rgba(200,148,58,0.04); }

        .stat-num {
            font-family: 'Playfair Display', serif;
            font-size: 2.2rem;
            font-weight: 700;
            color: var(--gold2);
            line-height: 1;
            margin-bottom: 5px;
        }

        .stat-num sup {
            font-size: 0.9rem;
            margin-left: 1px;
            opacity: 0.7;
        }

        .stat-label {
            font-family: 'DM Mono', monospace;
            font-size: 0.6rem;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.3);
        }

        /* Feature pills */
        .features {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            animation: revealUp 0.9s 0.5s cubic-bezier(0.16,1,0.3,1) both;
        }

        .feat-pill {
            display: flex;
            align-items: center;
            gap: 7px;
            padding: 7px 14px;
            border-radius: 100px;
            border: 1px solid rgba(200,148,58,0.15);
            font-size: 0.72rem;
            color: rgba(255,255,255,0.45);
            background: rgba(255,255,255,0.02);
            font-weight: 400;
            letter-spacing: 0.3px;
            transition: all 0.3s;
        }

        .feat-pill:hover {
            border-color: rgba(200,148,58,0.35);
            color: rgba(255,255,255,0.7);
            background: rgba(200,148,58,0.06);
        }

        .feat-pill i {
            font-size: 0.65rem;
            color: var(--gold);
        }

        @keyframes revealUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ════════════════════════════════
           PANNEAU DROIT
        ════════════════════════════════ */
        .right {
            width: 500px;
            background: var(--ivory);
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
        }

        /* Bande décorative top */
        .right-top-band {
            height: 4px;
            background: linear-gradient(90deg, var(--gold) 0%, var(--gold2) 50%, var(--gold3) 100%);
            flex-shrink: 0;
        }

        /* Motif géométrique décoratif */
        .right-deco {
            position: absolute;
            top: -40px;
            right: -40px;
            width: 240px;
            height: 240px;
            border-radius: 50%;
            border: 60px solid rgba(200,148,58,0.05);
            pointer-events: none;
        }

        .right-deco-2 {
            position: absolute;
            bottom: -60px;
            left: -60px;
            width: 200px;
            height: 200px;
            border-radius: 50%;
            border: 40px solid rgba(8,16,30,0.04);
            pointer-events: none;
        }

        /* Pattern corner */
        .corner-pattern {
            position: absolute;
            bottom: 0;
            right: 0;
            width: 160px;
            height: 160px;
            opacity: 0.04;
            background-image:
                repeating-linear-gradient(
                    45deg,
                    var(--ink) 0px,
                    var(--ink) 1px,
                    transparent 1px,
                    transparent 12px
                );
        }

        .right-inner {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 56px 52px;
            position: relative;
            z-index: 1;
            animation: slideRight 0.8s cubic-bezier(0.16,1,0.3,1) 0.1s both;
        }

        @keyframes slideRight {
            from { opacity: 0; transform: translateX(32px); }
            to   { opacity: 1; transform: translateX(0); }
        }

        /* Header formulaire */
        .form-header {
            margin-bottom: 40px;
        }

        .form-kicker {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 16px;
        }

        .kicker-bar {
            width: 32px;
            height: 2px;
            background: linear-gradient(90deg, var(--gold), var(--gold2));
            border-radius: 2px;
        }

        .kicker-text {
            font-family: 'DM Mono', monospace;
            font-size: 0.62rem;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            color: var(--gold);
            font-weight: 500;
        }

        .form-title {
            font-family: 'Playfair Display', serif;
            font-size: 2.8rem;
            font-weight: 900;
            color: var(--text);
            line-height: 1.0;
            letter-spacing: -1.5px;
            margin-bottom: 8px;
        }

        .form-title em {
            font-style: italic;
            color: var(--gold);
        }

        .form-subtitle {
            font-size: 0.88rem;
            color: var(--muted);
            font-weight: 300;
            line-height: 1.6;
        }

        /* Alerte erreur */
        .alert {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 14px 16px;
            border-radius: 10px;
            margin-bottom: 28px;
            animation: alertIn 0.4s cubic-bezier(0.34,1.56,0.64,1);
        }

        .alert-error {
            background: #FDF1EF;
            border: 1px solid rgba(192,57,43,0.2);
        }

        @keyframes alertIn {
            from { opacity: 0; transform: scale(0.95) translateY(-8px); }
            to   { opacity: 1; transform: scale(1) translateY(0); }
        }

        .alert-icon {
            width: 28px; height: 28px;
            border-radius: 8px;
            background: rgba(192,57,43,0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            color: var(--danger);
            font-size: 0.75rem;
        }

        .alert-body {
            flex: 1;
        }

        .alert-title {
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--danger);
            margin-bottom: 2px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .alert-text {
            font-size: 0.84rem;
            color: #8B2E23;
            line-height: 1.4;
        }

        /* Champs */
        .fields { display: flex; flex-direction: column; gap: 20px; }

        .field {}

        .field-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .field-label {
            font-family: 'DM Mono', monospace;
            font-size: 0.62rem;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: var(--text);
            font-weight: 500;
        }

        .field-hint {
            font-size: 0.72rem;
            color: var(--muted);
        }

        .field-body {
            position: relative;
        }

        .field-prefix {
            position: absolute;
            left: 0; top: 0; bottom: 0;
            width: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            pointer-events: none;
            z-index: 1;
        }

        .field-prefix-inner {
            width: 28px; height: 28px;
            border-radius: 7px;
            background: var(--ivory2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.72rem;
            color: var(--muted);
            transition: all 0.25s;
        }

        .field-input {
            width: 100%;
            padding: 13px 14px 13px 48px;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.93rem;
            color: var(--text);
            background: #fff;
            outline: none;
            transition: all 0.25s;
            -webkit-appearance: none;
        }

        .field-input::placeholder { color: #BDB5AA; }

        .field-input:hover { border-color: #C8BFB0; }

        .field-input:focus {
            border-color: var(--gold);
            box-shadow:
                0 0 0 3px rgba(200,148,58,0.12),
                0 1px 4px rgba(200,148,58,0.1);
        }

        .field-body:focus-within .field-prefix-inner {
            background: rgba(200,148,58,0.1);
            color: var(--gold);
        }

        /* Indicateur validité email */
        .field-status {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 0.75rem;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .field-status.show { opacity: 1; }
        .field-status.valid { color: var(--success); }
        .field-status.invalid { color: var(--danger); }

        .pass-input { padding-right: 44px; }

        .eye-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #BDB5AA;
            font-size: 0.82rem;
            padding: 6px;
            border-radius: 6px;
            transition: all 0.2s;
            line-height: 1;
        }

        .eye-toggle:hover {
            color: var(--text);
            background: var(--ivory2);
        }

        /* Force mdp */
        .strength-bar {
            height: 3px;
            border-radius: 2px;
            background: var(--border);
            margin-top: 8px;
            overflow: hidden;
        }

        .strength-fill {
            height: 100%;
            border-radius: 2px;
            width: 0%;
            transition: width 0.4s ease, background 0.4s;
        }

        /* Bouton submit */
        .btn-submit {
            width: 100%;
            margin-top: 28px;
            padding: 0;
            height: 52px;
            background: var(--text);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-family: 'DM Sans', sans-serif;
            font-weight: 600;
            font-size: 0.94rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 14px;
            position: relative;
            overflow: hidden;
            transition: all 0.35s cubic-bezier(0.34,1.56,0.64,1);
            letter-spacing: 0.2px;
        }

        /* Shimmer */
        .btn-submit::before {
            content: '';
            position: absolute;
            top: 0; left: -100%;
            width: 60%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.08), transparent);
            skewX(-20deg);
            transition: left 0.6s;
        }

        .btn-submit:hover::before { left: 160%; }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow:
                0 16px 40px rgba(8,16,30,0.3),
                0 4px 12px rgba(8,16,30,0.2),
                inset 0 1px 0 rgba(255,255,255,0.08);
        }

        .btn-submit:active { transform: translateY(0); }

        .btn-text { position: relative; z-index: 1; }

        .btn-badge {
            position: relative;
            z-index: 1;
            width: 32px; height: 32px;
            border-radius: 8px;
            background: rgba(200,148,58,0.18);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gold2);
            font-size: 0.78rem;
            transition: all 0.3s;
        }

        .btn-submit:hover .btn-badge {
            background: rgba(200,148,58,0.3);
            transform: translateX(3px);
        }

        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            gap: 14px;
            margin: 28px 0;
        }

        .divider-line {
            flex: 1;
            height: 1px;
            background: var(--border);
        }

        .divider-text {
            font-family: 'DM Mono', monospace;
            font-size: 0.6rem;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--muted);
        }

        /* Footer */
        .form-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .badge-secure {
            display: flex;
            align-items: center;
            gap: 7px;
            padding: 6px 12px;
            border-radius: 20px;
            background: rgba(45,158,107,0.08);
            border: 1px solid rgba(45,158,107,0.15);
        }

        .badge-secure-dot {
            width: 6px; height: 6px;
            border-radius: 50%;
            background: var(--success);
            animation: blink 2s ease-in-out infinite;
        }

        .badge-secure-text {
            font-family: 'DM Mono', monospace;
            font-size: 0.6rem;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--success);
        }

        .version {
            font-family: 'DM Mono', monospace;
            font-size: 0.62rem;
            letter-spacing: 1px;
            color: var(--muted);
            opacity: 0.5;
        }

        /* ════════ RESPONSIVE ════════ */
        @media (max-width: 900px) {
            .left { display: none; }
            .right { width: 100%; }
        }
    </style>
</head>
<body>

<!-- ════ GAUCHE ════ -->
<div class="left">
    <!-- Fonds -->
    <div class="left-overlay"></div>
    <div class="grain"></div>
    <div class="h-lines"></div>
    <div class="deco-line"></div>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>

    <!-- Particules -->
    <div class="particles" id="particles"></div>

    <!-- SVG lignes réseau -->
    <svg class="bg-canvas" viewBox="0 0 800 900" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">
        <defs>
            <radialGradient id="nodeGlow" cx="50%" cy="50%" r="50%">
                <stop offset="0%" stop-color="#C8943A" stop-opacity="0.5"/>
                <stop offset="100%" stop-color="#C8943A" stop-opacity="0"/>
            </radialGradient>
        </defs>
        <!-- Lignes de réseau subtiles -->
        <g stroke="rgba(200,148,58,0.07)" stroke-width="1" fill="none">
            <line x1="100" y1="200" x2="350" y2="450"/>
            <line x1="350" y1="450" x2="600" y2="300"/>
            <line x1="600" y1="300" x2="720" y2="600"/>
            <line x1="100" y1="200" x2="200" y2="700"/>
            <line x1="200" y1="700" x2="500" y2="800"/>
            <line x1="500" y1="800" x2="720" y2="600"/>
            <line x1="350" y1="450" x2="200" y2="700"/>
            <line x1="50" y1="500" x2="350" y2="450"/>
            <line x1="50" y1="500" x2="100" y2="200"/>
        </g>
        <!-- Noeuds -->
        <g fill="rgba(200,148,58,0.18)">
            <circle cx="100" cy="200" r="3"/>
            <circle cx="350" cy="450" r="4"/>
            <circle cx="600" cy="300" r="3"/>
            <circle cx="720" cy="600" r="3"/>
            <circle cx="200" cy="700" r="3"/>
            <circle cx="500" cy="800" r="3"/>
            <circle cx="50"  cy="500" r="2"/>
        </g>
        <!-- Noeud principal animé -->
        <circle cx="350" cy="450" r="10" fill="url(#nodeGlow)" opacity="0.6">
            <animate attributeName="r" values="8;14;8" dur="4s" repeatCount="indefinite"/>
            <animate attributeName="opacity" values="0.4;0.8;0.4" dur="4s" repeatCount="indefinite"/>
        </circle>
    </svg>

    <!-- Contenu -->
    <div class="left-content">
        <!-- Logo -->
        <div class="logo">
            <div class="logo-mark"><i class="fas fa-warehouse"></i></div>
            <div class="logo-name">Gestion<em>Approv</em></div>
        </div>

        <!-- Hero -->
        <div class="hero">
            <div class="hero-tag">
                <div class="hero-tag-dot"></div>
                <div class="hero-tag-text">Plateforme d'approvisionnement</div>
            </div>

            <h1 class="hero-headline">
                Maîtrisez<br>
                <span class="line-gold">votre chaîne</span>
                <span class="line-italic">d'achat.</span>
            </h1>

            <p class="hero-desc">
                De la commande fournisseur à la réception en entrepôt,
                gérez stocks et paiements avec une précision absolue.
            </p>

            <div class="stats">
                <div class="stat">
                    <div class="stat-num">10</div>
                    <div class="stat-label">Tables BD</div>
                </div>
                <div class="stat">
                    <div class="stat-num">28<sup>+</sup></div>
                    <div class="stat-label">Endpoints</div>
                </div>
                <div class="stat">
                    <div class="stat-num">3</div>
                    <div class="stat-label">Niveaux accès</div>
                </div>
            </div>
        </div>

        <!-- Feature pills -->
        <div class="features">
            <div class="feat-pill"><i class="fas fa-box-open"></i> Gestion stocks</div>
            <div class="feat-pill"><i class="fas fa-file-invoice"></i> Commandes</div>
            <div class="feat-pill"><i class="fas fa-chart-line"></i> Analytiques</div>
            <div class="feat-pill"><i class="fas fa-truck"></i> Fournisseurs</div>
            <div class="feat-pill"><i class="fas fa-shield-halved"></i> Multi-rôles</div>
        </div>
    </div>
</div>

<!-- ════ DROITE ════ -->
<div class="right">
    <div class="right-top-band"></div>
    <div class="right-deco"></div>
    <div class="right-deco-2"></div>
    <div class="corner-pattern"></div>

    <div class="right-inner">

        <!-- Header -->
        <div class="form-header">
            <div class="form-kicker">
                <div class="kicker-bar"></div>
                <div class="kicker-text">Accès sécurisé</div>
            </div>
            <h2 class="form-title">Bon<br><em>retour.</em></h2>
            <p class="form-subtitle">Connectez-vous à votre espace de gestion</p>
        </div>

        <?php if ($erreur): ?>
        <div class="alert alert-error">
            <div class="alert-icon"><i class="fas fa-exclamation"></i></div>
            <div class="alert-body">
                <div class="alert-title">Échec de connexion</div>
                <div class="alert-text"><?= htmlspecialchars($erreur) ?></div>
            </div>
        </div>
        <?php endif; ?>

        <form method="POST" id="loginForm" novalidate>

            <div class="fields">
                <!-- Email -->
                <div class="field">
                    <div class="field-head">
                        <label class="field-label" for="emailInput">Adresse email</label>
                    </div>
                    <div class="field-body">
                        <div class="field-prefix">
                            <div class="field-prefix-inner"><i class="fas fa-at"></i></div>
                        </div>
                        <input
                            type="email"
                            id="emailInput"
                            name="email"
                            class="field-input"
                            placeholder="vous@domaine.com"
                            value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                            autocomplete="email"
                            required
                            autofocus
                        >
                        <span class="field-status" id="emailStatus"></span>
                    </div>
                </div>

                <!-- Mot de passe -->
                <div class="field">
                    <div class="field-head">
                        <label class="field-label" for="passInput">Mot de passe</label>
                        <span class="field-hint" id="passHint"></span>
                    </div>
                    <div class="field-body">
                        <div class="field-prefix">
                            <div class="field-prefix-inner"><i class="fas fa-key"></i></div>
                        </div>
                        <input
                            type="password"
                            id="passInput"
                            name="password"
                            class="field-input pass-input"
                            placeholder="••••••••••"
                            autocomplete="current-password"
                            required
                        >
                        <button type="button" class="eye-toggle" id="eyeBtn" aria-label="Afficher le mot de passe">
                            <i class="fas fa-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                    <div class="strength-bar"><div class="strength-fill" id="strengthFill"></div></div>
                </div>
            </div>

            <button type="submit" class="btn-submit" id="submitBtn">
                <span class="btn-text" id="btnText">Accéder à mon espace</span>
                <div class="btn-badge" id="btnBadge">
                    <i class="fas fa-arrow-right" id="btnIcon"></i>
                </div>
            </button>

        </form>

        <div class="divider">
            <div class="divider-line"></div>
            <div class="divider-text">Sécurisé par chiffrement</div>
            <div class="divider-line"></div>
        </div>

        <div class="form-footer">
            <div class="badge-secure">
                <div class="badge-secure-dot"></div>
                <div class="badge-secure-text">SSL / TLS actif</div>
            </div>
            <div class="version">v1.0.0</div>
        </div>

    </div>
</div>

<script>
/* ── Particules dynamiques ── */
(function() {
    const container = document.getElementById('particles');
    if (!container) return;
    const configs = [
        [12,22,9,0.0,'0.5','dot'],   [28,46,7,1.2,'0.4','ring'],
        [55,14,11,2.5,'0.55','dot'], [72,62,8,0.8,'0.4','dot'],
        [85,32,10,3.1,'0.5','ring'], [40,78,7,1.8,'0.38','dot'],
        [20,58,9,4.0,'0.5','dot'],   [65,88,8,0.5,'0.38','ring'],
        [90,72,10,2.2,'0.45','dot'], [35,36,7,3.5,'0.42','ring'],
        [8, 82,9,1.0,'0.38','dot'], [50,52,11,2.8,'0.55','dot'],
        [15,40,8,5.0,'0.3','ring'],  [78,20,6,0.3,'0.45','dot'],
        [62,70,9,1.5,'0.35','dot'],  [44,10,7,4.2,'0.4','ring'],
    ];
    configs.forEach(([l,t,dur,delay,op,type]) => {
        const el = document.createElement('div');
        el.className = `p p-${type}`;
        el.style.cssText = `left:${l}%;top:${t}%;--dur:${dur}s;--delay:${delay}s;--op:${op}`;
        container.appendChild(el);
    });
})();

/* ── Toggle mot de passe ── */
document.getElementById('eyeBtn').addEventListener('click', function() {
    const inp  = document.getElementById('passInput');
    const icon = document.getElementById('eyeIcon');
    const shown = inp.type === 'text';
    inp.type = shown ? 'password' : 'text';
    icon.className = shown ? 'fas fa-eye' : 'fas fa-eye-slash';
    this.setAttribute('aria-label', shown ? 'Afficher le mot de passe' : 'Masquer le mot de passe');
});

/* ── Validation email en temps réel ── */
document.getElementById('emailInput').addEventListener('input', function() {
    const el  = document.getElementById('emailStatus');
    const val = this.value.trim();
    if (!val) { el.className = 'field-status'; return; }
    const ok = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val);
    el.textContent = ok ? '✓' : '✗';
    el.className   = `field-status show ${ok ? 'valid' : 'invalid'}`;
});

/* ── Force mot de passe ── */
document.getElementById('passInput').addEventListener('input', function() {
    const val  = this.value;
    const fill = document.getElementById('strengthFill');
    const hint = document.getElementById('passHint');
    let score  = 0;
    if (val.length >= 6)  score++;
    if (val.length >= 10) score++;
    if (/[A-Z]/.test(val)) score++;
    if (/[0-9]/.test(val)) score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;

    const pcts   = [0, 20, 40, 65, 85, 100];
    const colors = ['', '#E74C3C','#E67E22','#F1C40F','#2ECC71','#27AE60'];
    const labels = ['', 'Très faible','Faible','Moyen','Fort','Très fort'];

    fill.style.width      = val ? pcts[score] + '%' : '0%';
    fill.style.background = colors[score] || '';
    hint.textContent      = val ? labels[score] : '';
    hint.style.color      = colors[score] || '';
});

/* ── Soumission avec état loading ── */
document.getElementById('loginForm').addEventListener('submit', function(e) {
    const emailOk = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(
        document.getElementById('emailInput').value.trim()
    );
    const passOk  = document.getElementById('passInput').value.length >= 1;

    if (!emailOk || !passOk) return; // laisse la validation native

    const btn  = document.getElementById('submitBtn');
    const txt  = document.getElementById('btnText');
    const icon = document.getElementById('btnIcon');

    txt.textContent = 'Vérification…';
    icon.className  = 'fas fa-spinner fa-spin';
    btn.style.opacity        = '0.8';
    btn.style.pointerEvents  = 'none';
    btn.style.transform      = 'scale(0.99)';
});
</script>
</body>
</html>