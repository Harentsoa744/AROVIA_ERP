<?= view('partials/header') ?>

<h1>Rapports & Analyses</h1>

<!-- Filtre période -->
<form method="get" action="/finances/rapport">
    <label>Du</label>
    <input type="date" name="date_debut" value="<?= $dateDebut ?>">
    <label>Au</label>
    <input type="date" name="date_fin" value="<?= $dateFin ?>">
    <button type="submit">Générer</button>
</form>

<!-- Bilan -->
<h2>Bilan du <?= $dateDebut ?> au <?= $dateFin ?></h2>
<p><strong>Total recettes :</strong> <?= number_format($totaux['recettes'], 2) ?> Ar</p>
<p><strong>Total dépenses :</strong> <?= number_format($totaux['depenses'], 2) ?> Ar</p>
<p><strong>Bénéfice net :</strong> <?= number_format($totaux['benefice'], 2) ?> Ar</p>

<!-- Graphique évolution mensuelle -->
<h2>Évolution mensuelle</h2>
<canvas id="evolutionChart"></canvas>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
<script>
const evolution = <?= json_encode($evolution) ?>;

const mois = [...new Set(evolution.map(e => e.mois))].sort();

const recettes = mois.map(m => {
    const found = evolution.find(e => e.mois === m && e.type === 'recette');
    return found ? parseFloat(found.total) : 0;
});

const depenses = mois.map(m => {
    const found = evolution.find(e => e.mois === m && e.type === 'depense');
    return found ? parseFloat(found.total) : 0;
});

new Chart(document.getElementById('evolutionChart'), {
    type: 'bar',
    data: {
        labels: mois,
        datasets: [
            {
                label: 'Recettes',
                data: recettes,
                backgroundColor: '#4CAF50',
            },
            {
                label: 'Dépenses',
                data: depenses,
                backgroundColor: '#FF5722',
            }
        ]
    },
    options: {
        responsive: true,
        scales: { y: { beginAtZero: true } }
    }
});
</script>