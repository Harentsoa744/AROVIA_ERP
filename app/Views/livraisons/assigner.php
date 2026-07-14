<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
  <title>Assigner livreur — Miel Arovia</title>
  <link rel="stylesheet" href="<?= base_url('assets/bootstrap/bootstrap.min.css') ?>"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <link rel="stylesheet" href="<?= base_url('assets/css/global.css') ?>"/>
</head>
<body>
<?php include 'utils/header.php'; ?>
<?php include 'utils/side_bar.php'; ?>
<main class="main-wrapper">
  <div class="breadcrumb-bar"><a href="/livraisons">Livraisons</a> <span>›</span> Assigner livreur</div>
  <div class="page-header">
    <div class="page-title-wrap">
      <h1 class="page-title">Assigner un livreur</h1>
      <img src="/assets/images/Pattern simple - 1.png" alt="Pattern" class="header-pattern-img" />
    </div>
    <a href="/livraisons" class="btn-outline-gold"><i class="fa fa-arrow-left"></i> Retour</a>
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

    <div class="mb-4 p-3 bg-light rounded border">
      <h6 class="mb-2">Détails de la sortie</h6>
      <p><strong>Supermarché:</strong> <?= esc($sortie['supermarche_nom'] ?? '—') ?></p>
      <p><strong>Localisation:</strong> <?= esc($sortie['supermarche_adresse'] ?? '—') ?></p>
      <p><strong>Date:</strong> <?= esc(date('d/m/Y H:i', strtotime($sortie['date_sortie'] ?? 'now'))) ?></p>
      <p><strong>Valeur totale:</strong> <?= number_format($sortie['valeur_totale'] ?? 0, 0, ',', ' ') ?> Ar</p>
    </div>

    <form action="/livraisons/store_assignation" method="post">
      <?= csrf_field() ?>
      <input type="hidden" name="sortie_id" value="<?= (int) ($sortie['id'] ?? 0) ?>">

      <div class="mb-3">
        <label class="arovia-label" for="livreur_id">Livreur *</label>
        <select name="livreur_id" id="livreur_id" class="arovia-input" required>
          <option value="">Sélectionner...</option>
          <?php foreach ($livreurs_dispo as $livreur): ?>
            <option value="<?= (int) ($livreur['id'] ?? 0) ?>"><?= esc($livreur['nom'] ?? 'Livreur') ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="mb-3">
        <label class="arovia-label" for="date_prevue">Date prévue *</label>
        <input type="datetime-local" name="date_prevue" id="date_prevue" class="arovia-input" required>
      </div>

      <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn-gold">Assigner</button>
        <a href="/livraisons" class="btn-outline-gold">Annuler</a>
      </div>
    </form>
  </div>
</main>

<script src="<?= base_url('assets/bootstrap/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>
