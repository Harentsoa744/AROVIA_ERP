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

  <!-- Filtre rapide période -->
  <div class="content-card mb-4" style="padding:1rem 1.25rem;">
    <form method="get" action="/finances" class="finance-filter-bar">
      <label><i class="fa fa-calendar-days me-1"></i> Période :</label>
      <input type="date" name="date_debut" class="arovia-input" value="<?= esc($filtres['date_debut'] ?? '') ?>" style="width:150px;">
      <span style="color:var(--text-muted);font-size:.85rem;">→</span>
      <input type="date" name="date_fin" class="arovia-input" value="<?= esc($filtres['date_fin'] ?? '') ?>" style="width:150px;">
      <select name="type" class="arovia-input" style="width:140px;">
        <option value="">Tous types</option>
        <option value="recette" <?= (($filtres['type'] ?? '') === 'recette') ? 'selected' : '' ?>>Recettes</option>
        <option value="depense" <?= (($filtres['type'] ?? '') === 'depense') ? 'selected' : '' ?>>Dépenses</option>
      </select>
      <button type="submit" class="btn-gold" style="height:36px;padding:.4rem 1rem;"><i class="fa fa-filter me-1"></i>Filtrer</button>
      <a href="/finances" class="btn-outline-gold" style="height:36px;padding:.4rem .8rem;display:inline-flex;align-items:center;"><i class="fa fa-rotate-right"></i></a>
    </form>
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
        <div class="content-card-title">Dernières modifications</div>
        <div class="transaction-list" id="transactionList">
          <?php if (!empty($mouvements_recents)): ?>
            <?php foreach ($mouvements_recents as $m): ?>
              <div class="transaction-item">
                <div class="trans-icon <?= ($m['type'] ?? 'depense') === 'recette' ? 'bg-green' : 'bg-red' ?>"><i class="fa fa-arrow-<?= ($m['type'] ?? 'depense') === 'recette' ? 'up' : 'down' ?>"></i></div>
                <div class="trans-info">
                  <div class="trans-title"><?= esc($m['description'] ?? 'Transaction') ?></div>
                  <div class="trans-date"><?= esc($m['date_transaction'] ?? '') ?> — <?= esc($m['categorie'] ?? '') ?></div>
                </div>
                <div class="trans-amount <?= ($m['type'] ?? 'depense') === 'recette' ? 'text-green' : 'text-red' ?>"><?= ($m['type'] ?? 'depense') === 'recette' ? '+' : '−' ?> <?= number_format($m['montant'] ?? 0, 0, ',', ' ') ?> Ar</div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="text-muted text-center py-4"><i class="fa fa-inbox fa-2x mb-2 d-block"></i>Aucune transaction enregistrée.</div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</main>
<script src="assets/bootstrap/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
<script>
function toggleSubmenu(el){el.classList.toggle('open');el.nextElementSibling.classList.toggle('open');}
const ctx = document.getElementById('chartFinance').getContext('2d');
new Chart(ctx, {
  type: 'bar',
  data: {
    labels: ['Mois courant'],
    datasets: [
      { label: 'Revenus', data: [<?= (float) ($totaux_mois['recettes'] ?? 0) ?>], backgroundColor: 'rgba(93,122,46,0.85)', borderRadius: 6 },
      { label: 'Dépenses', data: [<?= (float) ($totaux_mois['depenses'] ?? 0) ?>], backgroundColor: 'rgba(192,57,43,0.85)', borderRadius: 6 }
    ]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: { display: true, position: 'top', labels: { font: { size: 12 }, color: '#8A8A8A' } },
      tooltip: { callbacks: { label: ctx => ` ${ctx.dataset.label}: ${ctx.parsed.y.toLocaleString('fr-FR')} Ar` } }
    },
    scales: {
      y: { beginAtZero: true, grid: { color: '#F0EBE3' }, ticks: { color: '#8A8A8A', callback: v => v.toLocaleString('fr-FR') + ' Ar' } },
      x: { grid: { display: false }, ticks: { color: '#8A8A8A' } }
    }
  }
});
</script>
</body>
</html>
