<?php
require_once 'includes/config.php';
if(empty($_SESSION['user'])){header('Location: login.php');exit;}
$nom  = htmlspecialchars($_SESSION['user']['nom']);
$role = htmlspecialchars($_SESSION['user']['role']);
?>
<?php require_once 'includes/header.php'; ?>
<?php require_once 'includes/sidebar.php'; ?>
<script>document.getElementById('nav-page-title').textContent='Vue d\'ensemble';</script>

<style>
/* ── Welcome banner ── */
.welcome{
  background:linear-gradient(112deg,var(--ink) 0%,var(--ink2) 48%,var(--ink3) 100%);
  border-radius:14px;padding:26px 30px;margin-bottom:22px;
  position:relative;overflow:hidden;box-shadow:var(--sh2);
}
.welcome::before{
  content:'';position:absolute;inset:0;
  background-image:linear-gradient(rgba(226,168,75,.038) 1px,transparent 1px),linear-gradient(90deg,rgba(226,168,75,.038) 1px,transparent 1px);
  background-size:38px 38px;
}
.welcome::after{
  content:'';position:absolute;right:-60px;top:-60px;width:260px;height:260px;border-radius:50%;
  background:radial-gradient(circle,rgba(226,168,75,.09) 0%,transparent 68%);
}
.w-line{position:absolute;top:0;left:0;right:0;height:2px;background:linear-gradient(90deg,var(--gold),rgba(226,168,75,.32),transparent);opacity:.6;}
.w-inner{position:relative;z-index:1;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;}

