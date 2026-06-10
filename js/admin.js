(() => {
    const API_BASE = window.JUNIA_API_BASE || "../../api";

    const $ = (selector, root = document) => root.querySelector(selector);

    const createCell = (text) => {
        const cell = document.createElement("td");
        cell.textContent = text || "";
        return cell;
    };

    const fetchJson = async (url, options = {}) => {
        const response = await fetch(url, {
            credentials: "include",
            ...options,
            headers: {
                ...(options.headers || {}),
                ...(options.body ? { "Content-Type": "application/json" } : {})
            }
        });
        const payload = await response.json();

        if (!response.ok || payload.error) {
            throw new Error(payload.error || "Action impossible.");
        }

        return payload;
    };

    const loadStats = async () => {
        const values = document.querySelectorAll(".stats-admin article strong");
        if (values.length < 3) return;

        try {
            const stats = await fetchJson(`${API_BASE}/admin.php?action=stats`);
            values[0].textContent = stats.etudiants;
            values[1].textContent = stats.entreprises;
            values[2].textContent = stats.convocations;
        } catch {
            values.forEach((value) => {
                value.textContent = "-";
            });
        }
    };

    const loadUsers = async (type) => {
        const tbody = $("#tbody-utilisateurs");
        if (!tbody) return;

        try {
            const users = await fetchJson(`${API_BASE}/admin.php?action=users&type=${encodeURIComponent(type)}`);
            tbody.replaceChildren();

            if (!users.length) {
                const row = document.createElement("tr");
                const cell = createCell("Aucun compte.");
                cell.colSpan = 4;
                row.append(cell);
                tbody.append(row);
                return;
            }

            users.forEach((user) => {
                const row = document.createElement("tr");
                const actionCell = document.createElement("td");
                const button = document.createElement("button");
                button.className = "bouton-discret";
                button.type = "button";
                button.textContent = "Supprimer";
                button.addEventListener("click", () => deleteUser(user.id, type));
                actionCell.append(button);

                row.append(
                    createCell(user.nom),
                    createCell(user.email),
                    createCell(user.date_creation),
                    actionCell
                );
                tbody.append(row);
            });
        } catch (exception) {
            tbody.replaceChildren();
            const row = document.createElement("tr");
            const cell = createCell(exception.message);
            cell.colSpan = 4;
            row.append(cell);
            tbody.append(row);
        }
    };

    async function deleteUser(id, type) {
        if (!window.confirm("Supprimer ce compte et ses données associées ?")) return;

        await fetchJson(`${API_BASE}/admin.php?action=delete_user`, {
            method: "POST",
            body: JSON.stringify({ id, type })
        });
        await loadUsers(type);
    }

    const loadRequests = async () => {
        const tbody = $("#tbody-demandes");
        if (!tbody) return;

        try {
            const requests = await fetchJson(`${API_BASE}/admin.php?action=contact_requests`);
            tbody.replaceChildren();

            if (!requests.length) {
                const row = document.createElement("tr");
                const cell = createCell("Aucune demande.");
                cell.colSpan = 5;
                row.append(cell);
                tbody.append(row);
                return;
            }

            requests.forEach((request) => {
                const row = document.createElement("tr");
                const actionCell = document.createElement("td");

                if (request.statut === "en attente") {
                    const button = document.createElement("button");
                    button.className = "bouton-primaire";
                    button.type = "button";
                    button.textContent = "Valider";
                    button.addEventListener("click", () => approveRequest(request.id));
                    actionCell.append(button);
                }

                row.append(
                    createCell(request.nom_entreprise),
                    createCell(request.email_contact),
                    createCell(request.message),
                    createCell(request.statut),
                    actionCell
                );
                tbody.append(row);
            });
        } catch (exception) {
            tbody.replaceChildren();
            const row = document.createElement("tr");
            const cell = createCell(exception.message);
            cell.colSpan = 5;
            row.append(cell);
            tbody.append(row);
        }
    };

    async function approveRequest(id) {
        await fetchJson(`${API_BASE}/admin.php?action=approve_contact`, {
            method: "POST",
            body: JSON.stringify({ id })
        });
        await loadRequests();
    }

    document.addEventListener("DOMContentLoaded", () => {
        const path = window.location.pathname;

        if (path.includes("dashboard.php")) {
            loadStats();
        }

        if (path.includes("utilisateurs.php")) {
            const filter = $("#filtre-type");
            loadUsers(filter?.value || "etudiants");
            filter?.addEventListener("change", () => loadUsers(filter.value));
        }

        if (path.includes("entreprises.php")) {
            loadUsers("entreprises");
        }

        if (path.includes("demandes-contact.php")) {
            loadRequests();
        }
    });
})();
