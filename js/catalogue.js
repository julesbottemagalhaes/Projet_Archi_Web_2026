let pageActuelle = 1;

document.addEventListener('DOMContentLoaded', () => {
    chargerProfils(1);

    document.getElementById('search-input').addEventListener('keypress', (e) => {
        if (e.key === 'Enter') chargerProfils(1);
    });
    document.getElementById('competence-input').addEventListener('keypress', (e) => {
        if (e.key === 'Enter') chargerProfils(1);
    });
    document.getElementById('ecole-input').addEventListener('keypress', (e) => {
        if (e.key === 'Enter') chargerProfils(1);
    });
    document.getElementById('domaine-select').addEventListener('change', () => {
        chargerProfils(1);
    });
});

async function chargerProfils(page = 1) {
    pageActuelle = page;
    const search = document.getElementById('search-input').value;
    const competence = document.getElementById('competence-input').value;
    const ecole = document.getElementById('ecole-input').value;
    const domaine = document.getElementById('domaine-select').value;

    const queryParams = new URLSearchParams({
        page,
        search,
        competence,
        ecole,
        domaine
    });

    try {
        const response = await fetch(`../api/profils.php?${queryParams.toString()}`);
        const profils = await response.json();
        
        afficherProfils(profils);
        
        // Gérer la pagination visuellement
        document.getElementById('page-info').textContent = `Page ${pageActuelle}`;
        document.getElementById('btn-prev').hidden = pageActuelle === 1;
        document.getElementById('btn-next').hidden = profils.length < 10;
        
    } catch (err) {
        console.error("Erreur lors du chargement des profils :", err);
    }
}

function afficherProfils(profils) {
    const grille = document.getElementById('grille-profils');
    grille.innerHTML = '';

    if (profils.length === 0) {
        grille.innerHTML = '<p>Aucun profil trouvé correspondant à vos critères.</p>';
        return;
    }

    profils.forEach(profil => {
        const card = document.createElement('article');
        card.className = 'carte-profil';
        
        const photoUrl = profil.photo ? profil.photo : '../uploads/photos/photo_profil.png';
        
        const tags = profil.domaines.map(d => `<span class="tag-domaine">${d}</span>`).join(' ');

        card.innerHTML = `
            <img src="${photoUrl}" alt="Photo de ${profil.nom}" loading="lazy">
            <div class="infos">
                <h3>${profil.nom}</h3>
                <p class="titre-etudiant"><em>${profil.titre}</em></p>
                <p class="bio">${profil.biographie}</p>
                <div class="tags">${tags}</div>
                <a class="bouton bouton-secondaire" href="detail-profil.php?id=${profil.id}">Consulter le profil</a>
            </div>
        `;
        grille.appendChild(card);
    });
}

function changerPage(offset) {
    chargerProfils(pageActuelle + offset);
}
