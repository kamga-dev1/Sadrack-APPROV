<?php
require_once 'includes/config.php';
if(empty($_SESSION['user'])){header('Location: login.php');exit;}
?>
<?php require_once 'includes/header.php'; ?>
<?php require_once 'includes/sidebar.php'; ?>
<script>document.getElementById('nav-page-title').textContent='Fournisseurs';</script>

<style>
/* ── Page header ── */
.ph{display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:14px;margin-bottom:22px;}
.ph-title{font-size:1.3rem;font-weight:700;color:var(--text);letter-spacing:-.3px;}
.ph-sub{font-size:.82rem;color:var(--text3);margin-top:4px;}
.ph-right{display:flex;align-items:center;gap:8px;}

/* ── Barre outils ── */
.toolbar{
  display:flex;align-items:center;gap:10px;flex-wrap:wrap;
  background:var(--surface);border:1px solid var(--border);
  border-radius:11px;padding:12px 16px;margin-bottom:16px;
  box-shadow:var(--sh1);
}
.toolbar-search{
  display:flex;align-items:center;gap:9px;
  background:var(--canvas);border:1px solid var(--border);
  border-radius:8px;padding:0 12px;flex:1;min-width:220px;max-width:360px;
  transition:border-color .18s;
}
.toolbar-search:focus-within{border-color:var(--gold);background:var(--surface);}
.toolbar-search i{color:var(--text4);font-size:.82rem;flex-shrink:0;}
.toolbar-search input{
  border:none;background:transparent;outline:none;
  font-family:inherit;font-size:.88rem;color:var(--text);
  padding:8px 0;width:100%;
}
.toolbar-search input::placeholder{color:var(--text4);}

.tb-filter{
  display:flex;align-items:center;gap:7px;
  padding:7px 13px;border-radius:8px;
  background:var(--canvas);border:1px solid var(--border);
  font-size:.82rem;font-weight:500;color:var(--text2);
  cursor:pointer;transition:all .16s;font-family:inherit;
}
.tb-filter:hover{border-color:var(--border2);}
.tb-filter select{border:none;background:transparent;outline:none;font-family:inherit;font-size:.82rem;color:var(--text2);cursor:pointer;}

.tb-sep{width:1px;height:24px;background:var(--border);margin:0 2px;}
.tb-count{font-size:.78rem;color:var(--text4);white-space:nowrap;margin-left:auto;}

/* ── Table card ── */
.tcard{background:var(--surface);border:1px solid var(--border);border-radius:12px;box-shadow:var(--sh1);overflow:hidden;}

/* ── Table ── */
.etbl{width:100%;border-collapse:collapse;table-layout:fixed;}
.etbl thead th{
  font-size:.65rem;font-weight:800;text-transform:uppercase;letter-spacing:1.2px;
  color:var(--text4);padding:10px 14px;
  border-bottom:2px solid var(--border);
  background:var(--surface2);white-space:nowrap;text-align:left;
  cursor:pointer;user-select:none;
  transition:color .15s;overflow:hidden;text-overflow:ellipsis;
}
.etbl thead th:hover{color:var(--text2);}
.etbl thead th .sort-ico{margin-left:4px;opacity:.35;font-size:.58rem;}
.etbl thead th.sorted{color:var(--gold-dk);}
.etbl thead th.sorted .sort-ico{opacity:1;color:var(--gold-dk);}

/* Largeurs fixes par colonne */
.etbl thead th:nth-child(1){width:22%;}   /* Fournisseur */
.etbl thead th:nth-child(2){width:13%;}   /* Contact */
.etbl thead th:nth-child(3){width:13%;}   /* Téléphone */
.etbl thead th:nth-child(4){width:16%;}   /* Email */
.etbl thead th:nth-child(5){width:14%;}   /* Conditions paiement */
.etbl thead th:nth-child(6){width:7%;text-align:center;}  /* Délai */
.etbl thead th:nth-child(7){width:8%;text-align:center;}  /* Note */
.etbl thead th:nth-child(8){width:8%;}    /* Statut */
.etbl thead th:nth-child(9){width:7%;text-align:right;}  /* Actions */

