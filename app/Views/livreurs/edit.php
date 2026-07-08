<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>Modifier le livreur — Miel Arovia</title>
  <link rel="stylesheet" href="<?= base_url('assets/bootstrap/bootstrap.min.css') ?>"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <link rel="stylesheet" href="<?= base_url('assets/css/global.css') ?>"/>
</head>
<body>
<?php include FCPATH . 'utils/header.php'; ?>
<?php include FCPATH . 'utils/side_bar.php'; ?>
<main class="main-wrapper">
  <div class="breadcrumb-bar">
    <a href="<?= base_url('livreurs') ?>">Livreurs</a>
    <span>›</span> Modifier
  </div>

  <div class="page-header">
    <h1 class="page-title">Modifier le livreur</h1>
    <a href="<?= base_url('livreurs') ?>" class="btn-outline-gold"><i class="fa fa-arrow-left"></i> Retour</a>
  </div>

  <div class="content-card" style="max-width: 600px;">
    <form method="post" action="<?= base_url('livreurs/update/' . $livreur['id']) ?>">
      <?= csrf_field() ?>
      
      <div class="row g-3">
        <div class="col-md-12">
          <label class="arovia-label" for="nom">Nom complet *</label>
          <input id="nom" name="nom" type="text" class="arovia-input" value="<?= esc($livreur['nom']) ?>" required/>
        </div>
        <div class="col-md-12">
          <label class="arovia-label" for="telephone">Téléphone *</label>
          <input id="telephone" name="telephone" type="text" class="arovia-input" value="<?= esc($livreur['telephone']) ?>" required/>
        </div>
        <div class="col-md-12">
          <label class="arovia-label" for="vehicule">Véhicule / Moyen *</label>
          <input id="vehicule" name="vehicule" type="text" class="arovia-input" value="<?= esc($livreur['vehicule']) ?>" required/>
        </div>
        <div class="col-md-12 mt-3">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="disponible" name="disponible" value="1" <?= !empty($livreur['disponible']) ? 'checked' : '' ?>>
            <label class="form-check-label fw-600 text-dark-primary" for="disponible">
              Livreur disponible et libre
            </label>
          </div>
        </div>
      </div>

      <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn-gold">Enregistrer</button>
        <a href="<?= base_url('livreurs') ?>" class="btn-outline-gold">Annuler</a>
      </div>
    </form>
  </div>
</main>
<script src="<?= base_url('assets/bootstrap/bootstrap.bundle.min.js') ?>"></script>
<script>function toggleSubmenu(el){el.classList.toggle('open');el.nextElementSibling.classList.toggle('open');}</script>
</body>
</html>
