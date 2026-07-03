<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/><meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>Finances — Miel Arovia</title>
  <link rel="stylesheet" href="assets/bootstrap/bootstrap.min.css"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <link rel="stylesheet" href="assets/css/global.css"/>
  <link rel="stylesheet" href="assets/css/finances.css"/>
</head>
<body>
<?php include 'utils/header.php'; ?>
<?php include 'utils/side_bar.php'; ?>
<main class="main-wrapper">
  <div class="breadcrumb-bar"><a href="gestion-stock.html">Gestion de stock</a> <span>›</span> Finances</div>
  <div class="page-header">
    <h1 class="page-title">Bilan Financier (Stock)</h1>
    <div class="d-flex gap-2 flex-wrap">
      <button class="date-picker-btn"><i class="fa fa-calendar"></i> Juin 2026 <i class="fa fa-chevron-down" style="font-size:.65rem"></i></button>
      <button class="btn-gold"><i class="fa fa-print"></i> Rapport</button>
    </div>
  </div>

  <div class="row g-3 mb-4">
    <div class="col-6 col-md-3"><div class="kpi-card"><div class="kpi-icon-wrap red"><i class="fa fa-arrow-down"></i></div><div class="kpi-label">Dépenses (Achats)</div><div class="kpi-value red">75 000 Ar</div></div></div>
    <div class="col-6 col-md-3"><div class="kpi-card"><div class="kpi-icon-wrap green"><i class="fa fa-arrow-up"></i></div><div class="kpi-label">Revenus (Ventes)</div><div class="kpi-value green">0 Ar</div></div></div>
    <div class="col-6 col-md-3"><div class="kpi-card"><div class="kpi-icon-wrap orange"><i class="fa fa-scale-unbalanced"></i></div><div class="kpi-label">Balance</div><div class="kpi-value orange">-75 000 Ar</div></div></div>
    <div class="col-6 col-md-3"><div class="kpi-card"><div class="kpi-icon-wrap blue"><i class="fa fa-piggy-bank"></i></div><div class="kpi-label">Trésorerie estimée</div><div class="kpi-value blue">1 250 000 Ar</div></div></div>
  </div>

  <div class="row g-3">
    <div class="col-lg-8">
      <div class="content-card h-100">
        <div class="content-card-title">Flux financiers (30 derniers jours)</div>
        <div class="chart-legend">
          <span class="legend-item green"><span class="legend-dot"></span> Revenus</span>
          <span class="legend-item red"><span class="legend-dot"></span> Dépenses</span>
        </div>
        <div class="chart-wrap"><canvas id="chartFinance"></canvas></div>
      </div>
    </div>
    <div class="col-lg-4">
      <div class="content-card h-100">
        <div class="content-card-title">Dernières transactions</div>
        <div class="transaction-list">
          <div class="transaction-item">
            <div class="trans-icon bg-red"><i class="fa fa-arrow-down"></i></div>
            <div class="trans-info">
              <div class="trans-title">Achat matière première</div>
              <div class="trans-date">28/06/2026 - Rakoto</div>
            </div>
            <div class="trans-amount text-red">-75 000 Ar</div>
          </div>
          <!-- Placeholder pour illustrer -->
          <div class="transaction-item">
            <div class="trans-icon bg-green"><i class="fa fa-arrow-up"></i></div>
            <div class="trans-info">
              <div class="trans-title">Vente 10 bocaux (Exemple)</div>
              <div class="trans-date">25/06/2026 - Supermarché A</div>
            </div>
            <div class="trans-amount text-green">+80 000 Ar</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
<script src="assets/bootstrap/bootstrap.bundle.min.js"></script>
<script src="assets/js/chart.min.js"></script>
<script>
function toggleSubmenu(el){el.classList.toggle('open');el.nextElementSibling.classList.toggle('open');}
// Chart init
const ctx = document.getElementById('chartFinance').getContext('2d');
new Chart(ctx, {
  type: 'bar',
  data: {
    labels: ['Sem 1', 'Sem 2', 'Sem 3', 'Sem 4'],
    datasets: [
      { label: 'Revenus', data: [20000, 35000, 0, 80000], backgroundColor: '#5D7A2E', borderRadius: 4 },
      { label: 'Dépenses', data: [15000, 0, 75000, 0], backgroundColor: '#C0392B', borderRadius: 4 }
    ]
  },
  options: {
    responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } },
    scales: { y: { beginAtZero: true, grid: { color: '#F0EBE3' } }, x: { grid: { display: false } } }
  }
});
</script>
</body>
</html>
