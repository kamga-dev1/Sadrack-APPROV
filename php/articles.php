<?php
require_once 'includes/config.php';
if(empty($_SESSION['user'])){header('Location: login.php');exit;}
?>
<?php require_once 'includes/header.php'; ?>
<?php require_once 'includes/sidebar.php'; ?>
<script>document.getElementById('nav-page-title').textContent='Articles';</script>

<style>
.ph{display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:14px;margin-bottom:22px;}
.ph-title{font-size:1.3rem;font-weight:700;color:var(--text);letter-spacing:-.3px;}
.ph-sub{font-size:.82rem;color:var(--text3);margin-top:4px;}
.ph-right{display:flex;align-items:center;gap:8px;}

/* KPI strip */
.kstrip{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:20px;}
@media(max-width:900px){.kstrip{grid-template-columns:repeat(2,1fr);}}
@media(max-width:480px){.kstrip{grid-template-columns:1fr;}}
.ks{background:var(--surface);border:1px solid var(--border);border-radius:11px;padding:14px 16px;display:flex;align-items:center;gap:12px;box-shadow:var(--sh1);}
.ks-ico{width:38px;height:38px;border-radius:9px;display:flex;align-items:center;justify-content:center;font-size:.9rem;flex-shrink:0;}
.ks-body{}
.ks-n{font-size:1.55rem;font-weight:800;color:var(--text);letter-spacing:-1px;line-height:1;}
.ks-l{font-size:.73rem;color:var(--text3);margin-top:3px;}

/* Toolbar */
.toolbar{display:flex;align-items:center;gap:10px;flex-wrap:wrap;background:var(--surface);border:1px solid var(--border);border-radius:11px;padding:12px 16px;margin-bottom:16px;box-shadow:var(--sh1);}
.tsearch{display:flex;align-items:center;gap:9px;background:var(--canvas);border:1px solid var(--border);border-radius:8px;padding:0 12px;flex:1;min-width:220px;max-width:340px;transition:border-color .18s;}
.tsearch:focus-within{border-color:var(--gold);}
.tsearch i{color:var(--text4);font-size:.82rem;}
.tsearch input{border:none;background:transparent;outline:none;font-family:inherit;font-size:.88rem;color:var(--text);padding:8px 0;width:100%;}
.tsearch input::placeholder{color:var(--text4);}
.tsel{display:flex;align-items:center;gap:7px;padding:7px 12px;border-radius:8px;background:var(--canvas);border:1px solid var(--border);font-size:.82rem;color:var(--text2);font-family:inherit;}
.tsel select{border:none;background:transparent;outline:none;font-family:inherit;font-size:.82rem;color:var(--text2);cursor:pointer;}
.tb-sep{width:1px;height:24px;background:var(--border);margin:0 2px;}
.tb-count{font-size:.78rem;color:var(--text4);white-space:nowrap;margin-left:auto;}

/* Table */
.tcard{background:var(--surface);border:1px solid var(--border);border-radius:12px;box-shadow:var(--sh1);overflow:hidden;}
.etbl{width:100%;border-collapse:collapse;table-layout:fixed;}
.etbl thead th{font-size:.65rem;font-weight:800;text-transform:uppercase;letter-spacing:1.2px;color:var(--text4);padding:10px 14px;border-bottom:2px solid var(--border);background:var(--surface2);white-space:nowrap;text-align:left;cursor:pointer;user-select:none;}
.etbl thead th:hover{color:var(--text2);}
.etbl thead th.sorted{color:var(--gold-dk);}
.sort-ico{margin-left:4px;opacity:.35;font-size:.58rem;}
.sorted .sort-ico{opacity:1;color:var(--gold-dk);}

/* Largeurs colonnes */
.etbl thead th:nth-child(1){width:21%;}  /* Article */
.etbl thead th:nth-child(2){width:10%;}  /* Référence */
.etbl thead th:nth-child(3){width:13%;}  /* Catégorie */
.etbl thead th:nth-child(4){width:14%;}  /* Fournisseur */
.etbl thead th:nth-child(5){width:9%;text-align:right;}   /* Prix achat */
.etbl thead th:nth-child(6){width:13%;}  /* Stock */
.etbl thead th:nth-child(7){width:9%;text-align:center;}  /* Délai appro */
.etbl thead th:nth-child(8){width:7%;}   /* Statut */
.etbl thead th:nth-child(9){width:7%;text-align:right;}   /* Actions */

