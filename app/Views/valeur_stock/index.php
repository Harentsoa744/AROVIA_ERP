<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/><meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>Valeur du stock — Miel Arovia</title>
  <link rel="stylesheet" href="<?= base_url('assets/bootstrap/bootstrap.min.css') ?>"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <link rel="stylesheet" href="<?= base_url('assets/css/global.css') ?>"/>
  <link rel="stylesheet" href="<?= base_url('assets/css/valeur-stock.css') ?>"/>
</head>
<body>
<?php include 'utils/header.php'; ?>
<?php include 'utils/side_bar.php'; ?>
<main class="main-wrapper">
  <div class="breadcrumb-bar"><a href="/valeur-stock">Gestion de stock</a> <span>›</span> Valeur du stock</div>
  <div class="page-header">
    <h1 class="page-title">Valeur du stock</h1>
    <a href="<?= base_url('valeur-stock/export') ?>" class="btn-gold"><i class="fa fa-download"></i> Exporter en CSV</a>
    <a href="<?= base_url('valeur-stock/export-pdf') ?>" class="btn-gold"><i class="fa fa-file-pdf"></i> Exporter en PDF</a>
  </div>

  <div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
      <div class="kpi-card"><div class="kpi-icon-wrap green"><i class="fa fa-droplet"></i></div><div class="kpi-label">Stock matière (L)</div><div class="kpi-value green"><?= number_format($stockMP['quantite_litres'] ?? 0, 2) ?> L</div></div>
    </div>
    <div class="col-6 col-md-3">
      <div class="kpi-card"><div class="kpi-icon-wrap gold"><i class="fa fa-jar"></i></div><div class="kpi-label">Bocaux en stock</div><div class="kpi-value gold"><?= count($stockPF ?? []) ?></div></div>
    </div>
    <div class="col-6 col-md-3">
      <div class="kpi-card"><div class="kpi-icon-wrap blue"><i class="fa fa-calculator"></i></div><div class="kpi-label">CUMP (Ar/L)</div><div class="kpi-value blue"><?= number_format($stockMP['cump_actuel'] ?? 0, 0, ',', ' ') ?></div></div>
    </div>
    <div class="col-6 col-md-3">
      <div class="kpi-card"><div class="kpi-icon-wrap orange"><i class="fa fa-scale-balanced"></i></div><div class="kpi-label">Valeur totale</div><div class="kpi-value orange"><?= number_format($valeurTotaleComptable ?? 0, 0, ',', ' ') ?> Ar</div></div>
    </div>
  </div>

  <div class="content-card">
    <div class="content-card-title">Détail de valorisation</div>
    <table class="arovia-table">
      <thead><tr><th>Article</th><th>Quantité</th><th>CUMP unitaire</th><th>Valeur totale</th></tr></thead>
      <tbody>
        <?php if (!empty($stockPF)): ?>
          <?php foreach ($stockPF as $bocal): ?>
            <tr>
              <td><div class="d-flex align-items-center gap-2"><div class="kpi-icon-wrap gold" style="width:32px;height:32px"><i class="fa fa-jar" style="font-size:.8rem"></i></div><?= esc($bocal['nom'] ?? 'Bocal') ?></div></td>
              <td><?= (int) ($bocal['quantite_disponible'] ?? 0) ?> unités</td>
              <td><?= number_format($bocal['cout_unitaire'] ?? 0, 0, ',', ' ') ?> Ar</td>
              <td class="fw-600 text-gold"><?= number_format($bocal['valeur_comptable'] ?? 0, 0, ',', ' ') ?> Ar</td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="4" class="text-center text-muted" style="padding:2rem">Aucune donnée de stock disponible.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
    <div class="valeur-total-bar">
      <span>Valeur totale du stock</span>
      <span class="valeur-total-num"><?= number_format($valeurTotaleComptable ?? 0, 0, ',', ' ') ?> Ar</span>
    </div>
  </div>
</main>
<script src="<?= base_url('assets/bootstrap/bootstrap.bundle.min.js') ?>"></script>
<script>function toggleSubmenu(el){el.classList.toggle('open');el.nextElementSibling.classList.toggle('open');}</script>
</body>
</html>
