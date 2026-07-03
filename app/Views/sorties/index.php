<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/><meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>Sorties (ventes) — Miel Arovia</title>
  <link rel="stylesheet" href="assets/bootstrap/bootstrap.min.css"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <link rel="stylesheet" href="assets/css/global.css"/>
  <link rel="stylesheet" href="assets/css/sorties.css"/>
</head>
<body>
<?php include 'utils/header.php'; ?>
<?php include 'utils/side_bar.php'; ?>
<main class="main-wrapper">
  <div class="breadcrumb-bar"><a href="gestion-stock.html">Gestion de stock</a> <span>›</span> Sorties (ventes)</div>
  <div class="page-header">
    <h1 class="page-title">Sorties (ventes)</h1>
    <button class="btn-gold" data-bs-toggle="modal" data-bs-target="#modalSortie"><i class="fa fa-plus"></i> Nouvelle vente</button>
  </div>
  <div class="row g-3 mb-4">
    <div class="col-6 col-md-3"><div class="kpi-card"><div class="kpi-icon-wrap red"><i class="fa fa-cart-shopping"></i></div><div class="kpi-label">Bocaux vendus</div><div class="kpi-value red">0</div></div></div>
    <div class="col-6 col-md-3"><div class="kpi-card"><div class="kpi-icon-wrap gold"><i class="fa fa-circle-dollar-to-slot"></i></div><div class="kpi-label">Chiffre d'affaires</div><div class="kpi-value gold">0 Ar</div></div></div>
    <div class="col-6 col-md-3"><div class="kpi-card"><div class="kpi-icon-wrap green"><i class="fa fa-jar"></i></div><div class="kpi-label">Stock restant</div><div class="kpi-value green">35</div></div></div>
    <div class="col-6 col-md-3"><div class="kpi-card"><div class="kpi-icon-wrap blue"><i class="fa fa-percent"></i></div><div class="kpi-label">Taux écoulement</div><div class="kpi-value blue">0%</div></div></div>
  </div>
  <div class="content-card">
    <table class="arovia-table">
      <thead>
        <tr><th>Date</th><th>Client</th><th>Nb. bocaux</th><th>Prix unit. (Ar)</th><th>Total (Ar)</th><th>Statut</th><th>Actions</th></tr>
      </thead>
      <tbody>
        <tr>
          <td colspan="7" style="text-align:center;padding:2rem;color:var(--text-muted)">
            <i class="fa fa-inbox" style="font-size:2rem;margin-bottom:.5rem;display:block"></i>
            Aucune vente enregistrée
          </td>
        </tr>
      </tbody>
    </table>
    <div class="table-footer">
      <span class="table-info-text">Affichage de 0 à 0 sur 0 résultat</span>
      <div class="arovia-pagination"><a href="#" class="page-btn">«</a><a href="#" class="page-btn active">1</a><a href="#" class="page-btn">»</a></div>
    </div>
  </div>
</main>
<div class="modal fade" id="modalSortie" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Nouvelle vente</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
        <div class="mb-3"><label class="arovia-label">Date *</label><input type="date" class="arovia-input"/></div>
        <div class="mb-3"><label class="arovia-label">Client</label><input type="text" class="arovia-input" placeholder="Nom du client"/></div>
        <div class="mb-3"><label class="arovia-label">Nombre de bocaux *</label><input type="number" class="arovia-input" placeholder="0"/></div>
        <div class="mb-3"><label class="arovia-label">Prix unitaire (Ar) *</label><input type="number" class="arovia-input" placeholder="0"/></div>
        <div class="mb-3"><label class="arovia-label">Statut</label><select class="arovia-input"><option>Payé</option><option>En attente</option><option>Annulé</option></select></div>
      </div>
      <div class="modal-footer"><button class="btn-outline-gold" data-bs-dismiss="modal">Annuler</button><button class="btn-gold">Enregistrer</button></div>
    </div>
  </div>
</div>
<script src="assets/bootstrap/bootstrap.bundle.min.js"></script>
<script>function toggleSubmenu(el){el.classList.toggle('open');el.nextElementSibling.classList.toggle('open');}</script>
</body>
</html>
