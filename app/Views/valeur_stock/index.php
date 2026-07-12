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
    <div class="page-title-wrap">
      <h1 class="page-title">Valeur du stock</h1>
      <img src="<?= base_url('assets/images/Pattern combi.png') ?>" alt="Pattern" class="header-pattern-img" />
    </div>
    <div class="d-flex gap-2">
      <a href="<?= base_url('valeur-stock/export') ?>" class="btn-gold"><i class="fa fa-download"></i> Exporter en CSV</a>
      <a href="<?= base_url('valeur-stock/export-pdf') ?>" class="btn-gold"><i class="fa fa-file-pdf"></i> Exporter en PDF</a>
    </div>
  </div>

  <div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
      <div class="kpi-card">
        <div class="kpi-icon-wrap green"><i class="fa fa-droplet"></i></div>
        <div class="kpi-label">Valeur Miel Brut (MP)</div>
        <div class="kpi-value green"><?= number_format($stockMP['valeur_stock'] ?? 0, 0, ',', ' ') ?> <small>Ar</small></div>
        <div class="kpi-sub"><?= number_format($stockMP['quantite_litres'] ?? 0, 2) ?> L à <?= number_format($stockMP['cump_actuel'] ?? 0, 0, ',', ' ') ?> Ar/L</div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="kpi-card">
        <div class="kpi-icon-wrap gold"><i class="fa fa-jar"></i></div>
        <div class="kpi-label">Valeur Bocaux (PF)</div>
        <div class="kpi-value gold"><?= number_format($valeurComptablePF ?? 0, 0, ',', ' ') ?> <small>Ar</small></div>
        <div class="kpi-sub">Coût de revient matière</div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="kpi-card">
        <div class="kpi-icon-wrap blue"><i class="fa fa-shop"></i></div>
        <div class="kpi-label">Valeur Vente Potentielle</div>
        <div class="kpi-value blue"><?= number_format($valeurVentePF ?? 0, 0, ',', ' ') ?> <small>Ar</small></div>
        <div class="kpi-sub">Selon tarifs catalogue</div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="kpi-card">
        <div class="kpi-icon-wrap orange"><i class="fa fa-scale-balanced"></i></div>
        <div class="kpi-label">Total Consolidé (MP+PF)</div>
        <div class="kpi-value orange"><?= number_format($valeurTotaleComptable ?? 0, 0, ',', ' ') ?> <small>Ar</small></div>
        <div class="kpi-sub">Valeur comptable totale</div>
      </div>
    </div>
  </div>

  <div class="content-card">
    <div class="content-card-title">Détail de valorisation du stock Produit Fini (Bocaux)</div>
    <table class="arovia-table">
      <thead>
        <tr>
          <th>Article</th>
          <th>Quantité en stock</th>
          <th>Coût matière unitaire</th>
          <th>Valeur Comptable</th>
          <th>Prix de Vente Catalogue</th>
          <th>Valeur Vente Potentielle</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($stockPF)): ?>
          <?php foreach ($stockPF as $bocal): ?>
            <tr>
              <td><div class="d-flex align-items-center gap-2"><div class="kpi-icon-wrap gold" style="width:32px;height:32px"><i class="fa fa-jar" style="font-size:.8rem"></i></div><?= esc($bocal['nom'] ?? 'Bocal') ?></div></td>
              <td><?= (int) ($bocal['quantite_disponible'] ?? 0) ?> unités</td>
              <td><?= number_format($bocal['cout_unitaire'] ?? 0, 0, ',', ' ') ?> Ar</td>
              <td class="fw-600 text-gold"><?= number_format($bocal['valeur_comptable'] ?? 0, 0, ',', ' ') ?> Ar</td>
              <td><?= $bocal['prix_vente'] !== null ? number_format($bocal['prix_vente'], 0, ',', ' ') . ' Ar' : '<span class="text-muted">Non défini</span>' ?></td>
              <td class="fw-600 text-blue"><?= number_format($bocal['valeur_vente'] ?? 0, 0, ',', ' ') ?> Ar</td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="6" class="text-center text-muted" style="padding:2rem">Aucune donnée de stock disponible.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</main>
<script src="<?= base_url('assets/bootstrap/bootstrap.bundle.min.js') ?>"></script>
<script>function toggleSubmenu(el){el.classList.toggle('open');el.nextElementSibling.classList.toggle('open');}</script>
</body>
</html>
