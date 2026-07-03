<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/><meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>Transformations — Miel Arovia</title>
  <link rel="stylesheet" href="assets/bootstrap/bootstrap.min.css"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <link rel="stylesheet" href="assets/css/global.css"/>
  <link rel="stylesheet" href="assets/css/transformations.css"/>
</head>
<body>
<?php include 'utils/header.php'; ?>
<?php include 'utils/side_bar.php'; ?>
<main class="main-wrapper">
  <div class="breadcrumb-bar"><a href="gestion-stock.html">Gestion de stock</a> <span>›</span> Transformations</div>
  <div class="page-header">
    <h1 class="page-title">Transformations (mise en bocal)</h1>
    <button class="btn-gold" data-bs-toggle="modal" data-bs-target="#modalTransfo"><i class="fa fa-plus"></i> Nouvelle transformation</button>
  </div>
  <div class="row g-3 mb-4">
    <div class="col-6 col-md-3"><div class="kpi-card"><div class="kpi-icon-wrap blue"><i class="fa fa-recycle"></i></div><div class="kpi-label">Transformations totales</div><div class="kpi-value dark">1</div></div></div>
    <div class="col-6 col-md-3"><div class="kpi-card"><div class="kpi-icon-wrap green"><i class="fa fa-droplet"></i></div><div class="kpi-label">Litres transformés</div><div class="kpi-value green">13.00 L</div></div></div>
    <div class="col-6 col-md-3"><div class="kpi-card"><div class="kpi-icon-wrap gold"><i class="fa fa-jar"></i></div><div class="kpi-label">Bocaux produits</div><div class="kpi-value gold">35</div></div></div>
    <div class="col-6 col-md-3"><div class="kpi-card"><div class="kpi-icon-wrap orange"><i class="fa fa-arrow-trend-down"></i></div><div class="kpi-label">Taux de perte</div><div class="kpi-value orange">2.3%</div></div></div>
  </div>
  <div class="content-card">
    <table class="arovia-table">
      <thead>
        <tr><th>Date</th><th>Litres utilisés</th><th>Nb. bocaux</th><th>Capacité bocal</th><th>Perte (L)</th><th>Statut</th><th>Actions</th></tr>
      </thead>
      <tbody>
        <tr>
          <td>28/06/2026</td>
          <td>13.00 L</td>
          <td><span class="badge-arovia badge-gold">35</span></td>
          <td>375 mL</td>
          <td class="text-orange">0.125 L</td>
          <td><span class="badge-arovia badge-green"><i class="fa fa-check me-1"></i>Terminé</span></td>
          <td>
            <button class="btn-icon-edit"><i class="fa fa-pen"></i></button>
            <button class="btn-icon-delete ms-1"><i class="fa fa-trash"></i></button>
          </td>
        </tr>
      </tbody>
    </table>
    <div class="table-footer">
      <span class="table-info-text">Affichage de 1 à 1 sur 1 résultat</span>
      <div class="arovia-pagination"><a href="#" class="page-btn">«</a><a href="#" class="page-btn active">1</a><a href="#" class="page-btn">»</a></div>
    </div>
  </div>
</main>
<div class="modal fade" id="modalTransfo" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Nouvelle transformation</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
        <div class="mb-3"><label class="arovia-label">Date *</label><input type="date" class="arovia-input"/></div>
        <div class="mb-3"><label class="arovia-label">Litres utilisés *</label><input type="number" class="arovia-input" placeholder="0.00"/></div>
        <div class="mb-3"><label class="arovia-label">Nombre de bocaux *</label><input type="number" class="arovia-input" placeholder="0"/></div>
        <div class="mb-3"><label class="arovia-label">Capacité par bocal (mL)</label><input type="number" class="arovia-input" placeholder="375"/></div>
        <div class="mb-3"><label class="arovia-label">Notes</label><textarea class="arovia-input" rows="2" placeholder="Observations..."></textarea></div>
      </div>
      <div class="modal-footer"><button class="btn-outline-gold" data-bs-dismiss="modal">Annuler</button><button class="btn-gold">Enregistrer</button></div>
    </div>
  </div>
</div>
<script src="assets/bootstrap/bootstrap.bundle.min.js"></script>
<script>function toggleSubmenu(el){el.classList.toggle('open');el.nextElementSibling.classList.toggle('open');}</script>
</body>
</html>
