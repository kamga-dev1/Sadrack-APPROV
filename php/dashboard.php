<?php
require_once 'includes/config.php';
if (empty($_SESSION['user'])) { header('Location: login.php'); exit; }
?>
<?php require_once 'includes/header.php'; ?>
<?php require_once 'includes/sidebar.php'; ?>

<script>document.getElementById('nav-page-title').textContent='Tableau de bord';</script>

<!-- ══ PAGE HEADER ══ -->
<div class="page-header">
  <div class="page-header-left">
    <h1 class="page-title"><i class="fas fa-gauge-high"></i>Tableau de bord</h1>
    <p class="page-sub">
      Bonjour <strong style="color:var(--gold)"><?= htmlspecialchars($_SESSION['user']['nom']) ?></strong>
      — <?= strftime('%A %d %B %Y') ?? date('d/m/Y') ?>
    </p>
  </div>
  <button class="btn-ghost" onclick="loadAll()">
    <i class="fas fa-rotate-right"></i>Actualiser
  </button>
</div>

<!-- ══ KPI CARDS ══ -->
<div class="row g-3 mb-4" id="kpi-row">
  <?php for($i=0;$i<4;$i++): ?>
  <div class="col-sm-6 col-xl-3">
    <div class="kpi-card" style="min-height:110px;display:flex;align-items:center;justify-content:center;">
      <i class="fas fa-spinner fa-spin" style="color:var(--txt-sub);font-size:1.2rem;"></i>
    </div>
  </div>
  <?php endfor; ?>
</div>

<!-- ══ ROW 2 : ALERTES ══ -->
<div class="row g-3 mb-4">

  <!-- Alertes stock -->
  <div class="col-lg-6">
    <div class="panel h-100">
      <div class="panel-head">
        <div class="panel-title">
          <i class="fas fa-triangle-exclamation"></i>Alertes Stock
        </div>
        <a href="articles.php" class="btn-ghost" style="padding:5px 11px;font-size:0.7rem;">
          Voir tout <i class="fas fa-arrow-right"></i>
        </a>
      </div>
      <div id="alertes-stock" style="padding:0;">
        <div style="padding:24px;text-align:center;"><i class="fas fa-spinner fa-spin" style="color:var(--txt-sub);"></i></div>
      </div>
    </div>
  </div>

  <!-- Paiements urgents -->
  <div class="col-lg-6">
    <div class="panel h-100">
      <div class="panel-head">
        <div class="panel-title">
          <i class="fas fa-clock" style="color:var(--red);"></i>Paiements Urgents
        </div>
        <a href="paiements.php" class="btn-ghost" style="padding:5px 11px;font-size:0.7rem;">
          Voir tout <i class="fas fa-arrow-right"></i>
        </a>
      </div>
      <div id="alertes-paiements" style="padding:0;">
        <div style="padding:24px;text-align:center;"><i class="fas fa-spinner fa-spin" style="color:var(--txt-sub);"></i></div>
      </div>
    </div>
  </div>

</div>

<!-- ══ ROW 3 : ACTIVITÉ ══ -->
<div class="row g-3">

  <!-- Dernières commandes -->
  <div class="col-lg-7">
    <div class="panel">
      <div class="panel-head">
        <div class="panel-title">
          <i class="fas fa-file-invoice"></i>Dernières Commandes
        </div>
        <a href="commandes.php" class="btn-ghost" style="padding:5px 11px;font-size:0.7rem;">
          Voir tout <i class="fas fa-arrow-right"></i>
        </a>
      </div>
      <div id="dernieres-commandes">
        <div style="padding:24px;text-align:center;"><i class="fas fa-spinner fa-spin" style="color:var(--txt-sub);"></i></div>
      </div>
    </div>
  </div>

  <!-- Mouvements stock -->
  <div class="col-lg-5">
    <div class="panel">
      <div class="panel-head">
        <div class="panel-title">
          <i class="fas fa-arrows-rotate" style="color:var(--green);"></i>Mouvements Stock
        </div>
        <a href="stock.php" class="btn-ghost" style="padding:5px 11px;font-size:0.7rem;">
          Voir tout <i class="fas fa-arrow-right"></i>
        </a>
      </div>
      <div id="derniers-mouvements">
        <div style="padding:24px;text-align:center;"><i class="fas fa-spinner fa-spin" style="color:var(--txt-sub);"></i></div>
      </div>
    </div>
  </div>

</div>

<script>
const API = 'http://localhost:3000/api';

async function loadAll(){
  await Promise.all([loadKPIs(), loadAlertes(), loadActivite()]);
}

/* ── KPIs ── */
async function loadKPIs(){
  try{
    const r = await fetch(`${API}/dashboard/kpis`);
    const d = await r.json();
    const k = d.data || {};

    const cards = [
      {val: k.commandes_en_cours ?? 0,    lbl:'Commandes en cours',   icon:'fa-file-invoice',    color:'#60A5FA', bg:'rgba(96,165,250,0.1)'},
      {val: k.articles_en_alerte ?? 0,    lbl:'Articles en alerte',   icon:'fa-triangle-exclamation', color:'#FBBF24', bg:'rgba(251,191,36,0.1)'},
      {val: k.paiements_en_retard ?? 0,   lbl:'Paiements en retard',  icon:'fa-clock',           color:'#F87171', bg:'rgba(248,113,113,0.1)'},
      {val: k.fournisseurs_actifs ?? 0,   lbl:'Fournisseurs actifs',  icon:'fa-building',        color:'#34D399', bg:'rgba(52,211,153,0.1)'},
    ];

    document.getElementById('kpi-row').innerHTML = cards.map((c,i) => `
      <div class="col-sm-6 col-xl-3" style="animation:fadeUp 0.4s ${i*0.07}s ease both;">
        <div class="kpi-card">
          <div class="kpi-icon" style="background:${c.bg};">
            <i class="fas ${c.icon}" style="color:${c.color};font-size:1.1rem;"></i>
          </div>
          <div class="kpi-val">${c.val}</div>
          <div class="kpi-lbl">${c.lbl}</div>
        </div>
      </div>
    `).join('');

  }catch(e){
    document.getElementById('kpi-row').innerHTML =
      '<div class="col-12"><div class="alert-danger-dark"><i class="fas fa-circle-xmark"></i>Erreur chargement KPIs</div></div>';
  }
}