.w-tag{
  font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:2px;
  color:rgba(226,168,75,.7);margin-bottom:7px;
  display:flex;align-items:center;gap:8px;
}
.w-tag::before{content:'';width:18px;height:1px;background:rgba(226,168,75,.4);}
.w-name{font-size:1.7rem;font-weight:700;color:#fff;letter-spacing:-.4px;line-height:1.1;margin-bottom:7px;}
.w-name strong{
  background:linear-gradient(90deg,var(--gold),var(--gold-lt));
  -webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;
}
.w-meta{display:flex;align-items:center;gap:10px;font-size:.82rem;color:rgba(255,255,255,.36);flex-wrap:wrap;}
.w-meta i{color:rgba(226,168,75,.45);font-size:.76rem;}
.w-role{
  background:rgba(226,168,75,.11);border:1px solid rgba(226,168,75,.22);
  color:var(--gold);font-size:.65rem;font-weight:800;
  text-transform:uppercase;letter-spacing:1px;padding:2px 9px;border-radius:16px;
}

/* Stats rapides */
.w-stats{display:flex;align-items:center;gap:7px;}
.w-stat{
  background:rgba(255,255,255,.058);border:1px solid rgba(255,255,255,.09);
  border-radius:10px;padding:11px 18px;text-align:center;min-width:76px;
}
.w-stat-n{font-size:1.6rem;font-weight:800;color:#fff;line-height:1;margin-bottom:3px;font-variant-numeric:tabular-nums;}
.w-stat-n.gd{background:linear-gradient(90deg,var(--gold),var(--gold-lt));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;}
.w-stat-l{font-size:.62rem;color:rgba(255,255,255,.28);text-transform:uppercase;letter-spacing:1px;}
.w-sep{width:1px;height:38px;background:rgba(255,255,255,.07);}
.btn-rf{
  display:flex;align-items:center;gap:7px;padding:9px 16px;border-radius:9px;
  background:rgba(226,168,75,.11);border:1px solid rgba(226,168,75,.24);
  color:var(--gold);font-size:.8rem;font-weight:600;
  cursor:pointer;transition:all .2s;font-family:inherit;
}
.btn-rf:hover{background:rgba(226,168,75,.2);}
.btn-rf i{transition:transform .45s;}
.btn-rf:hover i{transform:rotate(180deg);}

/* ── KPI grid ── */
.kpi-row{display:grid;grid-template-columns:repeat(4,1fr);gap:13px;margin-bottom:20px;}
@media(max-width:1100px){.kpi-row{grid-template-columns:repeat(2,1fr);}}
@media(max-width:560px){.kpi-row{grid-template-columns:1fr;}}

.kc{
  background:var(--surface);border:1px solid var(--border);
  border-radius:12px;padding:20px 18px 18px;
  box-shadow:var(--sh1);transition:all .22s;cursor:default;
  position:relative;overflow:hidden;
}
.kc::after{
  content:'';position:absolute;left:0;top:0;bottom:0;width:3px;
  background:var(--kc-color,var(--gold));
  opacity:0;transition:opacity .22s;
}
.kc:hover{transform:translateY(-2px);box-shadow:var(--sh2);}
.kc:hover::after{opacity:1;}
.kc-top{display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;}
.kc-ico{
  width:42px;height:42px;border-radius:10px;
  display:flex;align-items:center;justify-content:center;font-size:1rem;
  background:var(--kc-bg,rgba(226,168,75,.12));
  color:var(--kc-color,var(--gold));
}
.kc-tag{
  font-size:.65rem;font-weight:700;padding:3px 9px;border-radius:16px;
  background:var(--kc-bg,rgba(226,168,75,.12));
  color:var(--kc-color,var(--gold));
  border:1px solid var(--kc-bd,rgba(226,168,75,.25));
}
.kc-num{font-size:2.4rem;font-weight:800;color:var(--text);letter-spacing:-1.5px;line-height:1;margin-bottom:5px;font-variant-numeric:tabular-nums;}
.kc-lbl{font-size:.84rem;color:var(--text3);font-weight:500;}

/* ── Content grids ── */
.grid-a{display:grid;grid-template-columns:1.55fr 1fr;gap:14px;margin-bottom:14px;}
.grid-b{display:grid;grid-template-columns:1fr 1fr;gap:14px;}
@media(max-width:960px){.grid-a,.grid-b{grid-template-columns:1fr;}}
</style>

<!-- Welcome -->
<div class="welcome">
  <div class="w-line"></div>
  <div class="w-inner">
    <div>
      <div class="w-tag">Tableau de bord</div>
      <div class="w-name">Bonjour, <strong><?= $nom ?></strong></div>
      <div class="w-meta">
        <i class="fas fa-calendar-check"></i>
        <span id="wdate"></span>
        <span class="w-role"><?= $role ?></span>
      </div>
    </div>
    <div class="w-stats">
      <div class="w-stat"><div class="w-stat-n" id="sc">—</div><div class="w-stat-l">Commandes</div></div>
      <div class="w-sep"></div>
      <div class="w-stat"><div class="w-stat-n" id="sf">—</div><div class="w-stat-l">Fournisseurs</div></div>
      <div class="w-sep"></div>
      <div class="w-stat"><div class="w-stat-n gd" id="sa">—</div><div class="w-stat-l">Alertes</div></div>
      <button class="btn-rf" onclick="loadAll()"><i class="fas fa-rotate-right" id="ri"></i>Actualiser</button>
    </div>
  </div>
</div>

<!-- KPIs -->
<div class="kpi-row" id="kpi-row">
  <?php for($i=0;$i<4;$i++): ?>
  <div class="kc"><div class="st-load"><i class="fas fa-spinner fa-spin"></i></div></div>
  <?php endfor; ?>
</div>

<!-- Ligne 1 -->
<div class="grid-a">
  <div class="card">
    <div class="card-head">
      <div class="card-head-title"><div class="card-head-icon" style="background:var(--info-bg);color:var(--info);"><i class="fas fa-file-contract"></i></div>Dernières Commandes</div>
      <a href="commandes.php" class="btn-sm">Tout voir <i class="fas fa-arrow-right"></i></a>
    </div>
    <div id="box-cmd"><div class="st-load"><i class="fas fa-spinner fa-spin"></i></div></div>
  </div>
  <div class="card">
    <div class="card-head">
      <div class="card-head-title"><div class="card-head-icon" style="background:var(--ok-bg);color:var(--ok);"><i class="fas fa-arrows-rotate"></i></div>Mouvements Stock</div>
      <a href="stock.php" class="btn-sm">Tout voir <i class="fas fa-arrow-right"></i></a>
    </div>
    <div id="box-mvt"><div class="st-load"><i class="fas fa-spinner fa-spin"></i></div></div>
  </div>
</div>

<!-- Ligne 2 -->
<div class="grid-b">
  <div class="card">
    <div class="card-head">
      <div class="card-head-title"><div class="card-head-icon" style="background:var(--warn-bg);color:var(--warn);"><i class="fas fa-triangle-exclamation"></i></div>Alertes Stock</div>
      <a href="articles.php" class="btn-sm">Tout voir <i class="fas fa-arrow-right"></i></a>
    </div>
    <div id="box-stk"><div class="st-load"><i class="fas fa-spinner fa-spin"></i></div></div>
  </div>
  <div class="card">
    <div class="card-head">
      <div class="card-head-title"><div class="card-head-icon" style="background:var(--crit-bg);color:var(--crit);"><i class="fas fa-clock-rotate-left"></i></div>Paiements Urgents</div>
      <a href="paiements.php" class="btn-sm">Tout voir <i class="fas fa-arrow-right"></i></a>
    </div>
    <div id="box-pai"><div class="st-load"><i class="fas fa-spinner fa-spin"></i></div></div>
  </div>
</div>

<script>
const API='http://localhost:3000/api';
document.getElementById('wdate').textContent=new Date().toLocaleDateString('fr-FR',{weekday:'long',day:'numeric',month:'long',year:'numeric'});

async function loadAll(){
  const ri=document.getElementById('ri');ri.classList.add('fa-spin');
  await Promise.all([loadKPIs(),loadAlertes(),loadActivite()]);
  setTimeout(()=>ri.classList.remove('fa-spin'),600);
}

async function loadKPIs(){
  try{
    const d=(await(await fetch(`${API}/dashboard/kpis`)).json()).data||{};
    document.getElementById('sc').textContent=d.commandes_en_cours??0;
    document.getElementById('sf').textContent=d.fournisseurs_actifs??0;
    document.getElementById('sa').textContent=(d.articles_en_alerte??0)+(d.paiements_en_retard??0);
    const kpis=[
      {v:d.commandes_en_cours??0,  l:'Commandes en cours', i:'fa-file-contract',     c:'var(--info)', bg:'var(--info-bg)', bd:'var(--info-bd)', t:'En cours'},
      {v:d.articles_en_alerte??0,  l:'Articles en alerte', i:'fa-triangle-exclamation',c:'var(--warn)',bg:'var(--warn-bg)', bd:'var(--warn-bd)', t:(d.articles_en_alerte??0)>0?'Attention':'Normal'},
      {v:d.paiements_en_retard??0, l:'Paiements en retard',i:'fa-clock-rotate-left',  c:'var(--crit)',bg:'var(--crit-bg)', bd:'var(--crit-bd)', t:(d.paiements_en_retard??0)>0?'Urgent':'Normal'},
      {v:d.fournisseurs_actifs??0, l:'Fournisseurs actifs', i:'fa-building',          c:'var(--ok)',  bg:'var(--ok-bg)',   bd:'var(--ok-bd)',   t:'Actifs'},
    ];
    document.getElementById('kpi-row').innerHTML=kpis.map((k,i)=>`
      <div class="kc" style="--kc-color:${k.c};--kc-bg:${k.bg};--kc-bd:${k.bd};animation:kUp .3s ${i*.06}s ease both;">
        <div class="kc-top">
          <div class="kc-ico"><i class="fas ${k.i}"></i></div>
          <span class="kc-tag">${k.t}</span>
        </div>
        <div class="kc-num">${k.v}</div>
        <div class="kc-lbl">${k.l}</div>
      </div>`).join('');
  }catch(e){}
}

async function loadAlertes(){
  try{
    const d=(await(await fetch(`${API}/dashboard/alertes`)).json()).data||{};
    const st=d.stock||[];
    document.getElementById('box-stk').innerHTML=st.length===0
      ?`<div class="st-ok"><div class="st-ok-icon"><i class="fas fa-check"></i></div><div class="st-ok-text">Tous les stocks sont suffisants</div></div>`
      :st.map(a=>`<div class="item-row">
          <div class="item-stripe" style="background:var(--warn);"></div>
          <div class="item-ico" style="background:var(--warn-bg);color:var(--warn);"><i class="fas fa-cube"></i></div>
          <div class="item-body"><div class="item-title">${a.nom}</div><div class="item-sub">${a.reference}</div></div>
          <div class="item-end">
            <div class="item-val" style="color:var(--crit);">${a.stock_actuel} <span style="font-size:.7rem;font-weight:400;color:var(--text4);">${a.unite}</span></div>
            <div class="item-val-sub">seuil: ${a.seuil_minimum}</div>
          </div>
        </div>`).join('');
    const pa=d.paiements||[];
    document.getElementById('box-pai').innerHTML=pa.length===0
      ?`<div class="st-ok"><div class="st-ok-icon"><i class="fas fa-check"></i></div><div class="st-ok-text">Aucun paiement en retard</div></div>`
      :pa.map(p=>`<div class="item-row">
          <div class="item-stripe" style="background:${p.statut==='en_retard'?'var(--crit)':'var(--warn)'};"></div>
          <div class="item-ico" style="background:var(--crit-bg);color:var(--crit);"><i class="fas fa-receipt"></i></div>
          <div class="item-body"><div class="item-title">${p.fournisseur_nom}</div><div class="item-sub">${fmtDate(p.date_echeance)}</div></div>
          <div class="item-end">
            <div class="item-val" style="color:var(--warn);">${fmt(p.montant)}</div>
            <div class="item-val-sub">FCFA</div>
          </div>
        </div>`).join('');
  }catch(e){}
}

async function loadActivite(){
  try{
    const d=(await(await fetch(`${API}/dashboard/activite`)).json()).data||{};
    const cm=d.commandes||[];
    document.getElementById('box-cmd').innerHTML=cm.length===0
      ?`<div class="st-empty"><i class="fas fa-inbox"></i><p>Aucune commande récente</p></div>`
      :`<div style="overflow-x:auto;"><table class="e-table">
          <thead><tr><th>N° Commande</th><th>Fournisseur</th><th>Montant TTC</th><th>Statut</th></tr></thead>
          <tbody>${cm.map(c=>`<tr>
            <td class="c-mono">${c.numero_commande}</td>
            <td class="c-main">${c.fournisseur_nom}</td>
            <td class="c-amt">${fmt(c.montant_total_ttc)} <span class="c-dim">FCFA</span></td>
            <td>${statusBadge(c.statut)}</td>
          </tr>`).join('')}</tbody>
        </table></div>`;
    const mv=d.mouvements||[];
    document.getElementById('box-mvt').innerHTML=mv.length===0
      ?`<div class="st-empty"><i class="fas fa-inbox"></i><p>Aucun mouvement récent</p></div>`
      :mv.map(m=>{
        const e=m.type_mouvement==='entree';
        return`<div class="item-row">
          <div class="item-ico" style="background:${e?'var(--ok-bg)':'var(--crit-bg)'};color:${e?'var(--ok)':'var(--crit)'};">
            <i class="fas fa-arrow-${e?'down':'up'}"></i>
          </div>
          <div class="item-body"><div class="item-title">${m.article_nom}</div><div class="item-sub">${e?'Entrée en stock':'Sortie de stock'}</div></div>
          <div class="item-end">
            <div class="item-val" style="color:${e?'var(--ok)':'var(--crit)'};">${e?'+':'−'}${m.quantite}</div>
            <div class="item-val-sub">${m.unite||''}</div>
          </div>
        </div>`;}).join('');
  }catch(e){}
}

const _s=document.createElement('style');
_s.textContent=`@keyframes kUp{from{opacity:0;transform:translateY(10px);}to{opacity:1;transform:translateY(0);}}`;
document.head.appendChild(_s);
loadAll();
</script>
<?php require_once 'includes/footer.php'; ?>