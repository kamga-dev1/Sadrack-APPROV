<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!defined('API_URL')) define('API_URL', 'http://localhost:3000/api');
$_u    = $_SESSION['user'] ?? [];
$_nom  = htmlspecialchars($_u['nom']   ?? 'Utilisateur');
$_role = htmlspecialchars($_u['role']  ?? 'user');
$_mail = htmlspecialchars($_u['email'] ?? '');
$_init = strtoupper(mb_substr($_u['nom'] ?? 'U', 0, 1));
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>GestionApprov — ERP</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;0,9..40,800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
/* ═══════════════════════════════════════════
   ENTERPRISE DESIGN SYSTEM — GestionApprov
   Base font: 15px  |  Fonds: bleu-gris chaud
═══════════════════════════════════════════ */
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
  /* Palette login */
  --ink:      #05080F;
  --ink2:     #0C1829;
  --ink3:     #152038;
  --gold:     #E2A84B;
  --gold-lt:  #F5C96A;
  --gold-dk:  #B87220;

  /* Surfaces — bleu-gris chaud, ni trop blanc ni trop foncé */
  --canvas:   #E4E8F0;   /* fond de page */
  --surface:  #EFF1F7;   /* cards, sidebar */
  --surface2: #E8EBF3;   /* headers de section */
  --border:   #CDD4E3;
  --border2:  #B8C2D8;

  /* Texte */
  --text:     #0D1526;
  --text2:    #2E3F5A;
  --text3:    #5A6E88;
  --text4:    #8FA4BF;

  /* Sémantique */
  --ok:    #0B8A55;  --ok-bg:   #DDF2E9;  --ok-bd:   #A8DCBF;
  --warn:  #B8720A;  --warn-bg: #FEF2DC;  --warn-bd: #F5CC84;
  --crit:  #C01E1E;  --crit-bg: #FCE8E8;  --crit-bd: #F0A8A8;
  --info:  #1452BE;  --info-bg: #E5EEFF;  --info-bd: #A0BAFA;

  /* Layout */
  --nav-h:  58px;
  --side-w: 248px;

  /* Ombres */
  --sh1: 0 1px 3px rgba(13,21,38,.07), 0 1px 2px rgba(13,21,38,.05);
  --sh2: 0 4px 14px rgba(13,21,38,.1),  0 2px 4px rgba(13,21,38,.06);
  --sh3: 0 12px 32px rgba(13,21,38,.14), 0 4px 8px rgba(13,21,38,.07);
}

html,body{
  height:100%;
  font-family:'DM Sans',system-ui,sans-serif;
  background:var(--canvas);
  color:var(--text);
  font-size:15px;
  line-height:1.55;
  -webkit-font-smoothing:antialiased;
}
*::selection{background:rgba(226,168,75,.2);}
::-webkit-scrollbar{width:5px;height:5px;}
::-webkit-scrollbar-track{background:var(--canvas);}
::-webkit-scrollbar-thumb{background:var(--border2);border-radius:8px;}

/* ══ TOPBAR ══ */
.topbar{
  position:fixed;inset:0 0 auto 0;
  height:var(--nav-h);z-index:600;
  background:var(--ink);
  display:flex;align-items:center;
  padding:0 20px 0 0;
  box-shadow:0 2px 20px rgba(5,8,15,.45);
}
.topbar::after{
  content:'';position:absolute;bottom:0;left:0;right:0;height:2px;
  background:linear-gradient(90deg,var(--gold) 0%,rgba(226,168,75,.35) 40%,transparent 65%);
}

