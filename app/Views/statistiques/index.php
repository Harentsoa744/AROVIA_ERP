<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>Statistiques — Miel Arovia</title>
  <link rel="stylesheet" href="assets/bootstrap/bootstrap.min.css"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <link rel="stylesheet" href="assets/css/global.css"/>
  <link rel="stylesheet" href="assets/css/statistiques.css"/>
</head>
<body>

<!-- TOPBAR -->
<?php include 'utils/header.php'; ?>
<?php include 'utils/side_bar.php'; ?>
<!-- MAIN -->
<main class="main-wrapper">
  <div class="breadcrumb-bar">
    <a href="gestion-stock.html">Gestion de stock</a> <span>›</span> Statistiques
  </div>

  <div class="page-header">
    <h1 class="page-title">Statistiques</h1>
    <div class="d-flex gap-2 flex-wrap">
      <button class="date-picker-btn">
        <i class="fa fa-calendar"></i> 28 juin 2026 – 28 juin 2026
        <i class="fa fa-chevron-down" style="font-size:.65rem"></i>
      </button>
      <button class="btn-gold"><i class="fa fa-download"></i> Exporter</button>
    </div>
  </div>

  <!-- KPI Cards -->
  <div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
      <div class="kpi-card">
        <div class="kpi-icon-wrap green"><i class="fa fa-droplet"></i></div>
        <div class="kpi-label">Total litres entrés</div>
        <div class="kpi-value green">15.00 <small>L</small></div>
        <div class="kpi-sub">Sur la période sélectionnée</div>
      </div>
    </div>
    <div class="col-6 col-xl-3">
      <div class="kpi-card">
        <div class="kpi-icon-wrap red"><i class="fa fa-jar"></i></div>
        <div class="kpi-label">Total bocaux vendus</div>
        <div class="kpi-value red">0</div>
        <div class="kpi-sub">Sur la période sélectionnée</div>
      </div>
    </div>
    <div class="col-6 col-xl-3">
      <div class="kpi-card">
        <div class="kpi-icon-wrap gold"><i class="fa fa-user-group"></i></div>
        <div class="kpi-label">Fournisseur principal</div>
        <div class="kpi-value gold" style="font-size:1.4rem">Rakoto</div>
        <div class="kpi-sub">100% des entrées</div>
      </div>
    </div>
    <div class="col-6 col-xl-3">
      <div class="kpi-card">
        <div class="kpi-icon-wrap blue"><i class="fa fa-chart-line"></i></div>
        <div class="kpi-label">Taux de vente</div>
        <div class="kpi-value blue">0%</div>
        <div class="kpi-sub">Par rapport aux entrées</div>
      </div>
    </div>
  </div>

  <!-- Charts Row -->
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
          <span class="legend-item green"><span class="legend-dot"></span> Rakoto (100%)</span>
        </div>
        <div class="chart-wrap chart-doughnut-wrap">
          <canvas id="chartRepartition"></canvas>
        </div>
      </div>
    </div>
  </div>

  <!-- Summary Table -->
  <div class="content-card">
    <div class="content-card-title">Résumé des entrées et sorties</div>
    <div class="row g-0">
      <div class="col-6 col-md-3">
        <div class="summary-cell">
          <div class="summary-label text-orange">Litres entrés</div>
          <div class="summary-value text-green">15.00 L</div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="summary-cell">
          <div class="summary-label text-red">Bocaux vendus</div>
          <div class="summary-value">0</div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="summary-cell">
          <div class="summary-label text-gold">Stock estimé (L)</div>
          <div class="summary-value text-gold">15.00 L</div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="summary-cell">
          <div class="summary-label text-blue">Variation</div>
          <div class="summary-value text-blue">+15.00 L</div>
        </div>
      </div>
    </div>
  </div>
</main>

<script src="assets/bootstrap/bootstrap.bundle.min.js"></script>
<script src="assets/js/chart.min.js"></script>
<script>
function toggleSubmenu(el){
  el.classList.toggle('open');
  el.nextElementSibling.classList.toggle('open');
}

// Line Chart
const ctx1 = document.getElementById('chartEvolution').getContext('2d');
new Chart(ctx1, {
  type: 'line',
  data: {
    labels: ['28 juin 2026'],
    datasets: [
      {
        label: 'Litres de miel entrés',
        data: [15],
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
        data: [0],
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

// Doughnut Chart
const ctx2 = document.getElementById('chartRepartition').getContext('2d');
new Chart(ctx2, {
  type: 'doughnut',
  data: {
    labels: ['Rakoto'],
    datasets: [{
      data: [100],
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
