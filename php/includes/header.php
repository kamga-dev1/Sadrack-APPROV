<?php
if (session_status() === PHP_SESSION_NONE) session_start();
define('API_URL', 'http://localhost:3000/api');
require_once 'includes/config.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>GestionApprov</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<style>
:root{
  --bg-base:    #07101E;
  --bg-nav:     #050D1A;
  --bg-side:    #060E1C;
  --bg-content: #0D1B2E;
  --bg-card:    #0F2040;
  --bg-card2:   #112244;
  --gold:       #E2A84B;
  --gold2:      #F5C96A;
  --gold-dim:   rgba(226,168,75,0.12);
  --gold-border:rgba(226,168,75,0.18);
  --txt:        #EEF2F8;
  --txt-muted:  #6B7A90;
  --txt-sub:    #4A5568;
  --border:     rgba(255,255,255,0.06);
  --border2:    rgba(255,255,255,0.1);
  --green:      #34D399;
  --red:        #F87171;
  --blue:       #60A5FA;
  --yellow:     #FBBF24;
  --sidebar-w:  240px;
  --nav-h:      58px;
}

*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
body{
  font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;
  background:var(--bg-content);
  color:var(--txt);
  min-height:100vh;
  font-size:0.875rem;
}

/* ══ SCROLLBAR ══ */
::-webkit-scrollbar{width:5px;height:5px;}
::-webkit-scrollbar-track{background:var(--bg-base);}
::-webkit-scrollbar-thumb{background:rgba(226,168,75,0.25);border-radius:10px;}
::-webkit-scrollbar-thumb:hover{background:rgba(226,168,75,0.45);}

/* ══ NAVBAR ══ */
.navbar-main{
  position:fixed;top:0;left:0;right:0;
  height:var(--nav-h);z-index:1000;
  background:var(--bg-nav);
  border-bottom:1px solid var(--border);
  display:flex;align-items:center;
  padding:0 20px;gap:16px;
  box-shadow:0 2px 20px rgba(0,0,0,0.3);
}

/* Ligne dorée sous navbar */
.navbar-main::after{
  content:'';
  position:absolute;bottom:0;left:0;right:0;height:1px;
  background:linear-gradient(90deg,transparent,var(--gold),transparent);
  opacity:0.2;
}