/* Logo */
.t-brand{
  width:var(--side-w);flex-shrink:0;height:100%;
  display:flex;align-items:center;gap:11px;
  padding:0 20px;text-decoration:none;
  border-right:1px solid rgba(255,255,255,.06);
}
.t-mark{
  width:34px;height:34px;border-radius:9px;flex-shrink:0;
  background:linear-gradient(135deg,var(--gold),var(--gold-dk));
  display:flex;align-items:center;justify-content:center;
  font-size:1rem;color:#fff;
  box-shadow:0 3px 10px rgba(226,168,75,.32);
}
.t-name{font-size:1rem;font-weight:700;color:#fff;letter-spacing:-.1px;}
.t-name b{color:var(--gold);}

/* Centre */
.t-center{flex:1;display:flex;align-items:center;padding:0 20px;}
.t-crumb{
  display:flex;align-items:center;gap:6px;
  font-size:.88rem;font-weight:500;
  color:rgba(255,255,255,.3);letter-spacing:.1px;
}
.t-crumb-sep{font-size:.68rem;color:rgba(226,168,75,.32);}
.t-crumb-cur{color:rgba(255,255,255,.6);font-weight:600;}

/* Droite */
.t-right{display:flex;align-items:center;gap:6px;}

.t-btn{
  width:35px;height:35px;border-radius:8px;
  background:rgba(255,255,255,.055);border:1px solid rgba(255,255,255,.08);
  display:flex;align-items:center;justify-content:center;
  color:rgba(255,255,255,.42);font-size:.85rem;
  text-decoration:none;cursor:pointer;position:relative;transition:all .18s;
}
.t-btn:hover{background:rgba(255,255,255,.1);color:rgba(255,255,255,.85);}
.t-pip{
  position:absolute;top:-2px;right:-2px;
  width:8px;height:8px;border-radius:50%;
  background:var(--crit);border:2px solid var(--ink);
  display:none;animation:pip 2.5s ease-in-out infinite;
}
@keyframes pip{0%,100%{box-shadow:0 0 0 0 rgba(192,30,30,.45);}60%{box-shadow:0 0 0 5px transparent;}}

.t-div{width:1px;height:18px;background:rgba(255,255,255,.07);margin:0 3px;}

/* Chip user */
.t-user{
  display:flex;align-items:center;gap:8px;
  padding:5px 11px 5px 6px;border-radius:9px;
  background:rgba(255,255,255,.055);border:1px solid rgba(255,255,255,.08);
  cursor:pointer;position:relative;transition:all .18s;
}
.t-user:hover{background:rgba(255,255,255,.09);}
.t-av{
  width:29px;height:29px;border-radius:7px;flex-shrink:0;
  background:linear-gradient(135deg,var(--gold),var(--gold-dk));
  display:flex;align-items:center;justify-content:center;
  font-size:.82rem;font-weight:800;color:#fff;
}
.t-uname{font-size:.8rem;font-weight:700;color:rgba(255,255,255,.84);line-height:1.25;}
.t-urole{font-size:.72rem;color:var(--gold);font-weight:600;text-transform:uppercase;letter-spacing:.5px;}
.t-ucaret{font-size:.52rem;color:rgba(255,255,255,.25);margin-left:2px;}

/* Dropdown */
.t-drop{
  position:absolute;top:calc(100% + 9px);right:0;min-width:205px;
  background:var(--surface);border:1px solid var(--border);
  border-radius:12px;padding:6px;
  box-shadow:var(--sh3);display:none;z-index:700;
}
.t-drop.open{display:block;animation:dIn .16s ease;}
@keyframes dIn{from{opacity:0;transform:translateY(-4px);}to{opacity:1;transform:translateY(0);}}
.dd-head{padding:10px 12px 9px;border-bottom:1px solid var(--border);margin-bottom:4px;}
.dd-head-name{font-size:.88rem;font-weight:700;color:var(--text);}
.dd-head-email{font-size:.72rem;color:var(--text3);margin-top:2px;}
.dd-it{
  display:flex;align-items:center;gap:9px;
  padding:8px 12px;border-radius:8px;
  font-size:.82rem;color:var(--text2);
  text-decoration:none;transition:background .13s;cursor:pointer;
}
.dd-it:hover{background:var(--canvas);}
.dd-it.dx:hover{background:var(--crit-bg);color:var(--crit);}
.dd-it i{width:14px;text-align:center;font-size:.78rem;color:var(--text4);}
.dd-it.dx:hover i{color:var(--crit);}
.dd-sep{height:1px;background:var(--border);margin:4px 0;}

/* ══ SIDEBAR ══ */
.sidebar{
  position:fixed;top:var(--nav-h);left:0;bottom:0;
  width:var(--side-w);z-index:500;
  background:var(--surface);
  border-right:1px solid var(--border);
  display:flex;flex-direction:column;
  overflow-y:auto;overflow-x:hidden;
}

.s-section{padding:14px 10px 2px;}
.s-label{
  font-size:.65rem;font-weight:800;
  text-transform:uppercase;letter-spacing:2px;
  color:var(--text4);
  padding:0 8px 7px;display:block;
}
.s-item{
  display:flex;align-items:center;gap:10px;
  padding:9px 10px;border-radius:9px;
  text-decoration:none;color:var(--text3);
  font-size:.9rem;font-weight:500;
  transition:all .15s;margin-bottom:2px;
  position:relative;
}
.s-item:hover{background:var(--canvas);color:var(--text2);}
.s-item.on{
  background:linear-gradient(90deg,rgba(226,168,75,.12) 0%,rgba(226,168,75,.04) 100%);
  color:var(--ink2);font-weight:700;
}
.s-item.on::before{
  content:'';position:absolute;left:0;top:22%;bottom:22%;
  width:3px;border-radius:0 2px 2px 0;
  background:linear-gradient(180deg,var(--gold),var(--gold-dk));
}
.s-item i{width:16px;text-align:center;font-size:.84rem;flex-shrink:0;color:var(--text4);}
.s-item:hover i{color:var(--text3);}
.s-item.on i{color:var(--gold);}
.s-badge{
  margin-left:auto;font-size:.6rem;font-weight:800;
  background:var(--crit);color:#fff;
  padding:2px 6px;border-radius:8px;
}

/* Pied sidebar */
.s-foot{
  margin-top:auto;padding:12px;
  border-top:1px solid var(--border);
}
.s-foot-card{
  display:flex;align-items:center;gap:9px;
  padding:9px 10px;border-radius:9px;
  background:var(--canvas);border:1px solid var(--border);
}
.s-foot-av{
  width:30px;height:30px;border-radius:7px;flex-shrink:0;
  background:var(--ink2);
  display:flex;align-items:center;justify-content:center;
  font-size:.72rem;font-weight:800;color:var(--gold);
}
.s-foot-name{font-size:.82rem;font-weight:700;color:var(--text);}
.s-foot-role{font-size:.65rem;color:var(--text4);}

/* ══ LAYOUT ══ */
.app-wrap{margin-left:var(--side-w);padding-top:var(--nav-h);min-height:100vh;background:var(--canvas);}
.page{padding:26px 28px 60px;}

/* ══ COMPOSANTS RÉUTILISABLES ══ */

/* Page header */
.pg-header{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:24px;flex-wrap:wrap;gap:12px;}
.pg-title{font-size:1.3rem;font-weight:700;color:var(--text);letter-spacing:-.3px;}
.pg-sub{font-size:.82rem;color:var(--text3);margin-top:4px;}
.pg-actions{display:flex;align-items:center;gap:8px;}

/* Boutons */
.btn-primary{
  display:inline-flex;align-items:center;gap:7px;padding:9px 17px;border-radius:8px;
  background:var(--ink2);color:#fff;border:1px solid var(--ink2);
  font-size:.85rem;font-weight:600;cursor:pointer;transition:all .18s;font-family:inherit;text-decoration:none;
}
.btn-primary:hover{background:var(--ink);box-shadow:var(--sh2);}
.btn-gold{
  display:inline-flex;align-items:center;gap:7px;padding:9px 17px;border-radius:8px;
  background:linear-gradient(135deg,var(--gold),var(--gold-dk));
  color:#fff;border:none;font-size:.85rem;font-weight:600;
  cursor:pointer;transition:all .18s;font-family:inherit;text-decoration:none;
}
.btn-gold:hover{box-shadow:0 4px 16px rgba(226,168,75,.38);transform:translateY(-1px);}
.btn-secondary{
  display:inline-flex;align-items:center;gap:7px;padding:8px 15px;border-radius:8px;
  background:var(--surface);color:var(--text2);border:1px solid var(--border);
  font-size:.84rem;font-weight:500;cursor:pointer;transition:all .18s;font-family:inherit;text-decoration:none;
}
.btn-secondary:hover{background:var(--canvas);border-color:var(--text4);}
.btn-sm{
  display:inline-flex;align-items:center;gap:5px;padding:6px 11px;border-radius:7px;
  background:transparent;color:var(--text3);border:1px solid var(--border);
  font-size:.76rem;font-weight:600;cursor:pointer;transition:all .15s;font-family:inherit;text-decoration:none;
}
.btn-sm:hover{background:var(--canvas);color:var(--text2);border-color:var(--border2);}

/* Card */
.card{
  background:var(--surface);border:1px solid var(--border);
  border-radius:12px;box-shadow:var(--sh1);overflow:hidden;
}
.card-head{
  display:flex;align-items:center;justify-content:space-between;
  padding:14px 20px;border-bottom:1px solid var(--border);
  background:var(--surface2);
}
.card-head-title{
  display:flex;align-items:center;gap:9px;
  font-size:.9rem;font-weight:700;color:var(--text);
}
.card-head-icon{
  width:30px;height:30px;border-radius:8px;
  display:flex;align-items:center;justify-content:center;font-size:.8rem;
}

/* Table */
.e-table{width:100%;border-collapse:collapse;}
.e-table thead th{
  font-size:.66rem;font-weight:800;text-transform:uppercase;letter-spacing:1.2px;
  color:var(--text4);padding:10px 18px;
  border-bottom:2px solid var(--border);
  background:var(--surface2);white-space:nowrap;text-align:left;
}
.e-table tbody td{
  padding:12px 18px;border-bottom:1px solid var(--border);
  font-size:.88rem;color:var(--text2);vertical-align:middle;
}
.e-table tbody tr:last-child td{border-bottom:none;}
.e-table tbody tr:hover{background:var(--surface2);}
.e-table .c-main{color:var(--text);font-weight:600;}
.e-table .c-mono{font-family:'DM Mono',monospace;font-size:.83rem;}
.e-table .c-amt{font-weight:700;color:var(--gold-dk);font-family:'DM Mono',monospace;}
.e-table .c-dim{color:var(--text4);font-size:.78rem;}

/* Badges statut */
.sbadge{
  display:inline-flex;align-items:center;gap:5px;
  font-size:.7rem;font-weight:700;
  padding:3px 10px;border-radius:20px;
  white-space:nowrap;letter-spacing:.1px;
}
.sbadge::before{content:'';width:5px;height:5px;border-radius:50%;flex-shrink:0;}
.sb-ok    {background:var(--ok-bg);   color:var(--ok);   border:1px solid var(--ok-bd);}   .sb-ok::before{background:var(--ok);}
.sb-warn  {background:var(--warn-bg); color:var(--warn); border:1px solid var(--warn-bd);} .sb-warn::before{background:var(--warn);}
.sb-crit  {background:var(--crit-bg); color:var(--crit); border:1px solid var(--crit-bd);} .sb-crit::before{background:var(--crit);}
.sb-info  {background:var(--info-bg); color:var(--info); border:1px solid var(--info-bd);} .sb-info::before{background:var(--info);}
.sb-gold  {background:rgba(226,168,75,.13);color:var(--gold-dk);border:1px solid rgba(226,168,75,.3);} .sb-gold::before{background:var(--gold);}
.sb-muted {background:var(--canvas);color:var(--text3);border:1px solid var(--border);} .sb-muted::before{background:var(--text4);}

/* Items liste */
.item-row{
  display:flex;align-items:center;gap:12px;
  padding:12px 18px;border-bottom:1px solid var(--border);
  transition:background .12s;
}
.item-row:last-child{border-bottom:none;}
.item-row:hover{background:var(--surface2);}
.item-stripe{width:3px;height:34px;border-radius:2px;flex-shrink:0;}
.item-ico{width:32px;height:32px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:.82rem;flex-shrink:0;}
.item-body{flex:1;min-width:0;}
.item-title{font-size:.86rem;font-weight:600;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.item-sub{font-size:.71rem;color:var(--text4);margin-top:2px;}
.item-end{text-align:right;flex-shrink:0;}
.item-val{font-size:.88rem;font-weight:700;font-family:'DM Mono',monospace;}
.item-val-sub{font-size:.68rem;color:var(--text4);}

/* États */
.st-empty{padding:36px 20px;text-align:center;}
.st-empty i{font-size:1.8rem;color:var(--border2);display:block;margin-bottom:10px;}
.st-empty p{font-size:.84rem;color:var(--text4);}
.st-ok{padding:28px 20px;text-align:center;display:flex;flex-direction:column;align-items:center;gap:9px;}
.st-ok-icon{width:38px;height:38px;border-radius:50%;background:var(--ok-bg);display:flex;align-items:center;justify-content:center;}
.st-ok-icon i{font-size:1rem;color:var(--ok);}
.st-ok-text{font-size:.83rem;color:var(--text3);}
.st-load{padding:28px;text-align:center;color:var(--text4);}
.st-load i{font-size:1.1rem;}

/* Alertes inline */
#alert-zone{margin-bottom:16px;}
.al-ok {background:var(--ok-bg);  border:1px solid var(--ok-bd);  border-left:3px solid var(--ok);  border-radius:9px;padding:11px 15px;font-size:.85rem;color:var(--ok);  display:flex;align-items:center;gap:9px;}
.al-err{background:var(--crit-bg);border:1px solid var(--crit-bd);border-left:3px solid var(--crit);border-radius:9px;padding:11px 15px;font-size:.85rem;color:var(--crit);display:flex;align-items:center;gap:9px;}

/* Responsive */
@media(max-width:1024px){.app-wrap{margin-left:0;}.sidebar{transform:translateX(-100%);transition:transform .22s;}.sidebar.open{transform:translateX(0);}}
@media(max-width:640px){.page{padding:16px 14px 50px;}.t-brand{width:auto;}}
</style>
</head>
<body>

<!-- TOPBAR -->
<header class="topbar">
  <a href="dashboard.php" class="t-brand">
    <div class="t-mark"><i class="fas fa-warehouse"></i></div>
    <div class="t-name">Gestion<b>Approv</b></div>
  </a>
  <div class="t-center">
    <div class="t-crumb">
      <i class="fas fa-home" style="font-size:.7rem;color:rgba(226,168,75,.38);"></i>
      <i class="fas fa-chevron-right t-crumb-sep"></i>
      <span class="t-crumb-cur" id="nav-page-title">Tableau de bord</span>
    </div>
  </div>
  <div class="t-right">
    <a href="articles.php" class="t-btn" title="Alertes stock">
      <i class="fas fa-bell"></i>
      <div class="t-pip" id="bell-pip"></div>
    </a>
    <div class="t-div"></div>
    <div class="t-user" onclick="this.querySelector('.t-drop').classList.toggle('open')">
      <div class="t-av"><?= $_init ?></div>
      <div>
        <div class="t-uname"><?= $_nom ?></div>
        <div class="t-urole"><?= $_role ?></div>
      </div>
      <i class="fas fa-chevron-down t-ucaret"></i>
      <div class="t-drop">
        <div class="dd-head">
          <div class="dd-head-name"><?= $_nom ?></div>
          <div class="dd-head-email"><?= $_mail ?></div>
        </div>
        <div class="dd-sep"></div>
        <a href="logout.php" class="dd-it dx"><i class="fas fa-right-from-bracket"></i>Se déconnecter</a>
      </div>
    </div>
  </div>
</header>

<div class="app-wrap">