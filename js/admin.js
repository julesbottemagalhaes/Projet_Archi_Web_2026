document.addEventListener('DOMContentLoaded', () => {
    const pageId = document.body.id || document.querySelector('h2')?.textContent.toLowerCase() || '';

    if (pageId.includes('tableau de bord') || window.location.pathname.includes('dashboard.php')) {
        chargerStats();
    } else if (window.location.pathname.includes('utilisateurs.php')) {
        chargerUtilisateurs('etudiants');
        
        document.getElementById('filtre-type')?.addEventListener('change', (e) => {
            chargerUtilisateurs(e.target.value);
        });
    } else if (window.location.pathname.includes('entreprises.php')) {
        chargerUtilisateurs('entreprises');
    } else if (window.location.pathname.includes('demandes-contact.php')) {
        chargerDemandes();
    }
});

async function chargerStats() {
    try {
        const response = await fetch('../../api/admin.php?action=stats');
        const stats = await response.json();
        
        const articles = document.querySelectorAll('.stats-admin article strong');
        if (articles.length >= 3 && !stats.error) {
            articles[0].textContent = stats.etudiants;
            articles[1].textContent = stats.entreprises;
            articles[2].textContent = stats.convocations;
        }
    } catch (e) {
        console.error('Erreur chargement stats:', e);
    }
}

async function chargerUtilisateurs(type) {
    const tbody = document.getElementById('tbody-utilisateurs');
    if (!tbody) return;

    try {
        const response = await fetch(`../../api/admin.php?action=users&type=${type}`);
        const users = await response.json();
        
        tbody.innerHTML = '';
        if (users.length === 0) {
            tbody.innerHTML = '<tr><td colspan="4">Aucun utilisateur.</td></tr>';
            return;
        }

        users.forEach(u => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${u.nom}</td>
                <td>${u.email}</td>
                <td>${u.date_creation}</td>
                <td>
                    <button class="bouton-discret" onclick="supprimerUtilisateur(${u.id}, '${type}')">Supprimer</button>
                </td>
            `;
            tbody.appendChild(tr);
        });
    } catch (e) {
        console.error('Erreur chargement utilisateurs:', e);
    }
}

async function supprimerUtilisateur(id, type) {
    if (!confirm("Voulez-vous vraiment supprimer cet utilisateur ?")) return;

    try {
        const response = await fetch('../../api/admin.php?action=delete_user', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id, type })
        });
        const res = await response.json();
        
        if (res.success) {
            chargerUtilisateurs(type);
        } else {
            alert(res.error || "Erreur de suppression");
        }
    } catch (e) {
        console.error(e);
    }
}

async function chargerDemandes() {
    const tbody = document.getElementById('tbody-demandes');
    if (!tbody) return;

    try {
        const response = await fetch('../../api/admin.php?action=contact_requests');
        const reqs = await response.json();
        
        tbody.innerHTML = '';
        if (reqs.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5">Aucune demande.</td></tr>';
            return;
        }

        reqs.forEach(r => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${r.nom_entreprise}</td>
                <td>${r.email_contact}</td>
                <td>${r.message}</td>
                <td><strong>${r.statut}</strong></td>
                <td>
                    ${r.statut === 'en attente' ? `<button class="bouton-primaire" onclick="validerDemande(${r.id})">Valider</button>` : ''}
                </td>
            `;
            tbody.appendChild(tr);
        });
    } catch (e) {
        console.error(e);
    }
}

async function validerDemande(id) {
    try {
        const response = await fetch('../../api/admin.php?action=approve_contact', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id })
        });
        const res = await response.json();
        
        if (res.success) {
            chargerDemandes();
        }
    } catch (e) {
        console.error(e);
    }
}
