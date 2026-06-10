(() => {
    const API_BASE = window.JUNIA_API_BASE || "../api";

    document.addEventListener("DOMContentLoaded", () => {
        const form = document.querySelector("form");
        const message = document.getElementById("contact-message");
        if (!form || !message) return;

        form.addEventListener("submit", async (event) => {
            event.preventDefault();
            if (!form.reportValidity()) return;

            const submit = form.querySelector("button[type='submit']");
            const initialText = submit.textContent;
            submit.disabled = true;
            submit.textContent = "Envoi...";
            message.textContent = "";

            try {
                const response = await fetch(`${API_BASE}/contact.php`, {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    credentials: "include",
                    body: JSON.stringify({
                        nom: form.elements.nom.value.trim(),
                        email: form.elements.email.value.trim(),
                        message: form.elements.message.value.trim()
                    })
                });
                const payload = await response.json();

                if (!response.ok) {
                    throw new Error(payload.error || "Demande impossible.");
                }

                form.reset();
                message.textContent = "Demande envoyée. Un administrateur pourra la traiter.";
            } catch (exception) {
                message.textContent = exception.message;
            } finally {
                submit.disabled = false;
                submit.textContent = initialText;
            }
        });
    });
})();
