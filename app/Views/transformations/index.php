<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/><meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>Transformations — Miel Arovia</title>
  <link rel="stylesheet" href="<?= base_url('assets/bootstrap/bootstrap.min.css') ?>"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <link rel="stylesheet" href="<?= base_url('assets/css/global.css') ?>"/>
  <link rel="stylesheet" href="<?= base_url('assets/css/transformations.css') ?>"/>
</head>
<body>
<?php include 'utils/header.php'; ?>
<?php include 'utils/side_bar.php'; ?>
<main class="main-wrapper">
  <div class="breadcrumb-bar"><a href="/valeur-stock">Gestion de stock</a> <span>›</span> Transformations</div>
  <div class="page-header">
    <h1 class="page-title">Transformations (mise en bocal)</h1>
    <button class="btn-gold" data-bs-toggle="modal" data-bs-target="#modalTransfo"><i class="fa fa-plus"></i> Nouvelle transformation</button>
  </div>
  <div class="row g-3 mb-4">
    <div class="col-6 col-md-3"><div class="kpi-card"><div class="kpi-icon-wrap blue"><i class="fa fa-recycle"></i></div><div class="kpi-label">Transformations totales</div><div class="kpi-value dark"><?= (int) ($totalTransformations ?? 0) ?></div></div></div>
    <div class="col-6 col-md-3"><div class="kpi-card"><div class="kpi-icon-wrap green"><i class="fa fa-droplet"></i></div><div class="kpi-label">Litres transformés</div><div class="kpi-value green"><?= number_format($litresTransformes ?? 0, 2) ?> L</div></div></div>
    <div class="col-6 col-md-3"><div class="kpi-card"><div class="kpi-icon-wrap gold"><i class="fa fa-jar"></i></div><div class="kpi-label">Bocaux produits</div><div class="kpi-value gold"><?= (int) ($bocauxProduits ?? 0) ?></div></div></div>
    <div class="col-6 col-md-3"><div class="kpi-card"><div class="kpi-icon-wrap orange"><i class="fa fa-arrow-trend-down"></i></div><div class="kpi-label">Taux de perte</div><div class="kpi-value orange"><?= number_format($tauxPerte ?? 0, 2) ?>%</div></div></div>
  </div>
  <div class="content-card">
    <div class="mb-3 d-flex justify-content-end">
      <div style="position: relative; width: 250px;">
        <i class="fa fa-search" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
        <input type="text" id="tableSearch" class="arovia-input" placeholder="Rechercher..." style="padding-left: 36px; height: 38px; font-size: 0.9rem;">
      </div>
    </div>
    <table class="arovia-table">
      <thead>
        <tr><th>Date</th><th>Litres utilisés</th><th>Nb. bocaux</th><th>Type de bocal</th><th>Perte (L)</th><th>Statut</th></tr>
      </thead>
      <tbody>
        <?php if (!empty($transformations)): ?>
          <?php foreach ($transformations as $item): ?>
            <tr>
              <td><?= esc($item['date_transformation'] ?? '') ?></td>
              <td><?= number_format($item['quantite_litres_utilisee'] ?? 0, 2) ?> L</td>
              <td><span class="badge-arovia badge-gold"><?= (int) ($item['total_bocaux'] ?? 0) ?></span></td>
              <td><?= esc($item['bocal_noms'] ?? '—') ?></td>
              <td class="text-orange"><?= number_format(max(0, ($item['quantite_litres_utilisee'] ?? 0) - (($item['total_bocaux'] ?? 0) * ($item['volume_bocal_litres'] ?? 0))), 2) ?> L</td>
              <td><span class="badge-arovia badge-green"><i class="fa fa-check me-1"></i>Terminé</span></td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="6" class="text-center text-muted">Aucune transformation enregistrée.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</main>
<div class="modal fade" id="modalTransfo" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Nouvelle transformation</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <form method="post" action="/transformations">
        <div class="modal-body">
          <?php if (!empty($typesBocaux)): ?>
            <?php foreach ($typesBocaux as $type): ?>
              <div class="mb-3">
                <label class="arovia-label" for="quantite_<?= (int) $type['id'] ?>"><?= esc($type['nom'] ?? 'Bocal') ?> (<?= (int) ($type['volume_litres'] * 1000) ?> mL)</label>
                <input type="number" class="arovia-input" id="quantite_<?= (int) $type['id'] ?>" name="quantite_<?= (int) $type['id'] ?>" min="0" value="0" placeholder="0"/>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
        <div class="modal-footer"><button type="button" class="btn-outline-gold" data-bs-dismiss="modal">Annuler</button><button type="submit" class="btn-gold">Enregistrer</button></div>
      </form>
    </div>
  </div>
</div>
<script src="<?= base_url('assets/bootstrap/bootstrap.bundle.min.js') ?>"></script>
<script>
function toggleSubmenu(el){el.classList.toggle('open');el.nextElementSibling.classList.toggle('open');}

document.getElementById('tableSearch').addEventListener('keyup', function() {
  const query = this.value.toLowerCase();
  const rows = document.querySelectorAll('.arovia-table tbody tr');
  rows.forEach(row => {
    const text = row.textContent.toLowerCase();
    row.style.display = text.includes(query) ? '' : 'none';
  });
});
</script>
</body>
</html>
