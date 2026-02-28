<?php
require_once 'includes/config.php';
if(empty($_SESSION['user'])){header('Location: login.php');exit;}
?>
<?php require_once 'includes/header.php'; ?>
<?php require_once 'includes/sidebar.php'; ?>
<script>document.getElementById('nav-page-title').textContent='Fournisseurs';</script>

<style>
.ph{display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:14px;margin-bottom:22px;}
.ph-title{font-size:1.3rem;font-weight:700;color:var(--text);letter-spacing:-.3px;}
.ph-sub{font-size:.82rem;color:var(--text3);margin-top:4px;}
.ph-right{display:flex;align-items:center;gap:8px;}

.toolbar{display:flex;align-items:center;gap:10px;flex-wrap:wrap;background:var(--surface);border:1px solid var(--border);border-radius:11px;padding:12px 16px;margin-bottom:16px;box-shadow:var(--sh1);}
.toolbar-search{display:flex;align-items:center;gap:9px;background:var(--canvas);border:1px solid var(--border);border-radius:8px;padding:0 12px;flex:1;min-width:220px;max-width:360px;transition:border-color .18s;}
.toolbar-search:focus-within{border-color:var(--gold);}
.toolbar-search i{color:var(--text4);font-size:.82rem;}
.toolbar-search input{border:none;background:transparent;outline:none;font-family:inherit;font-size:.88rem;color:var(--text);padding:8px 0;width:100%;}
.toolbar-search input::placeholder{color:var(--text4);}
.tb-filter{display:flex;align-items:center;gap:7px;padding:7px 13px;border-radius:8px;background:var(--canvas);border:1px solid var(--border);font-size:.82rem;color:var(--text2);cursor:pointer;font-family:inherit;}
.tb-filter select{border:none;background:transparent;outline:none;font-family:inherit;font-size:.82rem;color:var(--text2);cursor:pointer;}
.tb-sep{width:1px;height:24px;background:var(--border);margin:0 2px;}
.tb-count{font-size:.78rem;color:var(--text4);white-space:nowrap;margin-left:auto;}

.tcard{background:var(--surface);border:1px solid var(--border);border-radius:12px;box-shadow:var(--sh1);overflow:hidden;}
.etbl{width:100%;border-collapse:collapse;table-layout:fixed;}
.etbl thead th{font-size:.65rem;font-weight:800;text-transform:uppercase;letter-spacing:1.2px;color:var(--text4);padding:10px 14px;border-bottom:2px solid var(--border);background:var(--surface2);white-space:nowrap;text-align:left;cursor:pointer;user-select:none;}
.etbl thead th:hover{color:var(--text2);}
.etbl thead th.sorted{color:var(--gold-dk);}
.sort-ico{margin-left:4px;opacity:.35;font-size:.58rem;}
.sorted .sort-ico{opacity:1;color:var(--gold-dk);}

/* Largeurs colonnes */
.etbl thead th:nth-child(1){width:22%;}
.etbl thead th:nth-child(2){width:13%;}
.etbl thead th:nth-child(3){width:13%;}
.etbl thead th:nth-child(4){width:16%;}
.etbl thead th:nth-child(5){width:13%;}
.etbl thead th:nth-child(6){width:7%;text-align:center;}
.etbl thead th:nth-child(7){width:8%;text-align:center;}
.etbl thead th:nth-child(8){width:8%;}
.etbl thead th:nth-child(9){width:7%;text-align:right;}

