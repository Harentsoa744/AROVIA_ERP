<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>Modifier une entreprise — Miel Arovia</title>
  <link rel="stylesheet" href="<?= base_url('assets/bootstrap/bootstrap.min.css') ?>"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <link rel="stylesheet" href="<?= base_url('assets/css/global.css') ?>"/>
</head>
<body>
<?php include FCPATH . 'utils/header.php'; ?>
<?php include FCPATH . 'utils/side_bar.php'; ?>

<main class="main-wrapper">
  <div class="breadcrumb-bar">
    <a href="<?= base_url('entreprise') ?>">Entreprises</a>
    <span>›</span> Modifier une entreprise
  </div>

  <div class="page-header">
    <div class="page-title-wrap">
      <h1 class="page-title">Modifier l'entreprise</h1>
      <img src="/assets/images/Pattern simple - 1.png" alt="Pattern" class="header-pattern-img" />
    </div>
    <a href="<?= base_url('entreprise') ?>" class="btn-outline-gold"><i class="fa fa-arrow-left"></i> Retour</a>
  </div>

  <div class="content-card" style="max-width: 600px;">
    <form action="<?= base_url('entreprise/update/' . $entreprise['id']) ?>" method="post">
      <?= csrf_field() ?>

      <div class="row g-3">
        <div class="col-md-12">
          <label class="arovia-label" for="nom">Nom *</label>
          <input type="text" id="nom" name="nom" class="arovia-input" value="<?= esc($entreprise['nom'] ?? '') ?>" required>
          <?php if (isset($validation) && $validation->getError('nom')) : ?>
            <span class="text-danger" style="font-size: 0.8rem;"><?= esc($validation->getError('nom')) ?></span>
          <?php endif; ?>
        </div>

        <div class="col-md-12">
          <label class="arovia-label" for="telephone">Téléphone</label>
          <input type="text" id="telephone" name="telephone" class="arovia-input" value="<?= esc($entreprise['telephone'] ?? '') ?>">
        </div>

        <div class="col-md-12">
          <label class="arovia-label" for="email">Email</label>
          <input type="email" id="email" name="email" class="arovia-input" value="<?= esc($entreprise['email'] ?? '') ?>">
          <?php if (isset($validation) && $validation->getError('email')) : ?>
            <span class="text-danger" style="font-size: 0.8rem;"><?= esc($validation->getError('email')) ?></span>
          <?php endif; ?>
        </div>
      </div>

      <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn-gold">Mettre à jour</button>
        <a href="<?= base_url('entreprise') ?>" class="btn-outline-gold">Annuler</a>
      </div>
    </form>
  </div>
</main>

<script src="<?= base_url('assets/bootstrap/bootstrap.bundle.min.js') ?>"></script>
<script>function toggleSubmenu(el){el.classList.toggle('open');el.nextElementSibling.classList.toggle('open');}</script>
</body>
</html>
