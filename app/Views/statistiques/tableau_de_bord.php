<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>Tableau de bord — Miel Arovia</title>
  <link rel="stylesheet" href="<?= base_url('assets/bootstrap/bootstrap.min.css') ?>"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <link rel="stylesheet" href="<?= base_url('assets/css/global.css') ?>"/>
</head>
<body>
<?php include FCPATH . 'utils/header.php'; ?>
<?php include FCPATH . 'utils/side_bar.php'; ?>

<main class="main-wrapper">
  <div class="breadcrumb-bar">
    <a href="<?= base_url('statistiques') ?>">Statistiques</a>
    <span>›</span> Vue détaillée (Tableau de bord)
  </div>

  <div class="page-header">
    <h1 class="page-title">Tableau de bord financier</h1>
    <a href="<?= base_url('statistiques') ?>" class="btn-outline-gold"><i class="fa fa-arrow-left"></i> Retour</a>
  </div>

  <!-- Formulaire de filtre -->
  <div class="content-card mb-4" style="padding: 1.25rem;">
    <form action="" method="get" class="row g-3 align-items-end">
      <div class="col-12 col-md-4">
        <label class="arovia-label" for="date_debut">Date de début</label>
        <input type="date" id="date_debut" name="date_debut" class="arovia-input" value="<?= esc($date_debut ?? '') ?>">
      </div>
      <div class="col-12 col-md-4">
        <label class="arovia-label" for="date_fin">Date de fin</label>
        <input type="date" id="date_fin" name="date_fin" class="arovia-input" value="<?= esc($date_fin ?? '') ?>">
      </div>
      <div class="col-12 col-md-4">
        <button type="submit" class="btn-gold w-100"><i class="fa fa-filter"></i> Filtrer</button>
      </div>
    </form>
  </div>

  <!-- KPIs -->
  <div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
      <div class="kpi-card">
        <div class="kpi-icon-wrap blue"><i class="fa fa-wallet"></i></div>
        <div class="kpi-label">Solde disponible</div>
        <div class="kpi-value blue"><?= number_format($solde_disponible, 2, ',', ' ') ?> <small>Ar</small></div>
        <div class="kpi-sub">Solde comptable global</div>
      </div>
    </div>
    <div class="col-6 col-lg-3">
      <div class="kpi-card">
        <div class="kpi-icon-wrap green"><i class="fa fa-arrow-up"></i></div>
        <div class="kpi-label">Entrées (période)</div>
        <div class="kpi-value green"><?= number_format($total_entrees_mois, 2, ',', ' ') ?> <small>Ar</small></div>
        <div class="kpi-sub">Ventes & Recettes</div>
      </div>
    </div>
    <div class="col-6 col-lg-3">
      <div class="kpi-card">
        <div class="kpi-icon-wrap red"><i class="fa fa-arrow-down"></i></div>
        <div class="kpi-label">Sorties (période)</div>
        <div class="kpi-value red"><?= number_format($total_sorties_mois, 2, ',', ' ') ?> <small>Ar</small></div>
        <div class="kpi-sub">Dépenses totales</div>
      </div>
    </div>
    <div class="col-6 col-lg-3">
      <div class="kpi-card">
        <?php $isPositive = ($benefice_net_mois >= 0); ?>
        <div class="kpi-icon-wrap <?= $isPositive ? 'green' : 'red' ?>"><i class="fa <?= $isPositive ? 'fa-scale-balanced' : 'fa-triangle-exclamation' ?>"></i></div>
        <div class="kpi-label">Bénéfice net (période)</div>
        <div class="kpi-value <?= $isPositive ? 'green' : 'red' ?>"><?= number_format($benefice_net_mois, 2, ',', ' ') ?> <small>Ar</small></div>
        <div class="kpi-sub">Bénéfice net calculé</div>
      </div>
    </div>
  </div>

  <!-- Graphiques -->
  <div class="row g-4">
    <div class="col-12 col-xl-6">
      <div class="content-card">
        <h3 class="content-card-title"><i class="fa fa-chart-line text-gold me-2"></i>Évolution Miel & Bocaux</h3>
        <div style="position: relative; height:320px;">
          <canvas id="courbeChart"></canvas>
        </div>
      </div>
    </div>
    <div class="col-12 col-xl-6">
      <div class="content-card">
        <h3 class="content-card-title"><i class="fa fa-chart-column text-gold me-2"></i>Flux financiers par mois</h3>
        <div style="position: relative; height:320px;">
          <canvas id="fluxChart"></canvas>
        </div>
      </div>
    </div>
  </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Données injectées depuis PHP
    const donneesGraphique      = <?= json_encode($donnees_graphique) ?>;
    const entreesParDate        = <?= json_encode($entreesParDate) ?>;
    const sortiesParDate        = <?= json_encode($sortiesParDate) ?>;
    const entreesParFournisseur = <?= json_encode($entreesParFournisseur) ?>;
    const sortiesParDestinataire= <?= json_encode($sortiesParDestinataire) ?>;
    const toutesLesDates        = <?= json_encode($axeDates) ?>;

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
                    borderColor: '#c8860a',
                    backgroundColor: 'rgba(200, 134, 10, 0.08)',
                    tension: 0.3,
                    fill: true
                },
                {
                    label: 'Bocaux vendus',
                    data: dataSorties,
                    borderColor: '#5d7a2e',
                    backgroundColor: 'rgba(93, 122, 46, 0.08)',
                    tension: 0.3,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
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
                    backgroundColor: 'rgba(93, 122, 46, 0.85)'
                },
                {
                    label: 'Sorties (Ar)',
                    data: dataDepenses,
                    backgroundColor: 'rgba(200, 134, 10, 0.85)'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'top' } }
        }
    });
</script>
<script src="<?= base_url('assets/bootstrap/bootstrap.bundle.min.js') ?>"></script>
<script>function toggleSubmenu(el){el.classList.toggle('open');el.nextElementSibling.classList.toggle('open');}</script>
</body>
</html>
