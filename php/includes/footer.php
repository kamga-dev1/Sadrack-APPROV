</div><!-- .page -->
</div><!-- .app-wrap -->
<script>
document.addEventListener('click',function(e){
  if(!e.target.closest('.t-user'))document.querySelectorAll('.t-drop').forEach(d=>d.classList.remove('open'));
});
(async function(){
  try{
    const d=(await(await fetch('http://localhost:3000/api/dashboard/alertes')).json()).data||{};
    const n=d.stock?.length||0;
    if(n>0){
      const p=document.getElementById('bell-pip');const s=document.getElementById('side-badge');
      if(p)p.style.display='block';
      if(s){s.textContent=n;s.style.display='inline-block';}
    }
  }catch(e){}
})();
function showSuccess(msg){const z=document.getElementById('alert-zone');if(z){z.innerHTML=`<div class="al-ok"><i class="fas fa-circle-check"></i>${msg}</div>`;setTimeout(()=>z.innerHTML='',4000);}}
function showError(msg){const z=document.getElementById('alert-zone');if(z){z.innerHTML=`<div class="al-err"><i class="fas fa-circle-xmark"></i>${msg}</div>`;}}
function fmt(n){return Number(n||0).toLocaleString('fr-FR');}
function fmtDate(d){return d?new Date(d).toLocaleDateString('fr-FR',{day:'2-digit',month:'2-digit',year:'numeric'}):'—';}
function statusBadge(s){
  const m={brouillon:['sb-muted','Brouillon'],en_attente:['sb-warn','En attente'],confirmee:['sb-info','Confirmée'],expediee:['sb-gold','Expédiée'],recue_partielle:['sb-warn','Reçue partielle'],recue_totale:['sb-ok','Reçue totale'],annulee:['sb-crit','Annulée'],actif:['sb-ok','Actif'],inactif:['sb-muted','Inactif'],suspendu:['sb-warn','Suspendu'],paye:['sb-ok','Payé'],en_retard:['sb-crit','En retard'],partielle:['sb-warn','Partielle'],complete:['sb-ok','Complète']};
  const[c,l]=m[s]||['sb-muted',s.replace(/_/g,' ')];
  return`<span class="sbadge ${c}">${l}</span>`;
}
</script>
</body></html>