.etbl tbody td{padding:12px 14px;border-bottom:1px solid var(--border);font-size:.86rem;color:var(--text2);vertical-align:middle;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
.etbl tbody td:nth-child(6){text-align:center;}
.etbl tbody td:nth-child(7){text-align:center;}
.etbl tbody tr:last-child td{border-bottom:none;}
.etbl tbody tr:hover{background:var(--surface2);}

.cell-name{display:flex;align-items:center;gap:9px;min-width:0;}
.cell-av{width:32px;height:32px;border-radius:8px;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:.72rem;font-weight:800;color:var(--gold);}
.cell-main{font-weight:700;color:var(--text);font-size:.88rem;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
.cell-sub{font-size:.7rem;color:var(--text4);margin-top:1px;}
.cell-mono{font-family:'DM Mono',monospace;font-size:.82rem;}
.cell-email{color:var(--info);font-size:.82rem;overflow:hidden;text-overflow:ellipsis;display:block;}
.cell-note{display:flex;align-items:center;justify-content:center;gap:2px;font-size:.86rem;color:var(--gold-dk);}
.cell-note .nv{font-weight:700;font-size:.74rem;color:var(--text3);margin-left:3px;}
.cell-actions{display:flex;align-items:center;gap:5px;justify-content:flex-end;}
.act-btn{width:30px;height:30px;border-radius:7px;display:flex;align-items:center;justify-content:center;font-size:.72rem;cursor:pointer;border:1px solid var(--border);background:var(--canvas);color:var(--text3);transition:all .15s;}
.act-btn:hover.edit{background:var(--info-bg);border-color:var(--info-bd);color:var(--info);}
.act-btn:hover.deact{background:var(--warn-bg);border-color:var(--warn-bd);color:var(--warn);}

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
.modal-ico{width:32px;height:32px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:.82rem;background:rgba(226,168,75,.12);color:var(--gold-dk);}
.modal-close{width:30px;height:30px;border-radius:7px;display:flex;align-items:center;justify-content:center;font-size:.8rem;cursor:pointer;background:transparent;border:1px solid transparent;color:var(--text3);transition:all .15s;}
.modal-close:hover{background:var(--crit-bg);border-color:var(--crit-bd);color:var(--crit);}
.modal-body{padding:22px;}
.modal-footer{display:flex;align-items:center;justify-content:flex-end;gap:8px;padding:16px 22px;border-top:1px solid var(--border);background:var(--surface2);}

/* Formulaire */
.frow{display:grid;gap:14px;margin-bottom:14px;}
.frow-2{grid-template-columns:1fr 1fr;}
@media(max-width:500px){.frow-2{grid-template-columns:1fr;}}
.fgroup{display:flex;flex-direction:column;gap:5px;}
.flabel{font-size:.75rem;font-weight:700;color:var(--text2);letter-spacing:.2px;}
.flabel span{color:var(--crit);margin-left:2px;}
.finput,.fselect,.ftextarea{width:100%;padding:9px 12px;border-radius:8px;border:1.5px solid var(--border);background:var(--canvas);color:var(--text);font-family:inherit;font-size:.88rem;transition:border-color .18s;outline:none;}
.finput:focus,.fselect:focus,.ftextarea:focus{border-color:var(--gold);background:var(--surface);box-shadow:0 0 0 3px rgba(226,168,75,.1);}
.finput.error{border-color:var(--crit);}
.ftextarea{resize:vertical;min-height:68px;}
.ferr{font-size:.72rem;color:var(--crit);margin-top:3px;display:none;}
.ferr.show{display:block;}
.star-group{display:flex;align-items:center;gap:4px;margin-top:4px;}
.star-btn{font-size:1.25rem;cursor:pointer;color:var(--border2);transition:color .12s;background:none;border:none;padding:0;line-height:1;}
.star-btn.on{color:var(--gold-dk);}
.star-lbl{font-size:.76rem;color:var(--text4);margin-left:5px;}

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
    <div class="ph-title"><i class="fas fa-building" style="color:var(--gold);margin-right:9px;"></i>Fournisseurs</div>
    <div class="ph-sub">Gestion de vos fournisseurs et partenaires commerciaux</div>
  </div>
  <div class="ph-right">
    <button class="btn-secondary" onclick="exportCSV()"><i class="fas fa-file-pdf"></i>Exporter PDF</button>
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

<!-- ═══ MODAL FORMULAIRE ═══ -->
<div class="modal-bg" id="modal-form">
  <div class="modal">
    <div class="modal-head">
      <div class="modal-title">
        <div class="modal-ico"><i class="fas fa-building"></i></div>
        <span id="modal-form-title">Nouveau fournisseur</span>
      </div>
      <button class="modal-close" onclick="closeModal('modal-form')"><i class="fas fa-xmark"></i></button>
    </div>
    <div class="modal-body">
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
      <div class="frow">
        <div class="fgroup">
          <label class="flabel">Nom du contact</label>
          <input type="text" class="finput" id="f-contact-nom" placeholder="Ex: Jean-Paul Mbarga">
        </div>
      </div>
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
      <div class="frow">
        <div class="fgroup">
          <label class="flabel">Adresse</label>
          <textarea class="ftextarea" id="f-adresse" placeholder="Rue, Quartier, BP, Ville…"></textarea>
        </div>
      </div>
      <div class="frow frow-2">
        <div class="fgroup">
          <label class="flabel">Conditions de paiement</label>
          <input type="text" class="finput" id="f-conditions" placeholder="Ex: 30 jours net">
        </div>
        <div class="fgroup">
          <label class="flabel">Délai livraison moyen (jours)</label>
          <input type="number" class="finput" id="f-delai" placeholder="Ex: 7" min="0">
        </div>
      </div>
      <div class="frow frow-2">
        <div class="fgroup">
          <label class="flabel">Note fournisseur</label>
          <div class="star-group" id="star-group">
            <button class="star-btn" type="button" data-val="1" onclick="setNote(1)">★</button>
            <button class="star-btn" type="button" data-val="2" onclick="setNote(2)">★</button>
            <button class="star-btn" type="button" data-val="3" onclick="setNote(3)">★</button>
            <button class="star-btn" type="button" data-val="4" onclick="setNote(4)">★</button>
            <button class="star-btn" type="button" data-val="5" onclick="setNote(5)">★</button>
            <span class="star-lbl" id="star-lbl">Non noté</span>
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

<!-- ═══ MODAL DÉSACTIVATION ═══ -->
<div class="modal-bg" id="modal-deact">
  <div class="modal cmodal">
    <div class="modal-head">
      <div class="modal-title" style="color:var(--warn);">
        <div class="modal-ico" style="background:var(--warn-bg);color:var(--warn);"><i class="fas fa-ban"></i></div>
        Désactiver le fournisseur
      </div>
      <button class="modal-close" onclick="closeModal('modal-deact')"><i class="fas fa-xmark"></i></button>
    </div>
    <div class="cbody">
      <div class="cico"><i class="fas fa-ban"></i></div>
      <div class="ctitle">Confirmer la désactivation</div>
      <div class="ctext">
        Le fournisseur <span class="cname" id="deact-name"></span> sera passé en statut
        <strong>Inactif</strong> et n'apparaîtra plus dans les nouvelles commandes.<br><br>
        Vous pourrez le réactiver en modifiant son statut.
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn-secondary" onclick="closeModal('modal-deact')">Annuler</button>
      <button class="btn-secondary" id="btn-deact" onclick="confirmDeact()"
        style="background:var(--warn);color:#fff;border-color:var(--warn);">
        <i class="fas fa-ban"></i>Désactiver
      </button>
    </div>
  </div>
</div>

<script>
const API = 'http://localhost:3000/api';
let _all=[],_shown=[],_page=1;
const PER=12;
let _editId=null,_deactId=null,_sortCol='nom',_sortAsc=true,_noteVal=null;

/* ── Étoiles ── */
function setNote(v){ _noteVal=(_noteVal===v)?null:v; document.getElementById('f-note').value=_noteVal??''; renderStars(); }
function renderStars(){
  document.querySelectorAll('#star-group .star-btn').forEach(b=>b.classList.toggle('on',_noteVal!==null&&+b.dataset.val<=_noteVal));
  document.getElementById('star-lbl').textContent=_noteVal!==null?`${_noteVal}/5`:'Non noté';
}
function displayNote(n){
  if(n===null||n===undefined||n==='') return '<span style="color:var(--text4);">—</span>';
  return`<div class="cell-note">${'★'.repeat(n)}${'☆'.repeat(5-n)}<span class="nv">${n}/5</span></div>`;
}

/* ── Chargement ── */
async function load(){
  try{
    const d=(await(await fetch(`${API}/fournisseurs`)).json());
    _all=d.data||d||[];
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

/* ── Filtre ── */
function filterTable(){
  const q=document.getElementById('search-input').value.trim().toLowerCase();
  const st=document.getElementById('filter-statut').value;
  _shown=_all.filter(f=>{
    const mq=!q||(f.nom||'').toLowerCase().includes(q)||(f.contact_nom||'').toLowerCase().includes(q)||(f.email||'').toLowerCase().includes(q)||(f.telephone||'').includes(q);
    const ms=!st||f.statut===st;
    return mq&&ms;
  });
  _shown.sort((a,b)=>{
    let va=a[_sortCol]??(_sortAsc?'\uFFFF':''),vb=b[_sortCol]??(_sortAsc?'\uFFFF':'');
    if(typeof va==='string'){va=va.toLowerCase();vb=(vb+'').toLowerCase();}
    else{va=parseFloat(va)||0;vb=parseFloat(vb)||0;}
    return _sortAsc?(va>vb?1:va<vb?-1:0):(va<vb?1:va>vb?-1:0);
  });
  _page=1; render();
}

function sortBy(col){
  _sortAsc=(_sortCol===col)?!_sortAsc:true; _sortCol=col;
  document.querySelectorAll('.etbl thead th').forEach(th=>{th.classList.remove('sorted');const ic=th.querySelector('.sort-ico');if(ic)ic.className='fas fa-sort sort-ico';});
  const cols=['nom','contact_nom','telephone','email','conditions_paiement','delai_livraison_moyen','note','statut'];
  const idx=cols.indexOf(col);
  if(idx>=0){
    const ths=document.querySelectorAll('.etbl thead th');
    ths[idx].classList.add('sorted');
    const ic=ths[idx].querySelector('.sort-ico');
    if(ic)ic.className=`fas fa-sort-${_sortAsc?'up':'down'} sort-ico`;
  }
  filterTable();
}

/* ── Rendu ── */
function render(){
  const total=_shown.length,pages=Math.max(1,Math.ceil(total/PER));
  if(_page>pages)_page=pages;
  document.getElementById('tb-count').textContent=`${total} fournisseur${total!==1?'s':''} trouvé${total!==1?'s':''}`;
  const slice=_shown.slice((_page-1)*PER,_page*PER);
  if(!slice.length){
    document.getElementById('tbl-body').innerHTML=
      `<tr><td colspan="9"><div class="empty-state">
        <div class="empty-ico"><i class="fas fa-building"></i></div>
        <div class="empty-title">Aucun fournisseur trouvé</div>
        <div class="empty-text">${_all.length===0?'Commencez par ajouter un fournisseur.':'Aucun résultat pour cette recherche.'}</div>
      </div></td></tr>`;
    document.getElementById('pager').style.display='none';
    return;
  }
  document.getElementById('tbl-body').innerHTML=slice.map(f=>`
    <tr>
      <td><div class="cell-name">
        <div class="cell-av" style="background:${hashColor(f.nom)};">${initials(f.nom)}</div>
        <div><div class="cell-main">${esc(f.nom)}</div><div class="cell-sub">${esc(f.code||'')}</div></div>
      </div></td>
      <td>${esc(f.contact_nom||'—')}</td>
      <td class="cell-mono">${esc(f.telephone||'—')}</td>
      <td><a href="mailto:${esc(f.email||'')}" class="cell-email" onclick="event.stopPropagation()">${esc(f.email||'—')}</a></td>
      <td style="font-size:.82rem;">${esc(f.conditions_paiement||'—')}</td>
      <td>${f.delai_livraison_moyen!=null?`<span class="cell-mono">${f.delai_livraison_moyen}j</span>`:'—'}</td>
      <td>${displayNote(f.note)}</td>
      <td>${badgeStatut(f.statut)}</td>
      <td>
        <div class="cell-actions">
          <div class="act-btn edit" title="Modifier" onclick="openEdit(${f.id_fournisseur})"><i class="fas fa-pen"></i></div>
          <div class="act-btn deact" title="Désactiver" onclick="openDeact(${f.id_fournisseur},'${esc(f.nom)}')"><i class="fas fa-ban"></i></div>
        </div>
      </td>
    </tr>`).join('');

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
  document.getElementById('modal-form-title').textContent='Nouveau fournisseur';
  clearForm();
  openModal('modal-form');
}

function openEdit(id){
  _editId=id;
  document.getElementById('modal-form-title').textContent='Modifier le fournisseur';
  const f=_all.find(x=>x.id_fournisseur===id);
  if(!f)return;
  document.getElementById('f-nom').value        = f.nom||'';
  document.getElementById('f-code').value       = f.code||'';
  document.getElementById('f-contact-nom').value= f.contact_nom||'';
  document.getElementById('f-tel').value        = f.telephone||'';
  document.getElementById('f-email').value      = f.email||'';
  document.getElementById('f-adresse').value    = f.adresse||'';
  document.getElementById('f-conditions').value = f.conditions_paiement||'';
  document.getElementById('f-delai').value      = f.delai_livraison_moyen!=null?f.delai_livraison_moyen:'';
  document.getElementById('f-statut').value     = f.statut||'actif';
  _noteVal=(f.note!=null&&f.note!=='')?parseInt(f.note):null;
  document.getElementById('f-note').value=_noteVal??'';
  renderStars();
  openModal('modal-form');
}

async function saveFournisseur(){
  let valid=true;
  ['nom','tel'].forEach(k=>{
    const el=document.getElementById(`f-${k}`),err=document.getElementById(`e-${k}`);
    if(!el.value.trim()){el.classList.add('error');err.classList.add('show');valid=false;}
    else{el.classList.remove('error');err.classList.remove('show');}
  });
  if(!valid)return;

  const delaiRaw=document.getElementById('f-delai').value.trim();
  const body={
    nom:                  document.getElementById('f-nom').value.trim(),
    code:                 document.getElementById('f-code').value.trim()||undefined,
    contact_nom:          document.getElementById('f-contact-nom').value.trim()||undefined,
    telephone:            document.getElementById('f-tel').value.trim(),
    email:                document.getElementById('f-email').value.trim()||undefined,
    adresse:              document.getElementById('f-adresse').value.trim()||undefined,
    conditions_paiement:  document.getElementById('f-conditions').value.trim()||undefined,
    delai_livraison_moyen:delaiRaw!==''?parseInt(delaiRaw):undefined,
    note:                 _noteVal!=null?_noteVal:undefined,
    statut:               document.getElementById('f-statut').value,
  };

  const btn=document.getElementById('btn-save');
  btn.disabled=true;btn.innerHTML='<i class="fas fa-spinner fa-spin"></i> Enregistrement…';
  try{
    const url=_editId?`${API}/fournisseurs/${_editId}`:`${API}/fournisseurs`;
    const r=await fetch(url,{method:_editId?'PUT':'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify(body)});
    if(!r.ok)throw new Error();
    closeModal('modal-form');
    showSuccess(_editId?'Fournisseur modifié avec succès.':'Fournisseur créé avec succès.');
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
    const r=await fetch(`${API}/fournisseurs/${_deactId}`,{method:'DELETE'});
    if(!r.ok)throw new Error();
    closeModal('modal-deact');
    showSuccess('Fournisseur désactivé.');
    await load();
  }catch(e){showError('Impossible de désactiver ce fournisseur.');closeModal('modal-deact');}
  finally{btn.disabled=false;btn.innerHTML='<i class="fas fa-ban"></i>Désactiver';}
}

/* ── Export PDF ── */
function exportCSV(){
  if(!_shown.length)return showError('Aucune donnée à exporter.');

  const date = new Date().toLocaleDateString('fr-FR',{day:'2-digit',month:'long',year:'numeric'});
  const actifs   = _shown.filter(f=>f.statut==='actif').length;
  const suspendus= _shown.filter(f=>f.statut==='suspendu').length;

  const rows = _shown.map(f=>{
    const noteStars = f.note ? '★'.repeat(f.note)+'☆'.repeat(5-f.note) : '—';
    const statutColor = f.statut==='actif'?'#0B8A55':f.statut==='suspendu'?'#B8720A':'#5A6880';
    const statutBg    = f.statut==='actif'?'#DDF2E9':f.statut==='suspendu'?'#FEF2DC':'#f0f3f8';
    const statutLbl   = f.statut==='actif'?'Actif':f.statut==='suspendu'?'Suspendu':'Inactif';
    return `<tr>
      <td><strong>${esc(f.nom)}</strong><br><span style="font-size:10px;color:#6B7F99;">${esc(f.code||'')}</span></td>
      <td>${esc(f.contact_nom||'—')}</td>
      <td style="font-family:monospace;font-size:11px;">${esc(f.telephone||'—')}</td>
      <td style="color:#1452BE;font-size:11px;">${esc(f.email||'—')}</td>
      <td style="font-size:11px;">${esc(f.conditions_paiement||'—')}</td>
      <td style="text-align:center;font-family:monospace;">${f.delai_livraison_moyen!=null?f.delai_livraison_moyen+'j':'—'}</td>
      <td style="text-align:center;color:#B87220;font-size:13px;">${noteStars}</td>
      <td style="text-align:center;"><span style="padding:2px 8px;border-radius:10px;font-size:10px;font-weight:700;background:${statutBg};color:${statutColor};">${statutLbl}</span></td>
    </tr>`;
  }).join('');

  const html = `<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Fournisseurs — GestionApprov</title>
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
      <h1>🏢 Liste des Fournisseurs</h1>
      <p>GestionApprov — Rapport généré le ${date}</p>
    </div>
    <div class="header-right">
      <strong>GestionApprov ERP</strong>
      ${_shown.length} fournisseur${_shown.length>1?'s':''} exporté${_shown.length>1?'s':''}
    </div>
  </div>
  <div class="stats">
    <div class="stat"><div class="stat-n">${_shown.length}</div><div class="stat-l">Total</div></div>
    <div class="stat"><div class="stat-n" style="color:#0B8A55;">${actifs}</div><div class="stat-l">Actifs</div></div>
    <div class="stat"><div class="stat-n" style="color:#B8720A;">${suspendus}</div><div class="stat-l">Suspendus</div></div>
  </div>
  <table>
    <thead>
      <tr>
        <th>Fournisseur</th>
        <th>Contact</th>
        <th>Téléphone</th>
        <th>Email</th>
        <th>Conditions paiement</th>
        <th style="text-align:center;">Délai livr.</th>
        <th style="text-align:center;">Note</th>
        <th style="text-align:center;">Statut</th>
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
  ['f-nom','f-code','f-contact-nom','f-tel','f-email','f-adresse','f-conditions','f-delai'].forEach(id=>{const el=document.getElementById(id);if(el)el.value='';});
  document.getElementById('f-statut').value='actif';
  document.querySelectorAll('.finput.error').forEach(e=>e.classList.remove('error'));
  document.querySelectorAll('.ferr.show').forEach(e=>e.classList.remove('show'));
  _noteVal=null;document.getElementById('f-note').value='';renderStars();
}
function esc(s){return String(s??'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');}
function initials(n){return(n||'?').split(/\s+/).slice(0,2).map(w=>w[0]).join('').toUpperCase();}
function hashColor(s){let h=0;for(let i=0;i<(s||'').length;i++)h=s.charCodeAt(i)+((h<<5)-h);return['#0C1829','#12243A','#0A2040','#162038','#1A2A4A','#0E1E32'][Math.abs(h)%6];}
function badgeStatut(s){const m={actif:['sb-ok','Actif'],inactif:['sb-muted','Inactif'],suspendu:['sb-warn','Suspendu']};const[c,l]=m[s]||['sb-muted',s||'—'];return`<span class="sbadge ${c}">${l}</span>`;}

document.querySelectorAll('.modal-bg').forEach(bg=>bg.addEventListener('click',e=>{if(e.target===bg)closeModal(bg.id);}));
document.addEventListener('keydown',e=>{if(e.key==='Escape')document.querySelectorAll('.modal-bg.open').forEach(m=>closeModal(m.id));});

load();
</script>

<?php require_once 'includes/footer.php'; ?>