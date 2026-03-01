<?php
require_once 'includes/config.php';
if(empty($_SESSION['user'])){header('Location: login.php');exit;}
$_uid = $_SESSION['user']['id'] ?? $_SESSION['user']['id_utilisateur'] ?? 0;
?>
<?php require_once 'includes/header.php'; ?>
<?php require_once 'includes/sidebar.php'; ?>
<script>
  if(document.getElementById('nav-page-title'))
    document.getElementById('nav-page-title').textContent = 'Stock';
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

/* ─── Tabs ─── */
.tabs{display:flex;gap:4px;background:var(--surface,#fff);border:1px solid var(--border,#e4e8f0);border-radius:11px;padding:6px;margin-bottom:16px;box-shadow:0 1px 4px rgba(5,8,15,.06);width:fit-content;}
.tab-btn{display:inline-flex;align-items:center;gap:7px;padding:8px 16px;border-radius:8px;font-family:inherit;font-size:.84rem;font-weight:600;cursor:pointer;border:none;background:transparent;color:var(--text3,#8FA4BF);transition:all .16s;}
.tab-btn.active{background:var(--ink2,#0D1526);color:#fff;}
.tab-btn:not(.active):hover{background:var(--surface2,#f4f6fb);color:var(--text2,#3a4a5c);}
.tab-panel{display:none;}
.tab-panel.active{display:block;}

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

/* ─── Table mouvements ─── */
.tcard{background:var(--surface,#fff);border:1px solid var(--border,#e4e8f0);border-radius:12px;box-shadow:0 1px 4px rgba(5,8,15,.06);overflow:hidden;}
.etbl{width:100%;border-collapse:collapse;table-layout:fixed;}
.etbl thead th{font-size:.65rem;font-weight:800;text-transform:uppercase;letter-spacing:1.2px;color:var(--text4,#aab5c6);padding:10px 14px;border-bottom:2px solid var(--border,#e4e8f0);background:var(--surface2,#f4f6fb);white-space:nowrap;text-align:left;cursor:pointer;user-select:none;}
.etbl thead th:hover{color:var(--text2,#3a4a5c);}
.etbl thead th.sorted{color:#6a3fbf;}
.sort-ico{margin-left:4px;opacity:.35;font-size:.58rem;}
.sorted .sort-ico{opacity:1;color:#6a3fbf;}
/* Date | Article | Type | Qté | Avant | Après | Opérateur | Doc/Motif */
.etbl thead th:nth-child(1){width:12%;}
.etbl thead th:nth-child(2){width:20%;}
.etbl thead th:nth-child(3){width:10%;}
.etbl thead th:nth-child(4){width:9%;text-align:right;}
.etbl thead th:nth-child(5){width:9%;text-align:right;}
.etbl thead th:nth-child(6){width:9%;text-align:right;}
.etbl thead th:nth-child(7){width:13%;}
.etbl thead th:nth-child(8){width:18%;}
.etbl tbody td{padding:10px 14px;border-bottom:1px solid var(--border,#e4e8f0);font-size:.85rem;color:var(--text2,#3a4a5c);vertical-align:middle;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
.etbl tbody td:nth-child(4),.etbl tbody td:nth-child(5),.etbl tbody td:nth-child(6){text-align:right;font-family:'DM Mono',monospace;}
.etbl tbody tr:last-child td{border-bottom:none;}
.etbl tbody tr:hover{background:var(--surface2,#f4f6fb);}

/* Flèche quantité selon type */
.qty-entree{color:#1a8a4a;font-weight:700;}
.qty-sortie{color:#c03030;font-weight:700;}
.qty-ajust{color:#6a3fbf;font-weight:700;}
.qty-inv{color:#1a7fd4;font-weight:700;}

/* Stock avant/après */
.stock-val{font-family:'DM Mono',monospace;font-size:.84rem;}
.stock-arrow{color:var(--text4,#aab5c6);font-size:.7rem;margin:0 2px;}

/* Ref document */
.doc-ref{font-family:'DM Mono',monospace;font-size:.76rem;color:var(--text4,#aab5c6);}

/* ─── Pagination ─── */
.pager{display:flex;align-items:center;justify-content:space-between;padding:12px 18px;border-top:1px solid var(--border,#e4e8f0);flex-wrap:wrap;gap:10px;}
.pager-info{font-size:.78rem;color:var(--text4,#aab5c6);}
.pager-btns{display:flex;align-items:center;gap:4px;}
.pg-btn{min-width:32px;height:32px;border-radius:7px;display:flex;align-items:center;justify-content:center;font-size:.78rem;font-weight:600;cursor:pointer;border:1px solid var(--border,#e4e8f0);background:var(--canvas,#f8f9fc);color:var(--text2,#3a4a5c);transition:all .15s;padding:0 6px;}
.pg-btn:hover{background:var(--surface,#fff);}
.pg-btn.on{background:#0D1526;color:#fff;border-color:#0D1526;}
.pg-btn:disabled{opacity:.38;cursor:default;}

/* ─── Panneau Ajustement ─── */
.adj-layout{display:grid;grid-template-columns:1fr 360px;gap:20px;align-items:start;}
@media(max-width:860px){.adj-layout{grid-template-columns:1fr;}}

.adj-form-card{background:var(--surface,#fff);border:1px solid var(--border,#e4e8f0);border-radius:12px;padding:22px;box-shadow:0 1px 4px rgba(5,8,15,.06);}
.adj-info-card{background:var(--surface,#fff);border:1px solid var(--border,#e4e8f0);border-radius:12px;padding:22px;box-shadow:0 1px 4px rgba(5,8,15,.06);}

.fsec-title{font-size:.68rem;font-weight:800;text-transform:uppercase;letter-spacing:1.5px;color:var(--text4,#aab5c6);margin-bottom:14px;}
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

/* Type mouvement — boutons radio custom */
.type-grid{display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:14px;}
.type-btn{display:flex;align-items:center;gap:9px;padding:10px 14px;border-radius:9px;border:1.5px solid var(--border,#e4e8f0);background:var(--canvas,#f8f9fc);cursor:pointer;transition:all .15s;font-family:inherit;font-size:.84rem;font-weight:600;color:var(--text2,#3a4a5c);}
.type-btn:hover{background:var(--surface2,#f4f6fb);}
.type-btn.selected.entree{border-color:#1a8a4a;background:#eafaf1;color:#1a8a4a;}
.type-btn.selected.sortie{border-color:#c03030;background:#fff0f0;color:#c03030;}
.type-btn.selected.ajustement{border-color:#6a3fbf;background:#f3f0ff;color:#6a3fbf;}
.type-btn.selected.inventaire{border-color:#1a7fd4;background:#ebf5ff;color:#1a7fd4;}
.type-ico{width:28px;height:28px;border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:.78rem;flex-shrink:0;}
.ico-entree{background:#eafaf1;color:#1a8a4a;}
.ico-sortie{background:#fff0f0;color:#c03030;}
.ico-ajust{background:#f3f0ff;color:#6a3fbf;}
.ico-inv{background:#ebf5ff;color:#1a7fd4;}

/* Aperçu article sélectionné */
.art-preview{background:var(--canvas,#f8f9fc);border:1px solid var(--border,#e4e8f0);border-radius:10px;padding:14px;margin-bottom:14px;display:none;}
.art-preview.show{display:block;}
.ap-row{display:flex;justify-content:space-between;align-items:center;padding:3px 0;}
.ap-lbl{font-size:.76rem;color:var(--text3,#8FA4BF);}
.ap-val{font-size:.86rem;font-weight:700;color:var(--text,#0D1526);font-family:'DM Mono',monospace;}
.ap-val.alerte{color:#c03030;}
.ap-val.ok{color:#1a8a4a;}

/* Simulation résultat */
.sim-result{background:var(--surface2,#f4f6fb);border:1px solid var(--border,#e4e8f0);border-radius:10px;padding:14px;margin-bottom:14px;display:none;}
.sim-result.show{display:block;}
.sim-row{display:flex;justify-content:space-between;align-items:center;padding:4px 0;font-size:.86rem;}
.sim-row.final{border-top:1px solid var(--border,#e4e8f0);margin-top:8px;padding-top:10px;font-weight:700;}
.sim-lbl{color:var(--text3,#8FA4BF);}
.sim-val{font-family:'DM Mono',monospace;font-weight:700;}
.sim-val.up{color:#1a8a4a;}
.sim-val.down{color:#c03030;}
.sim-val.same{color:#6a3fbf;}

/* Historique article (colonne droite) */
.hist-empty{padding:24px;text-align:center;color:var(--text4,#aab5c6);font-size:.83rem;}
.hist-item{display:flex;align-items:center;gap:10px;padding:9px 0;border-bottom:1px solid var(--border,#e4e8f0);}
.hist-item:last-child{border-bottom:none;}
.hist-ico{width:30px;height:30px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:.72rem;flex-shrink:0;}
.hist-body{flex:1;min-width:0;}
.hist-date{font-size:.72rem;color:var(--text4,#aab5c6);}
.hist-type{font-size:.8rem;font-weight:700;}
.hist-detail{font-size:.76rem;color:var(--text3,#8FA4BF);}
.hist-delta{font-family:'DM Mono',monospace;font-size:.9rem;font-weight:800;white-space:nowrap;}

/* ─── Badges ─── */
/* type_mouvement ENUM : entree | sortie | ajustement | inventaire */
.sbadge{display:inline-flex;align-items:center;padding:3px 9px;border-radius:20px;font-size:.71rem;font-weight:700;white-space:nowrap;}
.sb-ok{background:#eafaf1;color:#1a8a4a;}
.sb-crit{background:#fff0f0;color:#c03030;}
.sb-purple{background:#f3f0ff;color:#6a3fbf;}
.sb-blue{background:#ebf5ff;color:#1a7fd4;}
.sb-muted{background:#f0f2f5;color:#7a8a9a;}

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
      <i class="fas fa-boxes-stacked" style="color:var(--gold,#E2A84B);margin-right:9px;"></i>Gestion des stocks
    </div>
    <div class="ph-sub">Mouvements de stock et ajustements manuels</div>
  </div>
</div>

<!-- ═══ KPI STRIP ═══ -->
<div class="kstrip">
  <div class="ks">
    <div class="ks-ico" style="background:#eafaf1;color:#1a8a4a;"><i class="fas fa-arrow-down"></i></div>
    <div><div class="ks-n" id="ks-entrees">—</div><div class="ks-l">Entrées (100 dern.)</div></div>
  </div>
  <div class="ks">
    <div class="ks-ico" style="background:#fff0f0;color:#c03030;"><i class="fas fa-arrow-up"></i></div>
    <div><div class="ks-n" id="ks-sorties">—</div><div class="ks-l">Sorties (100 dern.)</div></div>
  </div>
  <div class="ks">
    <div class="ks-ico" style="background:#f3f0ff;color:#6a3fbf;"><i class="fas fa-sliders"></i></div>
    <div><div class="ks-n" id="ks-ajust">—</div><div class="ks-l">Ajustements</div></div>
  </div>
  <div class="ks">
    <div class="ks-ico" style="background:#fff4e0;color:#c87000;"><i class="fas fa-triangle-exclamation"></i></div>
    <div><div class="ks-n" id="ks-alerte">—</div><div class="ks-l">Articles en alerte</div></div>
  </div>
</div>

<!-- ═══ TABS ═══ -->
<div class="tabs">
  <button class="tab-btn active" onclick="switchTab('mouvements',this)">
    <i class="fas fa-list-ul"></i> Mouvements
  </button>
  <button class="tab-btn" onclick="switchTab('ajustement',this)">
    <i class="fas fa-sliders"></i> Ajustement manuel
  </button>
</div>

<!-- ════════════════ ONGLET MOUVEMENTS ════════════════ -->
<div class="tab-panel active" id="panel-mouvements">

  <div class="toolbar">
    <div class="tsearch">
      <i class="fas fa-search"></i>
      <input type="text" id="m-search" placeholder="Article, référence, opérateur…" oninput="filterMouvements()">
    </div>
    <div class="tsel">
      <i class="fas fa-filter" style="font-size:.76rem;"></i>
      <select id="m-type" onchange="filterMouvements()">
        <option value="">Tous les types</option>
        <option value="entree">Entrée</option>
        <option value="sortie">Sortie</option>
        <option value="ajustement">Ajustement</option>
        <option value="inventaire">Inventaire</option>
      </select>
    </div>
    <div class="tsel">
      <i class="fas fa-calendar" style="font-size:.76rem;"></i>
      <select id="m-periode" onchange="filterMouvements()">
        <option value="">Toute période</option>
        <option value="1">Aujourd'hui</option>
        <option value="7">7 derniers jours</option>
        <option value="30">30 derniers jours</option>
      </select>
    </div>
    <div class="tb-sep"></div>
    <div class="tb-count" id="m-count">Chargement…</div>
  </div>

  <div class="tcard">
    <div style="overflow-x:auto;">
      <table class="etbl">
        <thead>
          <tr>
            <th onclick="sortMvt('date_mouvement')">Date <i class="fas fa-sort sort-ico"></i></th>
            <th onclick="sortMvt('article_nom')">Article <i class="fas fa-sort sort-ico"></i></th>
            <th onclick="sortMvt('type_mouvement')">Type <i class="fas fa-sort sort-ico"></i></th>
            <th onclick="sortMvt('quantite')" style="text-align:right;">Quantité <i class="fas fa-sort sort-ico"></i></th>
            <th style="text-align:right;">Stock avant</th>
            <th style="text-align:right;">Stock après</th>
            <th onclick="sortMvt('utilisateur_nom')">Opérateur <i class="fas fa-sort sort-ico"></i></th>
            <th onclick="sortMvt('motif')">Motif / Réf doc <i class="fas fa-sort sort-ico"></i></th>
          </tr>
        </thead>
        <tbody id="mvt-body">
          <tr><td colspan="8">
            <div style="padding:24px;text-align:center;color:var(--text4,#aab5c6);">
              <i class="fas fa-spinner fa-spin"></i> Chargement…
            </div>
          </td></tr>
        </tbody>
      </table>
    </div>
    <div class="pager" id="mvt-pager" style="display:none;">
      <div class="pager-info" id="mvt-pager-info"></div>
      <div class="pager-btns" id="mvt-pager-btns"></div>
    </div>
  </div>
</div>

<!-- ════════════════ ONGLET AJUSTEMENT ════════════════ -->
<div class="tab-panel" id="panel-ajustement">
  <div class="adj-layout">

    <!-- Formulaire -->
    <div class="adj-form-card">
      <div class="fsec-title">Paramètres de l'ajustement</div>

      <!-- Article -->
      <div class="frow">
        <div class="fgroup">
          <label class="flabel">Article <span>*</span></label>
          <select class="fselect" id="a-article" onchange="onArticleChange()">
            <option value="">— Sélectionner un article —</option>
          </select>
          <div class="ferr" id="e-article">Veuillez sélectionner un article</div>
        </div>
      </div>

      <!-- Aperçu stock article -->
      <div class="art-preview" id="art-preview">
        <div class="ap-row">
          <span class="ap-lbl">Référence</span>
          <span class="ap-val" id="ap-ref">—</span>
        </div>
        <div class="ap-row">
          <span class="ap-lbl">Stock actuel</span>
          <span class="ap-val" id="ap-stock">—</span>
        </div>
        <div class="ap-row">
          <span class="ap-lbl">Seuil minimum</span>
          <span class="ap-val" id="ap-seuil">—</span>
        </div>
        <div class="ap-row">
          <span class="ap-lbl">Unité</span>
          <span class="ap-val" id="ap-unite">—</span>
        </div>
      </div>

      <!-- Type de mouvement -->
      <div class="fgroup" style="margin-bottom:14px;">
        <label class="flabel">Type de mouvement <span>*</span></label>
        <div class="type-grid" id="type-grid">
          <button class="type-btn selected entree" data-type="entree" onclick="selectType('entree')">
            <span class="type-ico ico-entree"><i class="fas fa-plus"></i></span>
            Entrée stock
          </button>
          <button class="type-btn sortie" data-type="sortie" onclick="selectType('sortie')">
            <span class="type-ico ico-sortie"><i class="fas fa-minus"></i></span>
            Sortie stock
          </button>
          <button class="type-btn ajustement" data-type="ajustement" onclick="selectType('ajustement')">
            <span class="type-ico ico-ajust"><i class="fas fa-sliders"></i></span>
            Ajustement
          </button>
          <button class="type-btn inventaire" data-type="inventaire" onclick="selectType('inventaire')">
            <span class="type-ico ico-inv"><i class="fas fa-clipboard-list"></i></span>
            Inventaire
          </button>
        </div>
        <div class="ferr" id="e-type">Veuillez sélectionner un type</div>
      </div>

      <!-- Quantité -->
      <div class="frow frow-2">
        <div class="fgroup">
          <label class="flabel" id="lbl-quantite">Quantité <span>*</span></label>
          <input type="number" class="finput" id="a-quantite"
            min="0.001" step="0.001" placeholder="0"
            oninput="onQuantiteChange()"
            style="font-family:'DM Mono',monospace;font-size:1rem;font-weight:700;">
          <div class="ferr" id="e-quantite">Quantité invalide</div>
        </div>
        <div class="fgroup">
          <label class="flabel">Unité</label>
          <input type="text" class="finput" id="a-unite-disp" disabled
            placeholder="—" style="font-family:'DM Mono',monospace;">
        </div>
      </div>

      <!-- Simulation résultat -->
      <div class="sim-result" id="sim-result">
        <div class="sim-row">
          <span class="sim-lbl">Stock actuel</span>
          <span class="sim-val" id="sim-avant">—</span>
        </div>
        <div class="sim-row">
          <span class="sim-lbl">Mouvement</span>
          <span class="sim-val" id="sim-delta">—</span>
        </div>
        <div class="sim-row final">
          <span class="sim-lbl">Nouveau stock</span>
          <span class="sim-val" id="sim-apres">—</span>
        </div>
      </div>

      <!-- Motif -->
      <div class="frow">
        <div class="fgroup">
          <label class="flabel">Motif <span>*</span></label>
          <textarea class="ftextarea" id="a-motif"
            placeholder="Expliquez la raison de cet ajustement…"></textarea>
          <div class="ferr" id="e-motif">Motif requis</div>
        </div>
      </div>

      <button class="btn-gold" id="btn-adj" onclick="saveAjustement()" style="width:100%;justify-content:center;">
        <i class="fas fa-check"></i> Valider l'ajustement
      </button>
    </div>

    <!-- Colonne droite : historique article sélectionné -->
    <div class="adj-info-card">
      <div class="fsec-title">Historique de l'article</div>
      <div id="hist-container">
        <div class="hist-empty">
          <i class="fas fa-chart-line" style="font-size:1.4rem;opacity:.3;display:block;margin-bottom:8px;"></i>
          Sélectionnez un article pour voir son historique
        </div>
      </div>
    </div>

  </div>
</div>


<!-- ════════════════════════════════════════════
     JAVASCRIPT
════════════════════════════════════════════ -->
<script>
/* ════════════════════════════════════════
   CONFIG
   API :  GET  /api/stock/mouvements        → 100 derniers
          GET  /api/stock/mouvements/:id    → par article
          POST /api/stock/ajustement
          GET  /api/articles                → pour select

   Champs GET /mouvements :
     id_mouvement, id_article, id_utilisateur, id_reception,
     type_mouvement, quantite, stock_avant, stock_apres,
     reference_document, date_mouvement, motif,
     reference (article), article_nom, utilisateur_nom

   ENUM type_mouvement : entree | sortie | ajustement | inventaire
════════════════════════════════════════ */
var API = 'http://localhost:3000/api';
var UID = <?= (int)$_uid ?>;

var _mvt      = [];   // tous les mouvements chargés
var _mvtShown = [];   // filtrés
var _articles = [];   // catalogue articles
var _mvtPage  = 1;
var PER       = 20;
var _mvtSort  = 'date_mouvement';
var _mvtAsc   = false;
var _typeSelectionne = 'entree';  // type mouvement formulaire

/* ════════════════════════════════════════
   UTILITAIRES — fix timezone MySQL → ISO UTC
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
function fmtDatetime(d) {
  if (!d) return '—';
  var dt = new Date(d);
  if (isNaN(dt.getTime())) return String(d);
  return dt.toLocaleDateString('fr-FR', { day:'2-digit', month:'2-digit', year:'numeric' })
    + ' ' + dt.toLocaleTimeString('fr-FR', { hour:'2-digit', minute:'2-digit' });
}
function fmt(n) { return (parseFloat(n) || 0).toLocaleString('fr-FR'); }
function truncate(s, max) { s = String(s||''); return s.length > max ? s.slice(0, max) + '…' : s; }
function esc(s) {
  return String(s == null ? '' : s)
    .replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

/* Badges type_mouvement */
var TYPE_CFG = {
  entree:      { cls:'sb-ok',     lbl:'Entrée',      ico:'fa-arrow-down',  qtyCls:'qty-entree', sign:'+' },
  sortie:      { cls:'sb-crit',   lbl:'Sortie',      ico:'fa-arrow-up',    qtyCls:'qty-sortie', sign:'−' },
  ajustement:  { cls:'sb-purple', lbl:'Ajustement',  ico:'fa-sliders',     qtyCls:'qty-ajust',  sign:'±' },
  inventaire:  { cls:'sb-blue',   lbl:'Inventaire',  ico:'fa-clipboard-list', qtyCls:'qty-inv', sign:'=' }
};
function badgeType(t) {
  var c = TYPE_CFG[t] || { cls:'sb-muted', lbl: t||'—' };
  return '<span class="sbadge ' + c.cls + '">' + c.lbl + '</span>';
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
    fetch(API + '/stock/mouvements').then(function(r){ return r.json(); }),
    fetch(API + '/articles').then(function(r){ return r.json(); })
  ]).then(function(res) {
    _mvt      = res[0].data || res[0] || [];
    _articles = (res[1].data || res[1] || []).filter(function(a){ return a.statut === 'actif'; });
    buildArticleSelect();
    computeKPIs();
    filterMouvements();
  }).catch(function() {
    document.getElementById('mvt-body').innerHTML =
      '<tr><td colspan="8"><div class="empty-state">' +
      '<div class="empty-ico"><i class="fas fa-wifi-slash"></i></div>' +
      '<div class="empty-title">Erreur de connexion</div>' +
      '<div class="empty-text">Impossible de joindre l\'API (port 3000).</div>' +
      '</div></td></tr>';
    document.getElementById('m-count').textContent = '—';
  });
}

function buildArticleSelect() {
  var h = '<option value="">— Sélectionner un article —</option>';
  _articles.forEach(function(a) {
    h += '<option value="' + a.id_article + '"'
       + ' data-stock="'  + (a.stock_actuel  || 0) + '"'
       + ' data-seuil="'  + (a.seuil_minimum || 0) + '"'
       + ' data-unite="'  + esc(a.unite || 'u') + '"'
       + ' data-ref="'    + esc(a.reference || '') + '"'
       + '>' + esc(a.nom) + ' — ' + esc(a.reference || '') + '</option>';
  });
  document.getElementById('a-article').innerHTML = h;
}

/* ════════════════════════════════════════
   KPIs — calculés sur les 100 mouvements chargés
   + articles en alerte depuis _articles
════════════════════════════════════════ */
function computeKPIs() {
  var entrees = 0, sorties = 0, ajust = 0;
  _mvt.forEach(function(m) {
    if (m.type_mouvement === 'entree')     entrees++;
    if (m.type_mouvement === 'sortie')     sorties++;
    if (m.type_mouvement === 'ajustement' || m.type_mouvement === 'inventaire') ajust++;
  });
  var alerte = _articles.filter(function(a) {
    return parseFloat(a.stock_actuel) <= parseFloat(a.seuil_minimum);
  }).length;

  document.getElementById('ks-entrees').textContent = entrees;
  document.getElementById('ks-sorties').textContent = sorties;
  document.getElementById('ks-ajust').textContent   = ajust;
  document.getElementById('ks-alerte').textContent  = alerte;
}

/* ════════════════════════════════════════
   FILTRE & TRI MOUVEMENTS
════════════════════════════════════════ */
function filterMouvements() {
  var q   = document.getElementById('m-search').value.trim().toLowerCase();
  var tp  = document.getElementById('m-type').value;
  var per = document.getElementById('m-periode').value;
  var now = today();

  _mvtShown = _mvt.filter(function(m) {
    var mq = !q
      || (m.article_nom     || '').toLowerCase().indexOf(q) >= 0
      || (m.reference       || '').toLowerCase().indexOf(q) >= 0
      || (m.utilisateur_nom || '').toLowerCase().indexOf(q) >= 0
      || (m.motif           || '').toLowerCase().indexOf(q) >= 0
      || (m.reference_document || '').toLowerCase().indexOf(q) >= 0;
    var mt = !tp || m.type_mouvement === tp;
    var mp = true;
    if (per) {
      var d = parseDateMidnight(m.date_mouvement);
      mp = d && (now - d) <= parseInt(per) * 86400000;
    }
    return mq && mt && mp;
  });

  _mvtShown.sort(function(a, b) {
    var va = a[_mvtSort] != null ? a[_mvtSort] : (_mvtAsc ? '\uFFFF' : '');
    var vb = b[_mvtSort] != null ? b[_mvtSort] : (_mvtAsc ? '\uFFFF' : '');
    var nums = ['quantite','stock_avant','stock_apres'];
    if (nums.indexOf(_mvtSort) >= 0) { va = parseFloat(va)||0; vb = parseFloat(vb)||0; }
    else if (typeof va === 'string') { va = va.toLowerCase(); vb = String(vb).toLowerCase(); }
    return _mvtAsc ? (va > vb ? 1 : va < vb ? -1 : 0)
                   : (va < vb ? 1 : va > vb ? -1 : 0);
  });

  _mvtPage = 1;
  renderMouvements();
}

function sortMvt(col) {
  _mvtAsc = (_mvtSort === col) ? !_mvtAsc : true;
  _mvtSort = col;
  document.querySelectorAll('.etbl thead th').forEach(function(th) {
    th.classList.remove('sorted');
    var ic = th.querySelector('.sort-ico');
    if (ic) ic.className = 'fas fa-sort sort-ico';
  });
  var cols = ['date_mouvement','article_nom','type_mouvement','quantite',null,null,'utilisateur_nom','motif'];
  var idx = cols.indexOf(col);
  if (idx >= 0) {
    var ths = document.querySelectorAll('#panel-mouvements .etbl thead th');
    if (ths[idx]) {
      ths[idx].classList.add('sorted');
      var ic = ths[idx].querySelector('.sort-ico');
      if (ic) ic.className = 'fas fa-sort-' + (_mvtAsc ? 'up' : 'down') + ' sort-ico';
    }
  }
  filterMouvements();
}

/* ════════════════════════════════════════
   RENDU TABLE MOUVEMENTS
════════════════════════════════════════ */
function renderMouvements() {
  var total = _mvtShown.length;
  var pages = Math.max(1, Math.ceil(total / PER));
  if (_mvtPage > pages) _mvtPage = pages;

  document.getElementById('m-count').textContent =
    total + ' mouvement' + (total !== 1 ? 's' : '');

  var slice = _mvtShown.slice((_mvtPage - 1) * PER, _mvtPage * PER);

  if (!slice.length) {
    document.getElementById('mvt-body').innerHTML =
      '<tr><td colspan="8"><div class="empty-state">' +
      '<div class="empty-ico"><i class="fas fa-boxes-stacked"></i></div>' +
      '<div class="empty-title">Aucun mouvement trouvé</div>' +
      '<div class="empty-text">' + (_mvt.length === 0 ? 'Aucun mouvement enregistré.' : 'Aucun résultat.') + '</div>' +
      '</div></td></tr>';
    document.getElementById('mvt-pager').style.display = 'none';
    return;
  }

  var html = '';
  slice.forEach(function(m) {
    var cfg     = TYPE_CFG[m.type_mouvement] || { qtyCls:'', sign:'' };
    var qtySign = (m.type_mouvement === 'sortie') ? '−' : (m.type_mouvement === 'ajustement' || m.type_mouvement === 'inventaire' ? '±' : '+');

    /* Stock avant → après avec flèche colorée */
    var stockAvant = fmt(m.stock_avant);
    var stockApres = fmt(m.stock_apres);
    var diff       = parseFloat(m.stock_apres) - parseFloat(m.stock_avant);
    var arrowCls   = diff > 0 ? 'color:#1a8a4a' : (diff < 0 ? 'color:#c03030' : 'color:#aab5c6');

    /* Motif ou référence document */
    var motifTxt = '';
    if (m.reference_document) {
      motifTxt = '<div class="doc-ref"><i class="fas fa-link" style="font-size:.6rem;margin-right:3px;opacity:.6;"></i>'
        + esc(m.reference_document) + '</div>';
    }
    if (m.motif) {
      motifTxt += '<div style="font-size:.78rem;color:var(--text3,#8FA4BF);">' + esc(truncate(m.motif, 35)) + '</div>';
    }
    if (!motifTxt) motifTxt = '<span style="color:var(--text4,#aab5c6);font-size:.78rem;">—</span>';

    html += '<tr>'
      + '<td style="font-size:.8rem;white-space:nowrap;">' + fmtDatetime(m.date_mouvement) + '</td>'
      + '<td>'
      +   '<div style="font-weight:600;color:var(--text,#0D1526);font-size:.86rem;">' + esc(m.article_nom) + '</div>'
      +   '<div style="font-family:\'DM Mono\',monospace;font-size:.72rem;color:var(--text4,#aab5c6);">' + esc(m.reference||'') + '</div>'
      + '</td>'
      + '<td>' + badgeType(m.type_mouvement) + '</td>'
      + '<td class="' + cfg.qtyCls + '">' + qtySign + ' ' + fmt(m.quantite) + '</td>'
      + '<td class="stock-val">' + stockAvant + '</td>'
      + '<td class="stock-val" style="' + arrowCls + ';font-weight:700;">' + stockApres + '</td>'
      + '<td style="font-size:.82rem;">' + esc(m.utilisateur_nom||'—') + '</td>'
      + '<td>' + motifTxt + '</td>'
      + '</tr>';
  });
  document.getElementById('mvt-body').innerHTML = html;

  /* Pagination */
  var pg    = document.getElementById('mvt-pager');
  var pbtns = document.getElementById('mvt-pager-btns');
  pg.style.display = 'flex';
  document.getElementById('mvt-pager-info').textContent =
    ((_mvtPage - 1) * PER + 1) + '–' + Math.min(_mvtPage * PER, total) + ' sur ' + total;

  var ph = '<button class="pg-btn" onclick="goMvtPage(' + (_mvtPage-1) + ')" '
    + (_mvtPage===1?'disabled':'') + '><i class="fas fa-chevron-left"></i></button>';
  for (var p = 1; p <= pages; p++) {
    if (pages > 7 && p > 2 && p < pages-1 && Math.abs(p-_mvtPage) > 1) {
      if (p===3||p===pages-2) ph += '<span style="padding:0 4px;color:#aab5c6;">…</span>';
      continue;
    }
    ph += '<button class="pg-btn ' + (p===_mvtPage?'on':'') + '" onclick="goMvtPage(' + p + ')">' + p + '</button>';
  }
  ph += '<button class="pg-btn" onclick="goMvtPage(' + (_mvtPage+1) + ')" '
    + (_mvtPage===pages?'disabled':'') + '><i class="fas fa-chevron-right"></i></button>';
  pbtns.innerHTML = ph;
}

function goMvtPage(p) {
  var pages = Math.ceil(_mvtShown.length / PER);
  if (p < 1 || p > pages) return;
  _mvtPage = p;
  renderMouvements();
  window.scrollTo({ top: 0, behavior: 'smooth' });
}

/* ════════════════════════════════════════
   ONGLETS
════════════════════════════════════════ */
function switchTab(name, btn) {
  document.querySelectorAll('.tab-btn').forEach(function(b){ b.classList.remove('active'); });
  document.querySelectorAll('.tab-panel').forEach(function(p){ p.classList.remove('active'); });
  btn.classList.add('active');
  document.getElementById('panel-' + name).classList.add('active');
}

/* ════════════════════════════════════════
   FORMULAIRE AJUSTEMENT
════════════════════════════════════════ */
function selectType(type) {
  _typeSelectionne = type;
  document.querySelectorAll('.type-btn').forEach(function(b) {
    b.classList.remove('selected');
  });
  document.querySelectorAll('.type-btn[data-type="' + type + '"]').forEach(function(b) {
    b.classList.add('selected');
  });
  document.getElementById('e-type').classList.remove('show');
  /* Adapter le label quantité selon le type */
  var lbl = document.querySelector('label[for-qty]') || document.getElementById('lbl-quantite');
  if (lbl) {
    lbl.childNodes[0].textContent = (type === 'inventaire')
      ? 'Nouveau stock total '
      : 'Quantité ';
  }
  /* Adapter le placeholder */
  var qEl = document.getElementById('a-quantite');
  if (qEl) qEl.placeholder = (type === 'inventaire') ? 'Stock réel compté' : '0';
  onQuantiteChange();  // recalculer la simulation
}

function onArticleChange() {
  var sel = document.getElementById('a-article');
  var opt = sel.options[sel.selectedIndex];
  var id  = sel.value;

  document.getElementById('e-article').classList.remove('show');
  sel.classList.remove('error');

  if (!id) {
    document.getElementById('art-preview').classList.remove('show');
    document.getElementById('hist-container').innerHTML =
      '<div class="hist-empty"><i class="fas fa-chart-line" style="font-size:1.4rem;opacity:.3;display:block;margin-bottom:8px;"></i>Sélectionnez un article pour voir son historique</div>';
    document.getElementById('sim-result').classList.remove('show');
    document.getElementById('a-unite-disp').value = '';
    return;
  }

  /* Aperçu article depuis data-* de l'option */
  var stock = parseFloat(opt.getAttribute('data-stock') || 0);
  var seuil = parseFloat(opt.getAttribute('data-seuil') || 0);
  var unite = opt.getAttribute('data-unite') || 'u';
  var ref   = opt.getAttribute('data-ref') || '';

  document.getElementById('ap-ref').textContent   = ref || '—';
  document.getElementById('ap-stock').textContent = fmt(stock) + ' ' + unite;
  document.getElementById('ap-seuil').textContent = fmt(seuil) + ' ' + unite;
  document.getElementById('ap-unite').textContent = unite;

  var stockEl = document.getElementById('ap-stock');
  stockEl.className = 'ap-val ' + (stock <= seuil ? 'alerte' : 'ok');

  document.getElementById('art-preview').classList.add('show');
  document.getElementById('a-unite-disp').value = unite;

  onQuantiteChange();
  loadHistorique(id);
}

function onQuantiteChange() {
  var sel   = document.getElementById('a-article');
  var opt   = sel.options[sel.selectedIndex];
  var id    = sel.value;
  if (!id) { document.getElementById('sim-result').classList.remove('show'); return; }

  var stock = parseFloat(opt.getAttribute('data-stock') || 0);
  var unite = opt.getAttribute('data-unite') || 'u';
  var qty   = parseFloat(document.getElementById('a-quantite').value) || 0;

  if (qty <= 0) { document.getElementById('sim-result').classList.remove('show'); return; }

  /*
    Convention API stock/ajustement :
      entree     → stock + qty
      sortie     → stock - qty  (API rejette si stock_apres < 0)
      ajustement → stock + qty  (delta positif = entrée, utiliser sortie pour retirer)
      inventaire → qty EST le nouveau stock total (mise à jour d'inventaire)
  */
  var stockApres;
  var type = _typeSelectionne;
  if (type === 'entree' || type === 'ajustement') {
    stockApres = stock + qty;
  } else if (type === 'sortie') {
    stockApres = stock - qty;
  } else if (type === 'inventaire') {
    stockApres = qty;  // la quantité saisie = nouveau stock réel
  } else {
    stockApres = stock + qty;
  }

  var diff = stockApres - stock;
  var diffCls = diff > 0 ? 'up' : (diff < 0 ? 'down' : 'same');
  var sign    = diff > 0 ? '+' : '';

  document.getElementById('sim-avant').textContent   = fmt(stock) + ' ' + unite;
  document.getElementById('sim-delta').className      = 'sim-val ' + diffCls;
  document.getElementById('sim-delta').textContent    = sign + fmt(diff) + ' ' + unite;
  document.getElementById('sim-apres').className      = 'sim-val ' + (stockApres < 0 ? 'down' : (stockApres > 0 ? 'up' : 'same'));
  document.getElementById('sim-apres').textContent    = fmt(stockApres) + ' ' + unite;
  document.getElementById('sim-result').classList.add('show');
}

/* ════════════════════════════════════════
   HISTORIQUE ARTICLE (colonne droite)
   GET /api/stock/mouvements/:id_article
════════════════════════════════════════ */
function loadHistorique(id_article) {
  document.getElementById('hist-container').innerHTML =
    '<div class="hist-empty"><i class="fas fa-spinner fa-spin"></i> Chargement…</div>';

  fetch(API + '/stock/mouvements/' + id_article).then(function(r){ return r.json(); }).then(function(resp) {
    var data = (resp.data || resp || []).slice(0, 10);  // afficher les 10 derniers
    if (!data.length) {
      document.getElementById('hist-container').innerHTML =
        '<div class="hist-empty">Aucun mouvement pour cet article</div>';
      return;
    }
    var html = '';
    data.forEach(function(m) {
      var cfg  = TYPE_CFG[m.type_mouvement] || { cls:'sb-muted', lbl:'?', ico:'fa-circle', qtyCls:'' };
      var diff = parseFloat(m.stock_apres) - parseFloat(m.stock_avant);
      var sign = diff >= 0 ? '+' : '';
      var col  = diff > 0 ? '#1a8a4a' : (diff < 0 ? '#c03030' : '#6a3fbf');

      html += '<div class="hist-item">'
        + '<div class="hist-ico ' + cfg.cls.replace('sb-','ico-') + '">'
        + '<i class="fas ' + cfg.ico + '"></i></div>'
        + '<div class="hist-body">'
        +   '<div class="hist-date">' + fmtDatetime(m.date_mouvement) + '</div>'
        +   '<div class="hist-type ' + cfg.qtyCls + '">' + cfg.lbl
        +     (m.utilisateur_nom ? ' — <span style="font-weight:400;color:var(--text3,#8FA4BF);">' + esc(m.utilisateur_nom) + '</span>' : '')
        +   '</div>'
        +   (m.motif ? '<div class="hist-detail">' + esc(truncate(m.motif, 40)) + '</div>' : '')
        + '</div>'
        + '<div class="hist-delta" style="color:' + col + ';">'
        +   sign + fmt(diff)
        + '</div>'
        + '</div>';
    });
    document.getElementById('hist-container').innerHTML = html;
  }).catch(function() {
    document.getElementById('hist-container').innerHTML =
      '<div class="hist-empty" style="color:#c03030;">Erreur de chargement</div>';
  });
}

/* ════════════════════════════════════════
   SAUVEGARDER AJUSTEMENT
   POST /api/stock/ajustement
   Body : { id_article, id_utilisateur, quantite,
            type_mouvement, motif }
   Réponse : { success, stock_avant, stock_apres, message }
════════════════════════════════════════ */
function saveAjustement() {
  var valid = true;

  var artSel = document.getElementById('a-article');
  if (!artSel.value) {
    artSel.classList.add('error');
    document.getElementById('e-article').classList.add('show');
    valid = false;
  } else {
    artSel.classList.remove('error');
    document.getElementById('e-article').classList.remove('show');
  }

  if (!_typeSelectionne) {
    document.getElementById('e-type').classList.add('show');
    valid = false;
  }

  var qtyEl = document.getElementById('a-quantite');
  var qty   = parseFloat(qtyEl.value);
  if (!qty || qty <= 0) {
    qtyEl.classList.add('error');
    document.getElementById('e-quantite').classList.add('show');
    valid = false;
  } else {
    qtyEl.classList.remove('error');
    document.getElementById('e-quantite').classList.remove('show');
  }

  var motifEl = document.getElementById('a-motif');
  if (!motifEl.value.trim()) {
    motifEl.classList.add('error');
    document.getElementById('e-motif').classList.add('show');
    valid = false;
  } else {
    motifEl.classList.remove('error');
    document.getElementById('e-motif').classList.remove('show');
  }

  if (!valid) return;

  var btn = document.getElementById('btn-adj');
  btn.disabled = true;
  btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Traitement…';

  var body = {
    id_article:     parseInt(artSel.value),
    id_utilisateur: UID,
    quantite:       qty,
    type_mouvement: _typeSelectionne,
    motif:          motifEl.value.trim()
  };

  fetch(API + '/stock/ajustement', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(body)
  }).then(function(r) {
    return r.json().then(function(d) { return { ok: r.ok, d: d }; });
  }).then(function(res) {
    if (!res.ok) throw new Error(res.d.message || 'Erreur serveur');

    /* Mettre à jour le stock dans _articles localement */
    var idArt = parseInt(artSel.value);
    for (var i = 0; i < _articles.length; i++) {
      if (_articles[i].id_article == idArt) {
        _articles[i].stock_actuel = res.d.stock_apres;
        break;
      }
    }
    /* Mettre à jour le data-stock de l'option */
    var opt = artSel.options[artSel.selectedIndex];
    if (opt) opt.setAttribute('data-stock', res.d.stock_apres);

    showSuccess('Ajustement effectué. Stock : '
      + fmt(res.d.stock_avant) + ' → ' + fmt(res.d.stock_apres));

    /* Réinitialiser formulaire */
    qtyEl.value    = '';
    motifEl.value  = '';
    document.getElementById('sim-result').classList.remove('show');
    document.getElementById('art-preview').classList.remove('show');
    artSel.value = '';
    document.getElementById('a-unite-disp').value = '';
    document.getElementById('hist-container').innerHTML =
      '<div class="hist-empty"><i class="fas fa-chart-line" style="font-size:1.4rem;opacity:.3;display:block;margin-bottom:8px;"></i>Sélectionnez un article</div>';

    /* Recharger les mouvements pour que la liste soit à jour */
    return load();
  }).catch(function(e) {
    showError('Erreur : ' + (e.message || ''));
  }).finally(function() {
    btn.disabled = false;
    btn.innerHTML = '<i class="fas fa-check"></i> Valider l\'ajustement';
  });
}

load();
</script>

<?php require_once 'includes/footer.php'; ?>