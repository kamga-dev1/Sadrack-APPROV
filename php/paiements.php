<?php
require_once 'includes/config.php';
if(empty($_SESSION['user'])){header('Location: login.php');exit;}
$_uid = $_SESSION['user']['id'] ?? $_SESSION['user']['id_utilisateur'] ?? 0;
?>
<?php require_once 'includes/header.php'; ?>
<?php require_once 'includes/sidebar.php'; ?>
<script>
  if(document.getElementById('nav-page-title'))
    document.getElementById('nav-page-title').textContent = 'Paiements';
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

/* ─── KPI strip ─── */
.kstrip{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:20px;}
@media(max-width:900px){.kstrip{grid-template-columns:repeat(2,1fr);}}
@media(max-width:480px){.kstrip{grid-template-columns:1fr;}}
.ks{background:var(--surface,#fff);border:1px solid var(--border,#e4e8f0);border-radius:11px;padding:14px 16px;display:flex;align-items:center;gap:12px;box-shadow:0 1px 4px rgba(5,8,15,.06);cursor:pointer;transition:box-shadow .15s;}
.ks:hover{box-shadow:0 3px 12px rgba(5,8,15,.1);}
.ks.active-filter{border-color:var(--gold,#E2A84B);box-shadow:0 0 0 3px rgba(226,168,75,.15);}
.ks-ico{width:38px;height:38px;border-radius:9px;display:flex;align-items:center;justify-content:center;font-size:.9rem;flex-shrink:0;}
.ks-n{font-size:1.55rem;font-weight:800;color:var(--text,#0D1526);letter-spacing:-1px;line-height:1;}
.ks-l{font-size:.73rem;color:var(--text3,#8FA4BF);margin-top:3px;}

/* ─── Bandeau urgents ─── */
.urgents-banner{background:#fff8f0;border:1.5px solid #f5c96a;border-radius:11px;padding:14px 18px;margin-bottom:18px;display:none;}
.urgents-banner.show{display:block;}
.urgents-title{display:flex;align-items:center;gap:8px;font-size:.86rem;font-weight:700;color:#b07000;margin-bottom:10px;}
.urgents-list{display:flex;flex-direction:column;gap:6px;}
.urgents-item{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;background:var(--surface,#fff);border:1px solid #f5dca0;border-radius:8px;padding:9px 14px;font-size:.84rem;}
.urgents-left{display:flex;flex-direction:column;gap:2px;}
.urgents-facture{font-family:'DM Mono',monospace;font-weight:700;color:var(--text,#0D1526);font-size:.82rem;}
.urgents-fourn{color:var(--text3,#8FA4BF);font-size:.78rem;}
.urgents-right{display:flex;align-items:center;gap:10px;}
.retard-pill{display:inline-flex;align-items:center;gap:4px;padding:3px 9px;border-radius:12px;font-size:.72rem;font-weight:700;}
.rp-urgent{background:#fff0f0;color:#c03030;}
.rp-warn{background:#fff4e0;color:#c87000;}
.urg-montant{font-family:'DM Mono',monospace;font-weight:700;color:#b07000;font-size:.88rem;}

/* ─── Toolbar ─── */
.toolbar{display:flex;align-items:center;gap:10px;flex-wrap:wrap;background:var(--surface,#fff);border:1px solid var(--border,#e4e8f0);border-radius:11px;padding:12px 16px;margin-bottom:16px;box-shadow:0 1px 4px rgba(5,8,15,.06);}
.tsearch{display:flex;align-items:center;gap:9px;background:var(--canvas,#f8f9fc);border:1px solid var(--border,#e4e8f0);border-radius:8px;padding:0 12px;flex:1;min-width:200px;max-width:320px;transition:border-color .18s;}
.tsearch:focus-within{border-color:var(--gold,#E2A84B);}
.tsearch i{color:var(--text4,#aab5c6);font-size:.82rem;}
.tsearch input{border:none;background:transparent;outline:none;font-family:inherit;font-size:.88rem;color:var(--text,#0D1526);padding:8px 0;width:100%;}
.tsearch input::placeholder{color:var(--text4,#aab5c6);}
.tsel{display:flex;align-items:center;gap:7px;padding:7px 12px;border-radius:8px;background:var(--canvas,#f8f9fc);border:1px solid var(--border,#e4e8f0);font-size:.82rem;color:var(--text2,#3a4a5c);font-family:inherit;}
.tsel select{border:none;background:transparent;outline:none;font-family:inherit;font-size:.82rem;color:var(--text2,#3a4a5c);cursor:pointer;}
.tb-sep{width:1px;height:24px;background:var(--border,#e4e8f0);margin:0 2px;}
.tb-count{font-size:.78rem;color:var(--text4,#aab5c6);white-space:nowrap;margin-left:auto;}
.tb-reset{font-size:.76rem;color:var(--gold,#E2A84B);cursor:pointer;font-weight:600;display:none;}
.tb-reset.show{display:inline;}

/* ─── Table ─── */
.tcard{background:var(--surface,#fff);border:1px solid var(--border,#e4e8f0);border-radius:12px;box-shadow:0 1px 4px rgba(5,8,15,.06);overflow:hidden;}
.etbl{width:100%;border-collapse:collapse;table-layout:fixed;}
.etbl thead th{font-size:.65rem;font-weight:800;text-transform:uppercase;letter-spacing:1.2px;color:var(--text4,#aab5c6);padding:10px 14px;border-bottom:2px solid var(--border,#e4e8f0);background:var(--surface2,#f4f6fb);white-space:nowrap;text-align:left;cursor:pointer;user-select:none;}
.etbl thead th:hover{color:var(--text2,#3a4a5c);}
.etbl thead th.sorted{color:#b87220;}
.sort-ico{margin-left:4px;opacity:.35;font-size:.58rem;}
.sorted .sort-ico{opacity:1;color:#b87220;}
/* N° Facture | Fournisseur | Commande | Montant | Échéance | Statut | Mode | Actions */
.etbl thead th:nth-child(1){width:12%;}
.etbl thead th:nth-child(2){width:16%;}
.etbl thead th:nth-child(3){width:12%;}
.etbl thead th:nth-child(4){width:11%;text-align:right;}
.etbl thead th:nth-child(5){width:11%;}
.etbl thead th:nth-child(6){width:10%;}
.etbl thead th:nth-child(7){width:10%;}
.etbl thead th:nth-child(8){width:11%;text-align:right;}
.etbl tbody td{padding:10px 14px;border-bottom:1px solid var(--border,#e4e8f0);font-size:.85rem;color:var(--text2,#3a4a5c);vertical-align:middle;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
.etbl tbody td:nth-child(4){text-align:right;}
.etbl tbody td:nth-child(8){text-align:right;}
.etbl tbody tr:last-child td{border-bottom:none;}
.etbl tbody tr:hover{background:var(--surface2,#f4f6fb);cursor:pointer;}
.etbl tbody tr.row-retard{background:#fff8f5;}
.etbl tbody tr.row-retard:hover{background:#fff0e8;}
.c-num{font-family:'DM Mono',monospace;font-weight:700;color:var(--text,#0D1526);font-size:.84rem;}
.c-price{font-family:'DM Mono',monospace;font-weight:700;color:#b87220;}
.c-date-late{color:#c03030;font-weight:700;}
.c-actions{display:flex;align-items:center;gap:5px;justify-content:flex-end;}
.act-btn{width:30px;height:30px;border-radius:7px;display:flex;align-items:center;justify-content:center;font-size:.72rem;cursor:pointer;border:1px solid var(--border,#e4e8f0);background:var(--canvas,#f8f9fc);color:var(--text3,#8FA4BF);transition:all .15s;}
.act-btn.view:hover{background:#ebf5ff;border-color:#90c6f8;color:#1a7fd4;}
.act-btn.pay:hover{background:#eafaf1;border-color:#79d6a0;color:#1a8a4a;}

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
.modal{background:var(--surface,#fff);border:1px solid var(--border,#e4e8f0);border-radius:16px;width:100%;max-width:580px;box-shadow:0 8px 40px rgba(5,8,15,.18);animation:mIn .22s ease;max-height:92vh;overflow-y:auto;}
.modal-lg{max-width:680px;}
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
.ftextarea{resize:vertical;min-height:56px;}
.ferr{font-size:.72rem;color:#e03e3e;margin-top:3px;display:none;}
.ferr.show{display:block;}
.fsep{border:none;border-top:1px solid var(--border,#e4e8f0);margin:4px 0 16px;}
.fsec-title{font-size:.68rem;font-weight:800;text-transform:uppercase;letter-spacing:1.5px;color:var(--text4,#aab5c6);margin-bottom:12px;}

/* ─── Détail paiement ─── */
.detail-meta{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:18px;}
@media(max-width:560px){.detail-meta{grid-template-columns:1fr 1fr;}}
.dmeta{background:var(--canvas,#f8f9fc);border:1px solid var(--border,#e4e8f0);border-radius:9px;padding:10px 13px;}
.dmeta-l{font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:var(--text4,#aab5c6);margin-bottom:3px;}
.dmeta-v{font-size:.9rem;font-weight:700;color:var(--text,#0D1526);}
.dmeta-v.price{color:#b87220;font-family:'DM Mono',monospace;font-size:1.05rem;}
.dmeta-v.late{color:#c03030;}
.dmeta-v.ok{color:#1a8a4a;}

/* ─── Mode paiement icons ─── */
.mode-ico{font-size:.75rem;margin-right:4px;}

/* ─── Badges ENUM DB ─── */
/* statut : en_attente | paye | en_retard | annule */
/* mode_paiement : virement | cheque | especes | autre */
.sbadge{display:inline-flex;align-items:center;padding:3px 10px;border-radius:20px;font-size:.72rem;font-weight:700;white-space:nowrap;}
.sb-info{background:#ebf5ff;color:#1a7fd4;}
.sb-ok{background:#eafaf1;color:#1a8a4a;}
.sb-crit{background:#fff0f0;color:#c03030;}
.sb-muted{background:#f0f2f5;color:#7a8a9a;}
.sb-warn{background:#fff4e0;color:#c87000;}

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
      <i class="fas fa-file-invoice-dollar" style="color:var(--gold,#E2A84B);margin-right:9px;"></i>Paiements
    </div>
    <div class="ph-sub">Suivi des factures et règlements fournisseurs</div>
  </div>
  <div class="ph-right">
    <button class="btn-gold" onclick="openCreate()">
      <i class="fas fa-plus"></i> Nouvelle facture
    </button>
  </div>
</div>

<!-- ═══ KPI STRIP (cliquables → filtres) ═══ -->
<div class="kstrip">
  <div class="ks" id="ks-btn-attente" onclick="filterByStatut('en_attente','ks-btn-attente')">
    <div class="ks-ico" style="background:#ebf5ff;color:#1a7fd4;"><i class="fas fa-clock"></i></div>
    <div><div class="ks-n" id="ks-attente">—</div><div class="ks-l">En attente</div></div>
  </div>
  <div class="ks" id="ks-btn-retard" onclick="filterByStatut('en_retard','ks-btn-retard')">
    <div class="ks-ico" style="background:#fff0f0;color:#c03030;"><i class="fas fa-triangle-exclamation"></i></div>
    <div><div class="ks-n" id="ks-retard">—</div><div class="ks-l">En retard</div></div>
  </div>
  <div class="ks" id="ks-btn-paye" onclick="filterByStatut('paye','ks-btn-paye')">
    <div class="ks-ico" style="background:#eafaf1;color:#1a8a4a;"><i class="fas fa-circle-check"></i></div>
    <div><div class="ks-n" id="ks-paye">—</div><div class="ks-l">Payés</div></div>
  </div>
  <div class="ks" id="ks-btn-total" onclick="filterByStatut('','ks-btn-total')">
    <div class="ks-ico" style="background:#fff8e6;color:#b87220;"><i class="fas fa-coins"></i></div>
    <div><div class="ks-n" id="ks-total">—</div><div class="ks-l">Total impayé (FCFA)</div></div>
  </div>
</div>

<!-- ═══ BANDEAU URGENTS ═══ -->
<div class="urgents-banner" id="urgents-banner">
  <div class="urgents-title">
    <i class="fas fa-circle-exclamation"></i>
    Paiements urgents — échéance dans les 7 jours ou dépassée
  </div>
  <div class="urgents-list" id="urgents-list"></div>
</div>

<!-- ═══ TOOLBAR ═══ -->
<div class="toolbar">
  <div class="tsearch">
    <i class="fas fa-search"></i>
    <input type="text" id="search-input" placeholder="N° facture, fournisseur, commande…" oninput="filterTable()">
  </div>
  <div class="tsel">
    <i class="fas fa-filter" style="font-size:.76rem;"></i>
    <select id="filter-statut" onchange="filterTable()">
      <option value="">Tous les statuts</option>
      <option value="en_attente">En attente</option>
      <option value="paye">Payé</option>
      <option value="en_retard">En retard</option>
      <option value="annule">Annulé</option>
    </select>
  </div>
  <div class="tsel">
    <i class="fas fa-university" style="font-size:.76rem;"></i>
    <select id="filter-mode" onchange="filterTable()">
      <option value="">Tous modes</option>
      <option value="virement">Virement</option>
      <option value="cheque">Chèque</option>
      <option value="especes">Espèces</option>
      <option value="autre">Autre</option>
    </select>
  </div>
  <div class="tb-sep"></div>
  <span class="tb-reset" id="tb-reset" onclick="resetFilters()">
    <i class="fas fa-xmark"></i> Réinitialiser
  </span>
  <div class="tb-count" id="tb-count">Chargement…</div>
</div>

<!-- ═══ TABLE ═══ -->
<div class="tcard">
  <div style="overflow-x:auto;">
    <table class="etbl">
      <thead>
        <tr>
          <th onclick="sortBy('numero_facture')">N° Facture <i class="fas fa-sort sort-ico"></i></th>
          <th onclick="sortBy('fournisseur_nom')">Fournisseur <i class="fas fa-sort sort-ico"></i></th>
          <th onclick="sortBy('numero_commande')">N° Commande <i class="fas fa-sort sort-ico"></i></th>
          <th onclick="sortBy('montant')" style="text-align:right;">Montant <i class="fas fa-sort sort-ico"></i></th>
          <th onclick="sortBy('date_echeance')">Échéance <i class="fas fa-sort sort-ico"></i></th>
          <th onclick="sortBy('statut')">Statut <i class="fas fa-sort sort-ico"></i></th>
          <th onclick="sortBy('mode_paiement')">Mode <i class="fas fa-sort sort-ico"></i></th>
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


<!-- ════════════════════════════════════════
     MODAL NOUVELLE FACTURE
════════════════════════════════════════ -->
<div class="modal-bg" id="modal-create">
  <div class="modal modal-lg">
    <div class="modal-head">
      <div class="modal-title">
        <div class="modal-ico" style="background:rgba(226,168,75,.12);color:#b87220;">
          <i class="fas fa-file-invoice-dollar"></i>
        </div>
        Enregistrer une facture
      </div>
      <button class="modal-close" onclick="closeModal('modal-create')"><i class="fas fa-xmark"></i></button>
    </div>
    <div class="modal-body">

      <div class="fsec-title">Informations facture</div>
      <div class="frow frow-2">
        <div class="fgroup">
          <label class="flabel">Fournisseur <span>*</span></label>
          <select class="fselect" id="f-fournisseur">
            <option value="">— Sélectionner —</option>
          </select>
          <div class="ferr" id="e-fournisseur">Champ requis</div>
        </div>
        <div class="fgroup">
          <label class="flabel">Commande liée</label>
          <select class="fselect" id="f-commande">
            <option value="">— Optionnel —</option>
          </select>
        </div>
      </div>
      <div class="frow frow-3">
        <div class="fgroup">
          <label class="flabel">N° Facture fournisseur <span>*</span></label>
          <input type="text" class="finput" id="f-numero" placeholder="FAC-2026-001">
          <div class="ferr" id="e-numero">Champ requis</div>
        </div>
        <div class="fgroup">
          <label class="flabel">Date facture <span>*</span></label>
          <input type="date" class="finput" id="f-date-facture">
          <div class="ferr" id="e-date-facture">Champ requis</div>
        </div>
        <div class="fgroup">
          <label class="flabel">Montant (FCFA) <span>*</span></label>
          <input type="number" class="finput" id="f-montant" min="0" step="1"
            placeholder="0" style="font-family:'DM Mono',monospace;font-weight:700;">
          <div class="ferr" id="e-montant">Montant invalide</div>
        </div>
      </div>
      <div class="frow frow-2">
        <div class="fgroup">
          <label class="flabel">Date d'échéance <span>*</span></label>
          <input type="date" class="finput" id="f-echeance">
          <div class="ferr" id="e-echeance">Champ requis</div>
        </div>
        <div class="fgroup">
          <label class="flabel">Mode de paiement</label>
          <select class="fselect" id="f-mode">
            <option value="">— Non défini —</option>
            <option value="virement">Virement bancaire</option>
            <option value="cheque">Chèque</option>
            <option value="especes">Espèces</option>
            <option value="autre">Autre</option>
          </select>
        </div>
      </div>
      <div class="frow">
        <div class="fgroup">
          <label class="flabel">Notes</label>
          <textarea class="ftextarea" id="f-notes" placeholder="Conditions particulières, références…"></textarea>
        </div>
      </div>

    </div>
    <div class="modal-footer">
      <button class="btn-secondary" onclick="closeModal('modal-create')">Annuler</button>
      <button class="btn-gold" id="btn-save" onclick="saveFacture()">
        <i class="fas fa-floppy-disk"></i> Enregistrer la facture
      </button>
    </div>
  </div>
</div>


<!-- ════════════════════════════════════════
     MODAL DÉTAIL / PAYER
════════════════════════════════════════ -->
<div class="modal-bg" id="modal-detail">
  <div class="modal modal-lg">
    <div class="modal-head">
      <div class="modal-title">
        <div class="modal-ico" style="background:rgba(226,168,75,.12);color:#b87220;">
          <i class="fas fa-receipt"></i>
        </div>
        <span id="detail-titre">Facture</span>
      </div>
      <button class="modal-close" onclick="closeModal('modal-detail')"><i class="fas fa-xmark"></i></button>
    </div>
    <div class="modal-body" id="detail-body">
      <div style="text-align:center;padding:32px;color:#aab5c6;">
        <i class="fas fa-spinner fa-spin"></i> Chargement…
      </div>
    </div>
    <div class="modal-footer" id="detail-footer">
      <button class="btn-secondary" onclick="closeModal('modal-detail')">Fermer</button>
    </div>
  </div>
</div>


<!-- ════════════════════════════════════════
     MODAL ENREGISTRER PAIEMENT
     PUT /api/paiements/:id/payer
════════════════════════════════════════ -->
<div class="modal-bg" id="modal-payer">
  <div class="modal">
    <div class="modal-head">
      <div class="modal-title">
        <div class="modal-ico" style="background:#eafaf1;color:#1a8a4a;">
          <i class="fas fa-circle-check"></i>
        </div>
        Enregistrer le paiement
      </div>
      <button class="modal-close" onclick="closeModal('modal-payer')"><i class="fas fa-xmark"></i></button>
    </div>
    <div class="modal-body">
      <input type="hidden" id="p-id">

      <!-- Résumé facture -->
      <div style="background:var(--canvas,#f8f9fc);border:1px solid var(--border,#e4e8f0);border-radius:10px;padding:13px 16px;margin-bottom:18px;">
        <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:8px;">
          <div>
            <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:var(--text4,#aab5c6);">Facture</div>
            <div style="font-family:'DM Mono',monospace;font-weight:700;color:var(--text,#0D1526);" id="p-num-display">—</div>
            <div style="font-size:.82rem;color:var(--text3,#8FA4BF);" id="p-fourn-display">—</div>
          </div>
          <div style="text-align:right;">
            <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:var(--text4,#aab5c6);">Montant</div>
            <div style="font-family:'DM Mono',monospace;font-size:1.3rem;font-weight:800;color:#b87220;" id="p-montant-display">—</div>
          </div>
        </div>
      </div>

      <div class="frow frow-2">
        <div class="fgroup">
          <label class="flabel">Date de paiement <span>*</span></label>
          <input type="date" class="finput" id="p-date">
          <div class="ferr" id="e-p-date">Date requise</div>
        </div>
        <div class="fgroup">
          <label class="flabel">Mode de paiement <span>*</span></label>
          <select class="fselect" id="p-mode">
            <option value="">— Sélectionner —</option>
            <option value="virement">Virement bancaire</option>
            <option value="cheque">Chèque</option>
            <option value="especes">Espèces</option>
            <option value="autre">Autre</option>
          </select>
          <div class="ferr" id="e-p-mode">Mode requis</div>
        </div>
      </div>
      <div class="frow">
        <div class="fgroup">
          <label class="flabel">Référence de paiement</label>
          <input type="text" class="finput" id="p-ref"
            placeholder="N° virement, chèque, reçu…"
            style="font-family:'DM Mono',monospace;">
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn-secondary" onclick="closeModal('modal-payer')">Annuler</button>
      <button class="btn-gold" id="btn-payer" onclick="confirmerPaiement()"
        style="background:#1a8a4a;">
        <i class="fas fa-circle-check"></i> Confirmer le paiement
      </button>
    </div>
  </div>
</div>


<!-- ════════════════════════════════════════
     JAVASCRIPT
════════════════════════════════════════ -->
<script>
/* ════════════════════════════════════════
   CONFIG
   API :  GET  /api/paiements           → liste triée date_echeance ASC
          GET  /api/paiements/urgents   → vue v_paiements_urgents + jours_retard
          GET  /api/paiements/:id       → détail
          POST /api/paiements           → créer facture
          PUT  /api/paiements/:id/payer → marquer payé

   Champs DB paiements :
     id_paiement, id_fournisseur, id_commande (nullable),
     id_utilisateur, numero_facture, date_facture, montant,
     date_echeance, statut, mode_paiement, date_paiement,
     reference_paiement, notes, created_at, updated_at
     + fournisseur_nom (JOIN), numero_commande (LEFT JOIN, nullable)

   ENUM statut       : en_attente | paye | en_retard | annule
   ENUM mode_paiement: virement | cheque | especes | autre

   Trigger DB : passe en_retard si date_echeance < CURDATE() au UPDATE
════════════════════════════════════════ */
var API = 'http://localhost:3000/api';
var UID = <?= (int)$_uid ?>;

var _all        = [];
var _shown      = [];
var _fournisseurs = [];
var _commandes  = [];
var _page       = 1;
var PER         = 15;
var _sortCol    = 'date_echeance';
var _sortAsc    = true;  // tri par défaut ASC (les plus urgentes en premier)
var _kpiFilter  = '';    // filtre actif via KPI

/* ════════════════════════════════════════
   ENUM STATUTS & MODES (DB)
════════════════════════════════════════ */
var STATUTS = {
  en_attente: { cls: 'sb-info', lbl: 'En attente' },
  paye:       { cls: 'sb-ok',   lbl: 'Payé'       },
  en_retard:  { cls: 'sb-crit', lbl: 'En retard'  },
  annule:     { cls: 'sb-muted',lbl: 'Annulé'     }
};
var MODES = {
  virement: { ico: 'fa-building-columns', lbl: 'Virement'  },
  cheque:   { ico: 'fa-money-check',      lbl: 'Chèque'    },
  especes:  { ico: 'fa-money-bill-wave',  lbl: 'Espèces'   },
  autre:    { ico: 'fa-ellipsis',         lbl: 'Autre'      }
};

/* ════════════════════════════════════════
   UTILITAIRES — fix timezone ISO UTC
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
function today() {
  var n = new Date();
  return new Date(n.getFullYear(), n.getMonth(), n.getDate());
}
function fmtDate(d) {
  var s = extractDate(d);
  if (!s) return '—';
  var p = s.split('-');
  return p[2] + '/' + p[1] + '/' + p[0];
}
function fmt(n) { return (parseFloat(n) || 0).toLocaleString('fr-FR'); }
function esc(s) {
  return String(s == null ? '' : s)
    .replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}
function badgeStatut(s) {
  var e = STATUTS[s] || { cls: 'sb-muted', lbl: s || '—' };
  return '<span class="sbadge ' + e.cls + '">' + e.lbl + '</span>';
}
function badgeMode(m) {
  if (!m) return '<span style="color:var(--text4,#aab5c6);font-size:.78rem;">—</span>';
  var e = MODES[m] || { ico: 'fa-ellipsis', lbl: m };
  return '<span style="font-size:.82rem;"><i class="fas ' + e.ico + ' mode-ico"></i>' + e.lbl + '</span>';
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

/* ════════════════════════════════════════
   CHARGEMENT
════════════════════════════════════════ */
function load() {
  return Promise.all([
    fetch(API + '/paiements').then(function(r){ return r.json(); }),
    fetch(API + '/paiements/urgents').then(function(r){ return r.json(); }),
    fetch(API + '/fournisseurs').then(function(r){ return r.json(); }),
    fetch(API + '/commandes').then(function(r){ return r.json(); })
  ]).then(function(res) {
    _all          = res[0].data || res[0] || [];
    var urgents   = res[1].data || res[1] || [];
    _fournisseurs = (res[2].data || res[2] || []).filter(function(f){ return f.statut === 'actif'; });
    _commandes    = res[3].data || res[3] || [];

    buildSelects();
    computeKPIs();
    renderUrgents(urgents);
    filterTable();
  }).catch(function() {
    document.getElementById('tbl-body').innerHTML =
      '<tr><td colspan="8"><div class="empty-state">' +
      '<div class="empty-ico"><i class="fas fa-wifi-slash"></i></div>' +
      '<div class="empty-title">Erreur de connexion</div>' +
      '<div class="empty-text">Impossible de joindre l\'API (port 3000).</div>' +
      '</div></td></tr>';
    document.getElementById('tb-count').textContent = '—';
  });
}

function buildSelects() {
  /* Fournisseurs */
  var hf = '<option value="">— Sélectionner —</option>';
  _fournisseurs.forEach(function(f) {
    hf += '<option value="' + f.id_fournisseur + '">' + esc(f.nom) + '</option>';
  });
  document.getElementById('f-fournisseur').innerHTML = hf;

  /* Commandes — seulement celles reçues partiellement ou totalement (facturables) */
  var FACTURABLES = ['confirmee','expediee','recue_partielle','recue_totale'];
  var hc = '<option value="">— Optionnel —</option>';
  _commandes.filter(function(c){ return FACTURABLES.indexOf(c.statut) >= 0; })
    .forEach(function(c) {
      hc += '<option value="' + c.id_commande + '">'
          + esc(c.numero_commande) + ' — ' + esc(c.fournisseur_nom || '') + '</option>';
    });
  document.getElementById('f-commande').innerHTML = hc;
}

/* ════════════════════════════════════════
   KPIs — calculés localement
   Total impayé = sum des en_attente + en_retard
════════════════════════════════════════ */
function computeKPIs() {
  var attente = 0, retard = 0, paye = 0, totalImpaye = 0;
  _all.forEach(function(p) {
    if (p.statut === 'en_attente') { attente++; totalImpaye += parseFloat(p.montant || 0); }
    if (p.statut === 'en_retard')  { retard++;  totalImpaye += parseFloat(p.montant || 0); }
    if (p.statut === 'paye')       paye++;
  });
  document.getElementById('ks-attente').textContent = attente;
  document.getElementById('ks-retard').textContent  = retard;
  document.getElementById('ks-paye').textContent    = paye;
  document.getElementById('ks-total').textContent   = fmt(Math.round(totalImpaye));
}

/* ════════════════════════════════════════
   BANDEAU URGENTS
   Vue DB v_paiements_urgents retourne :
     id_paiement, numero_facture, date_facture, montant,
     date_echeance, statut, jours_retard, fournisseur, numero_commande
   Note : le champ est "fournisseur" (alias dans la vue), pas fournisseur_nom
════════════════════════════════════════ */
function renderUrgents(urgents) {
  if (!urgents.length) {
    document.getElementById('urgents-banner').classList.remove('show');
    return;
  }
  document.getElementById('urgents-banner').classList.add('show');
  var html = '';
  urgents.forEach(function(u) {
    var jours    = parseInt(u.jours_retard) || 0;
    var enRetard = jours > 0;
    var pillCls  = enRetard ? 'rp-urgent' : 'rp-warn';
    var pillTxt  = enRetard
      ? jours + ' jour' + (jours > 1 ? 's' : '') + ' de retard'
      : 'dans ' + Math.abs(jours) + ' jour' + (Math.abs(jours) > 1 ? 's' : '');

    html += '<div class="urgents-item">'
      + '<div class="urgents-left">'
      +   '<div class="urgents-facture"><i class="fas fa-file-invoice" style="margin-right:5px;opacity:.6;"></i>'
      +     esc(u.numero_facture) + '</div>'
      +   '<div class="urgents-fourn">'
      +     esc(u.fournisseur || '—')  /* ← alias DB dans v_paiements_urgents */
      +     (u.numero_commande ? ' · <span style="font-family:\'DM Mono\',monospace;">' + esc(u.numero_commande) + '</span>' : '')
      +   '</div>'
      + '</div>'
      + '<div class="urgents-right">'
      +   '<span class="retard-pill ' + pillCls + '">'
      +     '<i class="fas fa-' + (enRetard ? 'circle-exclamation' : 'clock') + '" style="font-size:.6rem;"></i> '
      +     pillTxt
      +   '</span>'
      +   '<span class="urg-montant">' + fmt(u.montant) + ' FCFA</span>'
      +   '<button class="btn-gold" style="padding:5px 12px;font-size:.78rem;" onclick="openPayer(' + u.id_paiement + ')">'
      +     '<i class="fas fa-circle-check"></i> Payer'
      +   '</button>'
      + '</div>'
      + '</div>';
  });
  document.getElementById('urgents-list').innerHTML = html;
}

/* ════════════════════════════════════════
   FILTRE & TRI
════════════════════════════════════════ */
function filterByStatut(statut, kpiId) {
  /* Toggle : si déjà actif → reset */
  if (_kpiFilter === statut && statut !== '') {
    _kpiFilter = '';
    document.querySelectorAll('.ks').forEach(function(k){ k.classList.remove('active-filter'); });
    document.getElementById('filter-statut').value = '';
  } else {
    _kpiFilter = statut;
    document.querySelectorAll('.ks').forEach(function(k){ k.classList.remove('active-filter'); });
    if (kpiId) document.getElementById(kpiId).classList.add('active-filter');
    document.getElementById('filter-statut').value = statut;
  }
  document.getElementById('tb-reset').classList.toggle('show', !!_kpiFilter);
  filterTable();
}

function resetFilters() {
  _kpiFilter = '';
  document.getElementById('search-input').value  = '';
  document.getElementById('filter-statut').value = '';
  document.getElementById('filter-mode').value   = '';
  document.querySelectorAll('.ks').forEach(function(k){ k.classList.remove('active-filter'); });
  document.getElementById('tb-reset').classList.remove('show');
  filterTable();
}

function filterTable() {
  var q    = document.getElementById('search-input').value.trim().toLowerCase();
  var st   = document.getElementById('filter-statut').value;
  var mode = document.getElementById('filter-mode').value;

  _shown = _all.filter(function(p) {
    var mq = !q
      || (p.numero_facture  || '').toLowerCase().indexOf(q) >= 0
      || (p.fournisseur_nom || '').toLowerCase().indexOf(q) >= 0
      || (p.numero_commande || '').toLowerCase().indexOf(q) >= 0
      || (p.notes           || '').toLowerCase().indexOf(q) >= 0;
    var ms = !st   || p.statut       === st;
    var mm = !mode || p.mode_paiement === mode;
    return mq && ms && mm;
  });

  /* Tri */
  var NUM_COLS = ['montant'];
  _shown.sort(function(a, b) {
    var va = a[_sortCol] != null ? a[_sortCol] : (_sortAsc ? '\uFFFF' : '');
    var vb = b[_sortCol] != null ? b[_sortCol] : (_sortAsc ? '\uFFFF' : '');
    if (NUM_COLS.indexOf(_sortCol) >= 0) { va = parseFloat(va)||0; vb = parseFloat(vb)||0; }
    else if (typeof va === 'string') { va = va.toLowerCase(); vb = String(vb).toLowerCase(); }
    return _sortAsc ? (va > vb ? 1 : va < vb ? -1 : 0)
                    : (va < vb ? 1 : va > vb ? -1 : 0);
  });

  /* Afficher ou masquer bouton reset */
  var hasFilter = q || st || mode;
  document.getElementById('tb-reset').classList.toggle('show', !!hasFilter);

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
  var cols = ['numero_facture','fournisseur_nom','numero_commande','montant','date_echeance','statut','mode_paiement'];
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
    total + ' facture' + (total !== 1 ? 's' : '');

  var slice = _shown.slice((_page - 1) * PER, _page * PER);
  var now   = today();

  if (!slice.length) {
    document.getElementById('tbl-body').innerHTML =
      '<tr><td colspan="8"><div class="empty-state">' +
      '<div class="empty-ico"><i class="fas fa-file-invoice-dollar"></i></div>' +
      '<div class="empty-title">Aucune facture trouvée</div>' +
      '<div class="empty-text">' + (_all.length === 0
        ? 'Enregistrez votre première facture fournisseur.'
        : 'Aucun résultat pour cette recherche.') + '</div>' +
      '</div></td></tr>';
    document.getElementById('pager').style.display = 'none';
    return;
  }

  var html = '';
  slice.forEach(function(p) {
    var ech       = parseDateMidnight(p.date_echeance);
    var enRetard  = ech && ech < now && (p.statut === 'en_attente' || p.statut === 'en_retard');
    var rowCls    = enRetard ? ' class="row-retard"' : '';
    var dateCls   = enRetard ? 'c-date-late' : '';

    /* Jours de retard */
    var retardTxt = '';
    if (enRetard && ech) {
      var jours = Math.floor((now - ech) / 86400000);
      retardTxt = ' <span style="font-size:.68rem;color:#c03030;font-weight:700;">('
        + jours + 'j)</span>';
    }

    html += '<tr' + rowCls + ' onclick="openDetail(' + p.id_paiement + ')">'
      + '<td><span class="c-num">' + esc(p.numero_facture) + '</span></td>'
      + '<td>' + esc(p.fournisseur_nom || '—') + '</td>'
      + '<td>'
      +   (p.numero_commande
          ? '<span style="font-family:\'DM Mono\',monospace;font-size:.82rem;">' + esc(p.numero_commande) + '</span>'
          : '<span style="color:var(--text4,#aab5c6);font-size:.78rem;">—</span>')
      + '</td>'
      + '<td><span class="c-price">' + fmt(p.montant) + '</span></td>'
      + '<td class="' + dateCls + '">' + fmtDate(p.date_echeance) + retardTxt + '</td>'
      + '<td>' + badgeStatut(p.statut) + '</td>'
      + '<td>' + badgeMode(p.mode_paiement) + '</td>'
      + '<td><div class="c-actions" onclick="event.stopPropagation()">'
      +   '<div class="act-btn view" title="Voir détail" onclick="openDetail(' + p.id_paiement + ')">'
      +     '<i class="fas fa-eye"></i></div>'
      +   (p.statut !== 'paye' && p.statut !== 'annule'
          ? '<div class="act-btn pay" title="Enregistrer paiement" onclick="openPayer(' + p.id_paiement + ')">'
          +   '<i class="fas fa-circle-check"></i></div>'
          : '')
      + '</div></td>'
      + '</tr>';
  });
  document.getElementById('tbl-body').innerHTML = html;

  /* Pagination */
  var pg    = document.getElementById('pager');
  var pbtns = document.getElementById('pager-btns');
  pg.style.display = 'flex';
  document.getElementById('pager-info').textContent =
    ((_page-1)*PER+1) + '–' + Math.min(_page*PER, total) + ' sur ' + total;

  var ph = '<button class="pg-btn" onclick="goPage(' + (_page-1) + ')" '
    + (_page===1?'disabled':'') + '><i class="fas fa-chevron-left"></i></button>';
  for (var p = 1; p <= pages; p++) {
    if (pages > 7 && p > 2 && p < pages-1 && Math.abs(p-_page) > 1) {
      if (p===3||p===pages-2) ph += '<span style="padding:0 4px;color:#aab5c6;">…</span>';
      continue;
    }
    ph += '<button class="pg-btn ' + (p===_page?'on':'') + '" onclick="goPage(' + p + ')">' + p + '</button>';
  }
  ph += '<button class="pg-btn" onclick="goPage(' + (_page+1) + ')" '
    + (_page===pages?'disabled':'') + '><i class="fas fa-chevron-right"></i></button>';
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
   CRÉER FACTURE
   POST /api/paiements
   Body : { id_fournisseur, id_commande?, id_utilisateur,
            numero_facture, date_facture, montant,
            date_echeance, mode_paiement?, notes? }
════════════════════════════════════════ */
function openCreate() {
  clearCreateForm();
  document.getElementById('f-date-facture').value = extractDate(new Date().toISOString());
  openModal('modal-create');
}

function saveFacture() {
  var valid = true;
  var fields = [
    { id: 'f-fournisseur', err: 'e-fournisseur', check: function(v){ return !!v; } },
    { id: 'f-numero',      err: 'e-numero',      check: function(v){ return v.trim().length > 0; } },
    { id: 'f-date-facture',err: 'e-date-facture', check: function(v){ return !!v; } },
    { id: 'f-montant',     err: 'e-montant',      check: function(v){ return parseFloat(v) > 0; } },
    { id: 'f-echeance',    err: 'e-echeance',     check: function(v){ return !!v; } }
  ];
  fields.forEach(function(f) {
    var el  = document.getElementById(f.id);
    var err = document.getElementById(f.err);
    if (!f.check(el.value)) {
      el.classList.add('error');
      err.classList.add('show');
      valid = false;
    } else {
      el.classList.remove('error');
      err.classList.remove('show');
    }
  });
  if (!valid) return;

  var btn = document.getElementById('btn-save');
  btn.disabled = true;
  btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enregistrement…';

  var cmdVal  = document.getElementById('f-commande').value;
  var modeVal = document.getElementById('f-mode').value;
  var notesVal= document.getElementById('f-notes').value.trim();

  var body = {
    id_fournisseur: parseInt(document.getElementById('f-fournisseur').value),
    id_utilisateur: UID,
    numero_facture: document.getElementById('f-numero').value.trim(),
    date_facture:   document.getElementById('f-date-facture').value,
    montant:        parseFloat(document.getElementById('f-montant').value),
    date_echeance:  document.getElementById('f-echeance').value
  };
  /* Champs optionnels — ne pas envoyer null si vide, l'API gère */
  if (cmdVal)   body.id_commande   = parseInt(cmdVal);
  if (modeVal)  body.mode_paiement = modeVal;
  if (notesVal) body.notes         = notesVal;

  fetch(API + '/paiements', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(body)
  }).then(function(r) {
    return r.json().then(function(d) { return { ok: r.ok, d: d }; });
  }).then(function(res) {
    if (!res.ok) throw new Error(res.d.message || 'Erreur serveur');
    closeModal('modal-create');
    showSuccess('Facture ' + document.getElementById('f-numero').value + ' enregistrée.');
    return load();
  }).catch(function(e) {
    showError('Erreur : ' + (e.message || ''));
  }).finally(function() {
    btn.disabled = false;
    btn.innerHTML = '<i class="fas fa-floppy-disk"></i> Enregistrer la facture';
  });
}

/* ════════════════════════════════════════
   DÉTAIL PAIEMENT
   GET /api/paiements/:id
════════════════════════════════════════ */
function openDetail(id) {
  document.getElementById('detail-body').innerHTML =
    '<div style="text-align:center;padding:32px;color:#aab5c6;"><i class="fas fa-spinner fa-spin"></i> Chargement…</div>';
  document.getElementById('detail-titre').textContent = 'Facture';
  document.getElementById('detail-footer').innerHTML =
    '<button class="btn-secondary" onclick="closeModal(\'modal-detail\')">Fermer</button>';
  openModal('modal-detail');

  fetch(API + '/paiements/' + id).then(function(r){ return r.json(); }).then(function(resp) {
    var p = resp.data || resp;
    renderDetail(p);
  }).catch(function() {
    document.getElementById('detail-body').innerHTML =
      '<div class="empty-state"><div class="empty-title">Erreur de chargement</div></div>';
  });
}

function renderDetail(p) {
  document.getElementById('detail-titre').textContent = p.numero_facture || 'Facture';

  var now      = today();
  var ech      = parseDateMidnight(p.date_echeance);
  var enRetard = ech && ech < now && (p.statut === 'en_attente' || p.statut === 'en_retard');
  var jours    = ech ? Math.floor((now - ech) / 86400000) : 0;

  document.getElementById('detail-body').innerHTML =
    '<div class="detail-meta">'
    + '<div class="dmeta"><div class="dmeta-l">Fournisseur</div><div class="dmeta-v">' + esc(p.fournisseur_nom || '—') + '</div></div>'
    + '<div class="dmeta"><div class="dmeta-l">Montant</div><div class="dmeta-v price">' + fmt(p.montant) + ' FCFA</div></div>'
    + '<div class="dmeta"><div class="dmeta-l">Statut</div><div class="dmeta-v">' + badgeStatut(p.statut) + '</div></div>'
    + '<div class="dmeta"><div class="dmeta-l">Date facture</div><div class="dmeta-v">' + fmtDate(p.date_facture) + '</div></div>'
    + '<div class="dmeta"><div class="dmeta-l">Échéance</div><div class="dmeta-v ' + (enRetard ? 'late' : '') + '">'
    +   fmtDate(p.date_echeance)
    +   (enRetard ? ' <span style="font-size:.72rem;color:#c03030;">(' + jours + 'j retard)</span>' : '')
    + '</div></div>'
    + '<div class="dmeta"><div class="dmeta-l">Commande</div><div class="dmeta-v">'
    +   (p.numero_commande
        ? '<span style="font-family:\'DM Mono\',monospace;">' + esc(p.numero_commande) + '</span>'
        : '—')
    + '</div></div>'
    + (p.mode_paiement
        ? '<div class="dmeta"><div class="dmeta-l">Mode</div><div class="dmeta-v">' + badgeMode(p.mode_paiement) + '</div></div>'
        : '')
    + (p.date_paiement
        ? '<div class="dmeta"><div class="dmeta-l">Payé le</div><div class="dmeta-v ok">' + fmtDate(p.date_paiement) + '</div></div>'
        : '')
    + (p.reference_paiement
        ? '<div class="dmeta"><div class="dmeta-l">Référence</div><div class="dmeta-v" style="font-family:\'DM Mono\',monospace;font-size:.82rem;">' + esc(p.reference_paiement) + '</div></div>'
        : '')
    + '</div>'
    + (p.notes
        ? '<div style="background:#fffbf2;border:1px solid #f5c96a;border-radius:9px;padding:11px 14px;font-size:.86rem;color:#5A6E88;">'
        +   '<i class="fas fa-note-sticky" style="margin-right:7px;"></i>' + esc(p.notes)
        + '</div>'
        : '');

  /* Bouton Payer dans le footer si pas encore payé */
  if (p.statut !== 'paye' && p.statut !== 'annule') {
    document.getElementById('detail-footer').innerHTML =
      '<button class="btn-secondary" onclick="closeModal(\'modal-detail\')">Fermer</button>'
      + '<button class="btn-gold" style="background:#1a8a4a;" onclick="closeModal(\'modal-detail\');openPayer(' + p.id_paiement + ')">'
      + '<i class="fas fa-circle-check"></i> Enregistrer le paiement</button>';
  }
}

/* ════════════════════════════════════════
   ENREGISTRER PAIEMENT
   PUT /api/paiements/:id/payer
   Body : { date_paiement, reference_paiement?, mode_paiement }
════════════════════════════════════════ */
function openPayer(id) {
  /* Trouver le paiement dans _all pour pré-remplir le résumé */
  var p = null;
  for (var i = 0; i < _all.length; i++) {
    if (String(_all[i].id_paiement) === String(id)) { p = _all[i]; break; }
  }

  document.getElementById('p-id').value = id;
  document.getElementById('p-date').value = extractDate(new Date().toISOString());
  document.getElementById('p-mode').value = '';
  document.getElementById('p-ref').value  = '';
  document.querySelectorAll('#modal-payer .ferr').forEach(function(e){ e.classList.remove('show'); });
  document.querySelectorAll('#modal-payer .finput.error, #modal-payer .fselect.error')
    .forEach(function(e){ e.classList.remove('error'); });

  if (p) {
    document.getElementById('p-num-display').textContent    = p.numero_facture || '—';
    document.getElementById('p-fourn-display').textContent  = p.fournisseur_nom || '—';
    document.getElementById('p-montant-display').textContent = fmt(p.montant) + ' FCFA';
    /* Pré-remplir le mode si déjà défini */
    if (p.mode_paiement) document.getElementById('p-mode').value = p.mode_paiement;
  }

  openModal('modal-payer');
}

function confirmerPaiement() {
  var valid = true;

  var dateEl = document.getElementById('p-date');
  var modeEl = document.getElementById('p-mode');
  if (!dateEl.value) {
    dateEl.classList.add('error');
    document.getElementById('e-p-date').classList.add('show');
    valid = false;
  } else {
    dateEl.classList.remove('error');
    document.getElementById('e-p-date').classList.remove('show');
  }
  if (!modeEl.value) {
    modeEl.classList.add('error');
    document.getElementById('e-p-mode').classList.add('show');
    valid = false;
  } else {
    modeEl.classList.remove('error');
    document.getElementById('e-p-mode').classList.remove('show');
  }
  if (!valid) return;

  var id  = document.getElementById('p-id').value;
  var btn = document.getElementById('btn-payer');
  btn.disabled = true;
  btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Traitement…';

  var body = {
    date_paiement: dateEl.value,
    mode_paiement: modeEl.value
  };
  var ref = document.getElementById('p-ref').value.trim();
  if (ref) body.reference_paiement = ref;

  fetch(API + '/paiements/' + id + '/payer', {
    method: 'PUT',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(body)
  }).then(function(r) {
    return r.json().then(function(d) { return { ok: r.ok, d: d }; });
  }).then(function(res) {
    if (!res.ok) throw new Error(res.d.message || 'Erreur serveur');
    closeModal('modal-payer');
    showSuccess('Paiement enregistré avec succès.');
    return load();
  }).catch(function(e) {
    showError('Erreur : ' + (e.message || ''));
  }).finally(function() {
    btn.disabled = false;
    btn.innerHTML = '<i class="fas fa-circle-check"></i> Confirmer le paiement';
  });
}

/* ════════════════════════════════════════
   UTILITAIRES MODALS & FORMULAIRES
════════════════════════════════════════ */
function openModal(id)  { document.getElementById(id).classList.add('open');    document.body.style.overflow = 'hidden'; }
function closeModal(id) { document.getElementById(id).classList.remove('open'); document.body.style.overflow = ''; }

function clearCreateForm() {
  ['f-fournisseur','f-commande','f-numero','f-date-facture','f-montant','f-echeance','f-mode','f-notes']
    .forEach(function(id){ document.getElementById(id).value = ''; });
  document.querySelectorAll('#modal-create .finput.error, #modal-create .fselect.error')
    .forEach(function(e){ e.classList.remove('error'); });
  document.querySelectorAll('#modal-create .ferr.show')
    .forEach(function(e){ e.classList.remove('show'); });
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