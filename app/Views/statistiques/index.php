<?= view('partials/header') ?>
<h1>Statistiques</h1>

<div style="display: flex; gap: 40px; flex-wrap: wrap;">

    <div style="width: 600px;">
        <h3>Évolution dans le temps</h3>
        <canvas id="courbeChart"></canvas>
    </div>

    <div style="width: 350px;">
        <h3>Répartition par fournisseur (litres entrés)</h3>
        <canvas id="fournisseurChart"></canvas>
    </div>

    <div style="width: 350px;">
        <h3>Répartition par destinataire (bocaux vendus)</h3>
        <canvas id="destinataireChart"></canvas>
    </div>

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>

<script>
// --- Données injectées depuis PHP ---
const entreesParDate = <?= json_encode($entreesParDate) ?>;
const sortiesParDate = <?= json_encode($sortiesParDate) ?>;
const entreesParFournisseur = <?= json_encode($entreesParFournisseur) ?>;
const sortiesParDestinataire = <?= json_encode($sortiesParDestinataire) ?>;

// --- Graphique 1 : Courbe entrées vs sorties dans le temps ---
// On fusionne toutes les dates des deux séries pour avoir un axe commun
const toutesLesDates = [...new Set([
    ...entreesParDate.map(e => e.jour),
    ...sortiesParDate.map(s => s.jour)
])].sort();

const dataEntrees = toutesLesDates.map(date => {
    const trouve = entreesParDate.find(e => e.jour === date);
    return trouve ? parseFloat(trouve.total_litres) : 0;
});

const dataSorties = toutesLesDates.map(date => {
    const trouve = sortiesParDate.find(s => s.jour === date);
    return trouve ? parseInt(trouve.total_quantite) : 0;
});

new Chart(document.getElementById('courbeChart'), {
    type: 'line',
    data: {
        labels: toutesLesDates,
        datasets: [
            {
                label: 'Litres de miel entrés',
                data: dataEntrees,
                borderColor: '#4CAF50',
                backgroundColor: '#4CAF50',
                tension: 0.2,
            },
            {
                label: 'Bocaux vendus',
                data: dataSorties,
                borderColor: '#FF7043',
                backgroundColor: '#FF7043',
                tension: 0.2,
            }
        ]
    },
    options: {
        responsive: true,
        scales: { y: { beginAtZero: true } }
    }
});

// --- Graphique 2 : Camembert par fournisseur ---
new Chart(document.getElementById('fournisseurChart'), {
    type: 'pie',
    data: {
        labels: entreesParFournisseur.map(f => f.fournisseur_nom),
        datasets: [{
            data: entreesParFournisseur.map(f => parseFloat(f.total_litres)),
            backgroundColor: ['#4CAF50', '#FFC107', '#2196F3', '#9C27B0', '#FF5722', '#00BCD4'],
        }]
    },
    options: { responsive: true }
});

// --- Graphique 3 : Camembert par destinataire ---
const labelsDestinataire = { touriste: 'Touriste', particulier: 'Particulier', hotel: 'Hôtel' };

new Chart(document.getElementById('destinataireChart'), {
    type: 'pie',
    data: {
        labels: sortiesParDestinataire.map(s => labelsDestinataire[s.destinataire_type] ?? s.destinataire_type),
        datasets: [{
            data: sortiesParDestinataire.map(s => parseInt(s.total_quantite)),
            backgroundColor: ['#FF7043', '#42A5F5', '#AB47BC'],
        }]
    },
    options: { responsive: true }
});
</script>