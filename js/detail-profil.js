document.addEventListener('DOMContentLoaded', async () => {
    const params = new URLSearchParams(window.location.search);
    const id = params.get('id');

    if (!id) {
        document.getElementById('cv-message').textContent = "Aucun profil sélectionné. Redirection vers le catalogue...";
        setTimeout(() => {
            window.location.href = 'catalogue.php';
        }, 2000);
        return;
    }

    try {
        const response = await fetch(`../api/profil.php?id=${id}`);
        if (!response.ok) throw new Error("Erreur de chargement du profil");
        const profil = await response.json();
        afficherProfil(profil);
    } catch (err) {
        console.error(err);
        document.getElementById('cv-message').textContent = "Erreur lors du chargement du profil.";
    }
});

function afficherProfil(profil) {
    document.title = `CV — ${profil.prenom} ${profil.nom}`;
    document.querySelector('h1').textContent = `${profil.prenom} ${profil.nom}`;
    document.querySelector('.identite-header p:last-child').textContent = profil.titre;
    
    document.getElementById('cv-photo').src = profil.photo ? `../uploads/photos/${profil.photo.split('/').pop()}` : window.JUNIA_DEFAULT_PHOTO;
    
    document.getElementById('cv-message').textContent = "Profil chargé depuis le serveur.";
    
    // Contact infos (header)
    const contactList = document.getElementById('cv-contact');
    if (contactList) {
        contactList.innerHTML = '';
        if (profil.email) {
            contactList.innerHTML += `<li><a href="mailto:${profil.email}">${profil.email}</a></li>`;
        }
        if (profil.ville) {
            contactList.innerHTML += `<li>${profil.ville}</li>`;
        }
        if (profil.linkedin) {
            contactList.innerHTML += `<li><a href="${profil.linkedin}" target="_blank">LinkedIn</a></li>`;
        }
    }

    // Compétences
    const compUl = document.getElementById('cv-competences');
    compUl.innerHTML = '';
    profil.competences.forEach(c => {
        compUl.innerHTML += `<li>${c}</li>`;
    });

    // Langues
    const langUl = document.getElementById('cv-langues');
    langUl.innerHTML = '';
    profil.langues.forEach(l => {
        langUl.innerHTML += `<li>${l}</li>`;
    });

    // Liens
    const liensUl = document.getElementById('cv-liens');
    liensUl.innerHTML = '';
    if (profil.github) {
        liensUl.innerHTML += `<li><a href="${profil.github}" target="_blank">GitHub</a></li>`;
    }

    // Profil (Bio)
    const bioSec = document.getElementById('section-profil');
    const bioP = document.getElementById('cv-profil');
    if (profil.profil) {
        bioSec.hidden = false;
        bioP.textContent = profil.profil;
    }

    // Formations
    const formDiv = document.getElementById('cv-formations');
    formDiv.innerHTML = '';
    profil.formations.forEach(f => {
        formDiv.innerHTML += `
            <article>
                <h3>${f.titre}</h3>
                <p class="dates"><em>${f.dates}</em></p>
                <p>${f.description}</p>
            </article>
        `;
    });

    // Experiences
    const expDiv = document.getElementById('cv-experiences');
    expDiv.innerHTML = '';
    profil.experiences.forEach(e => {
        expDiv.innerHTML += `
            <article>
                <h3>${e.titre}</h3>
                <p class="dates"><em>${e.dates}</em></p>
                <p>${e.description}</p>
            </article>
        `;
    });

    // Projets
    const projUl = document.getElementById('cv-projets');
    projUl.innerHTML = '';
    profil.projets.forEach(p => {
        projUl.innerHTML += `<li><strong>${p.titre}</strong> — ${p.description} <span class="dates"><em>${p.dates}</em></span></li>`;
    });

    // Centres interet
    const intUl = document.getElementById('cv-centres-interet');
    intUl.innerHTML = '';
    profil.centresInteret.forEach(c => {
        intUl.innerHTML += `<li>${c}</li>`;
    });
}
