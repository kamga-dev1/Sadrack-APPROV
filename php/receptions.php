<?php
require_once 'includes/config.php';
if(empty($_SESSION['user'])){header('Location: login.php');exit;}
$_uid = $_SESSION['user']['id'] ?? $_SESSION['user']['id_utilisateur'] ?? 0;
?>
<?php require_once 'includes/header.php'; ?>
<?php require_once 'includes/sidebar.php'; ?>
<script>
  if(document.getElementById('nav-page-title'))
    document.getElementById('nav-page-title').textContent = 'Réceptions';
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

/* ─── Table ─── */
.tcard{background:var(--surface,#fff);border:1px solid var(--border,#e4e8f0);border-radius:12px;box-shadow:0 1px 4px rgba(5,8,15,.06);overflow:hidden;}
.etbl{width:100%;border-collapse:collapse;table-layout:fixed;}
.etbl thead th{font-size:.65rem;font-weight:800;text-transform:uppercase;letter-spacing:1.2px;color:var(--text4,#aab5c6);padding:10px 14px;border-bottom:2px solid var(--border,#e4e8f0);background:var(--surface2,#f4f6fb);white-space:nowrap;text-align:left;cursor:pointer;user-select:none;}
.etbl thead th:hover{color:var(--text2,#3a4a5c);}
.etbl thead th.sorted{color:#1a8a4a;}
.sort-ico{margin-left:4px;opacity:.35;font-size:.58rem;}
.sorted .sort-ico{opacity:1;color:#1a8a4a;}
/* Largeurs : N° Réc. | N° Cmd | Fournisseur | Date | Réc. par | Statut | Observation | Actions */
.etbl thead th:nth-child(1){width:9%;}
.etbl thead th:nth-child(2){width:13%;}
.etbl thead th:nth-child(3){width:16%;}
.etbl thead th:nth-child(4){width:9%;}
.etbl thead th:nth-child(5){width:13%;}
.etbl thead th:nth-child(6){width:10%;}
.etbl thead th:nth-child(7){width:21%;}
.etbl thead th:nth-child(8){width:9%;text-align:right;}
.etbl tbody td{padding:11px 14px;border-bottom:1px solid var(--border,#e4e8f0);font-size:.86rem;color:var(--text2,#3a4a5c);vertical-align:middle;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
.etbl tbody td:nth-child(7){color:var(--text3,#8FA4BF);font-size:.8rem;font-style:italic;}
.etbl tbody td:nth-child(8){text-align:right;}
.etbl tbody tr:last-child td{border-bottom:none;}
.etbl tbody tr:hover{background:var(--surface2,#f4f6fb);cursor:pointer;}
.c-num{font-family:'DM Mono',monospace;font-weight:700;color:var(--text,#0D1526);font-size:.84rem;}
.c-id{font-family:'DM Mono',monospace;font-size:.78rem;color:var(--text4,#aab5c6);font-weight:600;}
.c-actions{display:flex;align-items:center;gap:5px;justify-content:flex-end;}
.act-btn{width:30px;height:30px;border-radius:7px;display:flex;align-items:center;justify-content:center;font-size:.72rem;cursor:pointer;border:1px solid var(--border,#e4e8f0);background:var(--canvas,#f8f9fc);color:var(--text3,#8FA4BF);transition:all .15s;}
.act-btn.view:hover{background:#ebf5ff;border-color:#90c6f8;color:#1a7fd4;}

/* ─── Pagination ─── */
.pager{display:flex;align-items:center;justify-content:space-between;padding:12px 18px;border-top:1px solid var(--border,#e4e8f0);flex-wrap:wrap;gap:10px;}
.pager-info{font-size:.78rem;color:var(--text4,#aab5c6);}
.pager-btns{display:flex;align-items:center;gap:4px;}
.pg-btn{min-width:32px;height:32px;border-radius:7px;display:flex;align-items:center;justify-content:center;font-size:.78rem;font-weight:600;cursor:pointer;border:1px solid var(--border,#e4e8f0);background:var(--canvas,#f8f9fc);color:var(--text2,#3a4a5c);transition:all .15s;padding:0 6px;}
.pg-btn:hover{background:var(--surface,#fff);}
.pg-btn.on{background:#0D1526;color:#fff;border-color:#0D1526;}
.pg-btn:disabled{opacity:.38;cursor:default;}

/* ─── Modals ─── */
.modal-bg{position:fixed;inset:0;z-index:900;background:rgba(5,8,15,.55);backdrop-filter:blur(4px);display:none;align-items:center;justify-content:center;padding:20px;}
.modal-bg.open{display:flex;animation:bgIn .2s ease;}
@keyframes bgIn{from{opacity:0}to{opacity:1}}
.modal{background:var(--surface,#fff);border:1px solid var(--border,#e4e8f0);border-radius:16px;width:100%;max-width:640px;box-shadow:0 8px 40px rgba(5,8,15,.18);animation:mIn .22s ease;max-height:92vh;overflow-y:auto;}
.modal-lg{max-width:860px;}
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
.frow-3{grid-template-columns:1fr 1fr 1fr;}
@media(max-width:600px){.frow-2,.frow-3{grid-template-columns:1fr;}}
.fgroup{display:flex;flex-direction:column;gap:5px;}
.flabel{font-size:.75rem;font-weight:700;color:var(--text2,#3a4a5c);}
.flabel span{color:#e03e3e;margin-left:2px;}
.finput,.fselect,.ftextarea{width:100%;padding:9px 12px;border-radius:8px;border:1.5px solid var(--border,#e4e8f0);background:var(--canvas,#f8f9fc);color:var(--text,#0D1526);font-family:inherit;font-size:.88rem;transition:border-color .18s;outline:none;}
.finput:focus,.fselect:focus,.ftextarea:focus{border-color:var(--gold,#E2A84B);background:var(--surface,#fff);box-shadow:0 0 0 3px rgba(226,168,75,.1);}
.finput.error,.fselect.error{border-color:#e03e3e;}
.finput:disabled,.fselect:disabled{opacity:.65;cursor:not-allowed;}
.ftextarea{resize:vertical;min-height:56px;}
.ferr{font-size:.72rem;color:#e03e3e;margin-top:3px;display:none;}
.ferr.show{display:block;}
.fsep{border:none;border-top:1px solid var(--border,#e4e8f0);margin:4px 0 16px;}
.fsec-title{font-size:.68rem;font-weight:800;text-transform:uppercase;letter-spacing:1.5px;color:var(--text4,#aab5c6);margin-bottom:12px;}

/* ─── Info commande sélectionnée ─── */
.cmd-preview{background:var(--canvas,#f8f9fc);border:1px solid var(--border,#e4e8f0);border-radius:10px;padding:12px 16px;margin-bottom:14px;display:none;}
.cmd-preview.show{display:block;}
.cmd-preview-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:10px;}
@media(max-width:560px){.cmd-preview-grid{grid-template-columns:1fr 1fr;}}
.cp-item{display:flex;flex-direction:column;gap:2px;}
.cp-lbl{font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.7px;color:var(--text4,#aab5c6);}
.cp-val{font-size:.88rem;font-weight:700;color:var(--text,#0D1526);}

/* ─── Tableau lignes réception (formulaire) ─── */
.lignes-rec-wrap{background:var(--canvas,#f8f9fc);border:1px solid var(--border,#e4e8f0);border-radius:10px;overflow:hidden;margin-bottom:14px;}
.lr-head{display:grid;grid-template-columns:1fr 90px 110px 110px 80px;gap:8px;padding:8px 14px;background:var(--surface2,#f4f6fb);border-bottom:1px solid var(--border,#e4e8f0);}
.lr-head span{font-size:.63rem;font-weight:800;text-transform:uppercase;letter-spacing:1px;color:var(--text4,#aab5c6);}
.lr-row{display:grid;grid-template-columns:1fr 90px 110px 110px 80px;gap:8px;padding:9px 14px;border-bottom:1px solid var(--border,#e4e8f0);align-items:center;}
.lr-row:last-child{border-bottom:none;}
.lr-row .finput{padding:6px 10px;font-size:.84rem;font-family:'DM Mono',monospace;}
.lr-art{font-size:.86rem;font-weight:600;color:var(--text,#0D1526);}
.lr-ref{font-size:.73rem;color:var(--text4,#aab5c6);font-family:'DM Mono',monospace;}
.lr-ecart{font-family:'DM Mono',monospace;font-size:.84rem;font-weight:700;text-align:center;}
.lr-ecart.ok{color:#1a8a4a;}
.lr-ecart.warn{color:#c87000;}
.lr-ecart.crit{color:#c03030;}
.lr-commentaire{padding:0 4px;font-size:.82rem;border-radius:6px;border:1.5px solid var(--border,#e4e8f0);background:var(--surface,#fff);color:var(--text,#0D1526);font-family:inherit;outline:none;width:100%;}
.lr-commentaire:focus{border-color:var(--gold,#E2A84B);}

/* ─── Résumé réception ─── */
.rec-summary{background:var(--surface2,#f4f6fb);border:1px solid var(--border,#e4e8f0);border-radius:10px;padding:14px 16px;margin-bottom:14px;display:flex;gap:24px;flex-wrap:wrap;}
.rs-item{display:flex;flex-direction:column;gap:3px;}
.rs-lbl{font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:var(--text4,#aab5c6);}
.rs-val{font-size:1.1rem;font-weight:800;font-family:'DM Mono',monospace;}
.rs-val.ok{color:#1a8a4a;}
.rs-val.warn{color:#c87000;}
.rs-val.crit{color:#e03e3e;}

/* ─── Détail réception ─── */
.detail-meta{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:18px;}
@media(max-width:560px){.detail-meta{grid-template-columns:1fr 1fr;}}
.dmeta{background:var(--canvas,#f8f9fc);border:1px solid var(--border,#e4e8f0);border-radius:9px;padding:10px 13px;}
.dmeta-l{font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:var(--text4,#aab5c6);margin-bottom:3px;}
.dmeta-v{font-size:.9rem;font-weight:700;color:var(--text,#0D1526);}

.detail-lignes{background:var(--canvas,#f8f9fc);border:1px solid var(--border,#e4e8f0);border-radius:10px;overflow:hidden;margin-bottom:16px;}
/* Détail : Article | Commandé | Reçu | Écart | Commentaire */
.dl-head{display:grid;grid-template-columns:1fr 90px 90px 90px 1fr;gap:8px;padding:8px 14px;background:var(--surface2,#f4f6fb);border-bottom:1px solid var(--border,#e4e8f0);}
.dl-head span{font-size:.63rem;font-weight:800;text-transform:uppercase;letter-spacing:1px;color:var(--text4,#aab5c6);}
.dl-row{display:grid;grid-template-columns:1fr 90px 90px 90px 1fr;gap:8px;padding:10px 14px;border-bottom:1px solid var(--border,#e4e8f0);align-items:center;font-size:.86rem;}
.dl-row:last-child{border-bottom:none;}
.dl-ref{font-family:'DM Mono',monospace;font-size:.73rem;color:var(--text4,#aab5c6);}
.dl-num{font-family:'DM Mono',monospace;font-weight:700;text-align:right;}
.dl-ecart{font-family:'DM Mono',monospace;font-weight:800;text-align:right;}
.dl-ecart.ok{color:#1a8a4a;}
.dl-ecart.warn{color:#c87000;}
.dl-ecart.crit{color:#e03e3e;}
.dl-comment{font-size:.78rem;color:var(--text3,#8FA4BF);font-style:italic;}

/* ─── Badges statuts réception ─── */
/* ENUM DB : en_cours | complete | partielle | litige */
.sbadge{display:inline-flex;align-items:center;padding:3px 10px;border-radius:20px;font-size:.72rem;font-weight:700;white-space:nowrap;}
.sb-muted{background:#f0f2f5;color:#7a8a9a;}
.sb-ok{background:#eafaf1;color:#1a8a4a;}
.sb-warn{background:#fff4e0;color:#c87000;}
.sb-crit{background:#fff0f0;color:#c03030;}
.sb-blue{background:#ebf5ff;color:#1a7fd4;}

/* ─── Badge écart inline ─── */
.ecart-pill{display:inline-flex;align-items:center;gap:4px;padding:2px 8px;border-radius:12px;font-size:.72rem;font-weight:700;font-family:'DM Mono',monospace;}
.ep-ok{background:#eafaf1;color:#1a8a4a;}
.ep-warn{background:#fff4e0;color:#c87000;}
.ep-crit{background:#fff0f0;color:#c03030;}

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

/* ─── Loading skeleton pour lignes commande ─── */
.lignes-loading{padding:24px;text-align:center;color:var(--text4,#aab5c6);font-size:.84rem;}
</style>

<div class="toast-wrap" id="toast-wrap"></div>

<!-- ═══ EN-TÊTE ═══ -->
<div class="ph">
  <div>
    <div class="ph-title">
      <i class="fas fa-truck-ramp-box" style="color:var(--gold,#E2A84B);margin-right:9px;"></i>Réceptions
    </div>
    <div class="ph-sub">Enregistrement et suivi des livraisons fournisseurs</div>
  </div>
  <div class="ph-right">
    <button class="btn-gold" onclick="openCreate()">
      <i class="fas fa-plus"></i> Nouvelle réception
    </button>
  </div>
</div>

<!-- ═══ KPI STRIP ═══ -->
<div class="kstrip">
  <div class="ks">
    <div class="ks-ico" style="background:#eafaf1;color:#1a8a4a;"><i class="fas fa-circle-check"></i></div>
    <div><div class="ks-n" id="ks-complete">—</div><div class="ks-l">Complètes</div></div>
  </div>
  <div class="ks">
    <div class="ks-ico" style="background:#fff4e0;color:#c87000;"><i class="fas fa-box-open"></i></div>
    <div><div class="ks-n" id="ks-partielle">—</div><div class="ks-l">Partielles</div></div>
  </div>
  <div class="ks">
    <div class="ks-ico" style="background:#fff0f0;color:#c03030;"><i class="fas fa-triangle-exclamation"></i></div>
    <div><div class="ks-n" id="ks-litige">—</div><div class="ks-l">En litige</div></div>
  </div>
  <div class="ks">
    <div class="ks-ico" style="background:#ebf5ff;color:#1a7fd4;"><i class="fas fa-calendar-day"></i></div>
    <div><div class="ks-n" id="ks-aujourd">—</div><div class="ks-l">Aujourd'hui</div></div>
  </div>
</div>

<!-- ═══ TOOLBAR ═══ -->
<div class="toolbar">
  <div class="tsearch">
    <i class="fas fa-search"></i>
    <input type="text" id="search-input" placeholder="N° commande, fournisseur, réceptionnaire…" oninput="filterTable()">
  </div>
  <div class="tsel">
    <i class="fas fa-filter" style="font-size:.76rem;"></i>
    <select id="filter-statut" onchange="filterTable()">
      <option value="">Tous les statuts</option>
      <option value="complete">Complète</option>
      <option value="partielle">Partielle</option>
      <option value="en_cours">En cours</option>
      <option value="litige">Litige</option>
    </select>
  </div>
  <div class="tsel">
    <i class="fas fa-calendar" style="font-size:.76rem;"></i>
    <select id="filter-periode" onchange="filterTable()">
      <option value="">Toute période</option>
      <option value="7">7 derniers jours</option>
      <option value="30">30 derniers jours</option>
      <option value="90">3 derniers mois</option>
    </select>
  </div>
  <div class="tb-sep"></div>
  <div class="tb-count" id="tb-count">Chargement…</div>
</div>

<!-- ═══ TABLE ═══ -->
<div class="tcard">
  <div style="overflow-x:auto;">
    <table class="etbl">
      <thead>
        <tr>
          <th onclick="sortBy('id_reception')">N° Réc. <i class="fas fa-sort sort-ico"></i></th>
          <th onclick="sortBy('numero_commande')">N° Commande <i class="fas fa-sort sort-ico"></i></th>
          <th onclick="sortBy('fournisseur_nom')">Fournisseur <i class="fas fa-sort sort-ico"></i></th>
          <th onclick="sortBy('date_reception')">Date réc. <i class="fas fa-sort sort-ico"></i></th>
          <th onclick="sortBy('receptionnaire')">Réceptionné par <i class="fas fa-sort sort-ico"></i></th>
          <th onclick="sortBy('statut')">Statut <i class="fas fa-sort sort-ico"></i></th>
          <th onclick="sortBy('observation')">Observation <i class="fas fa-sort sort-ico"></i></th>
          <th style="text-align:right;">Actions</th>
        </tr>
      </thead>
      <tbody id="tbl-body">
        <tr><td colspan="8">
          <div style="padding:24px;text-align:center;color:var(--text4,#aab5c6);">
            <i class="fas fa-spinner fa-spin"></i> Chargement…
          </div>
        </td></tr>
      </tbody>
    </table>
  </div>
  <div class="pager" id="pager" style="display:none;">
    <div class="pager-info" id="pager-info"></div>
    <div class="pager-btns" id="pager-btns"></div>
  </div>
</div>


<!-- ════════════════════════════════════════════
     MODAL NOUVELLE RÉCEPTION
════════════════════════════════════════════ -->
<div class="modal-bg" id="modal-create">
  <div class="modal modal-lg">
    <div class="modal-head">
      <div class="modal-title">
        <div class="modal-ico" style="background:rgba(26,138,74,.12);color:#1a8a4a;">
          <i class="fas fa-truck-ramp-box"></i>
        </div>
        Nouvelle réception
      </div>
      <button class="modal-close" onclick="closeModal('modal-create')"><i class="fas fa-xmark"></i></button>
    </div>
    <div class="modal-body">

      <!-- Étape 1 : Choisir la commande + date -->
      <div class="fsec-title">Informations générales</div>
      <div class="frow frow-2">
        <div class="fgroup">
          <label class="flabel">Commande à réceptionner <span>*</span></label>
          <select class="fselect" id="f-commande" onchange="onCommandeChange()">
            <option value="">— Sélectionner une commande —</option>
          </select>
          <div class="ferr" id="e-commande">Veuillez sélectionner une commande</div>
        </div>
        <div class="fgroup">
          <label class="flabel">Date de réception <span>*</span></label>
          <input type="date" class="finput" id="f-date">
          <div class="ferr" id="e-date">Date requise</div>
        </div>
      </div>

      <!-- Aperçu commande sélectionnée -->
      <div class="cmd-preview" id="cmd-preview">
        <div class="cmd-preview-grid">
          <div class="cp-item"><span class="cp-lbl">Fournisseur</span><span class="cp-val" id="cp-fournisseur">—</span></div>
          <div class="cp-item"><span class="cp-lbl">Date commande</span><span class="cp-val" id="cp-date-cmd">—</span></div>
          <div class="cp-item"><span class="cp-lbl">Livraison prévue</span><span class="cp-val" id="cp-livraison">—</span></div>
          <div class="cp-item"><span class="cp-lbl">Montant TTC</span><span class="cp-val" id="cp-montant">—</span></div>
          <div class="cp-item"><span class="cp-lbl">Statut actuel</span><span class="cp-val" id="cp-statut">—</span></div>
          <div class="cp-item"><span class="cp-lbl">Nb articles</span><span class="cp-val" id="cp-nb">—</span></div>
        </div>
      </div>

      <div class="frow">
        <div class="fgroup">
          <label class="flabel">Observation / Remarques</label>
          <textarea class="ftextarea" id="f-observation"
            placeholder="État de la livraison, commentaires transporteur, anomalies constatées…"></textarea>
        </div>
      </div>

      <hr class="fsep">

      <!-- Étape 2 : Lignes de réception -->
      <div class="fsec-title">
        Saisie des quantités reçues <span style="color:#e03e3e;">*</span>
      </div>
      <div id="lignes-rec-container">
        <div class="lignes-loading">
          <i class="fas fa-arrow-up" style="margin-right:6px;opacity:.4;"></i>
          Sélectionnez d'abord une commande pour afficher les articles
        </div>
      </div>
      <div class="ferr" id="e-lignes" style="margin-bottom:12px;">
        Renseignez au moins une quantité reçue
      </div>

      <!-- Résumé dynamique -->
      <div class="rec-summary" id="rec-summary" style="display:none;">
        <div class="rs-item"><span class="rs-lbl">Articles</span><span class="rs-val" id="rs-nb">0</span></div>
        <div class="rs-item"><span class="rs-lbl">Qté commandée</span><span class="rs-val" id="rs-cmd">0</span></div>
        <div class="rs-item"><span class="rs-lbl">Qté reçue</span><span class="rs-val ok" id="rs-rec">0</span></div>
        <div class="rs-item"><span class="rs-lbl">Écart total</span><span class="rs-val" id="rs-ecart">0</span></div>
        <div class="rs-item"><span class="rs-lbl">Statut prévu</span><span class="rs-val" id="rs-statut">—</span></div>
      </div>

    </div>
    <div class="modal-footer">
      <button class="btn-secondary" onclick="closeModal('modal-create')">Annuler</button>
      <button class="btn-gold" id="btn-save" onclick="saveReception()">
        <i class="fas fa-clipboard-check"></i> Enregistrer la réception
      </button>
    </div>
  </div>
</div>


<!-- ════════════════════════════════════════════
     MODAL DÉTAIL RÉCEPTION
════════════════════════════════════════════ -->
<div class="modal-bg" id="modal-detail">
  <div class="modal modal-lg">
    <div class="modal-head">
      <div class="modal-title">
        <div class="modal-ico" style="background:rgba(26,138,74,.12);color:#1a8a4a;">
          <i class="fas fa-clipboard-check"></i>
        </div>
        <span id="detail-titre">Détail réception</span>
      </div>
      <button class="modal-close" onclick="closeModal('modal-detail')"><i class="fas fa-xmark"></i></button>
    </div>
    <div class="modal-body" id="detail-body">
      <div style="text-align:center;padding:32px;color:#aab5c6;">
        <i class="fas fa-spinner fa-spin"></i> Chargement…
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn-secondary" onclick="closeModal('modal-detail')">Fermer</button>
    </div>
  </div>
</div>


<!-- ════════════════════════════════════════════
     JAVASCRIPT
════════════════════════════════════════════ -->
<script>
/* ════════════════════════════════════════
   CONFIG & STATE
   API :  GET  /api/receptions
          GET  /api/receptions/:id
          POST /api/receptions
          GET  /api/commandes  (pour select)
   Champs GET /receptions :
     id_reception, id_commande, id_utilisateur,
     date_reception, statut, observation, created_at, updated_at,
     numero_commande, fournisseur_nom, receptionnaire
   Champs GET /receptions/:id + lignes :
     article_nom, reference, unite,
     quantite_commandee, quantite_recue,
     ecart  (colonne générée DB = commandee - recue)
     commentaire
════════════════════════════════════════ */
var API = 'http://localhost:3000/api';
var UID = <?= (int)$_uid ?>;

var _all          = [];  // toutes les réceptions
var _shown        = [];  // filtrées
var _commandes    = [];  // commandes réceptionnables
var _page         = 1;
var PER           = 15;
var _sortCol      = 'date_reception';
var _sortAsc      = false;
var _lignesCreate = [];  // lignes dans le formulaire de création

/* ════════════════════════════════════════
   ENUM STATUTS RÉCEPTION (DB)
════════════════════════════════════════ */
var STATUTS_REC = {
  en_cours:  { cls: 'sb-blue', lbl: 'En cours'  },
  complete:  { cls: 'sb-ok',   lbl: 'Complète'  },
  partielle: { cls: 'sb-warn', lbl: 'Partielle' },
  litige:    { cls: 'sb-crit', lbl: 'Litige'    }
};

/* ════════════════════════════════════════
   UTILITAIRES — même logique que commandes.php
   pour éviter le bug timezone MySQL → ISO UTC
════════════════════════════════════════ */
function extractDate(d) {
  if (!d) return null;
  var s = String(d);
  if (/^\d{4}-\d{2}-\d{2}$/.test(s)) return s;
  var dt = new Date(s);
  if (isNaN(dt.getTime())) return null;
  var y = dt.getFullYear();
  var m = String(dt.getMonth() + 1).padStart(2, '0');
  var j = String(dt.getDate()).padStart(2, '0');
  return y + '-' + m + '-' + j;
}
function parseDateMidnight(d) {
  var s = extractDate(d);
  if (!s) return null;
  var p = s.split('-');
  return new Date(parseInt(p[0]), parseInt(p[1]) - 1, parseInt(p[2]));
}
function today() {
  var n = new Date();
  return new Date(n.getFullYear(), n.getMonth(), n.getDate());
}
function fmt(n) { return (parseFloat(n) || 0).toLocaleString('fr-FR'); }
function truncate(s, max) {
  s = String(s || '');
  return s.length > max ? s.slice(0, max) + '…' : s;
}
function fmtDate(d) {
  if (!d) return '—';
  var s = extractDate(d);
  if (!s) return '—';
  var p = s.split('-');
  return p[2] + '/' + p[1] + '/' + p[0];
}
function esc(s) {
  return String(s == null ? '' : s)
    .replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}
function badgeRec(s) {
  var e = STATUTS_REC[s] || { cls: 'sb-muted', lbl: s || '—' };
  return '<span class="sbadge ' + e.cls + '">' + e.lbl + '</span>';
}
function showToast(msg, type) {
  type = type || 'ok';
  var wrap = document.getElementById('toast-wrap');
  var div  = document.createElement('div');
  div.className = 'toast ' + type;
  div.innerHTML = '<i class="fas fa-' + (type === 'ok' ? 'circle-check' : 'circle-exclamation') + '"></i> ' + esc(msg);
  wrap.appendChild(div);
  setTimeout(function() {
    div.style.cssText += 'opacity:0;transform:translateX(20px);transition:all .3s;';
    setTimeout(function() { if (div.parentNode) div.parentNode.removeChild(div); }, 300);
  }, 3500);
}
function showSuccess(m) { showToast(m, 'ok'); }
function showError(m)   { showToast(m, 'err'); }

/* badge écart */
function ecartPill(ecart, unite) {
  ecart = parseFloat(ecart) || 0;
  var cls = ecart === 0 ? 'ep-ok' : (ecart > 0 ? 'ep-warn' : 'ep-crit');
  var sign = ecart > 0 ? '+' : '';  // écart négatif = reçu plus que commandé
  // DB : ecart = quantite_commandee - quantite_recue
  // > 0 → manquant ; < 0 → surplus ; = 0 → parfait
  var icon = ecart === 0
    ? '<i class="fas fa-check" style="font-size:.6rem;"></i>'
    : (ecart > 0 ? '<i class="fas fa-minus" style="font-size:.6rem;"></i>'
                 : '<i class="fas fa-plus"  style="font-size:.6rem;"></i>');
  return '<span class="ecart-pill ' + cls + '">'
    + icon + ' ' + fmt(Math.abs(ecart)) + (unite ? ' ' + esc(unite) : '')
    + '</span>';
}

/* ════════════════════════════════════════
   CHARGEMENT
════════════════════════════════════════ */
function load() {
  return Promise.all([
    fetch(API + '/receptions').then(function(r) { return r.json(); }),
    fetch(API + '/commandes').then(function(r)  { return r.json(); })
  ]).then(function(res) {
    _all = res[0].data || res[0] || [];

    /*
      Commandes réceptionnables = statuts confirmee, expediee, recue_partielle
      (pas brouillon/en_attente car pas encore approuvées,
       pas recue_totale/annulee car terminées)
    */
    var RECEPTIONNABLES = ['confirmee', 'expediee', 'recue_partielle'];
    _commandes = (res[1].data || res[1] || []).filter(function(c) {
      return RECEPTIONNABLES.indexOf(c.statut) >= 0;
    });

    buildCommandeSelect();
    computeKPIs();
    filterTable();
  }).catch(function() {
    document.getElementById('tbl-body').innerHTML =
      '<tr><td colspan="8"><div class="empty-state">' +
      '<div class="empty-ico"><i class="fas fa-wifi-slash"></i></div>' +
      '<div class="empty-title">Erreur de connexion</div>' +
      '<div class="empty-text">Impossible de joindre l\'API (port 3000).</div>' +
      '</div></td></tr>';
    document.getElementById('tb-count').textContent = '0 réception';
  });
}

function buildCommandeSelect() {
  var s = document.getElementById('f-commande');
  var h = '<option value="">— Sélectionner une commande —</option>';
  _commandes.forEach(function(c) {
    h += '<option value="' + c.id_commande + '">'
       + esc(c.numero_commande) + ' — ' + esc(c.fournisseur_nom)
       + ' (' + (STATUTS_CMD[c.statut] || c.statut) + ')'
       + '</option>';
  });
  s.innerHTML = h;
}

/* Libellés statuts commande pour le select */
var STATUTS_CMD = {
  confirmee:       'Confirmée',
  expediee:        'Expédiée',
  recue_partielle: 'Partielle'
};

/* ════════════════════════════════════════
   KPIs
════════════════════════════════════════ */
function computeKPIs() {
  var complete = 0, partielle = 0, litige = 0, auj = 0;
  var todayStr = extractDate(new Date().toISOString());

  _all.forEach(function(r) {
    if (r.statut === 'complete')  complete++;
    if (r.statut === 'partielle') partielle++;
    if (r.statut === 'litige')    litige++;
    if (extractDate(r.date_reception) === todayStr) auj++;
  });

  document.getElementById('ks-complete').textContent  = complete;
  document.getElementById('ks-partielle').textContent = partielle;
  document.getElementById('ks-litige').textContent    = litige;
  document.getElementById('ks-aujourd').textContent   = auj;
}

/* ════════════════════════════════════════
   FILTRE & TRI
════════════════════════════════════════ */
function filterTable() {
  var q   = document.getElementById('search-input').value.trim().toLowerCase();
  var st  = document.getElementById('filter-statut').value;
  var per = document.getElementById('filter-periode').value;
  var now = today();

  _shown = _all.filter(function(r) {
    var mq = !q
      || (r.numero_commande  || '').toLowerCase().indexOf(q) >= 0
      || (r.fournisseur_nom  || '').toLowerCase().indexOf(q) >= 0
      || (r.receptionnaire   || '').toLowerCase().indexOf(q) >= 0;
    var ms = !st || r.statut === st;
    var mp = !per || (now - parseDateMidnight(r.date_reception)) <= parseInt(per) * 86400000;
    return mq && ms && mp;
  });

  /* Tri — colonnes string vs number */
  var NUM_COLS = ['id_reception'];
  _shown.sort(function(a, b) {
    var va = a[_sortCol] != null ? a[_sortCol] : (_sortAsc ? '\uFFFF' : '');
    var vb = b[_sortCol] != null ? b[_sortCol] : (_sortAsc ? '\uFFFF' : '');
    if (NUM_COLS.indexOf(_sortCol) >= 0) {
      va = parseFloat(va) || 0;
      vb = parseFloat(vb) || 0;
    } else if (typeof va === 'string') {
      va = va.toLowerCase(); vb = String(vb).toLowerCase();
    }
    return _sortAsc ? (va > vb ? 1 : va < vb ? -1 : 0)
                    : (va < vb ? 1 : va > vb ? -1 : 0);
  });

  _page = 1;
  render();
}

function sortBy(col) {
  _sortAsc = (_sortCol === col) ? !_sortAsc : true;
  _sortCol = col;
  document.querySelectorAll('.etbl thead th').forEach(function(th) {
    th.classList.remove('sorted');
    var ic = th.querySelector('.sort-ico');
    if (ic) ic.className = 'fas fa-sort sort-ico';
  });
  var cols = ['id_reception','numero_commande','fournisseur_nom','date_reception','receptionnaire','statut','observation'];
  var idx  = cols.indexOf(col);
  if (idx >= 0) {
    var ths = document.querySelectorAll('.etbl thead th');
    if (ths[idx]) {
      ths[idx].classList.add('sorted');
      var ic = ths[idx].querySelector('.sort-ico');
      if (ic) ic.className = 'fas fa-sort-' + (_sortAsc ? 'up' : 'down') + ' sort-ico';
    }
  }
  filterTable();
}

/* ════════════════════════════════════════
   RENDU TABLE
════════════════════════════════════════ */
function render() {
  var total = _shown.length;
  var pages = Math.max(1, Math.ceil(total / PER));
  if (_page > pages) _page = pages;

  document.getElementById('tb-count').textContent =
    total + ' réception' + (total !== 1 ? 's' : '') + ' trouvée' + (total !== 1 ? 's' : '');

  var slice = _shown.slice((_page - 1) * PER, _page * PER);

  if (!slice.length) {
    document.getElementById('tbl-body').innerHTML =
      '<tr><td colspan="8"><div class="empty-state">' +
      '<div class="empty-ico"><i class="fas fa-truck-ramp-box"></i></div>' +
      '<div class="empty-title">Aucune réception trouvée</div>' +
      '<div class="empty-text">' +
        (_all.length === 0 ? 'Enregistrez votre première réception.' : 'Aucun résultat pour cette recherche.') +
      '</div></div></td></tr>';
    document.getElementById('pager').style.display = 'none';
    return;
  }

  var html = '';
  slice.forEach(function(r) {
    /* id_reception → afficher comme REC-00001 */
    var numRec = 'REC-' + String(r.id_reception).padStart(5, '0');

    html += '<tr onclick="openDetail(' + r.id_reception + ')">'
      + '<td><span class="c-id">' + esc(numRec) + '</span></td>'
      + '<td><span class="c-num">' + esc(r.numero_commande || '—') + '</span></td>'
      + '<td>' + esc(r.fournisseur_nom || '—') + '</td>'
      + '<td>' + fmtDate(r.date_reception) + '</td>'
      + '<td style="font-size:.82rem;">' + esc(r.receptionnaire || '—') + '</td>'
      + '<td>' + badgeRec(r.statut) + '</td>'
      /* observation — tronquée à 40 chars pour la liste */
      + '<td title="' + esc(r.observation||'') + '">' + esc(truncate(r.observation||'',40)) + '</td>'
      + '<td><div class="c-actions" onclick="event.stopPropagation()">'
      + '<div class="act-btn view" title="Voir détail" onclick="openDetail(' + r.id_reception + ')">'
      + '<i class="fas fa-eye"></i></div>'
      + '</div></td>'
      + '</tr>';
  });
  document.getElementById('tbl-body').innerHTML = html;

  /* Pagination */
  var pg    = document.getElementById('pager');
  var pbtns = document.getElementById('pager-btns');
  pg.style.display = 'flex';
  document.getElementById('pager-info').textContent =
    ((_page - 1) * PER + 1) + '–' + Math.min(_page * PER, total) + ' sur ' + total;

  var ph = '<button class="pg-btn" onclick="goPage(' + (_page - 1) + ')" '
    + (_page === 1 ? 'disabled' : '') + '><i class="fas fa-chevron-left"></i></button>';
  for (var p = 1; p <= pages; p++) {
    if (pages > 7 && p > 2 && p < pages - 1 && Math.abs(p - _page) > 1) {
      if (p === 3 || p === pages - 2) ph += '<span style="padding:0 4px;color:#aab5c6;">…</span>';
      continue;
    }
    ph += '<button class="pg-btn ' + (p === _page ? 'on' : '') + '" onclick="goPage(' + p + ')">' + p + '</button>';
  }
  ph += '<button class="pg-btn" onclick="goPage(' + (_page + 1) + ')" '
    + (_page === pages ? 'disabled' : '') + '><i class="fas fa-chevron-right"></i></button>';
  pbtns.innerHTML = ph;
}

function goPage(p) {
  var pages = Math.ceil(_shown.length / PER);
  if (p < 1 || p > pages) return;
  _page = p;
  render();
  window.scrollTo({ top: 0, behavior: 'smooth' });
}

/* ════════════════════════════════════════
   FORMULAIRE CRÉATION
════════════════════════════════════════ */
function openCreate() {
  _lignesCreate = [];
  clearCreateForm();
  /* Date du jour par défaut */
  document.getElementById('f-date').value = extractDate(new Date().toISOString());
  document.getElementById('cmd-preview').classList.remove('show');
  document.getElementById('lignes-rec-container').innerHTML =
    '<div class="lignes-loading">' +
    '<i class="fas fa-arrow-up" style="margin-right:6px;opacity:.4;"></i>' +
    'Sélectionnez d\'abord une commande pour afficher les articles' +
    '</div>';
  document.getElementById('rec-summary').style.display = 'none';
  openModal('modal-create');
}

/*
  Quand on change de commande :
  1. Afficher l'aperçu de la commande
  2. Charger GET /api/commandes/:id pour récupérer les lignes (articles commandés)
*/
function onCommandeChange() {
  var sel = document.getElementById('f-commande');
  var id  = sel.value;
  _lignesCreate = [];

  document.getElementById('cmd-preview').classList.remove('show');
  document.getElementById('rec-summary').style.display = 'none';
  document.getElementById('e-lignes').classList.remove('show');

  if (!id) {
    document.getElementById('lignes-rec-container').innerHTML =
      '<div class="lignes-loading">' +
      '<i class="fas fa-arrow-up" style="margin-right:6px;opacity:.4;"></i>' +
      'Sélectionnez d\'abord une commande' +
      '</div>';
    return;
  }

  /* Trouver la commande dans _commandes pour l'aperçu */
  var cmd = null;
  for (var i = 0; i < _commandes.length; i++) {
    if (String(_commandes[i].id_commande) === String(id)) { cmd = _commandes[i]; break; }
  }
  if (cmd) {
    document.getElementById('cp-fournisseur').textContent  = cmd.fournisseur_nom || '—';
    document.getElementById('cp-date-cmd').textContent     = fmtDate(cmd.date_commande);
    document.getElementById('cp-livraison').textContent    = fmtDate(cmd.date_prevue_livraison);
    document.getElementById('cp-montant').textContent      = fmt(cmd.montant_total_ttc || 0) + ' FCFA';
    document.getElementById('cp-statut').innerHTML         = badgeCmdStatut(cmd.statut);
    document.getElementById('cp-nb').textContent           = (cmd.nb_lignes || '?') + ' ligne' + (cmd.nb_lignes > 1 ? 's' : '');
    document.getElementById('cmd-preview').classList.add('show');
  }

  /* Charger le détail de la commande pour avoir les lignes */
  document.getElementById('lignes-rec-container').innerHTML =
    '<div class="lignes-loading"><i class="fas fa-spinner fa-spin"></i> Chargement des articles…</div>';

  fetch(API + '/commandes/' + id).then(function(r) { return r.json(); }).then(function(resp) {
    var commande = resp.data || resp;
    var lignes   = commande.lignes || [];

    if (!lignes.length) {
      document.getElementById('lignes-rec-container').innerHTML =
        '<div class="lignes-loading"><i class="fas fa-exclamation-circle" style="color:#c87000;margin-right:6px;"></i>'
        + 'Cette commande ne contient aucune ligne.</div>';
      return;
    }

    /* Initialiser _lignesCreate avec les articles de la commande */
    _lignesCreate = lignes.map(function(l) {
      return {
        id_article:         l.id_article,
        article_nom:        l.article_nom,
        reference:          l.reference || '',
        unite:              l.unite || 'u',
        quantite_commandee: parseFloat(l.quantite) || 0,
        quantite_recue:     parseFloat(l.quantite) || 0,  /* pré-remplir = tout reçu */
        commentaire:        ''
      };
    });

    renderLignesCreate();
  }).catch(function() {
    document.getElementById('lignes-rec-container').innerHTML =
      '<div class="lignes-loading" style="color:#c03030;">'
      + '<i class="fas fa-circle-exclamation" style="margin-right:6px;"></i>'
      + 'Impossible de charger les articles de cette commande.</div>';
  });
}

/* badge commande statut (mini) */
function badgeCmdStatut(s) {
  var map = {
    confirmee:       '<span class="sbadge sb-muted" style="font-size:.68rem;">Confirmée</span>',
    expediee:        '<span class="sbadge sb-warn"  style="font-size:.68rem;">Expédiée</span>',
    recue_partielle: '<span class="sbadge sb-blue"  style="font-size:.68rem;">Partielle</span>'
  };
  return map[s] || ('<span class="sbadge sb-muted" style="font-size:.68rem;">' + esc(s) + '</span>');
}

function renderLignesCreate() {
  if (!_lignesCreate.length) return;

  var html = '<div class="lignes-rec-wrap">'
    + '<div class="lr-head">'
    + '<span>Article</span>'
    + '<span style="text-align:right;">Commandé</span>'
    + '<span style="text-align:right;">Reçu <span style="color:#e03e3e;">*</span></span>'
    + '<span style="text-align:center;">Écart</span>'
    + '<span>Commentaire</span>'
    + '</div>';

  _lignesCreate.forEach(function(l, i) {
    var ecart = l.quantite_commandee - l.quantite_recue;
    var ecartCls = ecart === 0 ? 'ok' : (ecart > 0 ? 'warn' : 'crit');
    var ecartTxt = ecart === 0
      ? '<i class="fas fa-check"></i>'
      : (fmt(Math.abs(ecart)) + ' ' + esc(l.unite) + (ecart > 0 ? ' manquant' : ' surplus'));

    html += '<div class="lr-row">'
      + '<div>'
      +   '<div class="lr-art">' + esc(l.article_nom) + '</div>'
      +   '<div class="lr-ref">' + esc(l.reference) + ' · ' + esc(l.unite) + '</div>'
      + '</div>'
      /* Commandé (readonly) */
      + '<input type="number" class="finput" value="' + l.quantite_commandee + '" disabled'
      +   ' style="font-family:\'DM Mono\',monospace;text-align:right;">'
      /* Reçu (éditable) */
      + '<input type="number" class="finput" id="lr-rec-' + i + '"'
      +   ' value="' + l.quantite_recue + '" min="0" step="0.001"'
      +   ' oninput="onRecuChange(' + i + ',this)"'
      +   ' style="font-family:\'DM Mono\',monospace;text-align:right;">'
      /* Écart calculé */
      + '<div class="lr-ecart ' + ecartCls + '" id="lr-ecart-' + i + '">' + ecartTxt + '</div>'
      /* Commentaire */
      + '<input type="text" class="lr-commentaire" id="lr-com-' + i + '"'
      +   ' value="' + esc(l.commentaire) + '"'
      +   ' placeholder="Remarque…"'
      +   ' oninput="onCommentaireChange(' + i + ',this)">'
      + '</div>';
  });

  html += '</div>';
  document.getElementById('lignes-rec-container').innerHTML = html;
  document.getElementById('rec-summary').style.display = 'flex';
  updateSummary();
}

function onRecuChange(i, inp) {
  _lignesCreate[i].quantite_recue = parseFloat(inp.value) || 0;
  /* Mettre à jour l'écart affiché */
  var ecart = _lignesCreate[i].quantite_commandee - _lignesCreate[i].quantite_recue;
  var el    = document.getElementById('lr-ecart-' + i);
  if (el) {
    el.className = 'lr-ecart ' + (ecart === 0 ? 'ok' : (ecart > 0 ? 'warn' : 'crit'));
    el.innerHTML = ecart === 0
      ? '<i class="fas fa-check"></i>'
      : fmt(Math.abs(ecart)) + ' ' + esc(_lignesCreate[i].unite) + (ecart > 0 ? ' manquant' : ' surplus');
  }
  updateSummary();
}

function onCommentaireChange(i, inp) {
  _lignesCreate[i].commentaire = inp.value;
}

function updateSummary() {
  var totalCmd = 0, totalRec = 0;
  _lignesCreate.forEach(function(l) {
    totalCmd += parseFloat(l.quantite_commandee) || 0;
    totalRec += parseFloat(l.quantite_recue)     || 0;
  });
  var ecartTotal = totalCmd - totalRec;
  /* Statut prévu : même logique que l'API */
  var statutPrevu = ecartTotal === 0 ? 'complete' : 'partielle';
  var e = STATUTS_REC[statutPrevu];

  document.getElementById('rs-nb').textContent    = _lignesCreate.length;
  document.getElementById('rs-cmd').textContent   = fmt(totalCmd);
  document.getElementById('rs-rec').textContent   = fmt(totalRec);

  var ecartEl = document.getElementById('rs-ecart');
  ecartEl.textContent  = fmt(Math.abs(ecartTotal)) + (ecartTotal < 0 ? ' (surplus)' : '');
  ecartEl.className    = 'rs-val ' + (ecartTotal === 0 ? 'ok' : (ecartTotal > 0 ? 'warn' : 'crit'));

  document.getElementById('rs-statut').innerHTML =
    '<span class="sbadge ' + e.cls + '" style="font-size:.78rem;">' + e.lbl + '</span>';
}

/* ════════════════════════════════════════
   SAUVEGARDER LA RÉCEPTION
   POST /api/receptions
   Body : { id_commande, id_utilisateur, date_reception,
            observation?, lignes: [{ id_article,
            quantite_commandee, quantite_recue, commentaire? }] }
   Réponse : { success, id, statut, message }
════════════════════════════════════════ */
function saveReception() {
  var valid = true;

  /* Validation commande */
  var cmdSel = document.getElementById('f-commande');
  if (!cmdSel.value) {
    cmdSel.classList.add('error');
    document.getElementById('e-commande').classList.add('show');
    valid = false;
  } else {
    cmdSel.classList.remove('error');
    document.getElementById('e-commande').classList.remove('show');
  }

  /* Validation date */
  var dateEl = document.getElementById('f-date');
  if (!dateEl.value) {
    dateEl.classList.add('error');
    document.getElementById('e-date').classList.add('show');
    valid = false;
  } else {
    dateEl.classList.remove('error');
    document.getElementById('e-date').classList.remove('show');
  }

  /* Validation lignes : au moins une reçue */
  var lignesValides = _lignesCreate.filter(function(l) {
    return parseFloat(l.quantite_recue) >= 0 && l.id_article;
  });
  if (!lignesValides.length) {
    document.getElementById('e-lignes').classList.add('show');
    valid = false;
  } else {
    document.getElementById('e-lignes').classList.remove('show');
  }

  if (!valid) return;

  var btn = document.getElementById('btn-save');
  btn.disabled = true;
  btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enregistrement…';

  var body = {
    id_commande:    parseInt(cmdSel.value),
    id_utilisateur: UID,
    date_reception: dateEl.value,
    lignes: lignesValides.map(function(l) {
      return {
        id_article:         l.id_article,
        quantite_commandee: parseFloat(l.quantite_commandee),
        quantite_recue:     parseFloat(l.quantite_recue),
        commentaire:        l.commentaire || null
      };
    })
  };
  var obs = document.getElementById('f-observation').value.trim();
  if (obs) body.observation = obs;

  fetch(API + '/receptions', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(body)
  }).then(function(r) {
    return r.json().then(function(d) { return { ok: r.ok, d: d }; });
  }).then(function(res) {
    if (!res.ok) throw new Error(res.d.message || 'Erreur serveur');
    var e = STATUTS_REC[res.d.statut] || { lbl: res.d.statut };
    closeModal('modal-create');
    showSuccess('Réception enregistrée — Statut : ' + e.lbl
      + '. Stock mis à jour automatiquement.');
    return load();
  }).catch(function(e) {
    showError('Erreur : ' + (e.message || ''));
  }).finally(function() {
    btn.disabled = false;
    btn.innerHTML = '<i class="fas fa-clipboard-check"></i> Enregistrer la réception';
  });
}

/* ════════════════════════════════════════
   DÉTAIL RÉCEPTION
   GET /api/receptions/:id
   Retourne : { ...reception, lignes:[] }
   lignes : { article_nom, reference, unite,
              quantite_commandee, quantite_recue,
              ecart (DB calculé), commentaire }
════════════════════════════════════════ */
function openDetail(id) {
  document.getElementById('detail-body').innerHTML =
    '<div style="text-align:center;padding:32px;color:#aab5c6;">' +
    '<i class="fas fa-spinner fa-spin"></i> Chargement…</div>';
  document.getElementById('detail-titre').textContent = 'Détail réception';
  openModal('modal-detail');

  fetch(API + '/receptions/' + id).then(function(r) { return r.json(); }).then(function(resp) {
    var data = resp.data || resp;
    /*
      GET /receptions/:id ne retourne PAS receptionnaire (pas de JOIN utilisateurs).
      On le récupère depuis _all (liste déjà chargée) via id_reception.
      Si l'API est corrigée un jour pour inclure receptionnaire, ça marchera aussi.
    */
    if (!data.receptionnaire) {
      for (var i = 0; i < _all.length; i++) {
        if (String(_all[i].id_reception) === String(id)) {
          data.receptionnaire = _all[i].receptionnaire;
          break;
        }
      }
    }
    renderDetail(data);
  }).catch(function() {
    document.getElementById('detail-body').innerHTML =
      '<div class="empty-state"><div class="empty-title">Erreur de chargement</div></div>';
  });
}

function renderDetail(r) {
  var numRec = 'REC-' + String(r.id_reception).padStart(5, '0');
  document.getElementById('detail-titre').textContent = numRec;

  /* Lignes */
  var lignesHtml = '';
  (r.lignes || []).forEach(function(l) {
    /* ecart = colonne générée DB : quantite_commandee - quantite_recue */
    var ecart    = parseFloat(l.ecart) || 0;
    var ecartCls = ecart === 0 ? 'ok' : (ecart > 0 ? 'warn' : 'crit');
    var ecartSign = ecart > 0 ? '−' : (ecart < 0 ? '+' : '');  // manquant = −, surplus = +

    lignesHtml += '<div class="dl-row">'
      + '<div>'
      +   '<div style="font-weight:700;color:var(--text,#0D1526);font-size:.88rem;">' + esc(l.article_nom) + '</div>'
      +   '<div class="dl-ref">' + esc(l.reference || '') + ' · ' + esc(l.unite || '') + '</div>'
      + '</div>'
      + '<div class="dl-num">' + fmt(l.quantite_commandee) + '</div>'
      + '<div class="dl-num" style="color:#1a8a4a;">' + fmt(l.quantite_recue) + '</div>'
      + '<div class="dl-ecart ' + ecartCls + '">'
      +   (ecart === 0
            ? '<i class="fas fa-check" style="font-size:.7rem;"></i>'
            : ecartSign + fmt(Math.abs(ecart)))
      + '</div>'
      + '<div class="dl-comment">' + esc(l.commentaire || '') + '</div>'
      + '</div>';
  });

  /* Totaux */
  var totalCmd = (r.lignes || []).reduce(function(s,l){ return s + parseFloat(l.quantite_commandee||0); }, 0);
  var totalRec = (r.lignes || []).reduce(function(s,l){ return s + parseFloat(l.quantite_recue||0); }, 0);
  var totalEcart = totalCmd - totalRec;

  document.getElementById('detail-body').innerHTML =

    /* Méta */
    '<div class="detail-meta">'
    + '<div class="dmeta"><div class="dmeta-l">N° Réception</div><div class="dmeta-v">' + esc(numRec) + '</div></div>'
    + '<div class="dmeta"><div class="dmeta-l">Commande</div><div class="dmeta-v">' + esc(r.numero_commande || '—') + '</div></div>'
    + '<div class="dmeta"><div class="dmeta-l">Fournisseur</div><div class="dmeta-v">' + esc(r.fournisseur_nom || '—') + '</div></div>'
    + '<div class="dmeta"><div class="dmeta-l">Date réception</div><div class="dmeta-v">' + fmtDate(r.date_reception) + '</div></div>'
    + '<div class="dmeta"><div class="dmeta-l">Réceptionné par</div><div class="dmeta-v">' + esc(r.receptionnaire || '—') + '</div></div>'
    + '<div class="dmeta"><div class="dmeta-l">Statut</div><div class="dmeta-v">' + badgeRec(r.statut) + '</div></div>'
    + '</div>'

    /* Observation */
    + (r.observation
        ? '<div style="background:#fffbf2;border:1px solid #f5c96a;border-radius:9px;padding:11px 14px;margin-bottom:16px;font-size:.86rem;color:#5A6E88;">'
        +   '<i class="fas fa-note-sticky" style="margin-right:7px;"></i>' + esc(r.observation)
        + '</div>'
        : '')

    /* Tableau lignes */
    + '<div class="detail-lignes">'
    + '<div class="dl-head">'
    + '<span>Article · Réf</span>'
    + '<span style="text-align:right;">Commandé</span>'
    + '<span style="text-align:right;">Reçu</span>'
    + '<span style="text-align:right;">Écart</span>'
    + '<span>Commentaire</span>'
    + '</div>'
    + lignesHtml

    /* Barre totaux */
    + '<div style="display:flex;justify-content:flex-end;align-items:center;gap:20px;padding:12px 14px;'
    +   'background:var(--surface2,#f4f6fb);border-top:2px solid var(--border,#e4e8f0);flex-wrap:wrap;">'
    +   '<span style="font-size:.82rem;color:#8FA4BF;">Total commandé</span>'
    +   '<span style="font-family:\'DM Mono\',monospace;font-weight:700;">' + fmt(totalCmd) + '</span>'
    +   '<span style="font-size:.82rem;color:#8FA4BF;margin-left:12px;">Total reçu</span>'
    +   '<span style="font-family:\'DM Mono\',monospace;font-weight:700;color:#1a8a4a;">' + fmt(totalRec) + '</span>'
    +   '<span style="font-size:.82rem;color:#8FA4BF;margin-left:12px;">Écart total</span>'
    +   '<span style="font-family:\'DM Mono\',monospace;font-weight:700;color:'
    +     (totalEcart === 0 ? '#1a8a4a' : (totalEcart > 0 ? '#c87000' : '#c03030')) + ';">'
    +     fmt(Math.abs(totalEcart)) + (totalEcart < 0 ? ' surplus' : '')
    +   '</span>'
    + '</div>'
    + '</div>';
}

/* ════════════════════════════════════════
   UTILITAIRES MODAL & FORMULAIRE
════════════════════════════════════════ */
function openModal(id)  { document.getElementById(id).classList.add('open');    document.body.style.overflow = 'hidden'; }
function closeModal(id) { document.getElementById(id).classList.remove('open'); document.body.style.overflow = ''; }

function clearCreateForm() {
  document.getElementById('f-commande').value    = '';
  document.getElementById('f-date').value        = '';
  document.getElementById('f-observation').value = '';
  document.querySelectorAll('.fselect.error,.finput.error').forEach(function(e){ e.classList.remove('error'); });
  document.querySelectorAll('.ferr.show').forEach(function(e){ e.classList.remove('show'); });
}

document.querySelectorAll('.modal-bg').forEach(function(bg) {
  bg.addEventListener('click', function(e) { if (e.target === bg) closeModal(bg.id); });
});
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape')
    document.querySelectorAll('.modal-bg.open').forEach(function(m){ closeModal(m.id); });
});

load();
</script>

<?php require_once 'includes/footer.php'; ?>