<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>Statistiques — Miel Arovia</title>
  <link rel="stylesheet" href="/assets/bootstrap/bootstrap.min.css"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <link rel="stylesheet" href="/assets/css/global.css"/>
  <link rel="stylesheet" href="/assets/css/statistiques.css"/>
</head>
<body>

<?php include 'utils/header.php'; ?>
<?php include 'utils/side_bar.php'; ?>
<main class="main-wrapper">
  <div class="breadcrumb-bar">
    <a href="/valeur-stock">Gestion de stock</a> <span>›</span> Statistiques
  </div>

  <div class="page-header">
    <h1 class="page-title">Statistiques</h1>
    <div class="d-flex gap-2 flex-wrap">
      <a href="/statistiques/vente" class="btn-gold"><i class="fa fa-chart-line"></i> Vue détaillée</a>
    </div>
  </div>

  <div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
      <div class="kpi-card">
        <div class="kpi-icon-wrap green"><i class="fa fa-droplet"></i></div>
        <div class="kpi-label">Total litres entrés</div>
        <div class="kpi-value green"><?= number_format($totalLitresEntre ?? 0, 2) ?> <small>L</small></div>
        <div class="kpi-sub">Sur la période enregistrée</div>
      </div>
    </div>
    <div class="col-6 col-xl-3">
      <div class="kpi-card">
        <div class="kpi-icon-wrap red"><i class="fa fa-jar"></i></div>
        <div class="kpi-label">Total bocaux vendus</div>
        <div class="kpi-value red"><?= (int) ($totalBocauxVendus ?? 0) ?></div>
        <div class="kpi-sub">Sur la période enregistrée</div>
      </div>
    </div>
    <div class="col-6 col-xl-3">
      <div class="kpi-card">
        <div class="kpi-icon-wrap gold"><i class="fa fa-user-group"></i></div>
        <div class="kpi-label">Fournisseur principal</div>
        <div class="kpi-value gold" style="font-size:1.4rem"><?= esc($fournisseurPrincipal ?? 'Aucun') ?></div>
        <div class="kpi-sub">Source des entrées</div>
      </div>
    </div>
    <div class="col-6 col-xl-3">
      <div class="kpi-card">
        <div class="kpi-icon-wrap blue"><i class="fa fa-chart-line"></i></div>
        <div class="kpi-label">Taux de vente</div>
        <div class="kpi-value blue"><?= number_format($tauxVente ?? 0, 2) ?>%</div>
        <div class="kpi-sub">Par rapport aux entrées</div>
      </div>
    </div>
  </div>

  <div class="row g-3 mb-4">
    <div class="col-lg-7">
      <div class="content-card">
        <div class="content-card-title">Évolution dans le temps</div>
        <div class="chart-legend">
          <span class="legend-item green"><span class="legend-dot"></span> Litres de miel entrés</span>
          <span class="legend-item red"><span class="legend-dot"></span> Bocaux vendus</span>
        </div>
        <div class="chart-wrap">
          <canvas id="chartEvolution"></canvas>
        </div>
      </div>
    </div>
    <div class="col-lg-5">
      <div class="content-card">
        <div class="content-card-title">Répartition par fournisseur (litres entrés)</div>
        <div class="chart-legend">
          <span class="legend-item green"><span class="legend-dot"></span> <?= esc($fournisseurPrincipal ?? 'Aucun') ?></span>
        </div>
        <div class="chart-wrap chart-doughnut-wrap">
          <canvas id="chartRepartition"></canvas>
        </div>
      </div>
    </div>
  </div>

  <div class="content-card">
    <div class="content-card-title">Résumé des entrées et sorties</div>
    <div class="row g-0">
      <div class="col-6 col-md-3">
        <div class="summary-cell">
          <div class="summary-label text-orange">Litres entrés</div>
          <div class="summary-value text-green"><?= number_format($totalLitresEntre ?? 0, 2) ?> L</div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="summary-cell">
          <div class="summary-label text-red">Bocaux vendus</div>
          <div class="summary-value"><?= (int) ($totalBocauxVendus ?? 0) ?></div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="summary-cell">
          <div class="summary-label text-gold">Stock estimé (L)</div>
          <div class="summary-value text-gold"><?= number_format($stockEstime ?? 0, 2) ?> L</div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="summary-cell">
          <div class="summary-label text-blue">Variation</div>
          <div class="summary-value text-blue"><?= number_format(($stockEstime ?? 0), 2) ?> L</div>
        </div>
      </div>
    </div>
  </div>
</main>

<script src="<?= base_url('assets/bootstrap/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('assets/js/chart.min.js') ?>"></script>
<script>
function toggleSubmenu(el){
  el.classList.toggle('open');
  el.nextElementSibling.classList.toggle('open');
}

const labels = <?= json_encode(array_map(fn($row) => date('d/m/Y', strtotime($row['jour'] ?? date('Y-m-d'))), $entreesParDate ?? [])) ?>;
const entreesData = <?= json_encode(array_map(fn($row) => (float) ($row['total_litres'] ?? 0), $entreesParDate ?? [])) ?>;
const sortiesData = <?= json_encode(array_map(fn($row) => (float) ($row['total_quantite'] ?? 0), $sortiesParDate ?? [])) ?>;
const fournisseurs = <?= json_encode(array_map(fn($row) => $row['fournisseur_nom'] ?? 'Inconnu', $entreesParFournisseur ?? [])) ?>;
const fournisseurData = <?= json_encode(array_map(fn($row) => (float) ($row['total_litres'] ?? 0), $entreesParFournisseur ?? [])) ?>;

const ctx1 = document.getElementById('chartEvolution').getContext('2d');
new Chart(ctx1, {
  type: 'line',
  data: {
    labels: labels.length ? labels : ['Aucune donnée'],
    datasets: [
      {
        label: 'Litres de miel entrés',
        data: entreesData.length ? entreesData : [0],
        borderColor: '#5D7A2E',
        backgroundColor: 'rgba(93,122,46,0.12)',
        borderWidth: 2,
        fill: true,
        tension: 0.4,
        pointBackgroundColor: '#5D7A2E',
        pointRadius: 5,
      },
      {
        label: 'Bocaux vendus',
        data: sortiesData.length ? sortiesData : [0],
        borderColor: '#C0392B',
        backgroundColor: 'rgba(192,57,43,0.08)',
        borderWidth: 2,
        fill: true,
        tension: 0.4,
        pointBackgroundColor: '#C0392B',
        pointRadius: 5,
      }
    ]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: { legend: { display: false } },
    scales: {
      y: { beginAtZero: true, grid: { color: '#F0EBE3' }, ticks: { color: '#8A8A8A', font: { size: 11 } } },
      x: { grid: { display: false }, ticks: { color: '#8A8A8A', font: { size: 11 } } }
    }
  }
});

const ctx2 = document.getElementById('chartRepartition').getContext('2d');
new Chart(ctx2, {
  type: 'doughnut',
  data: {
    labels: fournisseurs.length ? fournisseurs : ['Aucun fournisseur'],
    datasets: [{
      data: fournisseurData.length ? fournisseurData : [1],
      backgroundColor: ['#5D7A2E'],
      borderWidth: 0,
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: { legend: { display: false }, tooltip: { callbacks: { label: ctx => ` ${ctx.label}: ${ctx.parsed}%` } } },
    cutout: '0%',
  }
});
</script>
</body>
</html>
