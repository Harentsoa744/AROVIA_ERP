<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Ajouter un employé — Miel Arovia</title>
  <link rel="stylesheet" href="assets/bootstrap/bootstrap.min.css"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <link rel="stylesheet" href="assets/css/global.css"/>
</head>
<body>
<?php include 'utils/header.php'; ?>
<?php include 'utils/side_bar.php'; ?>
<main class="main-wrapper">
  <div class="breadcrumb-bar"><a href="/employes">Gestion employé</a> <span>›</span> Ajouter un employé</div>
  <div class="page-header">
    <div class="page-title-wrap">
      <h1 class="page-title">Ajouter un employé</h1>
      <img src="/assets/images/Pattern simple - 1.png" alt="Pattern" class="header-pattern-img" />
    </div>
    <a href="/employes" class="btn-outline-gold"><i class="fa fa-arrow-left"></i> Retour</a>
  </div>
  <div class="content-card" style="max-width: 760px;">
    <form method="post" action="<?= base_url('employes/store') ?>">
      <?= csrf_field() ?>
      <div class="row g-3">
        <div class="col-md-6"><label class="arovia-label" for="matricule">Matricule</label><input id="matricule" type="text" name="matricule" class="arovia-input"/></div>
        <div class="col-md-6"><label class="arovia-label" for="nom">Nom</label><input id="nom" type="text" name="nom" class="arovia-input"/></div>
        <div class="col-md-6"><label class="arovia-label" for="prenom">Prénom</label><input id="prenom" type="text" name="prenom" class="arovia-input"/></div>
        <div class="col-md-6"><label class="arovia-label" for="telephone">Téléphone</label><input id="telephone" type="text" name="telephone" class="arovia-input"/></div>
        <div class="col-md-6"><label class="arovia-label" for="email">Email</label><input id="email" type="email" name="email" class="arovia-input"/></div>
        <div class="col-md-6"><label class="arovia-label" for="poste">Poste</label><input id="poste" type="text" name="poste" class="arovia-input"/></div>
        <div class="col-md-6"><label class="arovia-label" for="salaire_base">Salaire</label><input id="salaire_base" type="number" name="salaire_base" class="arovia-input"/></div>
        <div class="col-md-6"><label class="arovia-label" for="date_embauche">Date d'embauche</label><input id="date_embauche" type="date" name="date_embauche" class="arovia-input"/></div>
      </div>
      <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn-gold">Enregistrer</button>
        <a href="/employes" class="btn-outline-gold">Annuler</a>
      </div>
    </form>
  </div>
</main>
<script src="assets/bootstrap/bootstrap.bundle.min.js"></script>
</body>
</html>