<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/><meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>Employés — Miel Arovia</title>
  <link rel="stylesheet" href="assets/bootstrap/bootstrap.min.css"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <link rel="stylesheet" href="assets/css/global.css"/>
  <link rel="stylesheet" href="assets/css/employes.css"/>
</head>
<body>
<?php include 'utils/header.php'; ?>
<?php include 'utils/side_bar.php'; ?>
<main class="main-wrapper">
  <div class="page-header">
    <h1 class="page-title">Gestion des Employés</h1>
    <button class="btn-gold" data-bs-toggle="modal" data-bs-target="#modalEmploye"><i class="fa fa-user-plus"></i> Ajouter</button>
  </div>
  
  <div class="row g-3">
    <!-- Exemple Card Employé -->
    <div class="col-12 col-md-6 col-lg-4">
      <div class="content-card d-flex flex-column align-items-center text-center">
        <div class="table-avatar mb-3" style="width:72px;height:72px;font-size:1.8rem">J</div>
        <h4 class="fw-700 text-dark-primary mb-1" style="font-size:1.1rem">Jean Randria</h4>
        <div class="text-muted" style="font-size:.85rem">Livreur</div>
        <div class="d-flex gap-2 mt-3 w-100">
          <button class="btn-outline-gold flex-fill" style="justify-content:center"><i class="fa fa-envelope"></i> Message</button>
          <button class="btn-icon-edit"><i class="fa fa-pen"></i></button>
        </div>
      </div>
    </div>
    <div class="col-12 col-md-6 col-lg-4">
      <div class="content-card d-flex flex-column align-items-center text-center">
        <div class="table-avatar mb-3" style="width:72px;height:72px;font-size:1.8rem;background:var(--accent-blue)">M</div>
        <h4 class="fw-700 text-dark-primary mb-1" style="font-size:1.1rem">Marie Rasoa</h4>
        <div class="text-muted" style="font-size:.85rem">Responsable Stock</div>
        <div class="d-flex gap-2 mt-3 w-100">
          <button class="btn-outline-gold flex-fill" style="justify-content:center"><i class="fa fa-envelope"></i> Message</button>
          <button class="btn-icon-edit"><i class="fa fa-pen"></i></button>
        </div>
      </div>
    </div>
  </div>
</main>
<!-- Modal -->
<div class="modal fade" id="modalEmploye" tabindex="-1">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Ajouter 
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Ajouter un employé</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
        <div class="mb-3"><label class="arovia-label">Nom complet *</label><input type="text" class="arovia-input"/></div>
        <div class="mb-3"><label class="arovia-label">Poste *</label><input type="text" class="arovia-input"/></div>
        <div class="mb-3"><label class="arovia-label">Email</label><input type="email" class="arovia-input"/></div>
        <div class="mb-3"><label class="arovia-label">Téléphone</label><input type="tel" class="arovia-input"/></div>
      </div>
      <div class="modal-footer"><button class="btn-outline-gold" data-bs-dismiss="modal">Annuler</button><button class="btn-gold">Enregistrer</button></div>
    </div>
  </div>
</div>
<script src="assets/bootstrap/bootstrap.bundle.min.js"></script>
<script>function toggleSubmenu(el){el.classList.toggle('open');el.nextElementSibling.classList.toggle('open');}</script>
</body>
</html>
