<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>Fournisseurs — Miel Arovia</title>
  <link rel="stylesheet" href="assets/bootstrap/bootstrap.min.css"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <link rel="stylesheet" href="assets/css/global.css"/>
  <link rel="stylesheet" href="assets/css/fournisseurs.css"/>
</head>
<body>

<!-- TOPBAR -->
<?php include 'utils/header.php'; ?>
<?php include 'utils/side_bar.php'; ?>
<!-- MAIN -->
<main class="main-wrapper">
  <div class="breadcrumb-bar">
    <a href="gestion-stock.html">Gestion de stock</a>
    <span>›</span> Fournisseurs
  </div>

  <div class="page-header">
    <h1 class="page-title">Liste des fournisseurs</h1>
    <button class="btn-gold" data-bs-toggle="modal" data-bs-target="#modalAjout">
      <i class="fa fa-plus"></i> Ajouter un fournisseur
    </button>
  </div>

  <!-- Table Card -->
  <div class="content-card">
    <table class="arovia-table">
      <thead>
        <tr>
          <th>Nom <i class="fa fa-sort ms-1" style="font-size:.7rem"></i></th>
          <th>Contact</th>
          <th>Localisation</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>
            <div style="display:flex;align-items:center;gap:.5rem">
              <span class="table-avatar">R</span>
              Rakoto
            </div>
          </td>
          <td><i class="fa fa-envelope table-info-icon"></i> rakoto@gmail.com</td>
          <td><i class="fa fa-location-dot table-info-icon"></i> Andoharanofotsy</td>
          <td>
            <button class="btn-icon-edit" title="Modifier"><i class="fa fa-pen"></i></button>
            <button class="btn-icon-delete ms-1" title="Supprimer"><i class="fa fa-trash"></i></button>
          </td>
        </tr>
        <tr>
          <td>
            <div style="display:flex;align-items:center;gap:.5rem">
              <span class="table-avatar" style="background:var(--accent-blue)">M</span>
              Miaro
            </div>
          </td>
          <td><i class="fa fa-envelope table-info-icon"></i> miaro@arovia.mg</td>
          <td><i class="fa fa-location-dot table-info-icon"></i> Ambatolampy</td>
          <td>
            <button class="btn-icon-edit" title="Modifier"><i class="fa fa-pen"></i></button>
            <button class="btn-icon-delete ms-1" title="Supprimer"><i class="fa fa-trash"></i></button>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Pagination -->
    <div class="table-footer">
      <span class="table-info-text">Affichage de 1 à 2 sur 2 résultats</span>
      <div class="d-flex align-items-center gap-2">
        <select class="arovia-input" style="width:120px;padding:.3rem .7rem">
          <option>10 par page</option>
          <option>25 par page</option>
          <option>50 par page</option>
        </select>
        <div class="arovia-pagination">
          <a href="#" class="page-btn">«</a>
          <a href="#" class="page-btn active">1</a>
          <a href="#" class="page-btn">»</a>
        </div>
      </div>
    </div>
  </div>
</main>

<!-- Modal Ajout Fournisseur -->
<div class="modal fade" id="modalAjout" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Ajouter un fournisseur</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="arovia-label">Nom *</label>
          <input type="text" class="arovia-input" placeholder="Nom du fournisseur"/>
        </div>
        <div class="mb-3">
          <label class="arovia-label">Email</label>
          <input type="email" class="arovia-input" placeholder="email@exemple.com"/>
        </div>
        <div class="mb-3">
          <label class="arovia-label">Téléphone</label>
          <input type="tel" class="arovia-input" placeholder="+261 XX XX XXX XX"/>
        </div>
        <div class="mb-3">
          <label class="arovia-label">Localisation</label>
          <input type="text" class="arovia-input" placeholder="Ville / Quartier"/>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn-outline-gold" data-bs-dismiss="modal">Annuler</button>
        <button type="button" class="btn-gold">Enregistrer</button>
      </div>
    </div>
  </div>
</div>

<script src="assets/bootstrap/bootstrap.bundle.min.js"></script>
<script>
function toggleSubmenu(el){
  el.classList.toggle('open');
  el.nextElementSibling.classList.toggle('open');
}
</script>
</body>
</html>
