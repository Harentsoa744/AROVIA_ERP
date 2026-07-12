<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Modifier un supermarché — Miel Arovia</title>
  <link rel="stylesheet" href="<?= base_url('assets/bootstrap/bootstrap.min.css') ?>"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <link rel="stylesheet" href="<?= base_url('assets/css/global.css') ?>"/>
</head>
<body>
<?php include 'utils/header.php'; ?>
<?php include 'utils/side_bar.php'; ?>
<main class="main-wrapper">
  <div class="breadcrumb-bar"><a href="/supermarches">Supermarchés</a> <span>›</span> Modifier un supermarché</div>
  <div class="page-header">
    <div class="page-title-wrap">
      <h1 class="page-title">Modifier le supermarché</h1>
      <img src="<?= base_url('assets/images/Pattern combi.png') ?>" alt="Pattern" class="header-pattern-img" />
    </div>
    <a href="/supermarches" class="btn-outline-gold"><i class="fa fa-arrow-left"></i> Retour</a>
  </div>

  <div class="content-card" style="max-width: 760px;">
    <?php if (session()->getFlashdata('errors')): ?>
      <div class="alert alert-danger">
        <ul class="mb-0">
          <?php foreach (session()->getFlashdata('errors') as $error): ?>
            <li><?= esc($error) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <form action="/supermarches/<?= (int) ($supermarche['id'] ?? 0) ?>" method="post">
      <?= csrf_field() ?>
      <div class="mb-3">
        <label class="arovia-label" for="nom">Nom *</label>
        <input id="nom" name="nom" type="text" class="arovia-input" value="<?= esc(old('nom') ?? ($supermarche['nom'] ?? '')) ?>" required/>
      </div>
      <div class="mb-3">
        <label class="arovia-label" for="contact">Contact</label>
        <input id="contact" name="contact" type="text" class="arovia-input" value="<?= esc(old('contact') ?? ($supermarche['contact'] ?? '')) ?>"/>
      </div>
      <div class="mb-3">
        <label class="arovia-label" for="localisation">Localisation</label>
        <input id="localisation" name="localisation" type="text" class="arovia-input" value="<?= esc(old('localisation') ?? ($supermarche['localisation'] ?? '')) ?>"/>
      </div>
      <div class="d-flex gap-2">
        <button type="submit" class="btn-gold">Mettre à jour</button>
        <a href="/supermarches" class="btn-outline-gold">Annuler</a>
      </div>
    </form>
  </div>
</main>
<script src="<?= base_url('assets/bootstrap/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>