/* ── ALERTES ── */
async function loadAlertes(){
  try{
    const r = await fetch(`${API}/dashboard/alertes`);
    const d = await r.json();

    /* Stock */
    const st = d.data.stock || [];
    if(st.length === 0){
      document.getElementById('alertes-stock').innerHTML =
        `<div style="padding:20px;text-align:center;color:var(--green);font-size:0.8rem;">
           <i class="fas fa-circle-check" style="font-size:1.4rem;display:block;margin-bottom:6px;"></i>
           Tous les stocks sont suffisants
         </div>`;
    } else {
      document.getElementById('alertes-stock').innerHTML = `
        <div style="overflow-x:auto;">
          <table class="dark-table">
            <thead><tr>
              <th>Article</th><th>Stock</th><th>Seuil</th>
            </tr></thead>
            <tbody>
              ${st.map(a=>`<tr>
                <td><strong>${a.nom}</strong><br><span style="font-size:0.68rem;color:var(--txt-sub);">${a.reference}</span></td>
                <td><span class="sbadge sbadge-danger">${a.stock_actuel} ${a.unite}</span></td>
                <td style="color:var(--txt-sub);">${a.seuil_minimum} ${a.unite}</td>
              </tr>`).join('')}
            </tbody>
          </table>
        </div>`;
    }

    /* Paiements */
    const pa = d.data.paiements || [];
    if(pa.length === 0){
      document.getElementById('alertes-paiements').innerHTML =
        `<div style="padding:20px;text-align:center;color:var(--green);font-size:0.8rem;">
           <i class="fas fa-circle-check" style="font-size:1.4rem;display:block;margin-bottom:6px;"></i>
           Aucun paiement urgent
         </div>`;
    } else {
      document.getElementById('alertes-paiements').innerHTML = `
        <div style="overflow-x:auto;">
          <table class="dark-table">
            <thead><tr>
              <th>Fournisseur</th><th>Montant</th><th>Échéance</th>
            </tr></thead>
            <tbody>
              ${pa.map(p=>`<tr>
                <td><strong>${p.fournisseur_nom}</strong></td>
                <td style="color:var(--gold);font-weight:700;">${fmt(p.montant)} FCFA</td>
                <td>${badgeStatut(p.statut)} <span style="font-size:0.72rem;color:var(--txt-sub);">${fmtDate(p.date_echeance)}</span></td>
              </tr>`).join('')}
            </tbody>
          </table>
        </div>`;
    }
  }catch(e){}
}

/* ── ACTIVITÉ ── */
async function loadActivite(){
  try{
    const r = await fetch(`${API}/dashboard/activite`);
    const d = await r.json();

    /* Commandes */
    const cm = d.data.commandes || [];
    document.getElementById('dernieres-commandes').innerHTML = `
      <div style="overflow-x:auto;">
        <table class="dark-table">
          <thead><tr>
            <th>Numéro</th><th>Fournisseur</th><th>Montant TTC</th><th>Statut</th>
          </tr></thead>
          <tbody>
            ${cm.length===0
              ? '<tr><td colspan="4" style="text-align:center;padding:20px;color:var(--txt-sub);">Aucune commande</td></tr>'
              : cm.map(c=>`<tr>
                  <td><strong>${c.numero_commande}</strong></td>
                  <td>${c.fournisseur_nom}</td>
                  <td style="color:var(--gold);font-weight:700;">${fmt(c.montant_total_ttc)} FCFA</td>
                  <td>${badgeStatut(c.statut)}</td>
                </tr>`).join('')
            }
          </tbody>
        </table>
      </div>`;

    /* Mouvements */
    const mv = d.data.mouvements || [];
    document.getElementById('derniers-mouvements').innerHTML = `
      <div style="overflow-x:auto;">
        <table class="dark-table">
          <thead><tr>
            <th>Article</th><th>Type</th><th>Qté</th>
          </tr></thead>
          <tbody>
            ${mv.length===0
              ? '<tr><td colspan="3" style="text-align:center;padding:20px;color:var(--txt-sub);">Aucun mouvement</td></tr>'
              : mv.map(m=>`<tr>
                  <td><strong>${m.article_nom}</strong></td>
                  <td>${badgeStatut(m.type_mouvement)}</td>
                  <td style="font-weight:700;">${m.quantite}</td>
                </tr>`).join('')
            }
          </tbody>
        </table>
      </div>`;
  }catch(e){}
}

/* Animation */
const style=document.createElement('style');
style.textContent=`
  @keyframes fadeUp{from{opacity:0;transform:translateY(14px);}to{opacity:1;transform:translateY(0);}}
  .row>div{animation:fadeUp 0.4s ease both;}
`;
document.head.appendChild(style);

loadAll();
</script>

<?php require_once 'includes/footer.php'; ?>