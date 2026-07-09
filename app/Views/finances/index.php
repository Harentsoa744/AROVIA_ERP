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
  <div class="breadcrumb-bar"><a href="/valeur-stock">Gestion de stock</a> <span>›</span> Finances</div>
  <div class="page-header">
    <h1 class="page-title">Bilan Financier (Stock)</h1>
    <div class="d-flex gap-2 flex-wrap">
      <a href="/finances/tresorerie" class="btn-gold"><i class="fa fa-print"></i> Tresorerie</a>
      <a href="/finances/rapport" class="btn-gold"><i class="fa fa-print"></i> Rapport</a>
    </div>
  </div>

  <div class="row g-3 mb-4">
    <div class="col-6 col-md-3"><div class="kpi-card"><div class="kpi-icon-wrap red"><i class="fa fa-arrow-down"></i></div><div class="kpi-label">Dépenses (Achats)</div><div class="kpi-value red"><?= number_format($totaux_mois['depenses'] ?? 0, 0, ',', ' ') ?> Ar</div></div></div>
    <div class="col-6 col-md-3"><div class="kpi-card"><div class="kpi-icon-wrap green"><i class="fa fa-arrow-up"></i></div><div class="kpi-label">Revenus (Ventes)</div><div class="kpi-value green"><?= number_format($totaux_mois['recettes'] ?? 0, 0, ',', ' ') ?> Ar</div></div></div>
    <div class="col-6 col-md-3"><div class="kpi-card"><div class="kpi-icon-wrap orange"><i class="fa fa-scale-unbalanced"></i></div><div class="kpi-label">Balance</div><div class="kpi-value orange"><?= number_format(($totaux_mois['benefice'] ?? 0), 0, ',', ' ') ?> Ar</div></div></div>
    <div class="col-6 col-md-3"><div class="kpi-card"><div class="kpi-icon-wrap blue"><i class="fa fa-piggy-bank"></i></div><div class="kpi-label">Trésorerie estimée</div><div class="kpi-value blue"><?= number_format($solde ?? 0, 0, ',', ' ') ?> Ar</div></div></div>
  </div>

  <div class="row g-3">
    <div class="col-lg-8">
      <div class="content-card h-100">
        <div class="content-card-title">Flux financiers (mois courant)</div>
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
          <?php if (!empty($mouvements_recents)): ?>
            <?php foreach ($mouvements_recents as $m): ?>
              <div class="transaction-item">
                <div class="trans-icon <?= ($m['type'] ?? 'depense') === 'recette' ? 'bg-green' : 'bg-red' ?>"><i class="fa fa-arrow-<?= ($m['type'] ?? 'depense') === 'recette' ? 'up' : 'down' ?>"></i></div>
                <div class="trans-info">
                  <div class="trans-title"><?= esc($m['description'] ?? 'Transaction') ?></div>
                  <div class="trans-date"><?= esc($m['date_transaction'] ?? '') ?> - <?= esc($m['categorie'] ?? '') ?></div>
                </div>
                <div class="trans-amount <?= ($m['type'] ?? 'depense') === 'recette' ? 'text-green' : 'text-red' ?>"><?= ($m['type'] ?? 'depense') === 'recette' ? '+' : '-' ?> <?= number_format($m['montant'] ?? 0, 0, ',', ' ') ?> Ar</div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="text-muted">Aucune transaction enregistrée.</div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</main>
<script src="assets/bootstrap/bootstrap.bundle.min.js"></script>
<script src="assets/js/chart.min.js"></script>
<script>
function toggleSubmenu(el){el.classList.toggle('open');el.nextElementSibling.classList.toggle('open');}
const ctx = document.getElementById('chartFinance').getContext('2d');
new Chart(ctx, {
  type: 'bar',
  data: {
    labels: ['Mois courant'],
    datasets: [
      { label: 'Revenus', data: [<?= (float) ($totaux_mois['recettes'] ?? 0) ?>], backgroundColor: '#5D7A2E', borderRadius: 4 },
      { label: 'Dépenses', data: [<?= (float) ($totaux_mois['depenses'] ?? 0) ?>], backgroundColor: '#C0392B', borderRadius: 4 }
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