.nav-brand{
  display:flex;align-items:center;gap:10px;
  text-decoration:none;width:var(--sidebar-w);
  flex-shrink:0;
}
.nav-brand-icon{
  width:34px;height:34px;border-radius:9px;
  background:linear-gradient(135deg,var(--gold),#C47C20);
  display:flex;align-items:center;justify-content:center;
  font-size:0.9rem;color:#fff;flex-shrink:0;
  box-shadow:0 4px 12px rgba(226,168,75,0.25);
}
.nav-brand-name{
  font-size:1rem;font-weight:800;color:#fff;
  letter-spacing:-0.2px;line-height:1;
}
.nav-brand-name span{color:var(--gold);}

.nav-separator{width:1px;height:28px;background:var(--border2);flex-shrink:0;}

/* Titre page */
.nav-page-title{
  font-size:0.8rem;font-weight:600;
  color:var(--txt-muted);letter-spacing:0.3px;
}

.nav-spacer{flex:1;}

/* Badge alertes */
.nav-alert-btn{
  position:relative;
  width:36px;height:36px;border-radius:9px;
  background:var(--gold-dim);border:1px solid var(--gold-border);
  display:flex;align-items:center;justify-content:center;
  color:var(--gold);font-size:0.85rem;
  cursor:pointer;text-decoration:none;
  transition:all 0.2s;
}
.nav-alert-btn:hover{background:rgba(226,168,75,0.2);color:var(--gold2);}
.nav-badge{
  position:absolute;top:-4px;right:-4px;
  min-width:16px;height:16px;border-radius:8px;
  background:var(--red);color:#fff;
  font-size:0.58rem;font-weight:800;
  display:flex;align-items:center;justify-content:center;
  padding:0 4px;
  display:none;
}

/* Utilisateur */
.nav-user{
  display:flex;align-items:center;gap:10px;
  padding:6px 12px;border-radius:10px;
  background:rgba(255,255,255,0.04);
  border:1px solid var(--border);
  cursor:pointer;transition:all 0.2s;
  position:relative;
}
.nav-user:hover{background:rgba(255,255,255,0.07);border-color:var(--border2);}
.nav-user-avatar{
  width:30px;height:30px;border-radius:8px;
  background:linear-gradient(135deg,var(--gold),#C47C20);
  display:flex;align-items:center;justify-content:center;
  font-size:0.75rem;font-weight:800;color:#fff;flex-shrink:0;
}
.nav-user-info{}
.nav-user-name{font-size:0.78rem;font-weight:700;color:var(--txt);line-height:1.2;}
.nav-user-role{
  font-size:0.62rem;font-weight:600;
  text-transform:uppercase;letter-spacing:0.8px;
  color:var(--gold);
}
.nav-user-caret{font-size:0.6rem;color:var(--txt-muted);margin-left:2px;}

/* Dropdown utilisateur */
.nav-dropdown{
  position:absolute;top:calc(100% + 8px);right:0;
  min-width:180px;
  background:#0F2040;
  border:1px solid var(--border2);
  border-radius:12px;padding:6px;
  box-shadow:0 16px 40px rgba(0,0,0,0.5);
  display:none;z-index:999;
}
.nav-dropdown.show{display:block;animation:dropIn 0.2s ease;}
@keyframes dropIn{from{opacity:0;transform:translateY(-6px);}to{opacity:1;transform:translateY(0);}}
.nav-dd-item{
  display:flex;align-items:center;gap:10px;
  padding:9px 12px;border-radius:8px;
  font-size:0.78rem;color:var(--txt-muted);
  text-decoration:none;transition:all 0.15s;cursor:pointer;
}
.nav-dd-item:hover{background:rgba(255,255,255,0.05);color:var(--txt);}
.nav-dd-item.danger:hover{background:rgba(248,113,113,0.1);color:var(--red);}
.nav-dd-sep{height:1px;background:var(--border);margin:4px 0;}

/* ══ LAYOUT ══ */
.layout{
  display:flex;
  padding-top:var(--nav-h);
  min-height:100vh;
}

/* ══ SIDEBAR ══ */
.sidebar{
  width:var(--sidebar-w);
  background:var(--bg-side);
  border-right:1px solid var(--border);
  position:fixed;
  top:var(--nav-h);bottom:0;left:0;
  overflow-y:auto;overflow-x:hidden;
  z-index:900;
  padding:16px 0;
}

.side-section{
  padding:0 12px;
  margin-bottom:4px;
}
.side-label{
  font-size:0.58rem;font-weight:800;
  text-transform:uppercase;letter-spacing:2px;
  color:var(--txt-sub);
  padding:10px 10px 6px;
  display:block;
}
.side-item{
  display:flex;align-items:center;gap:10px;
  padding:9px 12px;border-radius:9px;
  text-decoration:none;
  color:var(--txt-muted);
  font-size:0.8rem;font-weight:500;
  transition:all 0.18s;
  margin-bottom:2px;
  position:relative;
}
.side-item:hover{
  background:rgba(255,255,255,0.04);
  color:var(--txt);
}
.side-item.active{
  background:var(--gold-dim);
  color:var(--gold);
  font-weight:600;
}
.side-item.active::before{
  content:'';
  position:absolute;left:0;top:20%;bottom:20%;
  width:3px;border-radius:0 3px 3px 0;
  background:var(--gold);
}
.side-item i{width:16px;text-align:center;font-size:0.8rem;flex-shrink:0;}
.side-badge{
  margin-left:auto;
  font-size:0.6rem;font-weight:800;
  background:var(--red);color:#fff;
  padding:1px 6px;border-radius:10px;
}

/* ══ MAIN ══ */
.main-content{
  flex:1;
  margin-left:var(--sidebar-w);
  min-height:calc(100vh - var(--nav-h));
  padding:24px;
  background:var(--bg-content);
}

/* ══ COMPOSANTS RÉUTILISABLES ══ */

/* Page header */
.page-header{
  display:flex;align-items:flex-start;justify-content:space-between;
  margin-bottom:24px;flex-wrap:wrap;gap:12px;
}
.page-header-left{}
.page-title{
  font-size:1.3rem;font-weight:800;
  color:var(--txt);letter-spacing:-0.3px;
  margin-bottom:3px;
}
.page-title i{color:var(--gold);margin-right:8px;}
.page-sub{font-size:0.77rem;color:var(--txt-muted);}

/* KPI cards */
.kpi-card{
  background:var(--bg-card);
  border:1px solid var(--border);
  border-radius:14px;padding:18px;
  transition:all 0.25s;
  position:relative;overflow:hidden;
}
.kpi-card::before{
  content:'';position:absolute;
  top:0;left:0;right:0;height:2px;
  background:linear-gradient(90deg,var(--gold),transparent);
  opacity:0;transition:opacity 0.25s;
}
.kpi-card:hover{
  border-color:var(--gold-border);
  transform:translateY(-2px);
  box-shadow:0 8px 24px rgba(0,0,0,0.3);
}
.kpi-card:hover::before{opacity:1;}
.kpi-icon{
  width:42px;height:42px;border-radius:11px;
  display:flex;align-items:center;justify-content:center;
  font-size:1rem;margin-bottom:14px;
}
.kpi-val{
  font-size:1.7rem;font-weight:800;
  color:var(--txt);letter-spacing:-0.5px;line-height:1;
  margin-bottom:4px;
}
.kpi-lbl{font-size:0.75rem;color:var(--txt-muted);font-weight:500;}
.kpi-delta{
  font-size:0.7rem;font-weight:600;margin-top:8px;
  display:flex;align-items:center;gap:4px;
}

/* Panel cards */
.panel{
  background:var(--bg-card);
  border:1px solid var(--border);
  border-radius:14px;overflow:hidden;
}
.panel-head{
  display:flex;align-items:center;justify-content:space-between;
  padding:16px 18px;
  border-bottom:1px solid var(--border);
}
.panel-title{
  font-size:0.85rem;font-weight:700;
  color:var(--txt);display:flex;align-items:center;gap:8px;
}
.panel-title i{color:var(--gold);}
.panel-body{padding:16px 18px;}

/* Boutons */
.btn-gold{
  background:linear-gradient(135deg,var(--gold),#C47C20);
  color:#fff;border:none;border-radius:9px;
  padding:8px 16px;font-size:0.78rem;font-weight:700;
  cursor:pointer;transition:all 0.2s;
  display:inline-flex;align-items:center;gap:7px;
  font-family:inherit;
}
.btn-gold:hover{transform:translateY(-1px);box-shadow:0 6px 18px rgba(226,168,75,0.3);}

.btn-ghost{
  background:rgba(255,255,255,0.05);
  border:1px solid var(--border);
  color:var(--txt-muted);border-radius:9px;
  padding:7px 14px;font-size:0.75rem;font-weight:600;
  cursor:pointer;transition:all 0.2s;
  display:inline-flex;align-items:center;gap:6px;
  text-decoration:none;font-family:inherit;
}
.btn-ghost:hover{background:rgba(255,255,255,0.08);color:var(--txt);border-color:var(--border2);}

/* Tables */
.dark-table{width:100%;border-collapse:collapse;}
.dark-table th{
  font-size:0.67rem;font-weight:800;
  text-transform:uppercase;letter-spacing:1px;
  color:var(--txt-sub);padding:10px 14px;
  border-bottom:1px solid var(--border);
  white-space:nowrap;
}
.dark-table td{
  padding:11px 14px;border-bottom:1px solid var(--border);
  font-size:0.8rem;color:var(--txt-muted);
  vertical-align:middle;
}
.dark-table tr:last-child td{border-bottom:none;}
.dark-table tbody tr{transition:background 0.15s;}
.dark-table tbody tr:hover{background:rgba(255,255,255,0.025);}
.dark-table td strong,.dark-table td .fw{color:var(--txt);}

/* Badges statut */
.sbadge{
  font-size:0.65rem;font-weight:700;
  padding:3px 9px;border-radius:20px;
  letter-spacing:0.3px;white-space:nowrap;
}
.sbadge-success{background:rgba(52,211,153,0.12);color:var(--green);border:1px solid rgba(52,211,153,0.2);}
.sbadge-warning{background:rgba(251,191,36,0.12);color:var(--yellow);border:1px solid rgba(251,191,36,0.2);}
.sbadge-danger{background:rgba(248,113,113,0.12);color:var(--red);border:1px solid rgba(248,113,113,0.2);}
.sbadge-info{background:rgba(96,165,250,0.12);color:var(--blue);border:1px solid rgba(96,165,250,0.2);}
.sbadge-muted{background:rgba(255,255,255,0.05);color:var(--txt-muted);border:1px solid var(--border);}
.sbadge-gold{background:var(--gold-dim);color:var(--gold);border:1px solid var(--gold-border);}

/* Alert zone */
#alert-zone{margin-bottom:16px;}
.alert-success-dark{
  background:rgba(52,211,153,0.1);border:1px solid rgba(52,211,153,0.2);
  border-left:3px solid var(--green);border-radius:10px;
  padding:11px 14px;font-size:0.8rem;color:var(--green);
  display:flex;align-items:center;gap:8px;
}
.alert-danger-dark{
  background:rgba(248,113,113,0.1);border:1px solid rgba(248,113,113,0.2);
  border-left:3px solid var(--red);border-radius:10px;
  padding:11px 14px;font-size:0.8rem;color:var(--red);
  display:flex;align-items:center;gap:8px;
}

/* Responsive */
@media(max-width:768px){
  .sidebar{transform:translateX(-100%);transition:transform 0.3s;}
  .sidebar.open{transform:translateX(0);}
  .main-content{margin-left:0;}
  .nav-brand{width:auto;}
}
</style>
</head>
<body>

<!-- ══ NAVBAR ══ -->
<nav class="navbar-main">
  <a href="/SADRACK-APPROV/php/dashboard.php" class="nav-brand">
    <div class="nav-brand-icon"><i class="fas fa-warehouse"></i></div>
    <div class="nav-brand-name">Gestion<span>Approv</span></div>
  </a>

  <div class="nav-separator"></div>
  <div class="nav-page-title" id="nav-page-title">Tableau de bord</div>

  <div class="nav-spacer"></div>

  <!-- Alertes -->
  <a href="/SADRACK-APPROV/php/articles.php" class="nav-alert-btn" title="Alertes stock">
    <i class="fas fa-bell"></i>
    <span class="nav-badge" id="nav-badge">0</span>
  </a>

  <!-- User -->
  <div class="nav-user" onclick="toggleDropdown()">
    <div class="nav-user-avatar">
      <?= strtoupper(substr($_SESSION['user']['nom'] ?? 'U', 0, 1)) ?>
    </div>
    <div class="nav-user-info">
      <div class="nav-user-name"><?= htmlspecialchars($_SESSION['user']['nom'] ?? '') ?></div>
      <div class="nav-user-role"><?= htmlspecialchars($_SESSION['user']['role'] ?? '') ?></div>
    </div>
    <i class="fas fa-chevron-down nav-user-caret"></i>
    <div class="nav-dropdown" id="navDropdown">
      <div class="nav-dd-item">
        <i class="fas fa-user-circle"></i>
        <?= htmlspecialchars($_SESSION['user']['email'] ?? '') ?>
      </div>
      <div class="nav-dd-sep"></div>
      <a href="/SADRACK-APPROV/php/logout.php" class="nav-dd-item danger">
        <i class="fas fa-right-from-bracket"></i>Se déconnecter
      </a>
    </div>
  </div>
</nav>

<!-- ══ LAYOUT ══ -->
<div class="layout">