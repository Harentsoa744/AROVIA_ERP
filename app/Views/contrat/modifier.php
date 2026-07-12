<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>Modifier le contrat — Miel Arovia</title>
  <link rel="stylesheet" href="<?= base_url('assets/bootstrap/bootstrap.min.css') ?>"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <link rel="stylesheet" href="<?= base_url('assets/css/global.css') ?>"/>
</head>
<body>
<?php include FCPATH . 'utils/header.php'; ?>
<?php include FCPATH . 'utils/side_bar.php'; ?>
<main class="main-wrapper">
  <div class="breadcrumb-bar">
    <a href="<?= base_url('contrat') ?>">Gestion de contrat</a>
    <span>›</span> Modifier le contrat
  </div>

  <div class="page-header">
    <div class="page-title-wrap">
      <h1 class="page-title">Modifier le contrat</h1>
      <img src="/assets/images/Pattern simple - 1.png" alt="Pattern" class="header-pattern-img" />
    </div>
    <a href="<?= base_url('contrat/detail/' . $contrat['id']) ?>" class="btn-outline-gold"><i class="fa fa-arrow-left"></i> Retour</a>
  </div>

  <div class="content-card" style="max-width: 760px;">
    <form action="<?= base_url('contrat/update/' . $contrat['id']) ?>" method="post">
      <?= csrf_field() ?>
      <div class="row g-3">
        
        <div class="col-md-12">
          <label class="arovia-label" for="sujet">Sujet *</label>
          <input type="text" id="sujet" name="sujet" class="arovia-input" value="<?= esc($contrat['sujet'] ?? '') ?>" required>
          <?php if (isset($validation) && $validation->getError('sujet')) : ?>
            <span class="text-danger" style="font-size: 0.8rem;"><?= esc($validation->getError('sujet')) ?></span>
          <?php endif; ?>
        </div>

        <div class="col-md-6">
          <label class="arovia-label" for="entreprise_id">Entreprise *</label>
          <select id="entreprise_id" name="entreprise_id" class="arovia-input" required>
            <option value="">-- Sélectionner --</option>
            <?php foreach ($entreprises as $entreprise) : ?>
              <option value="<?= $entreprise['id'] ?>" <?= (($contrat['entreprise_id'] ?? '') == $entreprise['id']) ? 'selected' : '' ?>>
                <?= esc($entreprise['nom']) ?>
              </option>
            <?php endforeach; ?>
          </select>
          <?php if (isset($validation) && $validation->getError('entreprise_id')) : ?>
            <span class="text-danger" style="font-size: 0.8rem;"><?= esc($validation->getError('entreprise_id')) ?></span>
          <?php endif; ?>
        </div>

        <div class="col-md-6">
          <label class="arovia-label" for="statut_id">Statut *</label>
          <select id="statut_id" name="statut_id" class="arovia-input" required>
            <?php foreach ($statuts as $statut) : ?>
              <option value="<?= $statut['id'] ?>" <?= (($contrat['statut_id'] ?? '') == $statut['id']) ? 'selected' : '' ?>>
                <?= esc($statut['nom']) ?>
              </option>
            <?php endforeach; ?>
          </select>
          <?php if (isset($validation) && $validation->getError('statut_id')) : ?>
            <span class="text-danger" style="font-size: 0.8rem;"><?= esc($validation->getError('statut_id')) ?></span>
          <?php endif; ?>
        </div>

        <div class="col-md-6">
          <label class="arovia-label" for="date_expiration">Date d'expiration</label>
          <input type="date" id="date_expiration" name="date_expiration" class="arovia-input" value="<?= esc($contrat['date_expiration'] ?? '') ?>">
        </div>

        <div class="col-md-12">
          <label class="arovia-label" for="description">Description</label>
          <textarea id="description" name="description" class="arovia-input" rows="4"><?= esc($contrat['description'] ?? '') ?></textarea>
        </div>

      </div>

      <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn-gold">Enregistrer</button>
        <a href="<?= base_url('contrat/detail/' . $contrat['id']) ?>" class="btn-outline-gold">Annuler</a>
      </div>
    </form>
  </div>
</main>
<script src="<?= base_url('assets/bootstrap/bootstrap.bundle.min.js') ?>"></script>
<script>function toggleSubmenu(el){el.classList.toggle('open');el.nextElementSibling.classList.toggle('open');}</script>
</body>
</html>
