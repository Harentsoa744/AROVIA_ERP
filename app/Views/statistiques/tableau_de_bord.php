<?= view('partials/header') ?>

<div style="font-family: sans-serif; padding: 20px;">
    <h1>📊 <?= esc($titre) ?></h1>

    <!-- Formulaire de filtre -->
    <form action="" method="get" style="margin-bottom: 35px; padding: 15px; background-color: #fff; border: 1px solid #e0e0e0; border-radius: 6px; display: flex; gap: 15px; align-items: flex-end;">
        <div>
            <label for="date_debut">Date de début</label><br>
            <input type="date" id="date_debut" name="date_debut" value="<?= esc($date_debut ?? '') ?>">
        </div>
        <div>
            <label for="date_fin">Date de fin</label><br>
            <input type="date" id="date_fin" name="date_fin" value="<?= esc($date_fin ?? '') ?>">
        </div>
        <button type="submit">Filtrer</button>
    </form>

    <!-- KPIs -->
    <div style="display: flex; gap: 20px; flex-wrap: wrap; margin-bottom: 35px;">
        <div style="padding: 15px; background: #fff; border: 1px solid #e0e0e0; border-radius: 6px; min-width: 180px;">
            <div style="font-size: 13px; color: #888;">Solde disponible</div>
            <div style="font-size: 22px; font-weight: bold;"><?= number_format($solde_disponible, 2) ?> Ar</div>
        </div>
        <div style="padding: 15px; background: #fff; border: 1px solid #e0e0e0; border-radius: 6px; min-width: 180px;">
            <div style="font-size: 13px; color: #888;">Entrées (période)</div>
            <div style="font-size: 22px; font-weight: bold; color: #4CAF50;"><?= number_format($total_entrees_mois, 2) ?> Ar</div>
        </div>
        <div style="padding: 15px; background: #fff; border: 1px solid #e0e0e0; border-radius: 6px; min-width: 180px;">
            <div style="font-size: 13px; color: #888;">Sorties (période)</div>
            <div style="font-size: 22px; font-weight: bold; color: #FF7043;"><?= number_format($total_sorties_mois, 2) ?> Ar</div>
        </div>
        <div style="padding: 15px; background: #fff; border: 1px solid #e0e0e0; border-radius: 6px; min-width: 180px;">
            <div style="font-size: 13px; color: #888;">Bénéfice net (période)</div>
            <div style="font-size: 22px; font-weight: bold; color: <?= $benefice_net_mois >= 0 ? '#c0ffc2' : '#FF7043' ?>;">
                <?= number_format($benefice_net_mois, 2) ?> Ar
            </div>
        </div>
    </div>

    <!-- Graphiques -->
    <div style="display: flex; gap: 40px; flex-wrap: wrap;">
        <div style="flex: 1; min-width: 300px;">
            <h3>Évolution Miel & Bocaux</h3>
            <canvas id="courbeChart"></canvas>
        </div>
        <div style="flex: 1; min-width: 300px;">
            <h3>Flux financiers par mois</h3>
            <canvas id="fluxChart"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Données injectées depuis PHP
    const donneesGraphique      = <?= json_encode($donnees_graphique) ?>;
    const entreesParDate        = <?= json_encode($entreesParDate) ?>;
    const sortiesParDate        = <?= json_encode($sortiesParDate) ?>;
    const entreesParFournisseur = <?= json_encode($entreesParFournisseur) ?>;
    const sortiesParDestinataire= <?= json_encode($sortiesParDestinataire) ?>;
    const toutesLesDates        = <?= json_encode($axeDates) ?>;

    // Destruction des instances existantes pour éviter les conflits Chart.js
    ['courbeChart', 'fluxChart', 'fournisseurChart', 'destinataireChart'].forEach(id => {
        const existing = Chart.getChart(id);
        if (existing) existing.destroy();
    });

    // --- 1. Graphique linéaire : Miel & Bocaux ---
    const dataEntrees = toutesLesDates.map(date => {
        const t = entreesParDate.find(e => e.jour === date);
        return t ? parseFloat(t.total_litres) : 0;
    });
    const dataSorties = toutesLesDates.map(date => {
        const t = sortiesParDate.find(s => s.jour === date);
        return t ? parseInt(t.total_quantite) : 0;
    });

    new Chart(document.getElementById('courbeChart'), {
        type: 'line',
        data: {
            labels: toutesLesDates,
            datasets: [
                {
                    label: 'Litres de miel',
                    data: dataEntrees,
                    borderColor: '#4CAF50',
                    backgroundColor: 'rgba(76,175,80,0.1)',
                    tension: 0.3,
                    fill: true
                },
                {
                    label: 'Bocaux vendus',
                    data: dataSorties,
                    borderColor: '#FF7043',
                    backgroundColor: 'rgba(255,112,67,0.1)',
                    tension: 0.3,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'top' } },
            scales: {
                x: { ticks: { maxTicksLimit: 10 } }
            }
        }
    });

    // --- 2. Graphique barres : Flux financiers par mois ---
    const moisLabels    = donneesGraphique.map(r => 'Mois ' + r.mois);
    const dataRecettes  = donneesGraphique.map(r => parseFloat(r.total_entrees  ?? 0));
    const dataDepenses  = donneesGraphique.map(r => parseFloat(r.total_sorties  ?? 0));

    new Chart(document.getElementById('fluxChart'), {
        type: 'bar',
        data: {
            labels: moisLabels,
            datasets: [
                {
                    label: 'Entrées (Ar)',
                    data: dataRecettes,
                    backgroundColor: 'rgba(76,175,80,0.7)'
                },
                {
                    label: 'Sorties (Ar)',
                    data: dataDepenses,
                    backgroundColor: 'rgba(255,112,67,0.7)'
                }
            ]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'top' } }
        }
    });
</script>