<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Rapports financiers — Miel Arovia</title>
  <link rel="stylesheet" href="<?= base_url('assets/bootstrap/bootstrap.min.css') ?>"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <link rel="stylesheet" href="<?= base_url('assets/css/global.css') ?>"/>
</head>
<body>
<?php include '../partials/header.php'; ?>
<?php include '../partials/side_bar.php'; ?>
<main class="main-wrapper">
  <div class="breadcrumb-bar"><a href="/finances">Finance</a> <span>›</span> Rapports & Analyses</div>
  <div class="page-header">
    <div class="page-title-wrap">
      <h1 class="page-title">Rapports & Analyses</h1>
      <img src="/assets/images/Pattern simple - 1.png" alt="Pattern" class="header-pattern-img" />
    </div>
    <a href="/finances" class="btn-outline-gold"><i class="fa fa-arrow-left"></i> Retour</a>
  </div>

  <div class="content-card mb-4">
    <form method="get" action="/finances/rapport" class="row g-3 align-items-end">
      <div class="col-md-4">
        <label class="arovia-label" for="date_debut">Du</label>
        <input id="date_debut" type="date" name="date_debut" class="arovia-input" value="<?= esc($dateDebut ?? '') ?>"/>
      </div>
      <div class="col-md-4">
        <label class="arovia-label" for="date_fin">Au</label>
        <input id="date_fin" type="date" name="date_fin" class="arovia-input" value="<?= esc($dateFin ?? '') ?>"/>
      </div>
      <div class="col-md-4">
        <button type="submit" class="btn-gold"><i class="fa fa-filter"></i> Générer</button>
      </div>
    </form>
  </div>

  <div class="row g-3 mb-4">
    <div class="col-md-4"><div class="kpi-card"><div class="kpi-icon-wrap green"><i class="fa fa-arrow-up"></i></div><div class="kpi-label">Total recettes</div><div class="kpi-value green"><?= number_format($totaux['recettes'] ?? 0, 2) ?> Ar</div></div></div>
    <div class="col-md-4"><div class="kpi-card"><div class="kpi-icon-wrap red"><i class="fa fa-arrow-down"></i></div><div class="kpi-label">Total dépenses</div><div class="kpi-value red"><?= number_format($totaux['depenses'] ?? 0, 2) ?> Ar</div></div></div>
    <div class="col-md-4"><div class="kpi-card"><div class="kpi-icon-wrap gold"><i class="fa fa-scale-balanced"></i></div><div class="kpi-label">Bénéfice net</div><div class="kpi-value gold"><?= number_format($totaux['benefice'] ?? 0, 2) ?> Ar</div></div></div>
  </div>

  <div class="content-card">
    <div class="content-card-title">Évolution mensuelle</div>
    <canvas id="evolutionChart"></canvas>
  </div>
</main>

<script src="<?= base_url('assets/bootstrap/bootstrap.bundle.min.js') ?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
<script>
const evolution = <?= json_encode($evolution ?? []) ?>;
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
      { label: 'Recettes', data: recettes, backgroundColor: '#5D7A2E' },
      { label: 'Dépenses', data: depenses, backgroundColor: '#C0392B' }
    ]
  },
  options: { responsive: true, scales: { y: { beginAtZero: true } } }
});
</script>
</body>
</html>