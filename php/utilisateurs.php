<?php
require_once 'includes/config.php';
if(empty($_SESSION['user'])){header('Location: login.php');exit;}
$_uid  = $_SESSION['user']['id'] ?? $_SESSION['user']['id_utilisateur'] ?? 0;
$_role = $_SESSION['user']['role'] ?? 'magasinier';
/* Seul un admin peut accéder à cette page */
if($_role !== 'admin'){header('Location: dashboard.php');exit;}
?>
<?php require_once 'includes/header.php'; ?>
<?php require_once 'includes/sidebar.php'; ?>
<script>
  if(document.getElementById('nav-page-title'))
    document.getElementById('nav-page-title').textContent = 'Utilisateurs';
</script>

<style>
/* ─── Layout ─── */
.ph{display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:14px;margin-bottom:22px;}
.ph-title{font-size:1.3rem;font-weight:700;color:var(--text);letter-spacing:-.3px;}
.ph-sub{font-size:.82rem;color:var(--text3);margin-top:4px;}
.ph-right{display:flex;align-items:center;gap:8px;}

/* ─── Boutons ─── */
.btn-gold{display:inline-flex;align-items:center;gap:8px;padding:9px 18px;border-radius:9px;background:var(--gold,#E2A84B);color:#fff;font-family:inherit;font-size:.86rem;font-weight:700;cursor:pointer;border:none;transition:filter .15s;}
.btn-gold:hover{filter:brightness(1.08);}
.btn-gold:disabled{opacity:.5;cursor:default;}
.btn-secondary{display:inline-flex;align-items:center;gap:8px;padding:9px 16px;border-radius:9px;background:transparent;color:var(--text2,#3a4a5c);font-family:inherit;font-size:.86rem;font-weight:600;cursor:pointer;border:1px solid var(--border,#e4e8f0);transition:background .15s;}
.btn-secondary:hover{background:var(--surface2,#f4f6fb);}
.btn-danger{display:inline-flex;align-items:center;gap:8px;padding:9px 16px;border-radius:9px;background:#fff0f0;color:#c03030;font-family:inherit;font-size:.86rem;font-weight:700;cursor:pointer;border:1.5px solid #f8b8b8;transition:all .15s;}
.btn-danger:hover{background:#ffe0e0;}
.btn-danger:disabled{opacity:.4;cursor:not-allowed;}

/* ─── KPI strip ─── */
.kstrip{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:20px;}
@media(max-width:900px){.kstrip{grid-template-columns:repeat(2,1fr);}}
@media(max-width:480px){.kstrip{grid-template-columns:1fr;}}
.ks{background:var(--surface,#fff);border:1px solid var(--border,#e4e8f0);border-radius:11px;padding:14px 16px;display:flex;align-items:center;gap:12px;box-shadow:0 1px 4px rgba(5,8,15,.06);}
.ks-ico{width:38px;height:38px;border-radius:9px;display:flex;align-items:center;justify-content:center;font-size:.9rem;flex-shrink:0;}
.ks-n{font-size:1.55rem;font-weight:800;color:var(--text,#0D1526);letter-spacing:-1px;line-height:1;}
.ks-l{font-size:.73rem;color:var(--text3,#8FA4BF);margin-top:3px;}

/* ─── Toolbar ─── */
.toolbar{display:flex;align-items:center;gap:10px;flex-wrap:wrap;background:var(--surface,#fff);border:1px solid var(--border,#e4e8f0);border-radius:11px;padding:12px 16px;margin-bottom:16px;box-shadow:0 1px 4px rgba(5,8,15,.06);}
.tsearch{display:flex;align-items:center;gap:9px;background:var(--canvas,#f8f9fc);border:1px solid var(--border,#e4e8f0);border-radius:8px;padding:0 12px;flex:1;min-width:220px;max-width:340px;transition:border-color .18s;}
.tsearch:focus-within{border-color:var(--gold,#E2A84B);}
.tsearch i{color:var(--text4,#aab5c6);font-size:.82rem;}
.tsearch input{border:none;background:transparent;outline:none;font-family:inherit;font-size:.88rem;color:var(--text,#0D1526);padding:8px 0;width:100%;}
.tsearch input::placeholder{color:var(--text4,#aab5c6);}
.tsel{display:flex;align-items:center;gap:7px;padding:7px 12px;border-radius:8px;background:var(--canvas,#f8f9fc);border:1px solid var(--border,#e4e8f0);font-size:.82rem;color:var(--text2,#3a4a5c);font-family:inherit;}
.tsel select{border:none;background:transparent;outline:none;font-family:inherit;font-size:.82rem;color:var(--text2,#3a4a5c);cursor:pointer;}
.tb-sep{width:1px;height:24px;background:var(--border,#e4e8f0);margin:0 2px;}
.tb-count{font-size:.78rem;color:var(--text4,#aab5c6);white-space:nowrap;margin-left:auto;}

/* ─── Grille utilisateurs (cartes) ─── */
.users-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:16px;}
.user-card{background:var(--surface,#fff);border:1px solid var(--border,#e4e8f0);border-radius:13px;padding:18px;box-shadow:0 1px 4px rgba(5,8,15,.06);transition:box-shadow .15s;cursor:pointer;position:relative;}
.user-card:hover{box-shadow:0 4px 16px rgba(5,8,15,.1);}
.user-card.inactive{opacity:.65;}
.user-card-top{display:flex;align-items:center;gap:13px;margin-bottom:14px;}
.user-avatar{width:46px;height:46px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;font-weight:800;flex-shrink:0;letter-spacing:-.5px;}
.avatar-admin{background:rgba(226,168,75,.15);color:#b87220;}
.avatar-gestionnaire{background:rgba(26,127,212,.15);color:#1a7fd4;}
.avatar-magasinier{background:rgba(26,138,74,.15);color:#1a8a4a;}
.user-info{flex:1;min-width:0;}
.user-name{font-size:.95rem;font-weight:700;color:var(--text,#0D1526);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.user-email{font-size:.78rem;color:var(--text3,#8FA4BF);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;margin-top:2px;}
.user-card-badges{display:flex;align-items:center;gap:6px;flex-wrap:wrap;margin-bottom:12px;}
.user-card-meta{display:flex;align-items:center;justify-content:space-between;font-size:.76rem;color:var(--text4,#aab5c6);}
.user-card-actions{display:flex;align-items:center;gap:6px;}
.uca{width:30px;height:30px;border-radius:7px;display:flex;align-items:center;justify-content:center;font-size:.72rem;cursor:pointer;border:1px solid var(--border,#e4e8f0);background:var(--canvas,#f8f9fc);color:var(--text3,#8FA4BF);transition:all .15s;}
.uca.edit:hover{background:#fff8e6;border-color:#f0c160;color:#b07000;}
.uca.pwd:hover{background:#f3f0ff;border-color:#c0b0f0;color:#6a3fbf;}
.uca.del:hover{background:#fff0f0;border-color:#f8b8b8;color:#c03030;}
.uca:disabled,.uca.disabled{opacity:.3;cursor:not-allowed;pointer-events:none;}
/* Badge "vous" */
.you-badge{position:absolute;top:12px;right:12px;font-size:.65rem;font-weight:700;background:rgba(226,168,75,.15);color:#b87220;padding:2px 8px;border-radius:10px;}

/* ─── Modals ─── */
.modal-bg{position:fixed;inset:0;z-index:900;background:rgba(5,8,15,.55);backdrop-filter:blur(4px);display:none;align-items:center;justify-content:center;padding:20px;}
.modal-bg.open{display:flex;animation:bgIn .2s ease;}
@keyframes bgIn{from{opacity:0}to{opacity:1}}
.modal{background:var(--surface,#fff);border:1px solid var(--border,#e4e8f0);border-radius:16px;width:100%;max-width:520px;box-shadow:0 8px 40px rgba(5,8,15,.18);animation:mIn .22s ease;max-height:92vh;overflow-y:auto;}
@keyframes mIn{from{opacity:0;transform:translateY(-12px) scale(.98)}to{opacity:1;transform:none}}
.modal-head{display:flex;align-items:center;justify-content:space-between;padding:18px 22px;border-bottom:1px solid var(--border,#e4e8f0);position:sticky;top:0;background:var(--surface,#fff);z-index:1;}
.modal-title{display:flex;align-items:center;gap:10px;font-size:.98rem;font-weight:700;color:var(--text,#0D1526);}
.modal-ico{width:32px;height:32px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:.82rem;}
.modal-close{width:30px;height:30px;border-radius:7px;display:flex;align-items:center;justify-content:center;font-size:.8rem;cursor:pointer;background:transparent;border:1px solid transparent;color:var(--text3,#8FA4BF);transition:all .15s;}
.modal-close:hover{background:#fff0f0;border-color:#f8b8b8;color:#e03e3e;}
.modal-body{padding:22px;}
.modal-footer{display:flex;align-items:center;justify-content:flex-end;gap:8px;padding:16px 22px;border-top:1px solid var(--border,#e4e8f0);background:var(--surface2,#f4f6fb);}

/* ─── Formulaire ─── */
.frow{display:grid;gap:14px;margin-bottom:14px;}
.frow-2{grid-template-columns:1fr 1fr;}
@media(max-width:540px){.frow-2{grid-template-columns:1fr;}}
.fgroup{display:flex;flex-direction:column;gap:5px;}
.flabel{font-size:.75rem;font-weight:700;color:var(--text2,#3a4a5c);}
.flabel span{color:#e03e3e;margin-left:2px;}
.finput,.fselect{width:100%;padding:9px 12px;border-radius:8px;border:1.5px solid var(--border,#e4e8f0);background:var(--canvas,#f8f9fc);color:var(--text,#0D1526);font-family:inherit;font-size:.88rem;transition:border-color .18s;outline:none;}
.finput:focus,.fselect:focus{border-color:var(--gold,#E2A84B);background:var(--surface,#fff);box-shadow:0 0 0 3px rgba(226,168,75,.1);}
.finput.error,.fselect.error{border-color:#e03e3e;}
.ferr{font-size:.72rem;color:#e03e3e;margin-top:3px;display:none;}
.ferr.show{display:block;}
.fsec-title{font-size:.68rem;font-weight:800;text-transform:uppercase;letter-spacing:1.5px;color:var(--text4,#aab5c6);margin-bottom:12px;}

/* Indicateur force mot de passe */
.pwd-strength{margin-top:6px;height:4px;border-radius:2px;background:var(--border,#e4e8f0);overflow:hidden;}
.pwd-bar{height:100%;border-radius:2px;transition:width .3s,background .3s;width:0;}
.pwd-hint{font-size:.7rem;color:var(--text4,#aab5c6);margin-top:4px;}

/* Input password wrapper */
.pwd-wrap{position:relative;}
.pwd-wrap .finput{padding-right:38px;}
.pwd-toggle{position:absolute;right:10px;top:50%;transform:translateY(-50%);cursor:pointer;color:var(--text4,#aab5c6);font-size:.82rem;transition:color .15s;}
.pwd-toggle:hover{color:var(--text2,#3a4a5c);}

/* ─── Role selector ─── */
.role-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:8px;}
.role-btn{display:flex;flex-direction:column;align-items:center;gap:6px;padding:11px 8px;border-radius:9px;border:1.5px solid var(--border,#e4e8f0);background:var(--canvas,#f8f9fc);cursor:pointer;transition:all .15s;font-family:inherit;}
.role-btn:hover{background:var(--surface2,#f4f6fb);}
.role-btn.selected.admin{border-color:#b87220;background:rgba(226,168,75,.1);}
.role-btn.selected.gestionnaire{border-color:#1a7fd4;background:rgba(26,127,212,.1);}
.role-btn.selected.magasinier{border-color:#1a8a4a;background:rgba(26,138,74,.1);}
.role-ico{width:32px;height:32px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:.82rem;}
.role-lbl{font-size:.74rem;font-weight:700;color:var(--text2,#3a4a5c);}
.role-desc{font-size:.65rem;color:var(--text4,#aab5c6);text-align:center;line-height:1.3;}

/* ─── Statut toggle ─── */
.statut-toggle{display:flex;align-items:center;gap:10px;background:var(--canvas,#f8f9fc);border:1px solid var(--border,#e4e8f0);border-radius:9px;padding:11px 14px;}
.toggle-track{width:42px;height:22px;border-radius:11px;background:var(--border,#e4e8f0);cursor:pointer;transition:background .2s;flex-shrink:0;position:relative;}
.toggle-track.on{background:#1a8a4a;}
.toggle-thumb{position:absolute;top:3px;left:3px;width:16px;height:16px;border-radius:50%;background:#fff;transition:transform .2s;box-shadow:0 1px 3px rgba(0,0,0,.2);}
.toggle-track.on .toggle-thumb{transform:translateX(20px);}
.toggle-text{font-size:.84rem;font-weight:600;color:var(--text2,#3a4a5c);}
.toggle-sub{font-size:.72rem;color:var(--text4,#aab5c6);}

/* ─── Modal confirmation désactivation ─── */
.confirm-body{text-align:center;padding:8px 0 16px;}
.confirm-ico{width:56px;height:56px;border-radius:14px;background:#fff0f0;display:flex;align-items:center;justify-content:center;font-size:1.4rem;color:#c03030;margin:0 auto 14px;}
.confirm-title{font-size:1rem;font-weight:700;color:var(--text,#0D1526);margin-bottom:8px;}
.confirm-text{font-size:.86rem;color:var(--text3,#8FA4BF);line-height:1.5;}

/* ─── Badges ─── */
.sbadge{display:inline-flex;align-items:center;padding:3px 10px;border-radius:20px;font-size:.72rem;font-weight:700;white-space:nowrap;}
.sb-ok{background:#eafaf1;color:#1a8a4a;}
.sb-muted{background:#f0f2f5;color:#7a8a9a;}
.sb-gold{background:#fff8e6;color:#b07000;}
.sb-blue{background:#ebf5ff;color:#1a7fd4;}

/* ─── Toast ─── */
.toast-wrap{position:fixed;bottom:24px;right:24px;z-index:9999;display:flex;flex-direction:column;gap:8px;pointer-events:none;}
.toast{display:flex;align-items:center;gap:10px;padding:12px 18px;border-radius:10px;font-size:.86rem;font-weight:600;box-shadow:0 4px 20px rgba(5,8,15,.2);animation:tIn .25s ease;pointer-events:all;max-width:320px;}
@keyframes tIn{from{opacity:0;transform:translateX(20px)}to{opacity:1;transform:none}}
.toast.ok{background:#1a8a4a;color:#fff;}
.toast.err{background:#c03030;color:#fff;}

/* ─── Empty ─── */
.empty-state{padding:52px 20px;text-align:center;}
.empty-ico{width:56px;height:56px;border-radius:14px;background:var(--canvas,#f8f9fc);border:1px solid var(--border,#e4e8f0);display:flex;align-items:center;justify-content:center;font-size:1.4rem;color:var(--text4,#aab5c6);margin:0 auto 14px;}
.empty-title{font-size:.95rem;font-weight:700;color:var(--text,#0D1526);margin-bottom:6px;}
.empty-text{font-size:.82rem;color:var(--text3,#8FA4BF);}
</style>

<div class="toast-wrap" id="toast-wrap"></div>

<!-- ═══ EN-TÊTE ═══ -->
<div class="ph">
  <div>
    <div class="ph-title">
      <i class="fas fa-users" style="color:var(--gold,#E2A84B);margin-right:9px;"></i>Utilisateurs
    </div>
    <div class="ph-sub">Gestion des comptes et des accès</div>
  </div>
  <div class="ph-right">
    <button class="btn-gold" onclick="openCreate()">
      <i class="fas fa-user-plus"></i> Nouvel utilisateur
    </button>
  </div>
</div>

<!-- ═══ KPI STRIP ═══ -->
<div class="kstrip">
  <div class="ks">
    <div class="ks-ico" style="background:#eafaf1;color:#1a8a4a;"><i class="fas fa-users"></i></div>
    <div><div class="ks-n" id="ks-total">—</div><div class="ks-l">Utilisateurs actifs</div></div>
  </div>
  <div class="ks">
    <div class="ks-ico" style="background:rgba(226,168,75,.15);color:#b87220;"><i class="fas fa-shield-halved"></i></div>
    <div><div class="ks-n" id="ks-admin">—</div><div class="ks-l">Administrateurs</div></div>
  </div>
  <div class="ks">
    <div class="ks-ico" style="background:#ebf5ff;color:#1a7fd4;"><i class="fas fa-user-tie"></i></div>
    <div><div class="ks-n" id="ks-gest">—</div><div class="ks-l">Gestionnaires</div></div>
  </div>
  <div class="ks">
    <div class="ks-ico" style="background:#f3f0ff;color:#6a3fbf;"><i class="fas fa-clock-rotate-left"></i></div>
    <div><div class="ks-n" id="ks-recent">—</div><div class="ks-l">Connectés (7j)</div></div>
  </div>
</div>

<!-- ═══ TOOLBAR ═══ -->
<div class="toolbar">
  <div class="tsearch">
    <i class="fas fa-search"></i>
    <input type="text" id="search-input" placeholder="Nom, email…" oninput="filterUsers()">
  </div>
  <div class="tsel">
    <i class="fas fa-filter" style="font-size:.76rem;"></i>
    <select id="filter-role" onchange="filterUsers()">
      <option value="">Tous les rôles</option>
      <option value="admin">Administrateur</option>
      <option value="gestionnaire">Gestionnaire</option>
      <option value="magasinier">Magasinier</option>
    </select>
  </div>
  <div class="tsel">
    <i class="fas fa-circle" style="font-size:.5rem;"></i>
    <select id="filter-statut" onchange="filterUsers()">
      <option value="actif">Actifs seulement</option>
      <option value="">Tous</option>
      <option value="inactif">Inactifs</option>
    </select>
  </div>
  <div class="tb-sep"></div>
  <div class="tb-count" id="tb-count">Chargement…</div>
</div>

<!-- ═══ GRILLE ═══ -->
<div id="users-grid" class="users-grid">
  <div style="grid-column:1/-1;padding:40px;text-align:center;color:var(--text4,#aab5c6);">
    <i class="fas fa-spinner fa-spin"></i> Chargement…
  </div>
</div>


<!-- ════════════════════════════════════
     MODAL CRÉER / MODIFIER
════════════════════════════════════ -->
<div class="modal-bg" id="modal-form">
  <div class="modal">
    <div class="modal-head">
      <div class="modal-title">
        <div class="modal-ico" id="form-ico" style="background:rgba(226,168,75,.12);color:#b87220;">
          <i class="fas fa-user-plus"></i>
        </div>
        <span id="form-titre">Nouvel utilisateur</span>
      </div>
      <button class="modal-close" onclick="closeModal('modal-form')"><i class="fas fa-xmark"></i></button>
    </div>
    <div class="modal-body">
      <input type="hidden" id="f-id">

      <!-- Nom & Email -->
      <div class="frow frow-2">
        <div class="fgroup">
          <label class="flabel">Nom complet <span>*</span></label>
          <input type="text" class="finput" id="f-nom" placeholder="Prénom Nom">
          <div class="ferr" id="e-nom">Champ requis</div>
        </div>
        <div class="fgroup">
          <label class="flabel">Email <span>*</span></label>
          <input type="email" class="finput" id="f-email" placeholder="user@domaine.cm">
          <div class="ferr" id="e-email">Email invalide</div>
        </div>
      </div>

      <!-- Rôle -->
      <div class="fgroup" style="margin-bottom:14px;">
        <label class="flabel">Rôle <span>*</span></label>
        <div class="role-grid">
          <button class="role-btn admin selected" data-role="admin" onclick="selectRole('admin')">
            <span class="role-ico" style="background:rgba(226,168,75,.15);color:#b87220;"><i class="fas fa-shield-halved"></i></span>
            <span class="role-lbl">Admin</span>
            <span class="role-desc">Accès complet</span>
          </button>
          <button class="role-btn gestionnaire" data-role="gestionnaire" onclick="selectRole('gestionnaire')">
            <span class="role-ico" style="background:#ebf5ff;color:#1a7fd4;"><i class="fas fa-user-tie"></i></span>
            <span class="role-lbl">Gestionnaire</span>
            <span class="role-desc">Commandes & paiements</span>
          </button>
          <button class="role-btn magasinier" data-role="magasinier" onclick="selectRole('magasinier')">
            <span class="role-ico" style="background:#eafaf1;color:#1a8a4a;"><i class="fas fa-boxes-stacked"></i></span>
            <span class="role-lbl">Magasinier</span>
            <span class="role-desc">Stock & réceptions</span>
          </button>
        </div>
        <div class="ferr" id="e-role">Sélectionnez un rôle</div>
      </div>

      <!-- Statut (visible en mode édition) -->
      <div id="statut-wrap" style="display:none;margin-bottom:14px;">
        <label class="flabel" style="display:block;margin-bottom:8px;">Statut du compte</label>
        <div class="statut-toggle" onclick="toggleStatut()">
          <div class="toggle-track" id="toggle-track">
            <div class="toggle-thumb"></div>
          </div>
          <div>
            <div class="toggle-text" id="toggle-text">Actif</div>
            <div class="toggle-sub">Cliquez pour changer</div>
          </div>
        </div>
        <input type="hidden" id="f-statut" value="actif">
      </div>

      <!-- Mot de passe (création uniquement) -->
      <div id="pwd-creation-wrap">
        <div class="fgroup" style="margin-bottom:8px;">
          <label class="flabel">Mot de passe <span>*</span></label>
          <div class="pwd-wrap">
            <input type="password" class="finput" id="f-pwd" placeholder="Min. 8 caractères"
              oninput="checkPwdStrength(this.value)">
            <span class="pwd-toggle" onclick="togglePwdViz('f-pwd',this)">
              <i class="fas fa-eye"></i>
            </span>
          </div>
          <div class="pwd-strength"><div class="pwd-bar" id="pwd-bar"></div></div>
          <div class="pwd-hint" id="pwd-hint">Saisissez un mot de passe</div>
          <div class="ferr" id="e-pwd">Mot de passe requis (8 car. min.)</div>
        </div>
        <div class="fgroup" style="margin-bottom:14px;">
          <label class="flabel">Confirmer le mot de passe <span>*</span></label>
          <div class="pwd-wrap">
            <input type="password" class="finput" id="f-pwd2" placeholder="Retapez le mot de passe">
            <span class="pwd-toggle" onclick="togglePwdViz('f-pwd2',this)">
              <i class="fas fa-eye"></i>
            </span>
          </div>
          <div class="ferr" id="e-pwd2">Les mots de passe ne correspondent pas</div>
        </div>
      </div>

    </div>
    <div class="modal-footer">
      <button class="btn-secondary" onclick="closeModal('modal-form')">Annuler</button>
      <button class="btn-gold" id="btn-form-save" onclick="saveUser()">
        <i class="fas fa-check"></i> Créer l'utilisateur
      </button>
    </div>
  </div>
</div>


<!-- ════════════════════════════════════
     MODAL CHANGER MOT DE PASSE
     PUT /api/utilisateurs/:id/password
════════════════════════════════════ -->
<div class="modal-bg" id="modal-pwd">
  <div class="modal" style="max-width:420px;">
    <div class="modal-head">
      <div class="modal-title">
        <div class="modal-ico" style="background:#f3f0ff;color:#6a3fbf;"><i class="fas fa-key"></i></div>
        Changer le mot de passe
      </div>
      <button class="modal-close" onclick="closeModal('modal-pwd')"><i class="fas fa-xmark"></i></button>
    </div>
    <div class="modal-body">
      <input type="hidden" id="pwd-user-id">
      <div style="background:var(--canvas,#f8f9fc);border:1px solid var(--border,#e4e8f0);border-radius:9px;padding:11px 14px;margin-bottom:16px;font-size:.86rem;color:var(--text2,#3a4a5c);">
        <i class="fas fa-user" style="margin-right:7px;color:var(--text4,#aab5c6);"></i>
        <span id="pwd-user-name">—</span>
      </div>
      <div class="fgroup" style="margin-bottom:8px;">
        <label class="flabel">Nouveau mot de passe <span>*</span></label>
        <div class="pwd-wrap">
          <input type="password" class="finput" id="new-pwd" placeholder="Min. 8 caractères"
            oninput="checkPwdStrength2(this.value)">
          <span class="pwd-toggle" onclick="togglePwdViz('new-pwd',this)">
            <i class="fas fa-eye"></i>
          </span>
        </div>
        <div class="pwd-strength"><div class="pwd-bar" id="pwd-bar2"></div></div>
        <div class="ferr" id="e-new-pwd">Mot de passe trop court (8 car. min.)</div>
      </div>
      <div class="fgroup">
        <label class="flabel">Confirmer <span>*</span></label>
        <div class="pwd-wrap">
          <input type="password" class="finput" id="new-pwd2" placeholder="Retapez">
          <span class="pwd-toggle" onclick="togglePwdViz('new-pwd2',this)">
            <i class="fas fa-eye"></i>
          </span>
        </div>
        <div class="ferr" id="e-new-pwd2">Les mots de passe ne correspondent pas</div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn-secondary" onclick="closeModal('modal-pwd')">Annuler</button>
      <button class="btn-gold" id="btn-pwd-save" onclick="savePwd()"
        style="background:#6a3fbf;">
        <i class="fas fa-key"></i> Changer le mot de passe
      </button>
    </div>
  </div>
</div>


<!-- ════════════════════════════════════
     MODAL CONFIRMATION DÉSACTIVATION
     DELETE /api/utilisateurs/:id → statut=inactif
════════════════════════════════════ -->
<div class="modal-bg" id="modal-confirm">
  <div class="modal" style="max-width:420px;">
    <div class="modal-head">
      <div class="modal-title">
        <div class="modal-ico" style="background:#fff0f0;color:#c03030;"><i class="fas fa-user-slash"></i></div>
        Désactiver l'utilisateur
      </div>
      <button class="modal-close" onclick="closeModal('modal-confirm')"><i class="fas fa-xmark"></i></button>
    </div>
    <div class="modal-body">
      <input type="hidden" id="del-id">
      <div class="confirm-body">
        <div class="confirm-ico"><i class="fas fa-user-slash"></i></div>
        <div class="confirm-title">Désactiver <span id="del-nom">cet utilisateur</span> ?</div>
        <div class="confirm-text">
          Le compte sera désactivé (pas supprimé).<br>
          L'utilisateur ne pourra plus se connecter.<br>
          Vous pourrez le réactiver depuis la fiche.
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn-secondary" onclick="closeModal('modal-confirm')">Annuler</button>
      <button class="btn-danger" id="btn-del-confirm" onclick="confirmDel()">
        <i class="fas fa-user-slash"></i> Désactiver
      </button>
    </div>
  </div>
</div>


<!-- ════════════════════════════════════
     JAVASCRIPT
════════════════════════════════════ -->
<script>
/* ════════════════════════════════════════
   CONFIG
   API :  GET    /api/utilisateurs
          GET    /api/utilisateurs/:id
          POST   /api/utilisateurs
          PUT    /api/utilisateurs/:id           → nom, email, role, statut
          PUT    /api/utilisateurs/:id/password  → mot_de_passe
          DELETE /api/utilisateurs/:id           → désactivation douce (statut=inactif)

   Champs retournés (jamais mot_de_passe) :
     id_utilisateur, nom, email, role, statut,
     derniere_connexion, created_at

   ENUM role   : admin | gestionnaire | magasinier
   ENUM statut : actif | inactif
════════════════════════════════════════ */
var API  = 'http://localhost:3000/api';
var UID  = <?= (int)$_uid ?>;   /* id de l'utilisateur connecté — ne peut pas se désactiver */

var _all    = [];
var _shown  = [];
var _roleSelectionne = 'admin';
var _editMode = false;  /* true = édition, false = création */

/* ════════════════════════════════════════
   UTILITAIRES
════════════════════════════════════════ */
function extractDate(d) {
  if (!d) return null;
  var s = String(d);
  if (/^\d{4}-\d{2}-\d{2}$/.test(s)) return s;
  var dt = new Date(s);
  if (isNaN(dt.getTime())) return null;
  return dt.getFullYear() + '-'
    + String(dt.getMonth() + 1).padStart(2, '0') + '-'
    + String(dt.getDate()).padStart(2, '0');
}
function parseDateMidnight(d) {
  var s = extractDate(d);
  if (!s) return null;
  var p = s.split('-');
  return new Date(parseInt(p[0]), parseInt(p[1]) - 1, parseInt(p[2]));
}
function today() { var n = new Date(); return new Date(n.getFullYear(), n.getMonth(), n.getDate()); }
function fmtDate(d) {
  var s = extractDate(d);
  if (!s) return '—';
  var p = s.split('-');
  return p[2] + '/' + p[1] + '/' + p[0];
}
function fmtDatetime(d) {
  if (!d) return '—';
  var dt = new Date(d);
  if (isNaN(dt.getTime())) return String(d);
  return dt.toLocaleDateString('fr-FR', { day:'2-digit', month:'2-digit', year:'numeric' })
    + ' ' + dt.toLocaleTimeString('fr-FR', { hour:'2-digit', minute:'2-digit' });
}
function esc(s) {
  return String(s == null ? '' : s)
    .replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}
function showToast(msg, type) {
  type = type || 'ok';
  var wrap = document.getElementById('toast-wrap');
  var div  = document.createElement('div');
  div.className = 'toast ' + type;
  div.innerHTML = '<i class="fas fa-' + (type==='ok' ? 'circle-check' : 'circle-exclamation') + '"></i> ' + esc(msg);
  wrap.appendChild(div);
  setTimeout(function() {
    div.style.cssText += 'opacity:0;transform:translateX(20px);transition:all .3s;';
    setTimeout(function(){ if(div.parentNode) div.parentNode.removeChild(div); }, 300);
  }, 3500);
}
function showSuccess(m) { showToast(m, 'ok'); }
function showError(m)   { showToast(m, 'err'); }

/* Initiales pour l'avatar */
function initiales(nom) {
  var parts = String(nom || '?').trim().split(' ');
  if (parts.length >= 2) return (parts[0][0] + parts[parts.length-1][0]).toUpperCase();
  return String(nom || '?').slice(0, 2).toUpperCase();
}

/* Badges rôle */
var ROLES = {
  admin:        { cls: 'sb-gold', lbl: 'Admin',       avatarCls: 'avatar-admin' },
  gestionnaire: { cls: 'sb-blue', lbl: 'Gestionnaire',avatarCls: 'avatar-gestionnaire' },
  magasinier:   { cls: 'sb-ok',   lbl: 'Magasinier',  avatarCls: 'avatar-magasinier' }
};
function badgeRole(r) {
  var e = ROLES[r] || { cls: 'sb-muted', lbl: r || '—' };
  return '<span class="sbadge ' + e.cls + '">' + e.lbl + '</span>';
}
function badgeStatut(s) {
  return s === 'actif'
    ? '<span class="sbadge sb-ok">Actif</span>'
    : '<span class="sbadge sb-muted">Inactif</span>';
}

/* Force mot de passe */
function checkPwdStrength(pwd) {
  var score = 0;
  if (pwd.length >= 8)  score++;
  if (/[A-Z]/.test(pwd)) score++;
  if (/[0-9]/.test(pwd)) score++;
  if (/[^A-Za-z0-9]/.test(pwd)) score++;
  var bar   = document.getElementById('pwd-bar');
  var hint  = document.getElementById('pwd-hint');
  var colors = ['#e03e3e','#c87000','#E2A84B','#1a8a4a'];
  var hints  = ['Trop court','Faible','Moyen','Fort'];
  bar.style.width     = (score * 25) + '%';
  bar.style.background = colors[score - 1] || '#e4e8f0';
  hint.textContent    = pwd.length === 0 ? 'Saisissez un mot de passe' : (hints[score - 1] || 'Très fort');
}
function checkPwdStrength2(pwd) {
  var score = 0;
  if (pwd.length >= 8)  score++;
  if (/[A-Z]/.test(pwd)) score++;
  if (/[0-9]/.test(pwd)) score++;
  if (/[^A-Za-z0-9]/.test(pwd)) score++;
  var bar   = document.getElementById('pwd-bar2');
  var colors = ['#e03e3e','#c87000','#E2A84B','#1a8a4a'];
  bar.style.width      = (score * 25) + '%';
  bar.style.background = colors[score - 1] || '#e4e8f0';
}
function togglePwdViz(inputId, btn) {
  var inp = document.getElementById(inputId);
  var ic  = btn.querySelector('i');
  if (inp.type === 'password') {
    inp.type = 'text';
    ic.className = 'fas fa-eye-slash';
  } else {
    inp.type = 'password';
    ic.className = 'fas fa-eye';
  }
}

/* ════════════════════════════════════════
   CHARGEMENT
════════════════════════════════════════ */
function load() {
  return fetch(API + '/utilisateurs').then(function(r){ return r.json(); }).then(function(resp) {
    _all = resp.data || resp || [];
    computeKPIs();
    filterUsers();
  }).catch(function() {
    document.getElementById('users-grid').innerHTML =
      '<div style="grid-column:1/-1;" class="empty-state">' +
      '<div class="empty-ico"><i class="fas fa-wifi-slash"></i></div>' +
      '<div class="empty-title">Erreur de connexion</div>' +
      '<div class="empty-text">Impossible de joindre l\'API (port 3000).</div></div>';
    document.getElementById('tb-count').textContent = '—';
  });
}

/* ════════════════════════════════════════
   KPIs
════════════════════════════════════════ */
function computeKPIs() {
  var actifs = 0, admins = 0, gests = 0, recents = 0;
  var sevenDaysAgo = today().getTime() - 7 * 86400000;
  _all.forEach(function(u) {
    if (u.statut === 'actif') actifs++;
    if (u.role   === 'admin')       admins++;
    if (u.role   === 'gestionnaire') gests++;
    if (u.derniere_connexion && parseDateMidnight(u.derniere_connexion) >= sevenDaysAgo) recents++;
  });
  document.getElementById('ks-total').textContent  = actifs;
  document.getElementById('ks-admin').textContent  = admins;
  document.getElementById('ks-gest').textContent   = gests;
  document.getElementById('ks-recent').textContent = recents;
}

/* ════════════════════════════════════════
   FILTRE & RENDU GRILLE
════════════════════════════════════════ */
function filterUsers() {
  var q  = document.getElementById('search-input').value.trim().toLowerCase();
  var r  = document.getElementById('filter-role').value;
  var s  = document.getElementById('filter-statut').value;

  _shown = _all.filter(function(u) {
    var mq = !q || (u.nom||'').toLowerCase().indexOf(q) >= 0
              || (u.email||'').toLowerCase().indexOf(q) >= 0;
    var mr = !r || u.role === r;
    var ms = !s || u.statut === s;
    return mq && mr && ms;
  });

  document.getElementById('tb-count').textContent =
    _shown.length + ' utilisateur' + (_shown.length !== 1 ? 's' : '');
  renderGrid();
}

function renderGrid() {
  var grid = document.getElementById('users-grid');
  if (!_shown.length) {
    grid.innerHTML = '<div style="grid-column:1/-1;" class="empty-state">'
      + '<div class="empty-ico"><i class="fas fa-users"></i></div>'
      + '<div class="empty-title">Aucun utilisateur trouvé</div>'
      + '<div class="empty-text">' + (_all.length === 0
          ? 'Créez votre premier utilisateur.'
          : 'Aucun résultat pour cette recherche.') + '</div></div>';
    return;
  }
  var now7 = today().getTime() - 7 * 86400000;
  var html = '';
  _shown.forEach(function(u) {
    var cfg       = ROLES[u.role] || { avatarCls:'avatar-magasinier', cls:'sb-muted', lbl:u.role };
    var isMe      = String(u.id_utilisateur) === String(UID);
    var inactive  = u.statut === 'inactif';
    var lastConn  = u.derniere_connexion ? fmtDatetime(u.derniere_connexion) : 'Jamais';
    var recent    = u.derniere_connexion && parseDateMidnight(u.derniere_connexion) >= new Date(now7);

    html += '<div class="user-card' + (inactive ? ' inactive' : '') + '">'
      + (isMe ? '<div class="you-badge"><i class="fas fa-circle-user" style="margin-right:3px;font-size:.5rem;"></i>Vous</div>' : '')
      + '<div class="user-card-top">'
      +   '<div class="user-avatar ' + cfg.avatarCls + '">' + initiales(u.nom) + '</div>'
      +   '<div class="user-info">'
      +     '<div class="user-name">' + esc(u.nom) + '</div>'
      +     '<div class="user-email">' + esc(u.email) + '</div>'
      +   '</div>'
      + '</div>'
      + '<div class="user-card-badges">'
      +   badgeRole(u.role) + ' ' + badgeStatut(u.statut)
      +   (recent ? ' <span class="sbadge sb-ok" style="font-size:.65rem;"><i class="fas fa-circle" style="font-size:.4rem;margin-right:3px;"></i>Actif</span>' : '')
      + '</div>'
      + '<div class="user-card-meta">'
      +   '<span title="Dernière connexion"><i class="fas fa-clock" style="margin-right:4px;opacity:.5;font-size:.7rem;"></i>' + lastConn + '</span>'
      +   '<div class="user-card-actions">'
      /* Éditer */
      +     '<div class="uca edit" title="Modifier" onclick="openEdit(' + u.id_utilisateur + ')">'
      +       '<i class="fas fa-pen"></i></div>'
      /* Changer mdp */
      +     '<div class="uca pwd" title="Changer le mot de passe" onclick="openChangePwd(' + u.id_utilisateur + ',\'' + esc(u.nom) + '\')">'
      +       '<i class="fas fa-key"></i></div>'
      /* Désactiver — désactivé pour le compte courant */
      +     '<div class="uca del' + (isMe ? ' disabled' : '') + '" '
      +       (isMe ? 'title="Vous ne pouvez pas vous désactiver"'
                    : 'title="Désactiver" onclick="openDel(' + u.id_utilisateur + ',\'' + esc(u.nom) + '\')"')
      +     '><i class="fas fa-user-slash"></i></div>'
      +   '</div>'
      + '</div>'
      + '</div>';
  });
  grid.innerHTML = html;
}

/* ════════════════════════════════════════
   RÔLE SELECTOR
════════════════════════════════════════ */
function selectRole(role) {
  _roleSelectionne = role;
  document.querySelectorAll('.role-btn').forEach(function(b){ b.classList.remove('selected'); });
  var btn = document.querySelector('.role-btn[data-role="' + role + '"]');
  if (btn) btn.classList.add('selected');
  document.getElementById('e-role').classList.remove('show');
}

/* ════════════════════════════════════════
   TOGGLE STATUT (édition)
════════════════════════════════════════ */
function toggleStatut() {
  var current = document.getElementById('f-statut').value;
  var newVal  = current === 'actif' ? 'inactif' : 'actif';
  document.getElementById('f-statut').value    = newVal;
  document.getElementById('toggle-text').textContent = newVal === 'actif' ? 'Actif' : 'Inactif';
  document.getElementById('toggle-track').classList.toggle('on', newVal === 'actif');
}

/* ════════════════════════════════════════
   CRÉER UTILISATEUR
   POST /api/utilisateurs
   Body : { nom, email, mot_de_passe, role }
════════════════════════════════════════ */
function openCreate() {
  _editMode = false;
  _roleSelectionne = 'admin';
  clearForm();
  document.getElementById('form-titre').textContent = 'Nouvel utilisateur';
  document.getElementById('form-ico').innerHTML     = '<i class="fas fa-user-plus"></i>';
  document.getElementById('btn-form-save').innerHTML = '<i class="fas fa-check"></i> Créer l\'utilisateur';
  document.getElementById('pwd-creation-wrap').style.display = 'block';
  document.getElementById('statut-wrap').style.display       = 'none';
  /* Rôle admin sélectionné par défaut */
  selectRole('admin');
  openModal('modal-form');
}

function openEdit(id) {
  var u = null;
  for (var i = 0; i < _all.length; i++) {
    if (String(_all[i].id_utilisateur) === String(id)) { u = _all[i]; break; }
  }
  if (!u) return;

  _editMode = true;
  _roleSelectionne = u.role;
  clearForm();

  document.getElementById('f-id').value     = u.id_utilisateur;
  document.getElementById('f-nom').value    = u.nom || '';
  document.getElementById('f-email').value  = u.email || '';
  document.getElementById('f-statut').value = u.statut || 'actif';

  document.getElementById('toggle-text').textContent = u.statut === 'actif' ? 'Actif' : 'Inactif';
  document.getElementById('toggle-track').classList.toggle('on', u.statut === 'actif');

  selectRole(u.role);

  document.getElementById('form-titre').textContent  = 'Modifier ' + (u.nom || 'l\'utilisateur');
  document.getElementById('form-ico').innerHTML      = '<i class="fas fa-user-pen"></i>';
  document.getElementById('btn-form-save').innerHTML = '<i class="fas fa-floppy-disk"></i> Enregistrer les modifications';
  document.getElementById('pwd-creation-wrap').style.display = 'none';
  document.getElementById('statut-wrap').style.display       = 'block';

  openModal('modal-form');
}

function saveUser() {
  var valid = true;

  /* Nom */
  var nomEl = document.getElementById('f-nom');
  if (!nomEl.value.trim()) {
    nomEl.classList.add('error');
    document.getElementById('e-nom').classList.add('show');
    valid = false;
  } else {
    nomEl.classList.remove('error');
    document.getElementById('e-nom').classList.remove('show');
  }

  /* Email */
  var emailEl = document.getElementById('f-email');
  var emailOk = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailEl.value);
  if (!emailOk) {
    emailEl.classList.add('error');
    document.getElementById('e-email').classList.add('show');
    valid = false;
  } else {
    emailEl.classList.remove('error');
    document.getElementById('e-email').classList.remove('show');
  }

  /* Rôle */
  if (!_roleSelectionne) {
    document.getElementById('e-role').classList.add('show');
    valid = false;
  }

  /* Mot de passe (création seulement) */
  if (!_editMode) {
    var pwdEl  = document.getElementById('f-pwd');
    var pwd2El = document.getElementById('f-pwd2');
    if (pwdEl.value.length < 8) {
      pwdEl.classList.add('error');
      document.getElementById('e-pwd').classList.add('show');
      valid = false;
    } else {
      pwdEl.classList.remove('error');
      document.getElementById('e-pwd').classList.remove('show');
    }
    if (pwdEl.value !== pwd2El.value) {
      pwd2El.classList.add('error');
      document.getElementById('e-pwd2').classList.add('show');
      valid = false;
    } else {
      pwd2El.classList.remove('error');
      document.getElementById('e-pwd2').classList.remove('show');
    }
  }

  if (!valid) return;

  var btn = document.getElementById('btn-form-save');
  btn.disabled = true;
  btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> ' + (_editMode ? 'Enregistrement…' : 'Création…');

  var id = document.getElementById('f-id').value;

  if (_editMode) {
    /* PUT /api/utilisateurs/:id → { nom, email, role, statut } */
    fetch(API + '/utilisateurs/' + id, {
      method: 'PUT',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        nom:    nomEl.value.trim(),
        email:  emailEl.value.trim(),
        role:   _roleSelectionne,
        statut: document.getElementById('f-statut').value
      })
    }).then(function(r){ return r.json().then(function(d){ return { ok: r.ok, d: d }; }); })
      .then(function(res) {
        if (!res.ok) throw new Error(res.d.message || 'Erreur serveur');
        closeModal('modal-form');
        showSuccess('Utilisateur mis à jour.');
        return load();
      }).catch(function(e){ showError('Erreur : ' + (e.message||'')); })
      .finally(function(){
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-floppy-disk"></i> Enregistrer les modifications';
      });
  } else {
    /* POST /api/utilisateurs → { nom, email, mot_de_passe, role } */
    fetch(API + '/utilisateurs', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        nom:          nomEl.value.trim(),
        email:        emailEl.value.trim(),
        mot_de_passe: document.getElementById('f-pwd').value,
        role:         _roleSelectionne
      })
    }).then(function(r){ return r.json().then(function(d){ return { ok: r.ok, d: d }; }); })
      .then(function(res) {
        if (!res.ok) throw new Error(res.d.message || 'Erreur serveur');
        closeModal('modal-form');
        showSuccess('Utilisateur créé avec succès.');
        return load();
      }).catch(function(e){ showError('Erreur : ' + (e.message||'')); })
      .finally(function(){
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-check"></i> Créer l\'utilisateur';
      });
  }
}

/* ════════════════════════════════════════
   CHANGER MOT DE PASSE
   PUT /api/utilisateurs/:id/password
   Body : { mot_de_passe }
════════════════════════════════════════ */
function openChangePwd(id, nom) {
  document.getElementById('pwd-user-id').value       = id;
  document.getElementById('pwd-user-name').textContent = nom;
  document.getElementById('new-pwd').value   = '';
  document.getElementById('new-pwd2').value  = '';
  document.getElementById('pwd-bar2').style.width = '0';
  document.querySelectorAll('#modal-pwd .ferr').forEach(function(e){ e.classList.remove('show'); });
  document.querySelectorAll('#modal-pwd .finput.error').forEach(function(e){ e.classList.remove('error'); });
  openModal('modal-pwd');
}

function savePwd() {
  var valid   = true;
  var pwd1El  = document.getElementById('new-pwd');
  var pwd2El  = document.getElementById('new-pwd2');

  if (pwd1El.value.length < 8) {
    pwd1El.classList.add('error');
    document.getElementById('e-new-pwd').classList.add('show');
    valid = false;
  } else {
    pwd1El.classList.remove('error');
    document.getElementById('e-new-pwd').classList.remove('show');
  }
  if (pwd1El.value !== pwd2El.value) {
    pwd2El.classList.add('error');
    document.getElementById('e-new-pwd2').classList.add('show');
    valid = false;
  } else {
    pwd2El.classList.remove('error');
    document.getElementById('e-new-pwd2').classList.remove('show');
  }
  if (!valid) return;

  var btn = document.getElementById('btn-pwd-save');
  btn.disabled = true;
  btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Modification…';

  var id = document.getElementById('pwd-user-id').value;
  fetch(API + '/utilisateurs/' + id + '/password', {
    method: 'PUT',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ mot_de_passe: pwd1El.value })
  }).then(function(r){ return r.json().then(function(d){ return { ok: r.ok, d: d }; }); })
    .then(function(res) {
      if (!res.ok) throw new Error(res.d.message || 'Erreur serveur');
      closeModal('modal-pwd');
      showSuccess('Mot de passe mis à jour.');
    }).catch(function(e){ showError('Erreur : ' + (e.message||'')); })
    .finally(function(){
      btn.disabled = false;
      btn.innerHTML = '<i class="fas fa-key"></i> Changer le mot de passe';
    });
}

/* ════════════════════════════════════════
   DÉSACTIVATION
   DELETE /api/utilisateurs/:id → statut = inactif
   Protection : impossible de se désactiver soi-même
════════════════════════════════════════ */
function openDel(id, nom) {
  if (String(id) === String(UID)) {
    showError('Vous ne pouvez pas désactiver votre propre compte.');
    return;
  }
  document.getElementById('del-id').value          = id;
  document.getElementById('del-nom').textContent   = nom;
  openModal('modal-confirm');
}

function confirmDel() {
  var id  = document.getElementById('del-id').value;
  var btn = document.getElementById('btn-del-confirm');
  btn.disabled = true;
  btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Désactivation…';

  fetch(API + '/utilisateurs/' + id, { method: 'DELETE' })
    .then(function(r){ return r.json().then(function(d){ return { ok: r.ok, d: d }; }); })
    .then(function(res) {
      if (!res.ok) throw new Error(res.d.message || 'Erreur serveur');
      closeModal('modal-confirm');
      showSuccess('Utilisateur désactivé. Il ne peut plus se connecter.');
      return load();
    }).catch(function(e){ showError('Erreur : ' + (e.message||'')); })
    .finally(function(){
      btn.disabled = false;
      btn.innerHTML = '<i class="fas fa-user-slash"></i> Désactiver';
    });
}

/* ════════════════════════════════════════
   UTILITAIRES MODALS & FORM
════════════════════════════════════════ */
function openModal(id)  { document.getElementById(id).classList.add('open');    document.body.style.overflow='hidden'; }
function closeModal(id) { document.getElementById(id).classList.remove('open'); document.body.style.overflow=''; }

function clearForm() {
  ['f-id','f-nom','f-email','f-pwd','f-pwd2'].forEach(function(id){
    var el = document.getElementById(id);
    if (el) el.value = '';
  });
  document.getElementById('f-statut').value = 'actif';
  document.querySelectorAll('#modal-form .finput.error, #modal-form .fselect.error')
    .forEach(function(e){ e.classList.remove('error'); });
  document.querySelectorAll('#modal-form .ferr.show')
    .forEach(function(e){ e.classList.remove('show'); });
  var bar = document.getElementById('pwd-bar');
  if (bar) { bar.style.width = '0'; }
  var hint = document.getElementById('pwd-hint');
  if (hint) hint.textContent = 'Saisissez un mot de passe';
  /* Reset role buttons */
  document.querySelectorAll('.role-btn').forEach(function(b){ b.classList.remove('selected'); });
}

document.querySelectorAll('.modal-bg').forEach(function(bg) {
  bg.addEventListener('click', function(e){ if(e.target===bg) closeModal(bg.id); });
});
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape')
    document.querySelectorAll('.modal-bg.open').forEach(function(m){ closeModal(m.id); });
});

load();
</script>

<?php require_once 'includes/footer.php'; ?>