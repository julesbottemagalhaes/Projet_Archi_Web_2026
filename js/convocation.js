document.addEventListener('DOMContentLoaded', () => {
    const btnOuvrir = document.getElementById('btn-ouvrir-convocation');
    const modal = document.getElementById('modal-convocation');
    const btnFermer = document.getElementById('btn-fermer-modal');
    const form = document.getElementById('form-convocation');
    const resultat = document.getElementById('conv-resultat');

    if (btnOuvrir && modal) {
        btnOuvrir.addEventListener('click', () => {
            modal.showModal();
        });

        btnFermer.addEventListener('click', () => {
            modal.close();
            form.reset();
            resultat.textContent = '';
        });

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const params = new URLSearchParams(window.location.search);
            const etudiant_id = params.get('id');
            
            if (!etudiant_id) {
                resultat.textContent = "Erreur: ID étudiant manquant.";
                return;
            }

            const data = {
                etudiant_id: parseInt(etudiant_id),
                date: document.getElementById('conv-date').value,
                heure: document.getElementById('conv-heure').value,
                lieu: document.getElementById('conv-lieu').value,
                type_contrat: document.getElementById('conv-contrat').value,
                message: document.getElementById('conv-message').value
            };

            try {
                const response = await fetch('../api/convocation.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });
                
                const json = await response.json();
                
                if (response.ok) {
                    resultat.style.color = "green";
                    resultat.textContent = "Convocation envoyée avec succès !";
                    setTimeout(() => {
                        modal.close();
                        form.reset();
                        resultat.textContent = '';
                    }, 2000);
                } else {
                    resultat.style.color = "red";
                    resultat.textContent = json.error || "Une erreur est survenue.";
                }
            } catch (err) {
                console.error(err);
                resultat.style.color = "red";
                resultat.textContent = "Erreur de connexion au serveur.";
            }
        });
    }

    // Gestion de l'historique des convocations
    const tableHistorique = document.getElementById('tbody-historique');
    if (tableHistorique) {
        chargerHistorique();
    }
});

async function chargerHistorique() {
    const tableHistorique = document.getElementById('tbody-historique');
    try {
        const response = await fetch('../api/historique-convocations.php');
        const convocations = await response.json();
        
        tableHistorique.innerHTML = '';
        
        if (convocations.length === 0) {
            tableHistorique.innerHTML = '<tr><td colspan="5">Aucune convocation envoyée.</td></tr>';
            return;
        }

        convocations.forEach(c => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td><a href="detail-profil.php?id=${c.etudiant_id}">${c.etudiant_nom}</a></td>
                <td>${c.type_contrat}</td>
                <td>${c.date_rdv} à ${c.heure_rdv}</td>
                <td>${c.lieu}</td>
                <td><strong>${c.statut}</strong></td>
            `;
            tableHistorique.appendChild(tr);
        });
    } catch (err) {
        console.error(err);
        tableHistorique.innerHTML = '<tr><td colspan="5">Erreur de chargement.</td></tr>';
    }
}
