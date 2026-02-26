</main>
</div><!-- fin .d-flex -->

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Utilitaires JS -->
<script src="/assets/js/utils.js"></script>
<script src="/assets/js/api.js"></script>

<script>
// Charger le nombre d'alertes stock dans la navbar
async function chargerAlertes() {
    try {
        const res = await fetch('http://localhost:3000/api/dashboard/alertes');
        const data = await res.json();
        if (data.success) {
            const nb = data.data.stock.length;
            const badge = document.getElementById('badge-alertes');
            if (badge) {
                badge.textContent = nb;
                badge.style.display = nb > 0 ? 'inline' : 'none';
            }
        }
    } catch (e) {}
}
chargerAlertes();
</script>

</body>
</html>