<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/><meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>Distribution — Miel Arovia</title>
  <link rel="stylesheet" href="assets/bootstrap/bootstrap.min.css"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <link rel="stylesheet" href="assets/css/global.css"/>
  <link rel="stylesheet" href="assets/css/distribution.css"/>
</head>
<body>
<?php include 'utils/header.php'; ?>
<?php include 'utils/side_bar.php'; ?>
<main class="main-wrapper">
  <div class="page-header">
    <h1 class="page-title">Suivi des Distributions</h1>
    <button class="btn-gold" data-bs-toggle="modal" data-bs-target="#modalLivraison"><i class="fa fa-plus"></i> Nouvelle Livraison</button>
  </div>
  
  <div class="row g-3 mb-4">
    <div class="col-6 col-md-4"><div class="kpi-card"><div class="kpi-icon-wrap blue"><i class="fa fa-spinner"></i></div><div class="kpi-label">En cours</div><div class="kpi-value blue">1</div></div></div>
    <div class="col-6 col-md-4"><div class="kpi-card"><div class="kpi-icon-wrap green"><i class="fa fa-check-double"></i></div><div class="kpi-label">Livrées (Mois)</div><div class="kpi-value green">12</div></div></div>
    <div class="col-6 col-md-4"><div class="kpi-card"><div class="kpi-icon-wrap orange"><i class="fa fa-triangle-exclamation"></i></div><div class="kpi-label">Incidents</div><div class="kpi-value orange">0</div></div></div>
  </div>

  <div class="content-card">
    <table class="arovia-table">
      <thead>
        <tr><th>ID</th><th>Client/Destination</th><th>Livreur</th><th>Date prévue</th><th>Statut</th><th>Actions</th></tr>
      </thead>
      <tbody>
        <tr>
          <td class="fw-600">#LIV-0042</td>
          <td>Supermarché B - Analakely</td>
          <td><span class="table-avatar" style="width:24px;height:24px;font-size:.7rem;margin-right:.3rem">J</span> Jean Randria</td>
          <td>Aujourd'hui, 14:00</td>
          <td><span class="badge-arovia badge-blue"><i class="fa fa-truck me-1"></i>En route</span></td>
          <td>
            <button class="btn-icon-edit" title="Détails"><i class="fa fa-eye"></i></button>
            <button class="btn-icon-edit ms-1 text-green bg-green-light" title="Marquer livré"><i class="fa fa-check"></i></button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</main>
<!-- Modal -->
<div class="modal fade" id="modalLivraison" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Planifier une livraison</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
        <div class="mb-3"><label class="arovia-label">Client / Destination *</label><input type="text" class="arovia-input"/></div>
        <div class="mb-3"><label class="arovia-label">Livreur assigné *</label><select class="arovia-input"><option>Jean Randria</option></select></div>
        <div class="row">
          <div class="col-6 mb-3"><label class="arovia-label">Date</label><input type="date" class="arovia-input"/></div>
          <div class="col-6 mb-3"><label class="arovia-label">Heure prévue</label><input type="time" class="arovia-input"/></div>
        </div>
        <div class="mb-3"><label class="arovia-label">Notes de livraison</label><textarea class="arovia-input" rows="2"></textarea></div>
      </div>
      <div class="modal-footer"><button class="btn-outline-gold" data-bs-dismiss="modal">Annuler</button><button class="btn-gold">Planifier</button></div>
    </div>
  </div>
</div>
<script src="assets/bootstrap/bootstrap.bundle.min.js"></script>
<script>function toggleSubmenu(el){el.classList.toggle('open');el.nextElementSibling.classList.toggle('open');}</script>
</body>
</html>
