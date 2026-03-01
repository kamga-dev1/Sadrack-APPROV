<?php
require_once 'includes/config.php';
if(empty($_SESSION['user'])){header('Location: login.php');exit;}
$_uid = $_SESSION['user']['id'] ?? $_SESSION['user']['id_utilisateur'] ?? 0;
?>
<?php require_once 'includes/header.php'; ?>
<?php require_once 'includes/sidebar.php'; ?>
<script>
  if(document.getElementById('nav-page-title'))
    document.getElementById('nav-page-title').textContent='Commandes';
</script>

<style>
.ph{display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:14px;margin-bottom:22px;}
.ph-title{font-size:1.3rem;font-weight:700;color:var(--text);letter-spacing:-.3px;}
.ph-sub{font-size:.82rem;color:var(--text3);margin-top:4px;}
.ph-right{display:flex;align-items:center;gap:8px;}
.btn-gold{display:inline-flex;align-items:center;gap:8px;padding:9px 18px;border-radius:9px;background:var(--gold,#E2A84B);color:#fff;font-family:inherit;font-size:.86rem;font-weight:700;cursor:pointer;border:none;transition:all .15s;}
.btn-gold:hover{filter:brightness(1.08);}
.btn-gold:disabled{opacity:.5;cursor:default;}
.btn-secondary{display:inline-flex;align-items:center;gap:8px;padding:9px 16px;border-radius:9px;background:transparent;color:var(--text2);font-family:inherit;font-size:.86rem;font-weight:600;cursor:pointer;border:1px solid var(--border);transition:all .15s;}
.btn-secondary:hover{background:var(--surface2,#f4f6fb);}

.kstrip{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:20px;}
@media(max-width:900px){.kstrip{grid-template-columns:repeat(2,1fr);}}
@media(max-width:480px){.kstrip{grid-template-columns:1fr;}}
.ks{background:var(--surface,#fff);border:1px solid var(--border,#e4e8f0);border-radius:11px;padding:14px 16px;display:flex;align-items:center;gap:12px;box-shadow:0 1px 4px rgba(5,8,15,.06);}
.ks-ico{width:38px;height:38px;border-radius:9px;display:flex;align-items:center;justify-content:center;font-size:.9rem;flex-shrink:0;}
.ks-n{font-size:1.55rem;font-weight:800;color:var(--text,#0D1526);letter-spacing:-1px;line-height:1;}
.ks-l{font-size:.73rem;color:var(--text3,#8FA4BF);margin-top:3px;}

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

.tcard{background:var(--surface,#fff);border:1px solid var(--border,#e4e8f0);border-radius:12px;box-shadow:0 1px 4px rgba(5,8,15,.06);overflow:hidden;}
.etbl{width:100%;border-collapse:collapse;table-layout:fixed;}
.etbl thead th{font-size:.65rem;font-weight:800;text-transform:uppercase;letter-spacing:1.2px;color:var(--text4,#aab5c6);padding:10px 14px;border-bottom:2px solid var(--border,#e4e8f0);background:var(--surface2,#f4f6fb);white-space:nowrap;text-align:left;cursor:pointer;user-select:none;}
.etbl thead th:hover{color:var(--text2,#3a4a5c);}
.etbl thead th.sorted{color:#b87220;}
.sort-ico{margin-left:4px;opacity:.35;font-size:.58rem;}
.sorted .sort-ico{opacity:1;color:#b87220;}
.etbl thead th:nth-child(1){width:13%;}
.etbl thead th:nth-child(2){width:18%;}
.etbl thead th:nth-child(3){width:10%;}
.etbl thead th:nth-child(4){width:11%;}
.etbl thead th:nth-child(5){width:13%;text-align:right;}
.etbl thead th:nth-child(6){width:13%;}
.etbl thead th:nth-child(7){width:12%;}
.etbl thead th:nth-child(8){width:10%;text-align:right;}
.etbl tbody td{padding:11px 14px;border-bottom:1px solid var(--border,#e4e8f0);font-size:.86rem;color:var(--text2,#3a4a5c);vertical-align:middle;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
.etbl tbody td:nth-child(5){text-align:right;}
.etbl tbody td:nth-child(8){text-align:right;}
.etbl tbody tr:last-child td{border-bottom:none;}
.etbl tbody tr:hover{background:var(--surface2,#f4f6fb);cursor:pointer;}
.c-num{font-family:'DM Mono',monospace;font-weight:700;color:var(--text,#0D1526);font-size:.84rem;}
.c-price{font-family:'DM Mono',monospace;font-weight:700;color:#b87220;}
.c-late{color:#e03e3e;font-weight:600;}
.c-actions{display:flex;align-items:center;gap:5px;justify-content:flex-end;}
.act-btn{width:30px;height:30px;border-radius:7px;display:flex;align-items:center;justify-content:center;font-size:.72rem;cursor:pointer;border:1px solid var(--border,#e4e8f0);background:var(--canvas,#f8f9fc);color:var(--text3,#8FA4BF);transition:all .15s;}
.act-btn.view:hover{background:#ebf5ff;border-color:#90c6f8;color:#1a7fd4;}
.act-btn.edit:hover{background:#fff8e6;border-color:#f0c160;color:#b07000;}
.act-btn.pdf:hover{background:#eafaf1;border-color:#79d6a0;color:#1a8a4a;}

.pager{display:flex;align-items:center;justify-content:space-between;padding:12px 18px;border-top:1px solid var(--border,#e4e8f0);flex-wrap:wrap;gap:10px;}
.pager-info{font-size:.78rem;color:var(--text4,#aab5c6);}
.pager-btns{display:flex;align-items:center;gap:4px;}
.pg-btn{min-width:32px;height:32px;border-radius:7px;display:flex;align-items:center;justify-content:center;font-size:.78rem;font-weight:600;cursor:pointer;border:1px solid var(--border,#e4e8f0);background:var(--canvas,#f8f9fc);color:var(--text2,#3a4a5c);transition:all .15s;padding:0 6px;}
.pg-btn:hover{background:var(--surface,#fff);}
.pg-btn.on{background:#0D1526;color:#fff;border-color:#0D1526;}
.pg-btn:disabled{opacity:.38;cursor:default;}

.modal-bg{position:fixed;inset:0;z-index:900;background:rgba(5,8,15,.55);backdrop-filter:blur(4px);display:none;align-items:center;justify-content:center;padding:20px;}
.modal-bg.open{display:flex;animation:bgIn .2s ease;}
@keyframes bgIn{from{opacity:0;}to{opacity:1;}}
.modal{background:var(--surface,#fff);border:1px solid var(--border,#e4e8f0);border-radius:16px;width:100%;max-width:640px;box-shadow:0 8px 40px rgba(5,8,15,.18);animation:mIn .22s ease;max-height:90vh;overflow-y:auto;}
.modal-lg{max-width:820px;}
@keyframes mIn{from{opacity:0;transform:translateY(-12px) scale(.98);}to{opacity:1;transform:none;}}
.modal-head{display:flex;align-items:center;justify-content:space-between;padding:18px 22px;border-bottom:1px solid var(--border,#e4e8f0);position:sticky;top:0;background:var(--surface,#fff);z-index:1;}
.modal-title{display:flex;align-items:center;gap:10px;font-size:.98rem;font-weight:700;color:var(--text,#0D1526);}
.modal-ico{width:32px;height:32px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:.82rem;}
.modal-close{width:30px;height:30px;border-radius:7px;display:flex;align-items:center;justify-content:center;font-size:.8rem;cursor:pointer;background:transparent;border:1px solid transparent;color:var(--text3,#8FA4BF);transition:all .15s;}
.modal-close:hover{background:#fff0f0;border-color:#f8b8b8;color:#e03e3e;}
.modal-body{padding:22px;}
.modal-footer{display:flex;align-items:center;justify-content:flex-end;gap:8px;padding:16px 22px;border-top:1px solid var(--border,#e4e8f0);background:var(--surface2,#f4f6fb);}

.frow{display:grid;gap:14px;margin-bottom:14px;}
.frow-2{grid-template-columns:1fr 1fr;}
@media(max-width:560px){.frow-2{grid-template-columns:1fr;}}
.fgroup{display:flex;flex-direction:column;gap:5px;}
.flabel{font-size:.75rem;font-weight:700;color:var(--text2,#3a4a5c);}
.flabel span{color:#e03e3e;margin-left:2px;}
.finput,.fselect,.ftextarea{width:100%;padding:9px 12px;border-radius:8px;border:1.5px solid var(--border,#e4e8f0);background:var(--canvas,#f8f9fc);color:var(--text,#0D1526);font-family:inherit;font-size:.88rem;transition:border-color .18s;outline:none;}
.finput:focus,.fselect:focus,.ftextarea:focus{border-color:var(--gold,#E2A84B);background:var(--surface,#fff);box-shadow:0 0 0 3px rgba(226,168,75,.1);}
.finput.error,.fselect.error{border-color:#e03e3e;}
.ftextarea{resize:vertical;min-height:60px;}
.ferr{font-size:.72rem;color:#e03e3e;margin-top:3px;display:none;}
.ferr.show{display:block;}
.fsep{border:none;border-top:1px solid var(--border,#e4e8f0);margin:4px 0 16px;}
.fsec-title{font-size:.68rem;font-weight:800;text-transform:uppercase;letter-spacing:1.5px;color:var(--text4,#aab5c6);margin-bottom:12px;}

.lignes-wrap{background:var(--canvas,#f8f9fc);border:1px solid var(--border,#e4e8f0);border-radius:10px;overflow:hidden;margin-bottom:14px;}
.lignes-head{display:grid;grid-template-columns:1fr 90px 130px 110px 36px;gap:8px;padding:8px 12px;background:var(--surface2,#f4f6fb);border-bottom:1px solid var(--border,#e4e8f0);}
.lignes-head span{font-size:.65rem;font-weight:800;text-transform:uppercase;letter-spacing:1px;color:var(--text4,#aab5c6);}
.ligne-row{display:grid;grid-template-columns:1fr 90px 130px 110px 36px;gap:8px;padding:8px 12px;border-bottom:1px solid var(--border,#e4e8f0);align-items:center;}
.ligne-row:last-child{border-bottom:none;}
.ligne-row .finput,.ligne-row .fselect{padding:6px 10px;font-size:.84rem;}
.ligne-total{font-family:'DM Mono',monospace;font-size:.84rem;font-weight:700;color:#b87220;text-align:right;padding-right:4px;}
.btn-del-ligne{width:28px;height:28px;border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:.7rem;cursor:pointer;border:1px solid var(--border,#e4e8f0);background:transparent;color:var(--text4,#aab5c6);transition:all .15s;flex-shrink:0;}
.btn-del-ligne:hover{background:#fff0f0;border-color:#f8b8b8;color:#e03e3e;}
.btn-add-ligne{display:flex;align-items:center;gap:7px;padding:8px 14px;border-radius:8px;border:1.5px dashed var(--border,#e4e8f0);background:transparent;color:var(--text3,#8FA4BF);font-family:inherit;font-size:.82rem;cursor:pointer;transition:all .16s;width:100%;justify-content:center;margin-bottom:14px;}
.btn-add-ligne:hover{border-color:var(--gold,#E2A84B);color:#b87220;background:rgba(226,168,75,.04);}

.totaux{background:var(--surface2,#f4f6fb);border:1px solid var(--border,#e4e8f0);border-radius:10px;padding:14px 16px;margin-bottom:14px;}
.totaux-row{display:flex;justify-content:space-between;align-items:center;padding:3px 0;font-size:.86rem;}
.totaux-row.total{border-top:1px solid var(--border,#e4e8f0);margin-top:8px;padding-top:10px;font-size:.98rem;font-weight:800;}
.totaux-row .lbl{color:var(--text3,#8FA4BF);}
.totaux-row .val{font-family:'DM Mono',monospace;font-weight:700;color:var(--text,#0D1526);}
.totaux-row.total .val{color:#b87220;font-size:1.1rem;}

.detail-meta{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:18px;}
@media(max-width:560px){.detail-meta{grid-template-columns:1fr 1fr;}}
.dmeta{background:var(--canvas,#f8f9fc);border:1px solid var(--border,#e4e8f0);border-radius:9px;padding:10px 13px;}
.dmeta-l{font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:var(--text4,#aab5c6);margin-bottom:3px;}
.dmeta-v{font-size:.9rem;font-weight:700;color:var(--text,#0D1526);}
.detail-lignes{background:var(--canvas,#f8f9fc);border:1px solid var(--border,#e4e8f0);border-radius:10px;overflow:hidden;margin-bottom:16px;}
.dl-head{display:grid;grid-template-columns:1fr 70px 110px 110px;gap:8px;padding:8px 14px;background:var(--surface2,#f4f6fb);border-bottom:1px solid var(--border,#e4e8f0);}
.dl-head span{font-size:.63rem;font-weight:800;text-transform:uppercase;letter-spacing:1px;color:var(--text4,#aab5c6);}
.dl-row{display:grid;grid-template-columns:1fr 70px 110px 110px;gap:8px;padding:10px 14px;border-bottom:1px solid var(--border,#e4e8f0);align-items:center;font-size:.86rem;}
.dl-row:last-child{border-bottom:none;}
.dl-ref{font-family:'DM Mono',monospace;font-size:.75rem;color:var(--text4,#aab5c6);}
.dl-qty{font-family:'DM Mono',monospace;font-weight:700;}
.dl-pu{font-family:'DM Mono',monospace;text-align:right;}
.dl-total{font-family:'DM Mono',monospace;font-weight:700;color:#b87220;text-align:right;}
.detail-total-bar{display:flex;flex-wrap:wrap;justify-content:flex-end;align-items:center;gap:8px 16px;padding:12px 14px;background:var(--surface2,#f4f6fb);border-top:2px solid var(--border,#e4e8f0);}
.detail-total-lbl{font-size:.82rem;color:var(--text3,#8FA4BF);}
.detail-total-val{font-family:'DM Mono',monospace;font-size:1.15rem;font-weight:800;color:#b87220;}
.statut-select-wrap{display:flex;align-items:center;gap:10px;flex-wrap:wrap;}
.statut-select-wrap select{padding:7px 12px;border-radius:8px;border:1.5px solid var(--border,#e4e8f0);background:var(--canvas,#f8f9fc);color:var(--text,#0D1526);font-family:inherit;font-size:.86rem;outline:none;cursor:pointer;}
.statut-select-wrap select:focus{border-color:var(--gold,#E2A84B);}

/* ─── Badges — 7 statuts DB ─── */
.sbadge{display:inline-flex;align-items:center;padding:3px 10px;border-radius:20px;font-size:.72rem;font-weight:700;white-space:nowrap;}
.sb-muted {background:#f0f2f5;color:#7a8a9a;}
.sb-info  {background:#ebf5ff;color:#1a7fd4;}
.sb-gold  {background:#fff8e6;color:#b07000;}
.sb-warn  {background:#fff4e0;color:#c87000;}
.sb-ok    {background:#eafaf1;color:#1a8a4a;}
.sb-crit  {background:#fff0f0;color:#c03030;}
.sb-purple{background:#f5f0ff;color:#6930c3;}
.sb-teal  {background:#e0f9f5;color:#0d7a6a;}

.toast-wrap{position:fixed;bottom:24px;right:24px;z-index:9999;display:flex;flex-direction:column;gap:8px;pointer-events:none;}
.toast{display:flex;align-items:center;gap:10px;padding:12px 18px;border-radius:10px;font-size:.86rem;font-weight:600;box-shadow:0 4px 20px rgba(5,8,15,.2);animation:tIn .25s ease;pointer-events:all;max-width:340px;}
@keyframes tIn{from{opacity:0;transform:translateX(20px);}to{opacity:1;transform:none;}}
.toast.ok{background:#1a8a4a;color:#fff;}
.toast.err{background:#c03030;color:#fff;}

.empty-state{padding:52px 20px;text-align:center;}
.empty-ico{width:56px;height:56px;border-radius:14px;background:var(--canvas,#f8f9fc);border:1px solid var(--border,#e4e8f0);display:flex;align-items:center;justify-content:center;font-size:1.4rem;color:var(--text4,#aab5c6);margin:0 auto 14px;}
.empty-title{font-size:.95rem;font-weight:700;color:var(--text,#0D1526);margin-bottom:6px;}
.empty-text{font-size:.82rem;color:var(--text3,#8FA4BF);}
</style>

<div class="toast-wrap" id="toast-wrap"></div>

<div class="ph">
  <div>
    <div class="ph-title"><i class="fas fa-file-invoice" style="color:var(--gold,#E2A84B);margin-right:9px;"></i>Commandes</div>
    <div class="ph-sub">Suivi des bons de commande fournisseurs</div>
  </div>
  <div class="ph-right">
    <button class="btn-gold" onclick="openCreate()"><i class="fas fa-plus"></i> Nouvelle commande</button>
  </div>
</div>

<div class="kstrip">
  <div class="ks">
    <div class="ks-ico" style="background:#ebf5ff;color:#1a7fd4;"><i class="fas fa-clock"></i></div>
    <div><div class="ks-n" id="ks-attente">—</div><div class="ks-l">En attente / Brouillon</div></div>
  </div>
  <div class="ks">
    <div class="ks-ico" style="background:#fff4e0;color:#c87000;"><i class="fas fa-truck"></i></div>
    <div><div class="ks-n" id="ks-cours">—</div><div class="ks-l">Confirmées / Expédiées</div></div>
  </div>
  <div class="ks">
    <div class="ks-ico" style="background:#fff0f0;color:#c03030;"><i class="fas fa-calendar-xmark"></i></div>
    <div><div class="ks-n" id="ks-retard">—</div><div class="ks-l">En retard</div></div>
  </div>
  <div class="ks">
    <div class="ks-ico" style="background:#eafaf1;color:#1a8a4a;"><i class="fas fa-circle-check"></i></div>
    <div><div class="ks-n" id="ks-valeur">—</div><div class="ks-l">Valeur totale TTC (FCFA)</div></div>
  </div>
</div>

<div class="toolbar">
  <div class="tsearch">
    <i class="fas fa-search"></i>
    <input type="text" id="search-input" placeholder="N° commande, fournisseur…" oninput="filterTable()">
  </div>
  <div class="tsel">
    <i class="fas fa-filter" style="font-size:.76rem;"></i>
    <!-- Valeurs exactes de l'ENUM DB -->
    <select id="filter-statut" onchange="filterTable()">
      <option value="">Tous les statuts</option>
      <option value="brouillon">Brouillon</option>
      <option value="en_attente">En attente</option>
      <option value="confirmee">Confirmée</option>
      <option value="expediee">Expédiée</option>
      <option value="recue_partielle">Reçue partielle</option>
      <option value="recue_totale">Reçue totale</option>
      <option value="annulee">Annulée</option>
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

<div class="tcard">
  <div style="overflow-x:auto;">
    <table class="etbl">
      <thead>
        <tr>
          <th onclick="sortBy('numero_commande')">N° Commande <i class="fas fa-sort sort-ico"></i></th>
          <th onclick="sortBy('fournisseur_nom')">Fournisseur <i class="fas fa-sort sort-ico"></i></th>
          <th onclick="sortBy('date_commande')">Date <i class="fas fa-sort sort-ico"></i></th>
          <th onclick="sortBy('date_prevue_livraison')">Livraison prévue <i class="fas fa-sort sort-ico"></i></th>
          <th onclick="sortBy('montant_total_ttc')" style="text-align:right;">Montant TTC <i class="fas fa-sort sort-ico"></i></th>
          <th onclick="sortBy('statut')">Statut <i class="fas fa-sort sort-ico"></i></th>
          <th onclick="sortBy('createur')">Créé par <i class="fas fa-sort sort-ico"></i></th>
          <th style="text-align:right;">Actions</th>
        </tr>
      </thead>
      <tbody id="tbl-body">
        <tr><td colspan="8"><div style="padding:24px;text-align:center;color:#aab5c6;"><i class="fas fa-spinner fa-spin"></i> Chargement…</div></td></tr>
      </tbody>
    </table>
  </div>
  <div class="pager" id="pager" style="display:none;">
    <div class="pager-info" id="pager-info"></div>
    <div class="pager-btns" id="pager-btns"></div>
  </div>
</div>

<!-- MODAL CRÉATION -->
<div class="modal-bg" id="modal-create">
  <div class="modal modal-lg">
    <div class="modal-head">
      <div class="modal-title">
        <div class="modal-ico" style="background:rgba(226,168,75,.12);color:#b87220;"><i class="fas fa-file-invoice"></i></div>
        Nouvelle commande
      </div>
      <button class="modal-close" onclick="closeModal('modal-create')"><i class="fas fa-xmark"></i></button>
    </div>
    <div class="modal-body">
      <div class="fsec-title">Informations générales</div>
      <div class="frow frow-2">
        <div class="fgroup">
          <label class="flabel">Fournisseur <span>*</span></label>
          <select class="fselect" id="f-fournisseur" onchange="onFournisseurChange()">
            <option value="">— Sélectionner —</option>
          </select>
          <div class="ferr" id="e-fournisseur">Champ requis</div>
        </div>
        <div class="fgroup">
          <label class="flabel">Date de livraison prévue</label>
          <input type="date" class="finput" id="f-livraison">
        </div>
      </div>
      <div class="frow">
        <div class="fgroup">
          <label class="flabel">Notes / Observations</label>
          <textarea class="ftextarea" id="f-notes" placeholder="Instructions de livraison, conditions particulières…"></textarea>
        </div>
      </div>
      <hr class="fsep">
      <div class="fsec-title">Lignes de commande <span style="color:#e03e3e;">*</span></div>
      <div class="lignes-wrap">
        <div class="lignes-head">
          <span>Article</span><span>Quantité</span><span>Prix unitaire (FCFA)</span>
          <span style="text-align:right;">Total HT</span><span></span>
        </div>
        <div id="lignes-body"></div>
      </div>
      <button class="btn-add-ligne" onclick="addLigne()"><i class="fas fa-plus"></i> Ajouter une ligne</button>
      <div class="ferr" id="e-lignes" style="margin-bottom:10px;">Ajoutez au moins une ligne valide</div>
      <div class="totaux">
        <div class="totaux-row"><span class="lbl">Sous-total HT</span><span class="val" id="t-ht">0 FCFA</span></div>
        <div class="totaux-row"><span class="lbl">TVA (19.25%)</span><span class="val" id="t-tva">0 FCFA</span></div>
        <div class="totaux-row total"><span class="lbl">Total TTC</span><span class="val" id="t-ttc">0 FCFA</span></div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn-secondary" onclick="closeModal('modal-create')">Annuler</button>
      <button class="btn-gold" id="btn-save" onclick="saveCommande()">
        <i class="fas fa-paper-plane"></i> Envoyer la commande
      </button>
    </div>
  </div>
</div>

<!-- MODAL DÉTAIL -->
<div class="modal-bg" id="modal-detail">
  <div class="modal modal-lg">
    <div class="modal-head">
      <div class="modal-title">
        <div class="modal-ico" style="background:rgba(226,168,75,.12);color:#b87220;"><i class="fas fa-file-invoice"></i></div>
        <span id="detail-numero">Commande</span>
      </div>
      <button class="modal-close" onclick="closeModal('modal-detail')"><i class="fas fa-xmark"></i></button>
    </div>
    <div class="modal-body" id="detail-body">
      <div style="text-align:center;padding:32px;color:#aab5c6;"><i class="fas fa-spinner fa-spin"></i></div>
    </div>
    <div class="modal-footer">
      <button class="btn-secondary" onclick="closeModal('modal-detail')">Fermer</button>
      <button class="btn-secondary" onclick="printCommande()" style="background:#eafaf1;color:#1a8a4a;border-color:#79d6a0;">
        <i class="fas fa-print"></i> Imprimer
      </button>
    </div>
  </div>
</div>

<script>
var API = 'http://localhost:3000/api';
var UID = <?= (int)$_uid ?>;
var _all=[], _shown=[], _fournisseurs=[], _articles=[];
var _page=1, PER=15, _sortCol='date_commande', _sortAsc=false;
var _lignes=[], _detailData=null;

/* ── ENUM STATUTS — strictement alignés avec la DB MySQL ──
   ENUM('brouillon','en_attente','confirmee','expediee','recue_partielle','recue_totale','annulee')
─────────────────────────────────────────────────────────── */
var STATUTS = {
  brouillon:       { cls:'sb-muted',  lbl:'Brouillon'       },
  en_attente:      { cls:'sb-info',   lbl:'En attente'      },
  confirmee:       { cls:'sb-gold',   lbl:'Confirmée'       },
  expediee:        { cls:'sb-warn',   lbl:'Expédiée'        },
  recue_partielle: { cls:'sb-purple', lbl:'Reçue partielle' },
  recue_totale:    { cls:'sb-ok',     lbl:'Reçue totale'    },
  annulee:         { cls:'sb-crit',   lbl:'Annulée'         }
};
var S_ATTENTE = ['brouillon','en_attente'];
var S_COURS   = ['confirmee','expediee','recue_partielle'];
var S_FINAL   = ['recue_totale','annulee'];

/* ── UTILS DATES ────────────────────────────────────────────
   Le problème : MySQL retourne les colonnes DATE via Node.js
   sous forme ISO-8601 avec heure UTC, ex :
     "2026-02-27T23:00:00.000Z"
   En UTC+1 (Douala), 23h UTC = minuit heure locale, donc
   new Date("2026-02-27T23:00:00.000Z").toLocaleDateString()
   affiche bien le 28/02 — MAIS si on veut comparer avec
   "aujourd'hui" pour le calcul de retard, il faut comparer
   à la même échelle.

   Solution retenue :
   • extractDate(d) → extrait toujours "YYYY-MM-DD" depuis
     l'ISO en lisant la date LOCALE du timestamp UTC
     (c'est ce que le serveur camerounais a enregistré).
   • parseDateMidnight(d) → crée un Date à minuit local
     pour les comparaisons >, <.
   • fmtDate(d) → affiche DD/MM/YYYY sans bug de timezone.
───────────────────────────────────────────────────────────── */

/**
 * Extrait la date locale (YYYY-MM-DD) depuis n'importe quel
 * format retourné par l'API : ISO "2026-02-27T23:00:00.000Z"
 * ou plain "2026-02-28".
 * On utilise le timestamp UTC pour obtenir la date locale
 * du fuseau du navigateur (= même fuseau que le serveur).
 */
function extractDate(d){
  if(!d) return null;
  var s=String(d);
  // Si c'est déjà YYYY-MM-DD sans heure → retourner tel quel
  if(/^\d{4}-\d{2}-\d{2}$/.test(s)) return s;
  // Sinon parser l'ISO complet et lire la date locale
  var dt=new Date(s);
  if(isNaN(dt.getTime())) return null;
  var y=dt.getFullYear();
  var m=String(dt.getMonth()+1).padStart(2,'0');
  var j=String(dt.getDate()).padStart(2,'0');
  return y+'-'+m+'-'+j;
}

/**
 * Retourne un Date à minuit heure locale pour comparaisons.
 * Utilisé pour : enRetard, filtre période.
 */
function parseDateMidnight(d){
  var s=extractDate(d);
  if(!s) return null;
  var p=s.split('-');
  return new Date(parseInt(p[0]),parseInt(p[1])-1,parseInt(p[2]));
}

/**
 * Aujourd'hui à minuit heure locale (pour comparaisons propres).
 */
function today(){
  var n=new Date();
  return new Date(n.getFullYear(),n.getMonth(),n.getDate());
}

function fmt(n){ return (parseFloat(n)||0).toLocaleString('fr-FR'); }

/**
 * Affiche une date en DD/MM/YYYY sans bug de timezone.
 * Fonctionne avec "2026-02-27T23:00:00.000Z" ET "2026-02-28".
 */
function fmtDate(d){
  if(!d) return '—';
  var s=extractDate(d);
  if(!s) return '—';
  var p=s.split('-');
  return p[2]+'/'+p[1]+'/'+p[0];  // DD/MM/YYYY
}
function esc(s){
  return String(s==null?'':s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}
function badgeStatut(s){
  var e=STATUTS[s]||{cls:'sb-muted',lbl:s||'—'};
  return '<span class="sbadge '+e.cls+'">'+e.lbl+'</span>';
}
function showToast(msg,type){
  type=type||'ok';
  var w=document.getElementById('toast-wrap');
  var d=document.createElement('div');
  d.className='toast '+type;
  d.innerHTML='<i class="fas fa-'+(type==='ok'?'circle-check':'circle-exclamation')+'"></i> '+esc(msg);
  w.appendChild(d);
  setTimeout(function(){
    d.style.cssText+='opacity:0;transform:translateX(20px);transition:all .3s;';
    setTimeout(function(){if(d.parentNode)d.parentNode.removeChild(d);},300);
  },3500);
}
function showSuccess(m){showToast(m,'ok');}
function showError(m){showToast(m,'err');}

/* ── CHARGEMENT ── */
function load(){
  return Promise.all([
    fetch(API+'/commandes').then(function(r){return r.json();}),
    fetch(API+'/fournisseurs').then(function(r){return r.json();}),
    fetch(API+'/articles').then(function(r){return r.json();})
  ]).then(function(res){
    _all          = res[0].data||res[0]||[];
    _fournisseurs = (res[1].data||res[1]||[]).filter(function(f){return f.statut==='actif';});
    _articles     = (res[2].data||res[2]||[]).filter(function(a){return a.statut==='actif';});
    populateFournisseurSelect();
    computeKPIs();
    filterTable();
  }).catch(function(){
    document.getElementById('tbl-body').innerHTML=
      '<tr><td colspan="8"><div class="empty-state">'+
      '<div class="empty-ico"><i class="fas fa-wifi-slash"></i></div>'+
      '<div class="empty-title">Erreur de connexion</div>'+
      '<div class="empty-text">Impossible de joindre l\'API sur le port 3000.</div>'+
      '</div></td></tr>';
    document.getElementById('tb-count').textContent='0 commandes';
  });
}

function populateFournisseurSelect(){
  var s=document.getElementById('f-fournisseur');
  var h='<option value="">— Sélectionner —</option>';
  _fournisseurs.forEach(function(f){h+='<option value="'+f.id_fournisseur+'">'+esc(f.nom)+'</option>';});
  s.innerHTML=h;
}

/* ── KPIs ── */
function computeKPIs(){
  var now=today(), attente=0, cours=0, retard=0, valeur=0;
  _all.forEach(function(c){
    if(S_ATTENTE.indexOf(c.statut)>=0) attente++;
    if(S_COURS.indexOf(c.statut)>=0)   cours++;
    // Retard = livraison dépassée + pas encore reçue totalement ni annulée
    if(c.date_prevue_livraison && parseDateMidnight(c.date_prevue_livraison)<today()
       && S_FINAL.indexOf(c.statut)<0) retard++;
    // Valeur = toutes sauf annulées — champ réel DB : montant_total_ttc
    if(c.statut!=='annulee') valeur+=parseFloat(c.montant_total_ttc||0);
  });
  document.getElementById('ks-attente').textContent=attente;
  document.getElementById('ks-cours').textContent=cours;
  document.getElementById('ks-retard').textContent=retard;
  document.getElementById('ks-valeur').textContent=fmt(Math.round(valeur));
}

/* ── FILTRE & TRI ── */
function filterTable(){
  var q=document.getElementById('search-input').value.trim().toLowerCase();
  var st=document.getElementById('filter-statut').value;
  var per=document.getElementById('filter-periode').value;
  var now=today();
  _shown=_all.filter(function(c){
    var mq=!q
      ||(c.numero_commande||'').toLowerCase().indexOf(q)>=0
      ||(c.fournisseur_nom||'').toLowerCase().indexOf(q)>=0
      ||(c.createur||'').toLowerCase().indexOf(q)>=0;
    var ms=!st||c.statut===st;
    var mp=!per||(now-parseDateMidnight(c.date_commande))<=parseInt(per)*86400000;
    return mq&&ms&&mp;
  });
  // Les montants viennent en STRING de mysql2 ("3500.00") — forcer parseFloat
  var NUM_COLS=['montant_total_ht','montant_tva','montant_total_ttc','nb_lignes'];
  _shown.sort(function(a,b){
    var raw_a=a[_sortCol]!=null?a[_sortCol]:null;
    var raw_b=b[_sortCol]!=null?b[_sortCol]:null;
    var va,vb;
    if(NUM_COLS.indexOf(_sortCol)>=0){
      va=parseFloat(raw_a)||0; vb=parseFloat(raw_b)||0;
    } else {
      va=raw_a!=null?String(raw_a).toLowerCase():(_sortAsc?'\uFFFF':'');
      vb=raw_b!=null?String(raw_b).toLowerCase():(_sortAsc?'\uFFFF':'');
    }
    return _sortAsc?(va>vb?1:va<vb?-1:0):(va<vb?1:va>vb?-1:0);
  });
  _page=1; render();
}

function sortBy(col){
  _sortAsc=(_sortCol===col)?!_sortAsc:true; _sortCol=col;
  document.querySelectorAll('.etbl thead th').forEach(function(th){
    th.classList.remove('sorted');
    var ic=th.querySelector('.sort-ico');
    if(ic) ic.className='fas fa-sort sort-ico';
  });
  var cols=['numero_commande','fournisseur_nom','date_commande','date_prevue_livraison','montant_total_ttc','statut','createur'];
  var idx=cols.indexOf(col);
  if(idx>=0){
    var ths=document.querySelectorAll('.etbl thead th');
    if(ths[idx]){
      ths[idx].classList.add('sorted');
      var ic=ths[idx].querySelector('.sort-ico');
      if(ic) ic.className='fas fa-sort-'+(_sortAsc?'up':'down')+' sort-ico';
    }
  }
  filterTable();
}

/* ── RENDU TABLE ── */
function render(){
  var total=_shown.length, pages=Math.max(1,Math.ceil(total/PER));
  if(_page>pages) _page=pages;
  document.getElementById('tb-count').textContent=total+' commande'+(total!==1?'s':'')+' trouvée'+(total!==1?'s':'');
  var slice=_shown.slice((_page-1)*PER,_page*PER), now=today();

  if(!slice.length){
    document.getElementById('tbl-body').innerHTML=
      '<tr><td colspan="8"><div class="empty-state">'+
      '<div class="empty-ico"><i class="fas fa-file-invoice"></i></div>'+
      '<div class="empty-title">Aucune commande trouvée</div>'+
      '<div class="empty-text">'+(_all.length===0?'Créez votre première commande.':'Aucun résultat pour cette recherche.')+'</div>'+
      '</div></td></tr>';
    document.getElementById('pager').style.display='none';
    return;
  }

  var html='';
  slice.forEach(function(c){
    var enRetard=c.date_prevue_livraison && parseDateMidnight(c.date_prevue_livraison)<today() && S_FINAL.indexOf(c.statut)<0;
    var livrTxt=c.date_prevue_livraison
      ? fmtDate(c.date_prevue_livraison)+(enRetard?' <i class="fas fa-exclamation-triangle" style="font-size:.7rem;color:#e03e3e;"></i>':'')
      : '—';
    html+='<tr onclick="openDetail('+c.id_commande+')">'
      +'<td><span class="c-num">'+esc(c.numero_commande)+'</span></td>'
      +'<td>'+esc(c.fournisseur_nom||'—')+'</td>'
      +'<td>'+fmtDate(c.date_commande)+'</td>'
      +'<td class="'+(enRetard?'c-late':'')+'">'+livrTxt+'</td>'
      // montant_total_ttc = champ réel de la vue v_commandes_detail
      +'<td><span class="c-price">'+fmt(c.montant_total_ttc||0)+'</span></td>'
      +'<td>'+badgeStatut(c.statut)+'</td>'
      // createur = alias u.nom AS createur dans v_commandes_detail
      +'<td style="font-size:.82rem;">'+esc(c.createur||'—')+'</td>'
      +'<td><div class="c-actions" onclick="event.stopPropagation()">'
      +'<div class="act-btn view" title="Détail" onclick="openDetail('+c.id_commande+')"><i class="fas fa-eye"></i></div>'
      +'<div class="act-btn edit" title="Statut"  onclick="openDetail('+c.id_commande+')"><i class="fas fa-pen"></i></div>'
      +'<div class="act-btn pdf"  title="Imprimer" onclick="printById('+c.id_commande+')"><i class="fas fa-print"></i></div>'
      +'</div></td></tr>';
  });
  document.getElementById('tbl-body').innerHTML=html;

  var pi=document.getElementById('pager'), pb=document.getElementById('pager-btns');
  pi.style.display='flex';
  document.getElementById('pager-info').textContent=((_page-1)*PER+1)+'–'+Math.min(_page*PER,total)+' sur '+total;
  var ph='<button class="pg-btn" onclick="goPage('+(_page-1)+')" '+(_page===1?'disabled':'')+'><i class="fas fa-chevron-left"></i></button>';
  for(var p=1;p<=pages;p++){
    if(pages>7&&p>2&&p<pages-1&&Math.abs(p-_page)>1){if(p===3||p===pages-2) ph+='<span style="padding:0 4px;color:#aab5c6;">…</span>';continue;}
    ph+='<button class="pg-btn '+(p===_page?'on':'')+'" onclick="goPage('+p+')">'+ p+'</button>';
  }
  ph+='<button class="pg-btn" onclick="goPage('+(_page+1)+')" '+(_page===pages?'disabled':'')+'><i class="fas fa-chevron-right"></i></button>';
  pb.innerHTML=ph;
}

function goPage(p){var pages=Math.ceil(_shown.length/PER);if(p<1||p>pages)return;_page=p;render();window.scrollTo({top:0,behavior:'smooth'});}

/* ── LIGNES FORMULAIRE ── */
function onFournisseurChange(){renderLignes();}
function addLigne(){_lignes.push({id_article:'',quantite:1,prix_unitaire:0});renderLignes();}
function removeLigne(i){_lignes.splice(i,1);renderLignes();}

function renderLignes(){
  var idF=document.getElementById('f-fournisseur').value;
  var sorted=_articles.slice().sort(function(a,b){
    return (String(a.id_fournisseur)===String(idF)?0:1)-(String(b.id_fournisseur)===String(idF)?0:1);
  });
  var html='';
  _lignes.forEach(function(l,i){
    var opts='<option value="">— Choisir article —</option>';
    sorted.forEach(function(a){
      opts+='<option value="'+a.id_article+'" data-prix="'+(a.prix_achat||0)+'"'
        +(String(a.id_article)===String(l.id_article)?' selected':'')+'>'+esc(a.nom)+' ('+esc(a.reference||'')+')</option>';
    });
    var tot=(parseFloat(l.quantite)||0)*(parseFloat(l.prix_unitaire)||0);
    html+='<div class="ligne-row">'
      +'<select class="fselect" onchange="onArticleChange('+i+',this)">'+opts+'</select>'
      +'<input type="number" class="finput" value="'+l.quantite+'" min="0.001" step="any" oninput="onQtyChange('+i+',this)" style="font-family:\'DM Mono\',monospace;">'
      +'<input type="number" class="finput" value="'+l.prix_unitaire+'" min="0" step="1" oninput="onPrixChange('+i+',this)" style="font-family:\'DM Mono\',monospace;" id="prix-'+i+'">'
      +'<div class="ligne-total" id="total-'+i+'">'+fmt(tot)+' FCFA</div>'
      +'<button class="btn-del-ligne" onclick="removeLigne('+i+')"><i class="fas fa-xmark"></i></button>'
      +'</div>';
  });
  document.getElementById('lignes-body').innerHTML=html;
  calcTotaux();
}

function onArticleChange(i,sel){
  _lignes[i].id_article=sel.value;
  var prix=parseFloat(sel.options[sel.selectedIndex].getAttribute('data-prix')||0);
  _lignes[i].prix_unitaire=prix;
  var el=document.getElementById('prix-'+i);
  if(el) el.value=prix;
  calcTotaux();updateTotal(i);
}
function onQtyChange(i,inp){_lignes[i].quantite=parseFloat(inp.value)||0;calcTotaux();updateTotal(i);}
function onPrixChange(i,inp){_lignes[i].prix_unitaire=parseFloat(inp.value)||0;calcTotaux();updateTotal(i);}
function updateTotal(i){var t=document.getElementById('total-'+i);if(t)t.textContent=fmt(_lignes[i].quantite*_lignes[i].prix_unitaire)+' FCFA';}
function calcTotaux(){
  var ht=0;
  _lignes.forEach(function(l){ht+=(parseFloat(l.quantite)||0)*(parseFloat(l.prix_unitaire)||0);});
  var tva=ht*0.1925,ttc=ht+tva;
  document.getElementById('t-ht').textContent=fmt(Math.round(ht))+' FCFA';
  document.getElementById('t-tva').textContent=fmt(Math.round(tva))+' FCFA';
  document.getElementById('t-ttc').textContent=fmt(Math.round(ttc))+' FCFA';
}

/* ── CRÉATION ── */
function openCreate(){_lignes=[];clearForm();renderLignes();calcTotaux();openModal('modal-create');}

function saveCommande(){
  var valid=true;
  var fEl=document.getElementById('f-fournisseur');
  if(!fEl.value){fEl.classList.add('error');document.getElementById('e-fournisseur').classList.add('show');valid=false;}
  else{fEl.classList.remove('error');document.getElementById('e-fournisseur').classList.remove('show');}
  var lignesValides=_lignes.filter(function(l){return l.id_article&&parseFloat(l.quantite)>0;});
  if(!lignesValides.length){document.getElementById('e-lignes').classList.add('show');valid=false;}
  else document.getElementById('e-lignes').classList.remove('show');
  if(!valid) return;

  var btn=document.getElementById('btn-save');
  btn.disabled=true; btn.innerHTML='<i class="fas fa-spinner fa-spin"></i> Envoi…';

  var body={
    id_fournisseur:parseInt(fEl.value),
    id_utilisateur:UID,
    date_commande:new Date().toISOString().slice(0,10),
    lignes:lignesValides.map(function(l){return{id_article:parseInt(l.id_article),quantite:parseFloat(l.quantite),prix_unitaire:parseFloat(l.prix_unitaire)};})
  };
  var liv=document.getElementById('f-livraison').value;
  var notes=document.getElementById('f-notes').value.trim();
  if(liv) body.date_prevue_livraison=liv;
  if(notes) body.notes=notes;

  fetch(API+'/commandes',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify(body)})
    .then(function(r){return r.json().then(function(d){return{ok:r.ok,d:d};});})
    .then(function(res){
      if(!res.ok) throw new Error(res.d.message||'Erreur');
      closeModal('modal-create');
      // API retourne { id, numero, montant, message } — on utilise res.d.numero
      showSuccess('Commande '+(res.d.numero||'')+' créée avec succès.');
      return load();
    })
    .catch(function(e){showError('Erreur : '+(e.message||'Connexion impossible'));})
    .finally(function(){btn.disabled=false;btn.innerHTML='<i class="fas fa-paper-plane"></i> Envoyer la commande';});
}

/* ── DÉTAIL ── */
function openDetail(id){
  document.getElementById('detail-body').innerHTML='<div style="text-align:center;padding:32px;color:#aab5c6;"><i class="fas fa-spinner fa-spin"></i></div>';
  document.getElementById('detail-numero').textContent='Commande';
  openModal('modal-detail');
  fetch(API+'/commandes/'+id).then(function(r){return r.json();}).then(function(d){
    _detailData=d.data||d;
    renderDetail(_detailData);
  }).catch(function(){
    document.getElementById('detail-body').innerHTML='<div class="empty-state"><div class="empty-title">Erreur de chargement</div></div>';
  });
}

function renderDetail(c){
  document.getElementById('detail-numero').textContent=c.numero_commande||'Commande';
  
  var enRetard=c.date_prevue_livraison&&parseDateMidnight(c.date_prevue_livraison)<today()&&S_FINAL.indexOf(c.statut)<0;

  var lignesHtml='';
  (c.lignes||[]).forEach(function(l){
    lignesHtml+='<div class="dl-row">'
      +'<div><div style="font-weight:700;font-size:.88rem;color:var(--text,#0D1526)">'+esc(l.article_nom)+'</div>'
      +'<div class="dl-ref">'+esc(l.reference||'')+'</div></div>'
      +'<div class="dl-qty">'+fmt(l.quantite)+' '+esc(l.unite||'')+'</div>'
      +'<div class="dl-pu">'+fmt(l.prix_unitaire)+' FCFA</div>'
      +'<div class="dl-total">'+fmt(l.total_ht)+' FCFA</div>'
      +'</div>';
  });

  // Champs DB réels : montant_total_ttc, montant_total_ht, montant_tva
  var ttc=parseFloat(c.montant_total_ttc||0);
  var ht=parseFloat(c.montant_total_ht||ttc/1.1925);
  var tva=parseFloat(c.montant_tva||ttc-ht);

  // Options statut — clés exactes de l'ENUM DB
  var selOpts='';
  Object.keys(STATUTS).forEach(function(k){
    selOpts+='<option value="'+k+'"'+(c.statut===k?' selected':'')+'>'+STATUTS[k].lbl+'</option>';
  });

  document.getElementById('detail-body').innerHTML=
    '<div class="detail-meta">'
    +'<div class="dmeta"><div class="dmeta-l">Fournisseur</div><div class="dmeta-v">'+esc(c.fournisseur_nom||'—')+'</div></div>'
    +'<div class="dmeta"><div class="dmeta-l">Date commande</div><div class="dmeta-v">'+fmtDate(c.date_commande)+'</div></div>'
    +'<div class="dmeta"><div class="dmeta-l">Livraison prévue</div><div class="dmeta-v '+(enRetard?'c-late':'')+'">'+
      (c.date_prevue_livraison?fmtDate(c.date_prevue_livraison):'—')+(enRetard?' <i class="fas fa-exclamation-triangle" style="font-size:.75rem;"></i>':'')+'</div></div>'
    +'<div class="dmeta"><div class="dmeta-l">Créé par</div><div class="dmeta-v">'+esc(c.createur||'—')+'</div></div>'
    +'<div class="dmeta"><div class="dmeta-l">Statut</div><div class="dmeta-v">'+badgeStatut(c.statut)+'</div></div>'
    +'<div class="dmeta"><div class="dmeta-l">Lignes</div><div class="dmeta-v">'+((c.lignes||[]).length)+' article'+((c.lignes||[]).length>1?'s':'')+'</div></div>'
    +'</div>'
    +(c.notes?'<div style="background:#fffbf2;border:1px solid #f5c96a;border-radius:9px;padding:11px 14px;margin-bottom:16px;font-size:.86rem;color:#5A6E88;"><i class="fas fa-note-sticky" style="margin-right:7px;"></i>'+esc(c.notes)+'</div>':'')
    +'<div class="detail-lignes">'
    +'<div class="dl-head"><span>Article</span><span>Qté</span><span style="text-align:right;">Prix unit.</span><span style="text-align:right;">Total HT</span></div>'
    +lignesHtml
    +'<div class="detail-total-bar">'
    +'<span class="detail-total-lbl">HT</span><span style="font-family:\'DM Mono\',monospace;font-weight:700;">'+fmt(Math.round(ht))+' FCFA</span>'
    +'<span class="detail-total-lbl" style="margin-left:12px;">TVA 19.25%</span><span style="font-family:\'DM Mono\',monospace;font-weight:700;">'+fmt(Math.round(tva))+' FCFA</span>'
    +'<span class="detail-total-lbl" style="margin-left:12px;">TTC</span><span class="detail-total-val">'+fmt(ttc)+' FCFA</span>'
    +'</div></div>'
    +'<div style="margin-top:14px;"><div class="fsec-title">Modifier le statut</div>'
    +'<div class="statut-select-wrap">'
    +'<select id="detail-statut-sel">'+selOpts+'</select>'
    +'<button class="btn-gold" onclick="saveStatut('+c.id_commande+')"><i class="fas fa-floppy-disk"></i> Mettre à jour</button>'
    +'</div></div>';
}

function saveStatut(id){
  var sel=document.getElementById('detail-statut-sel');
  if(!sel) return;
  fetch(API+'/commandes/'+id+'/statut',{method:'PUT',headers:{'Content-Type':'application/json'},body:JSON.stringify({statut:sel.value})})
    .then(function(r){return r.json().then(function(d){return{ok:r.ok,d:d};});})
    .then(function(res){
      if(!res.ok) throw new Error(res.d.message||'Erreur');
      showSuccess('Statut → '+(STATUTS[sel.value]?STATUTS[sel.value].lbl:sel.value));
      closeModal('modal-detail');
      return load();
    })
    .catch(function(e){showError(e.message||'Erreur lors de la mise à jour');});
}

/* ── IMPRESSION ── */
function printCommande(){if(_detailData) printPDF(_detailData);}
function printById(id){
  fetch(API+'/commandes/'+id).then(function(r){return r.json();}).then(function(d){printPDF(d.data||d);})
    .catch(function(){showError('Impossible de charger la commande.');});
}

function printPDF(c){
  var date=new Date().toLocaleDateString('fr-FR',{day:'2-digit',month:'long',year:'numeric'});
  var ttc=parseFloat(c.montant_total_ttc||0);
  var ht=parseFloat(c.montant_total_ht||ttc/1.1925);
  var tva=parseFloat(c.montant_tva||ttc-ht);
  var lh='';
  (c.lignes||[]).forEach(function(l,i){
    lh+='<tr style="'+(i%2===0?'background:#fafbfd;':'')+'"><td>'+esc(l.article_nom)+'</td><td style="font-family:monospace;font-size:11px;">'+esc(l.reference||'')+'</td>'
      +'<td style="text-align:center;">'+fmt(l.quantite)+' '+esc(l.unite||'')+'</td>'
      +'<td style="text-align:right;font-family:monospace;">'+fmt(l.prix_unitaire)+'</td>'
      +'<td style="text-align:right;font-family:monospace;font-weight:700;">'+fmt(l.total_ht)+'</td></tr>';
  });
  var html='<!DOCTYPE html><html lang="fr"><head><meta charset="UTF-8"><title>BC '+esc(c.numero_commande)+'</title>'
    +'<style>*{margin:0;padding:0;box-sizing:border-box;}body{font-family:\'Segoe UI\',Arial,sans-serif;font-size:12px;color:#0D1526;padding:30px;}'
    +'.header{display:flex;justify-content:space-between;margin-bottom:24px;padding-bottom:16px;border-bottom:3px solid #E2A84B;}'
    +'.logo{font-size:20px;font-weight:800;}.logo span{color:#E2A84B;}'
    +'.cmd-num{font-size:22px;font-weight:800;text-align:right;}.cmd-num small{display:block;font-size:11px;font-weight:400;color:#6B7F99;margin-top:2px;}'
    +'.meta{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px;}'
    +'.meta-box{background:#f4f6fb;border:1px solid #cdd4e3;border-radius:8px;padding:12px;}'
    +'.meta-title{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:#8FA4BF;margin-bottom:6px;}'
    +'.meta-val{font-size:13px;font-weight:700;}.meta-sub{font-size:11px;color:#5A6E88;margin-top:2px;}'
    +'table{width:100%;border-collapse:collapse;margin-bottom:16px;}'
    +'thead th{background:#05080F;color:#fff;padding:8px 10px;font-size:10px;font-weight:700;text-transform:uppercase;text-align:left;}'
    +'tbody td{padding:8px 10px;border-bottom:1px solid #e4e8f0;}'
    +'.totaux{display:flex;justify-content:flex-end;}.totaux-inner{width:280px;}'
    +'.trow{display:flex;justify-content:space-between;padding:4px 0;font-size:12px;}'
    +'.trow.total{border-top:2px solid #E2A84B;margin-top:6px;padding-top:8px;font-size:15px;font-weight:800;}'
    +'.trow .lbl{color:#5A6E88;}.trow .val{font-family:monospace;font-weight:700;}.trow.total .val{color:#B87220;}'
    +'.footer{margin-top:24px;padding-top:12px;border-top:1px solid #e4e8f0;display:flex;justify-content:space-between;font-size:10px;color:#8FA4BF;}'
    +'@media print{@page{margin:15mm;}}</style></head><body>'
    +'<div class="header"><div><div class="logo">Gestion<span>Approv</span></div><div style="font-size:11px;color:#6B7F99;margin-top:4px;">Bon de Commande Fournisseur</div></div>'
    +'<div class="cmd-num">'+esc(c.numero_commande)+'<small>Émis le '+fmtDate(c.date_commande)+'</small></div></div>'
    +'<div class="meta"><div class="meta-box"><div class="meta-title">Fournisseur</div><div class="meta-val">'+esc(c.fournisseur_nom||'—')+'</div></div>'
    +'<div class="meta-box"><div class="meta-title">Livraison prévue</div><div class="meta-val">'+(c.date_prevue_livraison?fmtDate(c.date_prevue_livraison):'Non définie')+'</div>'
    +'<div class="meta-sub">Créé par : '+esc(c.createur||'—')+'</div></div></div>'
    +(c.notes?'<div style="background:#fffbf2;border:1px solid #f5c96a;border-radius:8px;padding:10px 14px;margin-bottom:16px;font-size:11px;color:#5A6E88;"><strong>Notes :</strong> '+esc(c.notes)+'</div>':'')
    +'<table><thead><tr><th>Désignation</th><th>Référence</th><th style="text-align:center;">Quantité</th><th style="text-align:right;">Prix unit.</th><th style="text-align:right;">Total HT (FCFA)</th></tr></thead>'
    +'<tbody>'+lh+'</tbody></table>'
    +'<div class="totaux"><div class="totaux-inner">'
    +'<div class="trow"><span class="lbl">Sous-total HT</span><span class="val">'+fmt(Math.round(ht))+' FCFA</span></div>'
    +'<div class="trow"><span class="lbl">TVA (19.25%)</span><span class="val">'+fmt(Math.round(tva))+' FCFA</span></div>'
    +'<div class="trow total"><span class="lbl">Total TTC</span><span class="val">'+fmt(ttc)+' FCFA</span></div>'
    +'</div></div>'
    +'<div class="footer"><span>GestionApprov ERP</span><span>Imprimé le '+date+'</span></div>'
    +'<script>window.onload=function(){window.print();};<\/script></body></html>';
  var w=window.open('','_blank','width=1000,height=800');
  if(!w){showError('Autorisez les popups pour imprimer.');return;}
  w.document.write(html); w.document.close();
}

/* ── MODALS ── */
function openModal(id){document.getElementById(id).classList.add('open');document.body.style.overflow='hidden';}
function closeModal(id){document.getElementById(id).classList.remove('open');document.body.style.overflow='';}
function clearForm(){
  ['f-fournisseur','f-livraison','f-notes'].forEach(function(id){document.getElementById(id).value='';});
  document.querySelectorAll('.error').forEach(function(e){e.classList.remove('error');});
  document.querySelectorAll('.ferr.show').forEach(function(e){e.classList.remove('show');});
}
document.querySelectorAll('.modal-bg').forEach(function(bg){
  bg.addEventListener('click',function(e){if(e.target===bg) closeModal(bg.id);});
});
document.addEventListener('keydown',function(e){
  if(e.key==='Escape') document.querySelectorAll('.modal-bg.open').forEach(function(m){closeModal(m.id);});
});

load();
</script>

<?php require_once 'includes/footer.php'; ?>