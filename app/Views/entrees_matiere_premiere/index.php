<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/><meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>Entrées matière première — Miel Arovia</title>
  <link rel="stylesheet" href="assets/bootstrap/bootstrap.min.css"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <link rel="stylesheet" href="assets/css/global.css"/>
  <link rel="stylesheet" href="assets/css/entrees.css"/>
</head>
<body>
<?php include 'utils/header.php'; ?>
<?php include 'utils/side_bar.php'; ?>
<main class="main-wrapper">
  <div class="breadcrumb-bar"><a href="gestion-stock.html">Gestion de stock</a> <span>›</span> Entrées matière première</div>
  <div class="page-header">
    <h1 class="page-title">Entrées matière première</h1>
    <button class="btn-gold" data-bs-toggle="modal" data-bs-target="#modalEntree"><i class="fa fa-plus"></i> Nouvelle entrée</button>
  </div>
  <!-- KPIs -->
  <div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
      <div class="kpi-card"><div class="kpi-icon-wrap green"><i class="fa fa-droplet"></i></div><div class="kpi-label">Total litres reçus</div><div class="kpi-value green">15.00 L</div></div>
    </div>
    <div class="col-6 col-md-3">
      <div class="kpi-card"><div class="kpi-icon-wrap gold"><i class="fa fa-receipt"></i></div><div class="kpi-label">Nb. entrées</div><div class="kpi-value dark">1</div></div>
    </div>
    <div class="col-6 col-md-3">
      <div class="kpi-card"><div class="kpi-icon-wrap orange"><i class="fa fa-circle-dollar-to-slot"></i></div><div class="kpi-label">Coût total</div><div class="kpi-value orange">75 000 Ar</div></div>
    </div>
    <div class="col-6 col-md-3">
      <div class="kpi-card"><div class="kpi-icon-wrap blue"><i class="fa fa-calculator"></i></div><div class="kpi-label">CUMP</div><div class="kpi-value blue">5 000 Ar/L</div></div>
    </div>
  </div>
  <div class="content-card">
    <table class="arovia-table">
      <thead>
        <tr><th>Date</th><th>Fournisseur</th><th>Quantité (L)</th><th>Prix unit. (Ar)</th><th>Total (Ar)</th><th>Actions</th></tr>
      </thead>
      <tbody>
        <tr>
          <td>28/06/2026</td>
          <td><span class="table-avatar">R</span> Rakoto</td>
          <td><span class="badge-arovia badge-green">15.00 L</span></td>
          <td>5 000</td>
          <td class="fw-600 text-orange">75 000</td>
          <td>
            <button class="btn-icon-edit"><i class="fa fa-pen"></i></button>
            <button class="btn-icon-delete ms-1"><i class="fa fa-trash"></i></button>
          </td>
        </tr>
      </tbody>
    </table>
    <div class="table-footer">
      <span class="table-info-text">Affichage de 1 à 1 sur 1 résultat</span>
      <div class="d-flex align-items-center gap-2">
        <select class="arovia-input" style="width:120px;padding:.3rem .7rem"><option>10 par page</option></select>
        <div class="arovia-pagination"><a href="#" class="page-btn">«</a><a href="#" class="page-btn active">1</a><a href="#" class="page-btn">»</a></div>
      </div>
    </div>
  </div>
</main>
<!-- Modal -->
<div class="modal fade" id="modalEntree" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Nouvelle entrée matière première</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
        <div class="mb-3"><label class="arovia-label">Date *</label><input type="date" class="arovia-input"/></div>
        <div class="mb-3"><label class="arovia-label">Fournisseur *</label><select class="arovia-input"><option>Sélectionner...</option><option>Rakoto</option></select></div>
        <div class="mb-3"><label class="arovia-label">Quantité (L) *</label><input type="number" class="arovia-input" placeholder="0.00"/></div>
        <div class="mb-3"><label class="arovia-label">Prix unitaire (Ar/L) *</label><input type="number" class="arovia-input" placeholder="0"/></div>
        <div class="mb-3"><label class="arovia-label">Notes</label><textarea class="arovia-input" rows="3" placeholder="Observations..."></textarea></div>
      </div>
      <div class="modal-footer"><button class="btn-outline-gold" data-bs-dismiss="modal">Annuler</button><button class="btn-gold">Enregistrer</button></div>
    </div>
  </div>
</div>
<script src="assets/bootstrap/bootstrap.bundle.min.js"></script>
<script>function toggleSubmenu(el){el.classList.toggle('open');el.nextElementSibling.classList.toggle('open');}</script>
</body>
</html>
