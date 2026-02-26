// Formater un montant en FCFA
function formatMontant(montant) {
    return new Intl.NumberFormat('fr-FR').format(montant) + ' FCFA';
}

// Formater une date
function formatDate(date) {
    if (!date) return '—';
    return new Date(date).toLocaleDateString('fr-FR');
}

// Badge statut commande
function badgeStatut(statut) {
    const badges = {
        'brouillon':       'secondary',
        'en_attente':      'warning',
        'confirmee':       'info',
        'expediee':        'primary',
        'recue_partielle': 'warning',
        'recue_totale':    'success',
        'annulee':         'danger',
        'en_retard':       'danger',
        'paye':            'success',
        'actif':           'success',
        'inactif':         'secondary',
        'suspendu':        'warning',
    };
    const color = badges[statut] || 'secondary';
    return `<span class="badge bg-${color}">${statut.replace('_', ' ')}</span>`;
}

// Afficher alerte succès
function showSuccess(msg) {
    const el = document.getElementById('alert-zone');
    if (el) {
        el.innerHTML = `
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-circle-check me-2"></i>${msg}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>`;
        setTimeout(() => el.innerHTML = '', 4000);
    }
}

// Afficher alerte erreur
function showError(msg) {
    const el = document.getElementById('alert-zone');
    if (el) {
        el.innerHTML = `
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-circle-xmark me-2"></i>${msg}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>`;
    }
}

// Confirmer suppression
function confirmer(msg = 'Êtes-vous sûr ?') {
    return confirm(msg);
}