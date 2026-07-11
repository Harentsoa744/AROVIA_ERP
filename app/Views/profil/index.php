<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>Mon Profil — Miel Arovia</title>
  <link rel="stylesheet" href="<?= base_url('assets/bootstrap/bootstrap.min.css') ?>"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <link rel="stylesheet" href="<?= base_url('assets/css/global.css') ?>"/>
</head>
<body>
<?php include FCPATH . 'utils/header.php'; ?>
<?php include FCPATH . 'utils/side_bar.php'; ?>

<main class="main-wrapper">
  <div class="page-header">
    <h1 class="page-title">Mon Profil</h1>
  </div>

  <?php if (!empty($success)): ?>
    <div class="alert alert-success py-2 px-3 mb-4" style="font-size:0.9rem; border-radius:8px; border:none; background: rgba(93,122,46,0.12); color: var(--accent-green);">
      <i class="fa fa-check-circle me-1"></i> <?= esc($success) ?>
    </div>
  <?php endif; ?>

  <?php if (!empty($error)): ?>
    <div class="alert alert-danger py-2 px-3 mb-4" style="font-size:0.9rem; border-radius:8px; border:none; background: rgba(192,57,43,0.12); color: var(--accent-red);">
      <i class="fa fa-exclamation-circle me-1"></i> <?= esc($error) ?>
    </div>
  <?php endif; ?>

  <div class="row g-4">
    <div class="col-12 col-lg-4">
      <div class="content-card d-flex flex-column align-items-center text-center">
        <?php
          $initiales = strtoupper(substr((string) ($user['prenom'] ?? ''), 0, 1) . substr((string) ($user['nom'] ?? ''), 0, 1));
        ?>
        <?php if (!empty($user['photo_profil'])): ?>
          <img src="<?= base_url('uploads/utilisateurs/' . $user['photo_profil']) ?>" 
               alt="Photo de profil" 
               class="rounded-circle mb-3" 
               style="width:90px; height:90px; object-fit:cover; border:3px solid var(--primary-gold);">
        <?php else: ?>
          <div class="table-avatar mb-3" style="width:90px; height:90px; font-size:2.2rem; margin-right:0; display:flex; align-items:center; justify-content:center;">
            <?= esc($initiales ?: '?') ?>
          </div>
        <?php endif; ?>
        <h4 class="fw-700 text-dark-primary mb-1" style="font-size:1.25rem;">
          <?= esc(($user['prenom'] ?? '') . ' ' . ($user['nom'] ?? '')) ?>
        </h4>
        <span class="badge-arovia badge-gold mt-1"><?= esc($user['role_nom'] ?? 'Utilisateur') ?></span>

        <hr class="w-100 my-4" style="border-color: var(--border-color);">

        <div class="w-100 text-start">
          <div class="mb-2 text-muted" style="font-size:0.85rem;">
            <i class="fa fa-envelope me-2 text-gold"></i><?= esc($user['email'] ?? '') ?>
          </div>
          <div class="text-muted" style="font-size:0.85rem;">
            <i class="fa fa-calendar me-2 text-gold"></i>Membre depuis le : <?= !empty($user['date_creation']) ? esc(date('d/m/Y', strtotime((string) $user['date_creation']))) : '—' ?>
          </div>
        </div>
      </div>
    </div>

    <div class="col-12 col-lg-8">
      <div class="content-card">
        <h3 class="content-card-title" style="font-size:1.1rem;"><i class="fa fa-user-gear me-2 text-gold"></i>Modifier mes informations</h3>

        <form method="POST" action="<?= base_url('profil/update') ?>" enctype="multipart/form-data">
          <?= csrf_field() ?>
          <div class="row g-3">
            <div class="col-12 col-md-6">
              <label class="arovia-label" for="nom">Nom</label>
              <input id="nom" name="nom" type="text" class="arovia-input" value="<?= esc($user['nom'] ?? '') ?>" required/>
            </div>
            <div class="col-12 col-md-6">
              <label class="arovia-label" for="prenom">Prénom</label>
              <input id="prenom" name="prenom" type="text" class="arovia-input" value="<?= esc($user['prenom'] ?? '') ?>"/>
            </div>
            <div class="col-12">
              <label class="arovia-label" for="email">Adresse Email</label>
              <input id="email" name="email" type="email" class="arovia-input" value="<?= esc($user['email'] ?? '') ?>" required/>
            </div>

            <div class="col-12">
              <label class="arovia-label" for="photo_profil">Photo de profil</label>
              <input id="photo_profil" name="photo_profil" type="file" class="arovia-input" accept="image/*"/>
              <small class="text-muted">Formats acceptés : JPG, PNG, GIF (max 2MB)</small>
            </div>

            <div class="col-12 mt-4">
              <h3 class="content-card-title" style="font-size:1.1rem; margin-bottom: 0.5rem;"><i class="fa fa-lock me-2 text-gold"></i>Sécurité</h3>
              <p class="text-muted mb-3" style="font-size:0.8rem;">Laissez ce champ vide si vous ne souhaitez pas modifier votre mot de passe actuel.</p>
              <label class="arovia-label" for="password">Nouveau mot de passe</label>
              <input id="password" name="password" type="password" class="arovia-input" placeholder="••••••••"/>
            </div>

            <div class="col-12 mt-4 text-end">
              <button type="submit" class="btn-gold"><i class="fa fa-save"></i> Enregistrer les modifications</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</main>

<script src="<?= base_url('assets/bootstrap/bootstrap.bundle.min.js') ?>"></script>
<script>function toggleSubmenu(el){el.classList.toggle('open');el.nextElementSibling.classList.toggle('open');}</script>
</body>
</html>