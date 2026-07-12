<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Historique des livraisons</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Sora:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/liste.css">
</head>
<body>
<div class="page-wrapper">
    <header class="page-header">
        <div class="header-left"><h1>Historique général</h1>
            <div class="page-title-wrap">
              <h1>Historique général</h1>
              <img src="/assets/images/Pattern simple - 1.png" alt="Pattern" class="header-pattern-img" />
            </div>
            <span class="employee-count" id="count">— livraisons</span>
        </div>
        <a href="/livraisons" class="btn-cancel">🏠 Retour Distribution</a>
    </header>

    <div class="toolbar">
        <div class="search-wrap">
            <input type="text" id="search" placeholder="Rechercher par adresse ou livreur...">
        </div>
        <select id="statut">
            <option value="">Tous les états</option>
            <option value="EN_ATTENTE">En attente</option>
            <option value="EN_COURS">En cours</option>
            <option value="EFFECTUEE">Effectuée</option>
            <option value="ANNULEE">Annulée</option>
        </select>
    </div>

    <div class="employee-card" style="padding: 20px; overflow-x: auto; background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.08);">
        <table style="width: 100%; border-collapse: collapse; text-align: left; color: #e2e8f0;" id="table-historique">
            <thead>
                <tr style="border-bottom: 2px solid rgba(255,255,255,0.1); height: 40px; color: #a0aec0;">
                    <th>ID Vente</th>
                    <th>Livreur</th>
                    <th>Destination</th>
                    <th>Date Planifiée</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody id="livraisons-rows">
                </tbody>
        </table>
    </div>
</div>

<script>
function loadLivraisons() {
    const search = document.getElementById('search').value;
    const statut = document.getElementById('statut').value;
    const tbody = document.getElementById('livraisons-rows');
    const countEl = document.getElementById('count');

    fetch(`/livraisons/ajax?search=${encodeURIComponent(search)}&statut=${encodeURIComponent(statut)}`)
        .then(res => res.json())
        .then(data => {
            tbody.innerHTML = '';
            countEl.textContent = `${data.length} course${data.length > 1 ? 's' : ''}`;

            data.forEach(liv => {
                const badgeStyle = liv.statut.toLowerCase();
                tbody.innerHTML += `
                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.05); height: 50px;">
                        <td style="color: black;"><strong>#${liv.vente_id}</strong></td>
                        <td style="color: black;">${liv.livreur_nom ? liv.livreur_nom : '<span style="color:#718096">Non assigné</span>'}</td>
                        <td style="color: black;">${liv.adresse_livraison}</td>
                        <td style="font-size: 13px; color:black;">${liv.date_prevue}</td>
                        <td style="color: black;"><span class="${badgeStyle}">${liv.statut}</span></td>
                    </tr>
                `;
            });
        });
}

document.getElementById('search').addEventListener('keyup', loadLivraisons);
document.getElementById('statut').addEventListener('change', loadLivraisons);
loadLivraisons();
</script>
</body>
</html>