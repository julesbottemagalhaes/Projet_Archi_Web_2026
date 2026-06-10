(() => {
    const API_BASE = window.JUNIA_API_BASE || "../api";
    const currentUser = window.JUNIA_CURRENT_USER || null;

    const $ = (selector, root = document) => root.querySelector(selector);

    let modalBound = false;

    const createText = (tag, text) => {
        const element = document.createElement(tag);
        element.textContent = text || "";
        return element;
    };

    const bindModal = () => {
        if (modalBound || currentUser?.role !== "company") return;

        const button = $("#btn-ouvrir-convocation");
        const modal = $("#modal-convocation");
        const close = $("#btn-fermer-modal");
        const form = $("#form-convocation");
        const result = $("#conv-resultat");

        if (!button || !modal || !form) return;

        modalBound = true;

        button.addEventListener("click", () => {
            result.textContent = "";
            modal.showModal();
        });

        close?.addEventListener("click", () => {
            form.reset();
            result.textContent = "";
            modal.close();
        });

        form.addEventListener("submit", async (event) => {
            event.preventDefault();

            const studentId = window.JUNIA_PROFILE_ID;
            if (!studentId) {
                result.textContent = "Profil étudiant introuvable.";
                return;
            }

            const payload = {
                etudiant_id: studentId,
                date: form.elements.date.value,
                heure: form.elements.heure.value,
                lieu: form.elements.lieu.value.trim(),
                type_contrat: form.elements.contrat.value,
                message: form.elements.message.value.trim()
            };

            const submit = $("button[type='submit']", form);
            submit.disabled = true;
            result.className = "message-cv";
            result.textContent = "Envoi de la convocation...";

            try {
                const response = await fetch(`${API_BASE}/convocation.php`, {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    credentials: "include",
                    body: JSON.stringify(payload)
                });
                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.error || "Convocation impossible.");
                }

                result.className = "message-cv succes";
                result.textContent = "Convocation enregistrée.";
                setTimeout(() => {
                    form.reset();
                    modal.close();
                    result.textContent = "";
                }, 1200);
            } catch (exception) {
                result.className = "erreur";
                result.textContent = exception.message;
            } finally {
                submit.disabled = false;
            }
        });
    };

    const loadHistory = async () => {
        const tbody = $("#tbody-historique");
        if (!tbody) return;

        try {
            const response = await fetch(`${API_BASE}/historique-convocations.php`, {
                credentials: "include"
            });
            const rows = await response.json();

            if (!response.ok) {
                throw new Error(rows.error || "Chargement impossible.");
            }

            tbody.replaceChildren();

            if (!rows.length) {
                const row = document.createElement("tr");
                const cell = document.createElement("td");
                cell.colSpan = 5;
                cell.textContent = "Aucune convocation envoyée.";
                row.append(cell);
                tbody.append(row);
                return;
            }

            rows.forEach((convocation) => {
                const row = document.createElement("tr");

                const studentCell = document.createElement("td");
                const link = document.createElement("a");
                link.href = `detail-profil.php?id=${encodeURIComponent(convocation.etudiant_id)}`;
                link.textContent = convocation.etudiant_nom;
                studentCell.append(link);

                row.append(
                    studentCell,
                    createText("td", convocation.type_contrat),
                    createText("td", `${convocation.date_rdv} à ${convocation.heure_rdv}`),
                    createText("td", convocation.lieu),
                    createText("td", convocation.statut)
                );
                tbody.append(row);
            });
        } catch (exception) {
            tbody.replaceChildren();
            const row = document.createElement("tr");
            const cell = document.createElement("td");
            cell.colSpan = 5;
            cell.textContent = exception.message;
            row.append(cell);
            tbody.append(row);
        }
    };

    document.addEventListener("DOMContentLoaded", () => {
        bindModal();
        loadHistory();
    });

    document.addEventListener("junia:profile-loaded", bindModal);
})();
