<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/><meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>Valeur du stock — Miel Arovia</title>
  <link rel="stylesheet" href="assets/bootstrap/bootstrap.min.css"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <link rel="stylesheet" href="assets/css/global.css"/>
  <link rel="stylesheet" href="assets/css/valeur-stock.css"/>
</head>
<body>
<?php include 'utils/header.php'; ?>
<?php include 'utils/side_bar.php'; ?>
<main class="main-wrapper">
  <div class="breadcrumb-bar"><a href="gestion-stock.html">Gestion de stock</a> <span>›</span> Valeur du stock</div>
  <div class="page-header">
    <h1 class="page-title">Valeur du stock</h1>
    <button class="btn-gold"><i class="fa fa-download"></i> Exporter</button>
  </div>
  <div class="row g-3 mb-4">
    <div class="col-6 col-md-3"><div class="kpi-card"><div class="kpi-icon-wrap green"><i class="fa fa-droplet"></i></div><div class="kpi-label">Stock matière (L)</div><div class="kpi-value green">2.00 L</div></div></div>
    <div class="col-6 col-md-3"><div class="kpi-card"><div class="kpi-icon-wrap gold"><i class="fa fa-jar"></i></div><div class="kpi-label">Bocaux en stock</div><div class="kpi-value gold">35</div></div></div>
    <div class="col-6 col-md-3"><div class="kpi-card"><div class="kpi-icon-wrap blue"><i class="fa fa-calculator"></i></div><div class="kpi-label">CUMP (Ar/L)</div><div class="kpi-value blue">5 000</div></div></div>
    <div class="col-6 col-md-3"><div class="kpi-card"><div class="kpi-icon-wrap orange"><i class="fa fa-scale-balanced"></i></div><div class="kpi-label">Valeur totale</div><div class="kpi-value orange">178 500 Ar</div></div></div>
  </div>
  <div class="content-card">
    <div class="content-card-title">Détail de valorisation</div>
    <table class="arovia-table">
      <thead><tr><th>Article</th><th>Quantité</th><th>CUMP unitaire</th><th>Valeur totale</th></tr></thead>
      <tbody>
        <tr>
          <td><div class="d-flex align-items-center gap-2"><div class="kpi-icon-wrap green" style="width:32px;height:32px"><i class="fa fa-droplet" style="font-size:.8rem"></i></div>Miel brut (L)</div></td>
          <td>2.00 L</td>
          <td>5 000 Ar</td>
          <td class="fw-600 text-green">10 000 Ar</td>
        </tr>
        <tr>
          <td><div class="d-flex align-items-center gap-2"><div class="kpi-icon-wrap gold" style="width:32px;height:32px"><i class="fa fa-jar" style="font-size:.8rem"></i></div>Bocaux 375 mL</div></td>
          <td>35 unités</td>
          <td>4 814 Ar</td>
          <td class="fw-600 text-gold">168 500 Ar</td>
        </tr>
      </tbody>
    </table>
    <div class="valeur-total-bar">
      <span>Valeur totale du stock</span>
      <span class="valeur-total-num">178 500 Ar</span>
    </div>
  </div>
</main>
<script src="assets/bootstrap/bootstrap.bundle.min.js"></script>
<script>function toggleSubmenu(el){el.classList.toggle('open');el.nextElementSibling.classList.toggle('open');}</script>
</body>
</html>