.etbl tbody td{padding:11px 14px;border-bottom:1px solid var(--border);font-size:.86rem;color:var(--text2);vertical-align:middle;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
.etbl tbody td:nth-child(5){text-align:right;}
.etbl tbody td:nth-child(7){text-align:center;}
.etbl tbody tr:last-child td{border-bottom:none;}
.etbl tbody tr:hover{background:var(--surface2);}

.c-main{font-weight:700;color:var(--text);font-size:.88rem;}
.c-sub{font-size:.7rem;color:var(--text4);margin-top:1px;}
.c-ref{font-family:'DM Mono',monospace;font-size:.8rem;color:var(--text3);}
.c-price{font-family:'DM Mono',monospace;font-weight:700;color:var(--gold-dk);font-size:.86rem;}

/* Stock indicator */
.stock-wrap{display:flex;align-items:center;gap:8px;}
.stock-bar{height:5px;border-radius:3px;background:var(--border);flex:1;max-width:60px;overflow:hidden;}
.stock-fill{height:100%;border-radius:3px;}
.stock-val{font-family:'DM Mono',monospace;font-size:.82rem;font-weight:700;white-space:nowrap;}

/* Alert badge sur article */
.alert-dot{display:inline-block;width:7px;height:7px;border-radius:50%;background:var(--crit);margin-left:5px;vertical-align:middle;animation:pulse 2s ease-in-out infinite;}
@keyframes pulse{0%,100%{opacity:1;}50%{opacity:.4;}}

.c-actions{display:flex;align-items:center;gap:5px;justify-content:flex-end;}
.act-btn{width:30px;height:30px;border-radius:7px;display:flex;align-items:center;justify-content:center;font-size:.72rem;cursor:pointer;border:1px solid var(--border);background:var(--canvas);color:var(--text3);transition:all .15s;}
.act-btn:hover.edit{background:var(--info-bg);border-color:var(--info-bd);color:var(--info);}
.act-btn:hover.deact{background:var(--warn-bg);border-color:var(--warn-bd);color:var(--warn);}

/* Pagination */
.pager{display:flex;align-items:center;justify-content:space-between;padding:12px 18px;border-top:1px solid var(--border);flex-wrap:wrap;gap:10px;}
.pager-info{font-size:.78rem;color:var(--text4);}
.pager-btns{display:flex;align-items:center;gap:4px;}
.pg-btn{min-width:32px;height:32px;border-radius:7px;display:flex;align-items:center;justify-content:center;font-size:.78rem;font-weight:600;cursor:pointer;border:1px solid var(--border);background:var(--canvas);color:var(--text2);transition:all .15s;padding:0 6px;}
.pg-btn:hover{background:var(--surface);}
.pg-btn.on{background:var(--ink2);color:#fff;border-color:var(--ink2);}
.pg-btn:disabled{opacity:.38;cursor:default;}

/* Modals */
.modal-bg{position:fixed;inset:0;z-index:900;background:rgba(5,8,15,.55);backdrop-filter:blur(4px);display:none;align-items:center;justify-content:center;padding:20px;}
.modal-bg.open{display:flex;animation:bgIn .2s ease;}
@keyframes bgIn{from{opacity:0;}to{opacity:1;}}
.modal{background:var(--surface);border:1px solid var(--border);border-radius:16px;width:100%;max-width:580px;box-shadow:var(--sh3);animation:mIn .22s ease;max-height:90vh;overflow-y:auto;}
@keyframes mIn{from{opacity:0;transform:translateY(-12px) scale(.98);}to{opacity:1;transform:none;}}
.modal-head{display:flex;align-items:center;justify-content:space-between;padding:18px 22px;border-bottom:1px solid var(--border);position:sticky;top:0;background:var(--surface);z-index:1;}
.modal-title{display:flex;align-items:center;gap:10px;font-size:.98rem;font-weight:700;color:var(--text);}
.modal-ico{width:32px;height:32px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:.82rem;}
.modal-close{width:30px;height:30px;border-radius:7px;display:flex;align-items:center;justify-content:center;font-size:.8rem;cursor:pointer;background:transparent;border:1px solid transparent;color:var(--text3);transition:all .15s;}
.modal-close:hover{background:var(--crit-bg);border-color:var(--crit-bd);color:var(--crit);}
.modal-body{padding:22px;}
.modal-footer{display:flex;align-items:center;justify-content:flex-end;gap:8px;padding:16px 22px;border-top:1px solid var(--border);background:var(--surface2);}

/* Formulaire */
.frow{display:grid;gap:14px;margin-bottom:14px;}
.frow-2{grid-template-columns:1fr 1fr;}
.frow-3{grid-template-columns:1fr 1fr 1fr;}
@media(max-width:500px){.frow-2,.frow-3{grid-template-columns:1fr;}}
.fgroup{display:flex;flex-direction:column;gap:5px;}
.flabel{font-size:.75rem;font-weight:700;color:var(--text2);}
.flabel span{color:var(--crit);margin-left:2px;}
.finput,.fselect,.ftextarea{width:100%;padding:9px 12px;border-radius:8px;border:1.5px solid var(--border);background:var(--canvas);color:var(--text);font-family:inherit;font-size:.88rem;transition:border-color .18s;outline:none;}
.finput:focus,.fselect:focus,.ftextarea:focus{border-color:var(--gold);background:var(--surface);box-shadow:0 0 0 3px rgba(226,168,75,.1);}
.finput.error{border-color:var(--crit);}
.finput:read-only{background:var(--surface2);color:var(--text3);cursor:default;}
.ftextarea{resize:vertical;min-height:68px;}
.ferr{font-size:.72rem;color:var(--crit);margin-top:3px;display:none;}
.ferr.show{display:block;}
.fhint{font-size:.7rem;color:var(--text4);margin-top:3px;}

/* Section séparateur dans modal */
.fsep{border:none;border-top:1px solid var(--border);margin:4px 0 16px;}
.fsec-title{font-size:.68rem;font-weight:800;text-transform:uppercase;letter-spacing:1.5px;color:var(--text4);margin-bottom:12px;}

/* Confirm modal */
.cmodal{max-width:400px;}
.cbody{padding:26px 22px;text-align:center;}
.cico{width:52px;height:52px;border-radius:50%;background:var(--warn-bg);display:flex;align-items:center;justify-content:center;font-size:1.3rem;color:var(--warn);margin:0 auto 14px;}
.ctitle{font-size:1rem;font-weight:700;color:var(--text);margin-bottom:7px;}
.ctext{font-size:.84rem;color:var(--text3);line-height:1.6;}
.cname{font-weight:700;color:var(--text);}

/* Empty */
.empty-state{padding:52px 20px;text-align:center;}
.empty-ico{width:56px;height:56px;border-radius:14px;background:var(--canvas);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;font-size:1.4rem;color:var(--text4);margin:0 auto 14px;}
.empty-title{font-size:.95rem;font-weight:700;color:var(--text);margin-bottom:6px;}
.empty-text{font-size:.82rem;color:var(--text3);}
</style>

<!-- En-tête page -->
<div class="ph">
  <div>
    <div class="ph-title"><i class="fas fa-cube" style="color:var(--gold);margin-right:9px;"></i>Articles</div>
    <div class="ph-sub">Catalogue des articles, stocks et seuils d'approvisionnement</div>
  </div>
  <div class="ph-right">
    <button class="btn-secondary" onclick="exportCSV()"><i class="fas fa-file-pdf"></i>Exporter PDF</button>
    <button class="btn-gold" onclick="openCreate()"><i class="fas fa-plus"></i>Nouvel article</button>
  </div>
</div>

<!-- KPI strip -->
<div class="kstrip" id="kstrip">
  <div class="ks"><div class="ks-ico" style="background:var(--info-bg);color:var(--info);"><i class="fas fa-cube"></i></div><div class="ks-body"><div class="ks-n" id="ks-total">—</div><div class="ks-l">Articles actifs</div></div></div>
  <div class="ks"><div class="ks-ico" style="background:var(--crit-bg);color:var(--crit);"><i class="fas fa-triangle-exclamation"></i></div><div class="ks-body"><div class="ks-n" id="ks-alerte">—</div><div class="ks-l">En alerte stock</div></div></div>
  <div class="ks"><div class="ks-ico" style="background:var(--ok-bg);color:var(--ok);"><i class="fas fa-warehouse"></i></div><div class="ks-body"><div class="ks-n" id="ks-valeur">—</div><div class="ks-l">Valeur stock (FCFA)</div></div></div>
  <div class="ks"><div class="ks-ico" style="background:var(--warn-bg);color:var(--warn);"><i class="fas fa-tags"></i></div><div class="ks-body"><div class="ks-n" id="ks-cat">—</div><div class="ks-l">Catégories</div></div></div>
</div>

<!-- Toolbar -->
<div class="toolbar">
  <div class="tsearch">
    <i class="fas fa-search"></i>
    <input type="text" id="search-input" placeholder="Rechercher par nom, référence, fournisseur…" oninput="filterTable()">
  </div>
  <div class="tsel">
    <i class="fas fa-filter" style="color:var(--text4);font-size:.76rem;"></i>
    <select id="filter-cat" onchange="filterTable()"><option value="">Toutes catégories</option></select>
  </div>
  <div class="tsel">
    <i class="fas fa-filter" style="color:var(--text4);font-size:.76rem;"></i>
    <select id="filter-statut" onchange="filterTable()">
      <option value="">Tous les statuts</option>
      <option value="actif">Actif</option>
      <option value="inactif">Inactif</option>
    </select>
  </div>
  <div class="tsel">
    <i class="fas fa-exclamation-triangle" style="color:var(--text4);font-size:.76rem;"></i>
    <select id="filter-alerte" onchange="filterTable()">
      <option value="">Tout le stock</option>
      <option value="1">En alerte uniquement</option>
    </select>
  </div>
  <div class="tb-sep"></div>
  <div class="tb-count" id="tb-count">Chargement…</div>
</div>

<!-- Table -->
<div class="tcard">
  <div style="overflow-x:auto;">
    <table class="etbl">
      <thead>
        <tr>
          <th onclick="sortBy('nom')">Article <i class="fas fa-sort sort-ico"></i></th>
          <th onclick="sortBy('reference')">Référence <i class="fas fa-sort sort-ico"></i></th>
          <th onclick="sortBy('categorie_nom')">Catégorie <i class="fas fa-sort sort-ico"></i></th>
          <th onclick="sortBy('fournisseur_nom')">Fournisseur <i class="fas fa-sort sort-ico"></i></th>
          <th onclick="sortBy('prix_achat')">Prix achat <i class="fas fa-sort sort-ico"></i></th>
          <th onclick="sortBy('stock_actuel')">Stock <i class="fas fa-sort sort-ico"></i></th>
          <th onclick="sortBy('delai_approvisionnement')">Délai appro. <i class="fas fa-sort sort-ico"></i></th>
          <th onclick="sortBy('statut')">Statut <i class="fas fa-sort sort-ico"></i></th>
          <th style="text-align:right;">Actions</th>
        </tr>
      </thead>
      <tbody id="tbl-body">
        <tr><td colspan="9"><div style="padding:24px;text-align:center;color:var(--text4);"><i class="fas fa-spinner fa-spin"></i> Chargement…</div></td></tr>
      </tbody>
    </table>
  </div>
  <div class="pager" id="pager" style="display:none;">
    <div class="pager-info" id="pager-info"></div>
    <div class="pager-btns" id="pager-btns"></div>
  </div>
</div>

<!-- ═══ MODAL FORMULAIRE ═══ -->
<div class="modal-bg" id="modal-form">
  <div class="modal">
    <div class="modal-head">
      <div class="modal-title">
        <div class="modal-ico" style="background:rgba(226,168,75,.12);color:var(--gold-dk);"><i class="fas fa-cube"></i></div>
        <span id="modal-form-title">Nouvel article</span>
      </div>
      <button class="modal-close" onclick="closeModal('modal-form')"><i class="fas fa-xmark"></i></button>
    </div>
    <div class="modal-body">

      <!-- Identification -->
      <div class="fsec-title">Identification</div>
      <div class="frow frow-2">
        <div class="fgroup">
          <label class="flabel">Nom de l'article <span>*</span></label>
          <input type="text" class="finput" id="f-nom" placeholder="Ex: Câble réseau Cat6">
          <div class="ferr" id="e-nom">Champ requis</div>
        </div>
        <div class="fgroup">
          <label class="flabel">Référence <span>*</span></label>
          <input type="text" class="finput" id="f-reference" placeholder="Ex: CAB-RES-001" style="font-family:'DM Mono',monospace;">
          <div class="ferr" id="e-reference">Champ requis</div>
          <div class="fhint" id="ref-hint" style="display:none;">Non modifiable après création</div>
        </div>
      </div>
      <div class="frow frow-2">
        <div class="fgroup">
          <label class="flabel">Catégorie</label>
          <select class="fselect" id="f-categorie"><option value="">— Aucune —</option></select>
        </div>
        <div class="fgroup">
          <label class="flabel">Fournisseur principal</label>
          <select class="fselect" id="f-fournisseur"><option value="">— Aucun —</option></select>
        </div>
      </div>
      <div class="frow">
        <div class="fgroup">
          <label class="flabel">Description</label>
          <textarea class="ftextarea" id="f-description" placeholder="Description détaillée de l'article…"></textarea>
        </div>
      </div>

      <hr class="fsep">

      <!-- Unité & Prix -->
      <div class="fsec-title">Unité & Prix</div>
      <div class="frow frow-3">
        <div class="fgroup">
          <label class="flabel">Unité <span>*</span></label>
          <input type="text" class="finput" id="f-unite" placeholder="Ex: pièce, kg, m…">
          <div class="ferr" id="e-unite">Champ requis</div>
        </div>
        <div class="fgroup">
          <label class="flabel">Prix d'achat (FCFA)</label>
          <input type="number" class="finput" id="f-prix" placeholder="0" min="0" step="1" style="font-family:'DM Mono',monospace;">
        </div>
        <div class="fgroup">
          <label class="flabel">Délai appro. (jours)</label>
          <input type="number" class="finput" id="f-delai" placeholder="Ex: 7" min="0">
        </div>
      </div>

      <hr class="fsep">

      <!-- Stock -->
      <div class="fsec-title">Stock</div>
      <div class="frow frow-2" id="row-stock-actuel">
        <div class="fgroup">
          <label class="flabel">Stock actuel</label>
          <input type="number" class="finput" id="f-stock" placeholder="0" min="0" style="font-family:'DM Mono',monospace;">
          <div class="fhint">Stock initial à la création uniquement</div>
        </div>
        <div class="fgroup">
          <label class="flabel">Seuil minimum d'alerte</label>
          <input type="number" class="finput" id="f-seuil" placeholder="0" min="0" style="font-family:'DM Mono',monospace;">
        </div>
      </div>

      <!-- Statut visible uniquement en édition -->
      <div id="row-statut" style="display:none;">
        <hr class="fsep">
        <div class="fsec-title">Statut</div>
        <div class="frow" style="max-width:50%;">
          <div class="fgroup">
            <label class="flabel">Statut de l'article</label>
            <select class="fselect" id="f-statut">
              <option value="actif">Actif</option>
              <option value="inactif">Inactif</option>
            </select>
          </div>
        </div>
      </div>

    </div>
    <div class="modal-footer">
      <button class="btn-secondary" onclick="closeModal('modal-form')">Annuler</button>
      <button class="btn-gold" id="btn-save" onclick="saveArticle()">
        <i class="fas fa-floppy-disk"></i>Enregistrer
      </button>
    </div>
  </div>
</div>

<!-- ═══ MODAL DÉSACTIVATION ═══ -->
<div class="modal-bg" id="modal-deact">
  <div class="modal cmodal">
    <div class="modal-head">
      <div class="modal-title" style="color:var(--warn);">
        <div class="modal-ico" style="background:var(--warn-bg);color:var(--warn);"><i class="fas fa-ban"></i></div>
        Désactiver l'article
      </div>
      <button class="modal-close" onclick="closeModal('modal-deact')"><i class="fas fa-xmark"></i></button>
    </div>
    <div class="cbody">
      <div class="cico"><i class="fas fa-ban"></i></div>
      <div class="ctitle">Confirmer la désactivation</div>
      <div class="ctext">
        L'article <span class="cname" id="deact-name"></span> sera passé en statut <strong>Inactif</strong>
        et n'apparaîtra plus dans les nouvelles commandes.<br><br>
        Vous pourrez le réactiver en modifiant son statut.
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn-secondary" onclick="closeModal('modal-deact')">Annuler</button>
      <button class="btn-secondary" id="btn-deact" onclick="confirmDeact()" style="background:var(--warn);color:#fff;border-color:var(--warn);">
        <i class="fas fa-ban"></i>Désactiver
      </button>
    </div>
  </div>
</div>

<script>
const API='http://localhost:3000/api';
let _all=[],_shown=[],_fournisseurs=[],_categories=[];
let _page=1; const PER=15;
let _editId=null,_deactId=null,_sortCol='nom',_sortAsc=true;

/* ── Chargement initial ── */
async function load(){
  try{
    // Charger articles + fournisseurs + catégories en parallèle
    const [ra,rf,rc]=await Promise.all([
      fetch(`${API}/articles`).then(r=>r.json()),
      fetch(`${API}/fournisseurs`).then(r=>r.json()),
      fetch(`${API}/categories`).then(r=>r.json()).catch(()=>({data:[]}))
    ]);
    _all        = ra.data||ra||[];
    _fournisseurs = rf.data||rf||[];
    _categories   = rc.data||rc||[];
    populateSelects();
    computeKPIs();
    filterTable();
  }catch(e){
    document.getElementById('tbl-body').innerHTML=
      `<tr><td colspan="9"><div class="empty-state">
        <div class="empty-ico"><i class="fas fa-wifi-slash"></i></div>
        <div class="empty-title">Erreur de connexion</div>
        <div class="empty-text">Impossible de joindre l'API sur le port 3000.</div>
      </div></td></tr>`;
  }
}

/* ── Peuplement selects toolbar + formulaire ── */
function populateSelects(){
  // Filtre catégorie toolbar
  const fc=document.getElementById('filter-cat');
  const cats=[...new Set(_all.map(a=>a.categorie_nom).filter(Boolean))].sort();
  fc.innerHTML='<option value="">Toutes catégories</option>'+cats.map(c=>`<option value="${esc(c)}">${esc(c)}</option>`).join('');

  // Select fournisseur formulaire
  const sf=document.getElementById('f-fournisseur');
  sf.innerHTML='<option value="">— Aucun —</option>'+_fournisseurs.filter(f=>f.statut==='actif').map(f=>`<option value="${f.id_fournisseur}">${esc(f.nom)}</option>`).join('');

  // Select catégorie formulaire
  const sc=document.getElementById('f-categorie');
  sc.innerHTML='<option value="">— Aucune —</option>'+_categories.map(c=>`<option value="${c.id_categorie}">${esc(c.nom)}</option>`).join('');
}

/* ── KPIs ── */
function computeKPIs(){
  const actifs=_all.filter(a=>a.statut==='actif');
  const alertes=_all.filter(a=>parseFloat(a.stock_actuel||0)<parseFloat(a.seuil_minimum||0));
  const valeur=_all.reduce((s,a)=>s+(parseFloat(a.prix_achat||0)*parseFloat(a.stock_actuel||0)),0);
  const nbCats=new Set(_all.map(a=>a.id_categorie).filter(Boolean)).size;
  document.getElementById('ks-total').textContent=actifs.length;
  document.getElementById('ks-alerte').textContent=alertes.length;
  document.getElementById('ks-valeur').textContent=fmt(Math.round(valeur));
  document.getElementById('ks-cat').textContent=nbCats;
}

/* ── Filtre & Tri ── */
function filterTable(){
  const q=document.getElementById('search-input').value.trim().toLowerCase();
  const cat=document.getElementById('filter-cat').value;
  const st=document.getElementById('filter-statut').value;
  const al=document.getElementById('filter-alerte').value;

  _shown=_all.filter(a=>{
    const mq=!q||(a.nom||'').toLowerCase().includes(q)||(a.reference||'').toLowerCase().includes(q)||(a.fournisseur_nom||'').toLowerCase().includes(q)||(a.categorie_nom||'').toLowerCase().includes(q);
    const mc=!cat||(a.categorie_nom||'')=== cat;
    const ms=!st||a.statut===st;
    const ma=!al||(parseFloat(a.stock_actuel||0)<parseFloat(a.seuil_minimum||0));
    return mq&&mc&&ms&&ma;
  });

  _shown.sort((a,b)=>{
    let va=a[_sortCol]??(_sortAsc?'\uFFFF':''),vb=b[_sortCol]??(_sortAsc?'\uFFFF':'');
    if(typeof va==='string'){va=va.toLowerCase();vb=(vb+'').toLowerCase();}
    else{va=parseFloat(va)||0;vb=parseFloat(vb)||0;}
    return _sortAsc?(va>vb?1:va<vb?-1:0):(va<vb?1:va>vb?-1:0);
  });
  _page=1;render();
}

function sortBy(col){
  _sortAsc=(_sortCol===col)?!_sortAsc:true;_sortCol=col;
  document.querySelectorAll('.etbl thead th').forEach(th=>{th.classList.remove('sorted');const ic=th.querySelector('.sort-ico');if(ic)ic.className='fas fa-sort sort-ico';});
  const cols=['nom','reference','categorie_nom','fournisseur_nom','prix_achat','stock_actuel','delai_approvisionnement','statut'];
  const idx=cols.indexOf(col);
  if(idx>=0){
    const ths=document.querySelectorAll('.etbl thead th');
    ths[idx].classList.add('sorted');
    const ic=ths[idx].querySelector('.sort-ico');
    if(ic)ic.className=`fas fa-sort-${_sortAsc?'up':'down'} sort-ico`;
  }
  filterTable();
}

/* ── Rendu table ── */
function render(){
  const total=_shown.length,pages=Math.max(1,Math.ceil(total/PER));
  if(_page>pages)_page=pages;
  document.getElementById('tb-count').textContent=`${total} article${total!==1?'s':''} trouvé${total!==1?'s':''}`;

  const slice=_shown.slice((_page-1)*PER,_page*PER);
  if(!slice.length){
    document.getElementById('tbl-body').innerHTML=
      `<tr><td colspan="9"><div class="empty-state">
        <div class="empty-ico"><i class="fas fa-cube"></i></div>
        <div class="empty-title">Aucun article trouvé</div>
        <div class="empty-text">${_all.length===0?'Commencez par créer un article.':'Aucun résultat pour cette recherche.'}</div>
      </div></td></tr>`;
    document.getElementById('pager').style.display='none';
    return;
  }

  document.getElementById('tbl-body').innerHTML=slice.map(a=>{
    const stock=parseFloat(a.stock_actuel||0);
    const seuil=parseFloat(a.seuil_minimum||0);
    const enAlerte=stock<seuil;
    const pct=seuil>0?Math.min(100,Math.round((stock/seuil)*100)):100;
    const fillColor=enAlerte?'var(--crit)':pct<150?'var(--warn)':'var(--ok)';
    const stockColor=enAlerte?'color:var(--crit)':'';
    return`<tr>
      <td><div class="c-main">${esc(a.nom)}${enAlerte?'<span class="alert-dot" title="Stock sous seuil"></span>':''}</div><div class="c-sub">${esc(a.unite||'')}</div></td>
      <td><span class="c-ref">${esc(a.reference||'—')}</span></td>
      <td>${esc(a.categorie_nom||'—')}</td>
      <td style="font-size:.82rem;">${esc(a.fournisseur_nom||'—')}</td>
      <td><span class="c-price">${fmt(a.prix_achat||0)}</span></td>
      <td>
        <div class="stock-wrap">
          <div class="stock-bar"><div class="stock-fill" style="width:${pct}%;background:${fillColor};"></div></div>
          <span class="stock-val" style="${stockColor}">${fmt(stock)}</span>
        </div>
        <div style="font-size:.68rem;color:var(--text4);margin-top:2px;">seuil: ${fmt(seuil)}</div>
      </td>
      <td>${a.delai_approvisionnement!=null?`<span class="c-ref">${a.delai_approvisionnement}j</span>`:'—'}</td>
      <td>${badgeStatut(a.statut)}</td>
      <td>
        <div class="c-actions">
          <div class="act-btn edit" title="Modifier" onclick="openEdit(${a.id_article})"><i class="fas fa-pen"></i></div>
          <div class="act-btn deact" title="Désactiver" onclick="openDeact(${a.id_article},'${esc(a.nom)}')"><i class="fas fa-ban"></i></div>
        </div>
      </td>
    </tr>`;
  }).join('');

  // Pagination
  const pi=document.getElementById('pager'),pb=document.getElementById('pager-btns');
  pi.style.display='flex';
  document.getElementById('pager-info').textContent=`${(_page-1)*PER+1}–${Math.min(_page*PER,total)} sur ${total}`;
  let h=`<button class="pg-btn" onclick="goPage(${_page-1})" ${_page===1?'disabled':''}><i class="fas fa-chevron-left"></i></button>`;
  for(let p=1;p<=pages;p++){
    if(pages>7&&p>2&&p<pages-1&&Math.abs(p-_page)>1){if(p===3||p===pages-2)h+=`<span style="padding:0 4px;color:var(--text4);">…</span>`;continue;}
    h+=`<button class="pg-btn ${p===_page?'on':''}" onclick="goPage(${p})">${p}</button>`;
  }
  h+=`<button class="pg-btn" onclick="goPage(${_page+1})" ${_page===pages?'disabled':''}><i class="fas fa-chevron-right"></i></button>`;
  pb.innerHTML=h;
}

function goPage(p){const pages=Math.ceil(_shown.length/PER);if(p<1||p>pages)return;_page=p;render();window.scrollTo({top:0,behavior:'smooth'});}

/* ── Formulaire ── */
function openCreate(){
  _editId=null;
  document.getElementById('modal-form-title').textContent='Nouvel article';
  clearForm();
  // En création : référence modifiable, stock actuel visible, statut caché
  document.getElementById('f-reference').readOnly=false;
  document.getElementById('ref-hint').style.display='none';
  document.getElementById('row-stock-actuel').style.display='grid';
  document.getElementById('row-statut').style.display='none';
  openModal('modal-form');
}

function openEdit(id){
  _editId=id;
  document.getElementById('modal-form-title').textContent='Modifier l\'article';
  const a=_all.find(x=>x.id_article===id);
  if(!a)return;
  document.getElementById('f-nom').value        = a.nom||'';
  document.getElementById('f-reference').value  = a.reference||'';
  document.getElementById('f-description').value= a.description||'';
  document.getElementById('f-unite').value      = a.unite||'';
  document.getElementById('f-prix').value       = a.prix_achat!=null?a.prix_achat:'';
  document.getElementById('f-seuil').value      = a.seuil_minimum!=null?a.seuil_minimum:'';
  document.getElementById('f-delai').value      = a.delai_approvisionnement!=null?a.delai_approvisionnement:'';
  document.getElementById('f-statut').value     = a.statut||'actif';
  document.getElementById('f-fournisseur').value= a.id_fournisseur||'';
  document.getElementById('f-categorie').value  = a.id_categorie||'';
  // En édition : référence non modifiable, stock actuel caché (géré par réceptions), statut visible
  document.getElementById('f-reference').readOnly=true;
  document.getElementById('ref-hint').style.display='block';
  document.getElementById('row-stock-actuel').style.display='none';
  document.getElementById('row-statut').style.display='block';
  openModal('modal-form');
}

async function saveArticle(){
  let valid=true;
  ['nom','reference','unite'].forEach(k=>{
    // En édition, référence non requise (read-only)
    if(k==='reference'&&_editId)return;
    const el=document.getElementById(`f-${k}`),err=document.getElementById(`e-${k}`);
    if(!el||!el.value.trim()){if(el)el.classList.add('error');if(err)err.classList.add('show');valid=false;}
    else{el.classList.remove('error');if(err)err.classList.remove('show');}
  });
  if(!valid)return;

  const btn=document.getElementById('btn-save');
  btn.disabled=true;btn.innerHTML='<i class="fas fa-spinner fa-spin"></i> Enregistrement…';

  try{
    let body,url,method;
    if(!_editId){
      // POST — tous les champs
      body={
        nom:                    document.getElementById('f-nom').value.trim(),
        reference:              document.getElementById('f-reference').value.trim(),
        description:            document.getElementById('f-description').value.trim()||undefined,
        unite:                  document.getElementById('f-unite').value.trim(),
        prix_achat:             document.getElementById('f-prix').value!==''?parseFloat(document.getElementById('f-prix').value):undefined,
        stock_actuel:           document.getElementById('f-stock').value!==''?parseFloat(document.getElementById('f-stock').value):0,
        seuil_minimum:          document.getElementById('f-seuil').value!==''?parseFloat(document.getElementById('f-seuil').value):0,
        delai_approvisionnement:document.getElementById('f-delai').value!==''?parseInt(document.getElementById('f-delai').value):undefined,
        id_fournisseur:         document.getElementById('f-fournisseur').value||undefined,
        id_categorie:           document.getElementById('f-categorie').value||undefined,
      };
      url=`${API}/articles`;method='POST';
    }else{
      // PUT — uniquement champs modifiables
      body={
        nom:                    document.getElementById('f-nom').value.trim(),
        description:            document.getElementById('f-description').value.trim()||undefined,
        unite:                  document.getElementById('f-unite').value.trim(),
        prix_achat:             document.getElementById('f-prix').value!==''?parseFloat(document.getElementById('f-prix').value):undefined,
        seuil_minimum:          document.getElementById('f-seuil').value!==''?parseFloat(document.getElementById('f-seuil').value):0,
        delai_approvisionnement:document.getElementById('f-delai').value!==''?parseInt(document.getElementById('f-delai').value):undefined,
        statut:                 document.getElementById('f-statut').value,
      };
      url=`${API}/articles/${_editId}`;method='PUT';
    }
    const r=await fetch(url,{method,headers:{'Content-Type':'application/json'},body:JSON.stringify(body)});
    if(!r.ok)throw new Error();
    closeModal('modal-form');
    showSuccess(_editId?'Article modifié avec succès.':'Article créé avec succès.');
    await load();
  }catch(e){showError('Erreur lors de l\'enregistrement.');}
  finally{btn.disabled=false;btn.innerHTML='<i class="fas fa-floppy-disk"></i>Enregistrer';}
}

/* ── Désactivation ── */
function openDeact(id,nom){_deactId=id;document.getElementById('deact-name').textContent=nom;openModal('modal-deact');}

async function confirmDeact(){
  const btn=document.getElementById('btn-deact');
  btn.disabled=true;btn.innerHTML='<i class="fas fa-spinner fa-spin"></i> En cours…';
  try{
    const r=await fetch(`${API}/articles/${_deactId}`,{method:'DELETE'});
    if(!r.ok)throw new Error();
    closeModal('modal-deact');
    showSuccess('Article désactivé.');
    await load();
  }catch(e){showError('Impossible de désactiver cet article.');closeModal('modal-deact');}
  finally{btn.disabled=false;btn.innerHTML='<i class="fas fa-ban"></i>Désactiver';}
}

/* ── Export PDF ── */
function exportCSV(){
  if(!_shown.length)return showError('Aucune donnée à exporter.');

  const date = new Date().toLocaleDateString('fr-FR',{day:'2-digit',month:'long',year:'numeric'});
  const actifs  = _shown.filter(a=>a.statut==='actif').length;
  const alertes = _shown.filter(a=>parseFloat(a.stock_actuel||0)<parseFloat(a.seuil_minimum||0)).length;

  const rows = _shown.map(a=>{
    const stock = parseFloat(a.stock_actuel||0);
    const seuil = parseFloat(a.seuil_minimum||0);
    const enAlerte = stock < seuil;
    return `<tr style="${enAlerte?'background:#fceaea;':''}">
      <td>${esc(a.nom)}</td>
      <td style="font-family:monospace;font-size:11px;">${esc(a.reference||'—')}</td>
      <td>${esc(a.categorie_nom||'—')}</td>
      <td>${esc(a.fournisseur_nom||'—')}</td>
      <td style="font-family:monospace;">${esc(a.unite||'—')}</td>
      <td style="text-align:right;font-family:monospace;">${fmt(a.prix_achat||0)}</td>
      <td style="text-align:right;font-family:monospace;font-weight:700;color:${enAlerte?'#c01e1e':'#0B8A55'};">${fmt(stock)}</td>
      <td style="text-align:right;font-family:monospace;">${fmt(seuil)}</td>
      <td style="text-align:center;">${a.delai_approvisionnement!=null?a.delai_approvisionnement+'j':'—'}</td>
      <td style="text-align:center;"><span style="padding:2px 8px;border-radius:10px;font-size:10px;font-weight:700;background:${a.statut==='actif'?'#DDF2E9':'#f0f3f8'};color:${a.statut==='actif'?'#0B8A55':'#5A6880'};">${a.statut==='actif'?'Actif':'Inactif'}</span></td>
    </tr>`;
  }).join('');

  const html = `<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Articles — GestionApprov</title>
<style>
  *{margin:0;padding:0;box-sizing:border-box;}
  body{font-family:'Segoe UI',Arial,sans-serif;font-size:12px;color:#0D1526;padding:24px;}
  .header{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:20px;padding-bottom:14px;border-bottom:2px solid #E2A84B;}
  .header-left h1{font-size:18px;font-weight:800;color:#05080F;margin-bottom:3px;}
  .header-left p{font-size:11px;color:#6B7F99;}
  .header-right{text-align:right;font-size:11px;color:#6B7F99;}
  .header-right strong{display:block;font-size:13px;color:#0D1526;margin-bottom:2px;}
  .stats{display:flex;gap:12px;margin-bottom:18px;}
  .stat{background:#f4f6fb;border:1px solid #cdd4e3;border-radius:8px;padding:8px 14px;min-width:100px;}
  .stat-n{font-size:18px;font-weight:800;color:#0D1526;}
  .stat-l{font-size:10px;color:#6B7F99;margin-top:1px;}
  table{width:100%;border-collapse:collapse;}
  thead th{background:#05080F;color:#fff;padding:7px 8px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;text-align:left;white-space:nowrap;}
  tbody td{padding:7px 8px;border-bottom:1px solid #e4e8f0;vertical-align:middle;}
  tbody tr:hover{background:#f8f9fc;}
  .footer{margin-top:16px;padding-top:10px;border-top:1px solid #e4e8f0;font-size:10px;color:#8FA4BF;display:flex;justify-content:space-between;}
  @media print{body{padding:10px;}@page{margin:15mm;}}
</style>
</head>
<body>
  <div class="header">
    <div class="header-left">
      <h1>📦 Catalogue des Articles</h1>
      <p>GestionApprov — Rapport généré le ${date}</p>
    </div>
    <div class="header-right">
      <strong>GestionApprov ERP</strong>
      ${_shown.length} article${_shown.length>1?'s':''} exporté${_shown.length>1?'s':''}
    </div>
  </div>
  <div class="stats">
    <div class="stat"><div class="stat-n">${_shown.length}</div><div class="stat-l">Total articles</div></div>
    <div class="stat"><div class="stat-n">${actifs}</div><div class="stat-l">Actifs</div></div>
    <div class="stat" style="border-color:#f0a8a8;"><div class="stat-n" style="color:#c01e1e;">${alertes}</div><div class="stat-l">En alerte stock</div></div>
  </div>
  <table>
    <thead>
      <tr>
        <th>Nom article</th><th>Référence</th><th>Catégorie</th><th>Fournisseur</th>
        <th>Unité</th><th style="text-align:right;">Prix achat</th>
        <th style="text-align:right;">Stock actuel</th><th style="text-align:right;">Seuil min.</th>
        <th style="text-align:center;">Délai</th><th style="text-align:center;">Statut</th>
      </tr>
    </thead>
    <tbody>${rows}</tbody>
  </table>
  <div class="footer">
    <span>GestionApprov ERP — Confidentiel</span>
    <span>Exporté le ${date}</span>
  </div>
  <script>window.onload=()=>{window.print();}<\/script>
</body>
</html>`;

  const w = window.open('','_blank','width=1100,height=800');
  if(!w) return showError('Autorisez les popups pour exporter en PDF.');
  w.document.write(html);
  w.document.close();
}

/* ── Utils ── */
function openModal(id){document.getElementById(id).classList.add('open');document.body.style.overflow='hidden';}
function closeModal(id){document.getElementById(id).classList.remove('open');document.body.style.overflow='';}
function clearForm(){
  ['f-nom','f-reference','f-description','f-unite','f-prix','f-stock','f-seuil','f-delai'].forEach(id=>{const el=document.getElementById(id);if(el)el.value='';});
  document.getElementById('f-fournisseur').value='';
  document.getElementById('f-categorie').value='';
  document.getElementById('f-statut').value='actif';
  document.querySelectorAll('.finput.error').forEach(e=>e.classList.remove('error'));
  document.querySelectorAll('.ferr.show').forEach(e=>e.classList.remove('show'));
}
function esc(s){return String(s??'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');}
function badgeStatut(s){const m={actif:['sb-ok','Actif'],inactif:['sb-muted','Inactif']};const[c,l]=m[s]||['sb-muted',s||'—'];return`<span class="sbadge ${c}">${l}</span>`;}

document.querySelectorAll('.modal-bg').forEach(bg=>bg.addEventListener('click',e=>{if(e.target===bg)closeModal(bg.id);}));
document.addEventListener('keydown',e=>{if(e.key==='Escape')document.querySelectorAll('.modal-bg.open').forEach(m=>closeModal(m.id));});

load();
</script>

<?php require_once 'includes/footer.php'; ?>