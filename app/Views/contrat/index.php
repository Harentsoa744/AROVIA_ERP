<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/><meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>Gestion de contrat — Miel Arovia</title>
  <link rel="stylesheet" href="assets/bootstrap/bootstrap.min.css"/>
  <link rel="stylesheet" href="assets/css/global.css"/>
  <link rel="stylesheet" href="assets/css/contrats.css"/>
</head>
<body>
<?php include 'utils/header.php'; ?>
<?php include 'utils/side_bar.php'; ?>

<main class="main-wrapper">
  <div class="page-header">
    <h1 class="page-title">Gestion des contrats</h1>
    <button class="btn-gold" data-bs-toggle="modal" data-bs-target="#modalContrat"><i class="fa fa-plus"></i> Nouveau contrat</button>
  </div>
  
  <div class="row g-3 mb-4">
    <div class="col-6 col-md-4"><div class="kpi-card"><div class="kpi-icon-wrap blue"><i class="fa fa-file-signature"></i></div><div class="kpi-label">Contrats actifs</div><div class="kpi-value dark">1</div></div></div>
    <div class="col-6 col-md-4"><div class="kpi-card"><div class="kpi-icon-wrap orange"><i class="fa fa-hourglass-half"></i></div><div class="kpi-label">En attente</div><div class="kpi-value orange">0</div></div></div>
    <div class="col-6 col-md-4"><div class="kpi-card"><div class="kpi-icon-wrap red"><i class="fa fa-circle-xmark"></i></div><div class="kpi-label">Expirés</div><div class="kpi-value red">0</div></div></div>
  </div>

  <div class="content-card">
    <table class="arovia-table">
      <thead>
        <tr><th>Partenaire</th><th>Type</th><th>Date début</th><th>Date fin</th><th>Statut</th><th>Actions</th></tr>
      </thead>
      <tbody>
        <tr>
          <td><span class="table-avatar">R</span> Rakoto (Fournisseur)</td>
          <td>Approvisionnement</td>
          <td>01/01/2026</td>
          <td>31/12/2026</td>
          <td><span class="badge-arovia badge-green">Actif</span></td>
          <td>
            <button class="btn-icon-edit" title="Voir/Modifier"><i class="fa fa-eye"></i></button>
            <button class="btn-icon-delete ms-1"><i class="fa fa-trash"></i></button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</main>
<!-- Modal -->
<div class="modal fade" id="modalContrat" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Nouveau contrat</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
        <div class="mb-3"><label class="arovia-label">Partenaire *</label><input type="text" class="arovia-input" placeholder="Nom du fournisseur/client"/></div>
        <div class="mb-3"><label class="arovia-label">Type de contrat</label><select class="arovia-input"><option>Approvisionnement</option><option>Distribution</option><option>Embauche</option></select></div>
        <div class="row">
          <div class="col-6 mb-3"><label class="arovia-label">Date de début *</label><input type="date" class="arovia-input"/></div>
          <div class="col-6 mb-3"><label class="arovia-label">Date de fin</label><input type="date" class="arovia-input"/></div>
        </div>
        <div class="mb-3"><label class="arovia-label">Document joint (PDF)</label><input type="file" class="arovia-input"/></div>
      </div>
      <div class="modal-footer"><button class="btn-outline-gold" data-bs-dismiss="modal">Annuler</button><button class="btn-gold">Enregistrer</button></div>
    </div>
  </div>
</div>
<script>function toggleSubmenu(el){el.classList.toggle('open');el.nextElementSibling.classList.toggle('open');}</script>
</body>
</html>
