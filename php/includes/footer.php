</main>
</div><!-- .layout -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Dropdown user
function toggleDropdown(){
  document.getElementById('navDropdown').classList.toggle('show');
}
document.addEventListener('click', function(e){
  if (!e.target.closest('.nav-user')) {
    document.getElementById('navDropdown').classList.remove('show');
  }
});

// Alertes stock badge
async function loadAlertBadge(){
  try{
    const r = await fetch('http://localhost:3000/api/dashboard/alertes');
    const d = await r.json();
    const n = d.data?.stock?.length || 0;
    const nb = document.getElementById('nav-badge');
    const sb = document.getElementById('side-alert-badge');
    if(n > 0){
      if(nb){nb.textContent=n;nb.style.display='flex';}
      if(sb){sb.textContent=n;sb.style.display='inline-flex';}
    }
  }catch(e){}
}
loadAlertBadge();

// Alertes globales
function showSuccess(msg){
  const z=document.getElementById('alert-zone');
  if(z){z.innerHTML=`<div class="alert-success-dark"><i class="fas fa-circle-check"></i>${msg}</div>`;setTimeout(()=>z.innerHTML='',4000);}
}
function showError(msg){
  const z=document.getElementById('alert-zone');
  if(z){z.innerHTML=`<div class="alert-danger-dark"><i class="fas fa-circle-xmark"></i>${msg}</div>`;}
}
function badgeStatut(s){
  const m={
    brouillon:'muted',en_attente:'warning',confirmee:'info',
    expediee:'gold',recue_partielle:'warning',recue_totale:'success',annulee:'danger',
    actif:'success',inactif:'muted',suspendu:'warning',
    en_retard:'danger',paye:'success',partielle:'warning',complete:'success'
  };
  return `<span class="sbadge sbadge-${m[s]||'muted'}">${s.replace(/_/g,' ')}</span>`;
}
function fmt(n){return Number(n||0).toLocaleString('fr-FR');}
function fmtDate(d){return d?new Date(d).toLocaleDateString('fr-FR'):'—';}
</script>
</body>
</html>