.etbl tbody td{
  padding:11px 14px;border-bottom:1px solid var(--border);
  font-size:.85rem;color:var(--text2);vertical-align:middle;
  overflow:hidden;text-overflow:ellipsis;white-space:nowrap;
}
.etbl tbody td:nth-child(6){text-align:center;}
.etbl tbody td:nth-child(7){text-align:center;}
.etbl tbody tr:last-child td{border-bottom:none;}
.etbl tbody tr{transition:background .12s;cursor:default;}
.etbl tbody tr:hover{background:var(--surface2);}

/* Cellules spéciales */
.cell-name{display:flex;align-items:center;gap:9px;min-width:0;}
.cell-av{
  width:32px;height:32px;border-radius:8px;flex-shrink:0;
  display:flex;align-items:center;justify-content:center;
  font-size:.72rem;font-weight:800;
  background:var(--ink2);color:var(--gold);
}
.cell-main{font-weight:700;color:var(--text);font-size:.86rem;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
.cell-sub{font-size:.7rem;color:var(--text4);margin-top:1px;}
.cell-mono{font-family:'DM Mono',monospace;font-size:.8rem;}
.cell-email{color:var(--info);font-size:.82rem;display:block;overflow:hidden;text-overflow:ellipsis;}
.cell-actions{display:flex;align-items:center;gap:5px;justify-content:flex-end;}

/* Note stars */
.cell-note{display:flex;align-items:center;gap:3px;font-size:.78rem;color:var(--gold-dk);}
.cell-note .note-val{font-weight:700;margin-left:3px;color:var(--text2);}

/* Action buttons */
.act-btn{
  width:30px;height:30px;border-radius:7px;
  display:flex;align-items:center;justify-content:center;
  font-size:.72rem;cursor:pointer;border:1px solid var(--border);
  background:var(--canvas);color:var(--text3);
  transition:all .15s;
}
.act-btn:hover.edit{background:var(--info-bg);border-color:var(--info-bd);color:var(--info);}
.act-btn:hover.del{background:var(--crit-bg);border-color:var(--crit-bd);color:var(--crit);}

/* ── Pagination ── */
.pager{
  display:flex;align-items:center;justify-content:space-between;
  padding:12px 18px;border-top:1px solid var(--border);
  flex-wrap:wrap;gap:10px;
}
.pager-info{font-size:.78rem;color:var(--text4);}
.pager-btns{display:flex;align-items:center;gap:4px;}
.pg-btn{
  min-width:32px;height:32px;border-radius:7px;
  display:flex;align-items:center;justify-content:center;
  font-size:.78rem;font-weight:600;
  cursor:pointer;border:1px solid var(--border);
  background:var(--canvas);color:var(--text2);
  transition:all .15s;padding:0 6px;
}
.pg-btn:hover{background:var(--surface);border-color:var(--border2);}
.pg-btn.on{background:var(--ink2);color:#fff;border-color:var(--ink2);}
.pg-btn:disabled{opacity:.38;cursor:default;}

/* ── MODAL ── */
.modal-bg{
  position:fixed;inset:0;z-index:900;
  background:rgba(5,8,15,.55);
  backdrop-filter:blur(4px);
  display:none;align-items:center;justify-content:center;padding:20px;
}
.modal-bg.open{display:flex;animation:bgIn .2s ease;}
@keyframes bgIn{from{opacity:0;}to{opacity:1;}}

.modal{
  background:var(--surface);border:1px solid var(--border);
  border-radius:16px;width:100%;max-width:580px;
  box-shadow:var(--sh3);
  animation:mIn .22s ease;
  max-height:90vh;overflow-y:auto;
}
@keyframes mIn{from{opacity:0;transform:translateY(-14px) scale(.98);}to{opacity:1;transform:translateY(0) scale(1);}}

.modal-head{
  display:flex;align-items:center;justify-content:space-between;
  padding:18px 22px;border-bottom:1px solid var(--border);
  position:sticky;top:0;background:var(--surface);z-index:1;
}
.modal-title{
  display:flex;align-items:center;gap:10px;
  font-size:.98rem;font-weight:700;color:var(--text);
}
.modal-title-ico{
  width:32px;height:32px;border-radius:8px;
  display:flex;align-items:center;justify-content:center;font-size:.82rem;
  background:rgba(226,168,75,.12);color:var(--gold-dk);
}
.modal-close{
  width:30px;height:30px;border-radius:7px;
  display:flex;align-items:center;justify-content:center;
  font-size:.8rem;cursor:pointer;
  background:transparent;border:1px solid transparent;color:var(--text3);
  transition:all .15s;
}
.modal-close:hover{background:var(--crit-bg);border-color:var(--crit-bd);color:var(--crit);}
.modal-body{padding:22px;}
.modal-footer{
  display:flex;align-items:center;justify-content:flex-end;gap:8px;
  padding:16px 22px;border-top:1px solid var(--border);
  background:var(--surface2);
}

/* ── Formulaire ── */
.frow{display:grid;gap:14px;margin-bottom:14px;}
.frow-2{grid-template-columns:1fr 1fr;}
.frow-3{grid-template-columns:1fr 1fr 1fr;}
@media(max-width:500px){.frow-2,.frow-3{grid-template-columns:1fr;}}

.fgroup{display:flex;flex-direction:column;gap:5px;}
.flabel{font-size:.75rem;font-weight:700;color:var(--text2);letter-spacing:.2px;}
.flabel span{color:var(--crit);margin-left:2px;}
.finput,.fselect,.ftextarea{
  width:100%;padding:9px 12px;border-radius:8px;
  border:1.5px solid var(--border);
  background:var(--canvas);color:var(--text);
  font-family:inherit;font-size:.88rem;
  transition:border-color .18s,background .18s;
  outline:none;
}
.finput:focus,.fselect:focus,.ftextarea:focus{
  border-color:var(--gold);background:var(--surface);
  box-shadow:0 0 0 3px rgba(226,168,75,.1);
}
.finput.error{border-color:var(--crit);}
.ftextarea{resize:vertical;min-height:72px;}
.ferr{font-size:.72rem;color:var(--crit);margin-top:3px;display:none;}
.ferr.show{display:block;}

/* Note (étoiles interactives) */
.star-group{display:flex;align-items:center;gap:5px;margin-top:4px;}
.star-btn{font-size:1.2rem;cursor:pointer;color:var(--border2);transition:color .12s;background:none;border:none;padding:0;}
.star-btn.on{color:var(--gold-dk);}

/* ── Modal de confirmation ── */
.confirm-modal{max-width:400px;}
.confirm-body{padding:24px 22px;text-align:center;}
.confirm-ico{
  width:52px;height:52px;border-radius:50%;
  background:var(--crit-bg);
  display:flex;align-items:center;justify-content:center;
  font-size:1.3rem;color:var(--crit);
  margin:0 auto 14px;
}
.confirm-title{font-size:1rem;font-weight:700;color:var(--text);margin-bottom:7px;}
.confirm-text{font-size:.84rem;color:var(--text3);line-height:1.5;}
.confirm-name{font-weight:700;color:var(--text);}

/* ── État vide ── */
.empty-state{padding:52px 20px;text-align:center;}
.empty-ico{
  width:56px;height:56px;border-radius:14px;
  background:var(--canvas);border:1px solid var(--border);
  display:flex;align-items:center;justify-content:center;
  font-size:1.4rem;color:var(--text4);
  margin:0 auto 14px;
}
.empty-title{font-size:.95rem;font-weight:700;color:var(--text);margin-bottom:6px;}
.empty-text{font-size:.82rem;color:var(--text3);}
</style>

<!-- Page Header -->
<div class="ph">
  <div class="ph-left">
    <div class="ph-title"><i class="fas fa-building" style="color:var(--gold);margin-right:9px;"></i>Fournisseurs</div>
    <div class="ph-sub">Gestion de vos fournisseurs et partenaires commerciaux</div>
  </div>
  <div class="ph-right">
    <button class="btn-secondary" onclick="exportCSV()"><i class="fas fa-download"></i>Exporter</button>
    <button class="btn-gold" onclick="openCreate()"><i class="fas fa-plus"></i>Nouveau fournisseur</button>
  </div>
</div>

<!-- Toolbar -->
<div class="toolbar">
  <div class="toolbar-search">
    <i class="fas fa-search"></i>
    <input type="text" id="search-input" placeholder="Rechercher par nom, contact, email, téléphone…" oninput="filterTable()">
  </div>
  <div class="tb-filter">
    <i class="fas fa-filter" style="color:var(--text4);font-size:.76rem;"></i>
    <select id="filter-statut" onchange="filterTable()">
      <option value="">Tous les statuts</option>
      <option value="actif">Actif</option>
      <option value="inactif">Inactif</option>
      <option value="suspendu">Suspendu</option>
    </select>
  </div>
  <div class="tb-filter">
    <i class="fas fa-sort-amount-down" style="color:var(--text4);font-size:.76rem;"></i>
    <select id="filter-sort" onchange="filterTable()">
      <option value="nom">Trier par nom</option>
      <option value="note">Note</option>
      <option value="delai_livraison_moyen">Délai livraison</option>
      <option value="created_at">Date d'ajout</option>
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
          <th onclick="sortBy('nom')">Fournisseur <i class="fas fa-sort sort-ico"></i></th>
          <th onclick="sortBy('contact_nom')">Contact <i class="fas fa-sort sort-ico"></i></th>
          <th onclick="sortBy('telephone')">Téléphone <i class="fas fa-sort sort-ico"></i></th>
          <th onclick="sortBy('email')">Email <i class="fas fa-sort sort-ico"></i></th>
          <th onclick="sortBy('conditions_paiement')">Conditions paiement <i class="fas fa-sort sort-ico"></i></th>
          <th onclick="sortBy('delai_livraison_moyen')">Délai livr. <i class="fas fa-sort sort-ico"></i></th>
          <th onclick="sortBy('note')">Note <i class="fas fa-sort sort-ico"></i></th>
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

<!-- ═══ MODAL CRÉATION / ÉDITION ═══ -->
<div class="modal-bg" id="modal-form">
  <div class="modal">
    <div class="modal-head">
      <div class="modal-title">
        <div class="modal-title-ico"><i class="fas fa-building"></i></div>
        <span id="modal-form-title">Nouveau fournisseur</span>
      </div>
      <button class="modal-close" onclick="closeModal('modal-form')"><i class="fas fa-xmark"></i></button>
    </div>
    <div class="modal-body">

      <!-- Ligne 1 : Nom + Code -->
      <div class="frow frow-2">
        <div class="fgroup">
          <label class="flabel">Nom / Raison sociale <span>*</span></label>
          <input type="text" class="finput" id="f-nom" placeholder="Ex: CAMTEL SARL">
          <div class="ferr" id="e-nom">Champ requis</div>
        </div>
        <div class="fgroup">
          <label class="flabel">Code fournisseur</label>
          <input type="text" class="finput" id="f-code" placeholder="Ex: FOUR-001" style="font-family:'DM Mono',monospace;">
        </div>
      </div>

      <!-- Ligne 2 : Contact nom -->
      <div class="frow">
        <div class="fgroup">
          <label class="flabel">Nom du contact</label>
          <input type="text" class="finput" id="f-contact-nom" placeholder="Ex: Jean-Paul Mbarga">
        </div>
      </div>

      <!-- Ligne 3 : Téléphone + Email -->
      <div class="frow frow-2">
        <div class="fgroup">
          <label class="flabel">Téléphone <span>*</span></label>
          <input type="tel" class="finput" id="f-tel" placeholder="+237 6XX XXX XXX">
          <div class="ferr" id="e-tel">Champ requis</div>
        </div>
        <div class="fgroup">
          <label class="flabel">Email</label>
          <input type="email" class="finput" id="f-email" placeholder="contact@fournisseur.cm">
        </div>
      </div>

      <!-- Ligne 4 : Adresse -->
      <div class="frow">
        <div class="fgroup">
          <label class="flabel">Adresse complète</label>
          <textarea class="ftextarea" id="f-adresse" placeholder="Rue, Quartier, BP, Ville…" style="min-height:60px;"></textarea>
        </div>
      </div>

      <!-- Ligne 5 : Conditions paiement + Délai livraison -->
      <div class="frow frow-2">
        <div class="fgroup">
          <label class="flabel">Conditions de paiement</label>
          <input type="text" class="finput" id="f-conditions" placeholder="Ex: 30 jours net, comptant…">
        </div>
        <div class="fgroup">
          <label class="flabel">Délai livraison moyen (jours)</label>
          <input type="number" class="finput" id="f-delai" placeholder="Ex: 7" min="0">
        </div>
      </div>

      <!-- Ligne 6 : Note + Statut -->
      <div class="frow frow-2">
        <div class="fgroup">
          <label class="flabel">Note (0–5)</label>
          <div class="star-group" id="star-group">
            <button class="star-btn" type="button" data-val="1" onclick="setNote(1)">&#9733;</button>
            <button class="star-btn" type="button" data-val="2" onclick="setNote(2)">&#9733;</button>
            <button class="star-btn" type="button" data-val="3" onclick="setNote(3)">&#9733;</button>
            <button class="star-btn" type="button" data-val="4" onclick="setNote(4)">&#9733;</button>
            <button class="star-btn" type="button" data-val="5" onclick="setNote(5)">&#9733;</button>
            <span id="star-val-label" style="font-size:.78rem;color:var(--text4);margin-left:4px;">Non noté</span>
          </div>
          <input type="hidden" id="f-note" value="">
        </div>
        <div class="fgroup">
          <label class="flabel">Statut</label>
          <select class="fselect" id="f-statut">
            <option value="actif">Actif</option>
            <option value="inactif">Inactif</option>
            <option value="suspendu">Suspendu</option>
          </select>
        </div>
      </div>

    </div>
    <div class="modal-footer">
      <button class="btn-secondary" onclick="closeModal('modal-form')">Annuler</button>
      <button class="btn-gold" id="btn-save" onclick="saveFournisseur()">
        <i class="fas fa-floppy-disk"></i>Enregistrer
      </button>
    </div>
  </div>
</div>

<!-- ═══ MODAL CONFIRMATION SUPPRESSION ═══ -->
<div class="modal-bg" id="modal-del">
  <div class="modal confirm-modal">
    <div class="modal-head">
      <div class="modal-title" style="color:var(--crit);">
        <div class="modal-title-ico" style="background:var(--crit-bg);color:var(--crit);"><i class="fas fa-trash"></i></div>
        Supprimer le fournisseur
      </div>
      <button class="modal-close" onclick="closeModal('modal-del')"><i class="fas fa-xmark"></i></button>
    </div>
    <div class="confirm-body">
      <div class="confirm-ico"><i class="fas fa-triangle-exclamation"></i></div>
      <div class="confirm-title">Confirmer la suppression</div>
      <div class="confirm-text">Vous êtes sur le point de supprimer <span class="confirm-name" id="del-name"></span>.<br>Cette action est irréversible et supprimera toutes les données associées.</div>
    </div>
    <div class="modal-footer">
      <button class="btn-secondary" onclick="closeModal('modal-del')">Annuler</button>
      <button class="btn-secondary" id="btn-del" onclick="confirmDelete()" style="background:var(--crit);color:#fff;border-color:var(--crit);">
        <i class="fas fa-trash"></i>Supprimer définitivement
      </button>
    </div>
  </div>
</div>

<script>
const API = 'http://localhost:3000/api';
let _all   = [];
let _shown = [];
let _page  = 1;
const PER  = 12;
let _editId  = null;
let _delId   = null;
let _sortCol = 'nom';
let _sortAsc = true;
let _noteVal = null; // null = pas de note

/* ══ ÉTOILES ══ */
function setNote(val){
  _noteVal = (_noteVal === val) ? null : val; // clic sur même étoile = reset
  document.getElementById('f-note').value = _noteVal ?? '';
  renderStars();
}

function renderStars(){
  const stars = document.querySelectorAll('#star-group .star-btn');
  stars.forEach(btn => {
    btn.classList.toggle('on', _noteVal !== null && parseInt(btn.dataset.val) <= _noteVal);
  });
  const lbl = document.getElementById('star-val-label');
  lbl.textContent = _noteVal !== null ? `${_noteVal}/5` : 'Non noté';
}

function displayNote(n){
  if(n === null || n === undefined || n === '') return '<span style="color:var(--text4);font-size:.78rem;">—</span>';
  const stars = '★'.repeat(n) + '☆'.repeat(5 - n);
  return `<span class="cell-note">${stars} <span class="note-val">${n}/5</span></span>`;
}

/* ══ CHARGEMENT ══ */
async function load(){
  try{
    const r = await fetch(`${API}/fournisseurs`);
    const d = await r.json();
    _all = d.data || d || [];
    filterTable();
  }catch(e){
    document.getElementById('tbl-body').innerHTML =
      `<tr><td colspan="9"><div class="empty-state">
        <div class="empty-ico"><i class="fas fa-wifi-slash"></i></div>
        <div class="empty-title">Erreur de connexion</div>
        <div class="empty-text">Impossible de joindre l'API. Vérifiez que Node.js tourne sur le port 3000.</div>
      </div></td></tr>`;
  }
}

/* ══ FILTRE + TRI ══ */
function filterTable(){
  const q  = document.getElementById('search-input').value.trim().toLowerCase();
  const st = document.getElementById('filter-statut').value;

  _shown = _all.filter(f => {
    const matchQ = !q
      || (f.nom||'').toLowerCase().includes(q)
      || (f.contact_nom||'').toLowerCase().includes(q)
      || (f.email||'').toLowerCase().includes(q)
      || (f.telephone||'').includes(q)
      || (f.adresse||'').toLowerCase().includes(q);
    const matchSt = !st || f.statut === st;
    return matchQ && matchSt;
  });

  _shown.sort((a,b)=>{
    let va = a[_sortCol], vb = b[_sortCol];
    // Gérer null/undefined en les mettant à la fin
    if(va===null||va===undefined) va = _sortAsc ? '\uFFFF' : '';
    if(vb===null||vb===undefined) vb = _sortAsc ? '\uFFFF' : '';
    if(typeof va==='string') va=va.toLowerCase(), vb=(vb+'').toLowerCase();
    if(typeof va==='number'||!isNaN(va)) va=parseFloat(va)||0, vb=parseFloat(vb)||0;
    return _sortAsc ? (va>vb?1:va<vb?-1:0) : (va<vb?1:va>vb?-1:0);
  });

  _page = 1;
  render();
}

function sortBy(col){
  if(_sortCol===col) _sortAsc=!_sortAsc;
  else{_sortCol=col;_sortAsc=true;}
  document.querySelectorAll('.etbl thead th').forEach(th=>{
    th.classList.remove('sorted');
    const ico=th.querySelector('.sort-ico');
    if(ico) ico.className='fas fa-sort sort-ico';
  });
  const cols=['nom','contact_nom','telephone','email','conditions_paiement','delai_livraison_moyen','note','statut'];
  const idx=cols.indexOf(col);
  const ths=document.querySelectorAll('.etbl thead th');
  if(idx>=0){
    ths[idx].classList.add('sorted');
    const ico=ths[idx].querySelector('.sort-ico');
    if(ico) ico.className=`fas fa-sort-${_sortAsc?'up':'down'} sort-ico`;
  }
  filterTable();
}

/* ══ RENDU TABLE ══ */
function render(){
  const total = _shown.length;
  const pages = Math.max(1,Math.ceil(total/PER));
  if(_page>pages) _page=pages;

  document.getElementById('tb-count').textContent =
    `${total} fournisseur${total!==1?'s':''} trouvé${total!==1?'s':''}`;

  const slice = _shown.slice((_page-1)*PER, _page*PER);

  if(slice.length===0){
    document.getElementById('tbl-body').innerHTML =
      `<tr><td colspan="9"><div class="empty-state">
        <div class="empty-ico"><i class="fas fa-building"></i></div>
        <div class="empty-title">Aucun fournisseur trouvé</div>
        <div class="empty-text">${_all.length===0?'Commencez par ajouter un fournisseur.':'Modifiez vos critères de recherche.'}</div>
      </div></td></tr>`;
    document.getElementById('pager').style.display='none';
    return;
  }

  document.getElementById('tbl-body').innerHTML = slice.map(f => `
    <tr>
      <td>
        <div class="cell-name">
          <div class="cell-av" style="background:${hashColor(f.nom)};">${initials(f.nom)}</div>
          <div>
            <div class="cell-main">${esc(f.nom)}</div>
            <div class="cell-sub">${esc(f.code||'')}</div>
          </div>
        </div>
      </td>
      <td>${esc(f.contact_nom||'—')}</td>
      <td class="cell-mono">${esc(f.telephone||'—')}</td>
      <td><a href="mailto:${esc(f.email||'')}" class="cell-email" onclick="event.stopPropagation()">${esc(f.email||'—')}</a></td>
      <td style="font-size:.81rem;">${esc(f.conditions_paiement||'—')}</td>
      <td>${f.delai_livraison_moyen!=null ? `<span class="cell-mono">${f.delai_livraison_moyen}j</span>` : '—'}</td>
      <td>${displayNote(f.note)}</td>
      <td>${statut(f.statut)}</td>
      <td>
        <div class="cell-actions">
          <div class="act-btn edit" title="Modifier" onclick="openEdit(${f.id_fournisseur})"><i class="fas fa-pen"></i></div>
          <div class="act-btn del"  title="Supprimer" onclick="openDelete(${f.id_fournisseur},'${esc(f.nom)}')"><i class="fas fa-trash"></i></div>
        </div>
      </td>
    </tr>
  `).join('');

  // Pagination
  const pi = document.getElementById('pager');
  const pb = document.getElementById('pager-btns');
  pi.style.display='flex';
  document.getElementById('pager-info').textContent =
    `${(_page-1)*PER+1}–${Math.min(_page*PER,total)} sur ${total}`;

  let html=`<button class="pg-btn" onclick="goPage(${_page-1})" ${_page===1?'disabled':''}><i class="fas fa-chevron-left"></i></button>`;
  for(let p=1;p<=pages;p++){
    if(pages>7&&p>2&&p<pages-1&&Math.abs(p-_page)>1){
      if(p===3||p===pages-2) html+=`<span style="padding:0 4px;color:var(--text4);">…</span>`;
      continue;
    }
    html+=`<button class="pg-btn ${p===_page?'on':''}" onclick="goPage(${p})">${p}</button>`;
  }
  html+=`<button class="pg-btn" onclick="goPage(${_page+1})" ${_page===pages?'disabled':''}><i class="fas fa-chevron-right"></i></button>`;
  pb.innerHTML=html;
}

function goPage(p){
  const pages=Math.ceil(_shown.length/PER);
  if(p<1||p>pages) return;
  _page=p;render();window.scrollTo({top:0,behavior:'smooth'});
}

/* ══ MODAL FORMULAIRE ══ */
function openCreate(){
  _editId=null;
  document.getElementById('modal-form-title').textContent='Nouveau fournisseur';
  clearForm();
  openModal('modal-form');
}

function openEdit(id){
  _editId=id;
  document.getElementById('modal-form-title').textContent='Modifier le fournisseur';
  const f=_all.find(x=>x.id_fournisseur===id);
  if(!f) return;
  document.getElementById('f-nom').value         = f.nom||'';
  document.getElementById('f-code').value        = f.code||'';
  document.getElementById('f-contact-nom').value = f.contact_nom||'';
  document.getElementById('f-tel').value         = f.telephone||'';
  document.getElementById('f-email').value       = f.email||'';
  document.getElementById('f-adresse').value     = f.adresse||'';
  document.getElementById('f-conditions').value  = f.conditions_paiement||'';
  document.getElementById('f-delai').value       = f.delai_livraison_moyen!=null ? f.delai_livraison_moyen : '';
  document.getElementById('f-statut').value      = f.statut||'actif';
  _noteVal = (f.note!=null && f.note!='') ? parseInt(f.note) : null;
  document.getElementById('f-note').value        = _noteVal ?? '';
  renderStars();
  openModal('modal-form');
}

async function saveFournisseur(){
  let valid=true;
  ['nom','tel'].forEach(field=>{
    const el=document.getElementById(`f-${field}`);
    const err=document.getElementById(`e-${field}`);
    if(!el.value.trim()){el.classList.add('error');err.classList.add('show');valid=false;}
    else{el.classList.remove('error');err.classList.remove('show');}
  });
  if(!valid) return;

  const delaiRaw = document.getElementById('f-delai').value.trim();

  const body={
    nom:                  document.getElementById('f-nom').value.trim(),
    code:                 document.getElementById('f-code').value.trim()||undefined,
    contact_nom:          document.getElementById('f-contact-nom').value.trim()||undefined,
    telephone:            document.getElementById('f-tel').value.trim(),
    email:                document.getElementById('f-email').value.trim()||undefined,
    adresse:              document.getElementById('f-adresse').value.trim()||undefined,
    conditions_paiement:  document.getElementById('f-conditions').value.trim()||undefined,
    delai_livraison_moyen: delaiRaw!=='' ? parseInt(delaiRaw) : undefined,
    note:                 _noteVal!=null ? _noteVal : undefined,
    statut:               document.getElementById('f-statut').value,
  };

  const btn=document.getElementById('btn-save');
  btn.disabled=true;btn.innerHTML='<i class="fas fa-spinner fa-spin"></i> Enregistrement…';

  try{
    const url  = _editId ? `${API}/fournisseurs/${_editId}` : `${API}/fournisseurs`;
    const meth = _editId ? 'PUT' : 'POST';
    const r=await fetch(url,{method:meth,headers:{'Content-Type':'application/json'},body:JSON.stringify(body)});
    if(!r.ok) throw new Error();
    closeModal('modal-form');
    showSuccess(_editId?'Fournisseur modifié avec succès.':'Fournisseur créé avec succès.');
    await load();
  }catch(e){
    showError('Erreur lors de l\'enregistrement. Vérifiez les données saisies.');
  }finally{
    btn.disabled=false;btn.innerHTML='<i class="fas fa-floppy-disk"></i>Enregistrer';
  }
}

/* ══ SUPPRESSION ══ */
function openDelete(id,nom){
  _delId=id;
  document.getElementById('del-name').textContent=nom;
  openModal('modal-del');
}

async function confirmDelete(){
  const btn=document.getElementById('btn-del');
  btn.disabled=true;btn.innerHTML='<i class="fas fa-spinner fa-spin"></i> Suppression…';
  try{
    const r=await fetch(`${API}/fournisseurs/${_delId}`,{method:'DELETE'});
    if(!r.ok) throw new Error();
    closeModal('modal-del');
    showSuccess('Fournisseur supprimé.');
    await load();
  }catch(e){
    showError('Impossible de supprimer ce fournisseur. Il est peut-être lié à des commandes.');
    closeModal('modal-del');
  }finally{
    btn.disabled=false;btn.innerHTML='<i class="fas fa-trash"></i>Supprimer définitivement';
  }
}

/* ══ EXPORT CSV ══ */
function exportCSV(){
  if(!_shown.length) return showError('Aucune donnée à exporter.');
  const cols=['ID','Code','Nom','Contact','Téléphone','Email','Adresse','Conditions paiement','Délai livraison (j)','Note','Statut'];
  const rows=_shown.map(f=>[
    f.id_fournisseur,f.code,f.nom,f.contact_nom,f.telephone,
    f.email,f.adresse,f.conditions_paiement,f.delai_livraison_moyen,f.note,f.statut
  ].map(v=>`"${(v??'').toString().replace(/"/g,'""')}"`).join(','));
  const csv='\uFEFF'+[cols.join(','),...rows].join('\n');
  const a=document.createElement('a');
  a.href='data:text/csv;charset=utf-8,'+encodeURIComponent(csv);
  a.download=`fournisseurs_${new Date().toISOString().slice(0,10)}.csv`;
  a.click();
}

/* ══ UTILS ══ */
function openModal(id){document.getElementById(id).classList.add('open');document.body.style.overflow='hidden';}
function closeModal(id){document.getElementById(id).classList.remove('open');document.body.style.overflow='';}

function clearForm(){
  ['f-nom','f-code','f-contact-nom','f-tel','f-email','f-adresse','f-conditions','f-delai'].forEach(id=>{
    const el=document.getElementById(id);if(el)el.value='';
  });
  document.getElementById('f-statut').value='actif';
  document.querySelectorAll('.finput.error').forEach(e=>e.classList.remove('error'));
  document.querySelectorAll('.ferr.show').forEach(e=>e.classList.remove('show'));
  _noteVal=null;
  document.getElementById('f-note').value='';
  renderStars();
}

function esc(s){return String(s??'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');}
function initials(n){return (n||'?').split(/\s+/).slice(0,2).map(w=>w[0]).join('').toUpperCase();}
function hashColor(s){let h=0;for(let i=0;i<(s||'').length;i++)h=s.charCodeAt(i)+((h<<5)-h);const colors=['#0C1829','#12243A','#0A2040','#162038','#1A2A4A','#0E1E32'];return colors[Math.abs(h)%colors.length];}
function statut(s){
  const m={actif:['sb-ok','Actif'],inactif:['sb-muted','Inactif'],suspendu:['sb-warn','Suspendu']};
  const[c,l]=m[s]||['sb-muted',s||'—'];
  return`<span class="sbadge ${c}">${l}</span>`;
}

// Fermeture modals sur fond
document.querySelectorAll('.modal-bg').forEach(bg=>{
  bg.addEventListener('click',e=>{if(e.target===bg)closeModal(bg.id);});
});
// Touche Escape
document.addEventListener('keydown',e=>{
  if(e.key==='Escape') document.querySelectorAll('.modal-bg.open').forEach(m=>closeModal(m.id));
});

load();
</script>

<?php require_once 'includes/footer.php'; ?>