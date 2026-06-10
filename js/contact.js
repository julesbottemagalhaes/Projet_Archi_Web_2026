document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('form');
    if (!form) return;

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const data = {
            nom: document.getElementById('nom').value,
            email: document.getElementById('email').value,
            message: document.getElementById('message').value
        };

        const btnSubmit = form.querySelector('button[type="submit"]');
        const textInitial = btnSubmit.textContent;
        btnSubmit.disabled = true;
        btnSubmit.textContent = "Envoi...";

        try {
            const response = await fetch('../api/contact.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            const res = await response.json();

            if (res.success) {
                alert("Votre demande a bien été envoyée ! Un administrateur va la traiter prochainement.");
                form.reset();
            } else {
                alert(res.error || "Une erreur est survenue lors de l'envoi.");
            }
        } catch (err) {
            console.error(err);
            alert("Erreur réseau. Impossible d'envoyer la demande.");
        } finally {
            btnSubmit.disabled = false;
            btnSubmit.textContent = textInitial;
        }
    });
});
