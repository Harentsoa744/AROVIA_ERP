<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Configuration des seuils — Miel Arovia</title>
  <link rel="stylesheet" href="<?= base_url('assets/bootstrap/bootstrap.min.css') ?>"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <link rel="stylesheet" href="<?= base_url('assets/css/global.css') ?>"/>
</head>
<body>
<?php include FCPATH . 'utils/header.php'; ?>
<?php include FCPATH . 'utils/side_bar.php'; ?>
<main class="main-wrapper">
  <div class="breadcrumb-bar"><a href="/dashboard">Accueil</a> <span>›</span> Configuration</div>
  <div class="page-header">
    <h1 class="page-title">Configuration des Seuils d'Alerte</h1>
    <a href="/home" class="btn-outline-gold"><i class="fa fa-arrow-left"></i> Retour</a>
  </div>

  <div class="content-card" style="max-width: 650px;">
    <?php if (session()->getFlashdata('message')): ?>
      <div class="alert alert-success">
        <?= esc(session()->getFlashdata('message')) ?>
      </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('errors')): ?>
      <div class="alert alert-danger">
        <ul class="mb-0">
          <?php foreach (session()->getFlashdata('errors') as $error): ?>
            <li><?= esc($error) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <form action="/configuration" method="post">
      <?= csrf_field() ?>

      <h5 class="mb-3"><i class="fa fa-droplet text-green me-2"></i>Matière Première</h5>
      <div class="mb-4">
        <label class="arovia-label" for="seuil_mp">Seuil d'alerte miel brut (Litres) *</label>
        <input type="number" id="seuil_mp" name="seuil_mp" step="0.1" class="arovia-input" value="<?= esc($stockMP['seuil_alerte'] ?? 10) ?>" required/>
        <small class="text-muted">Alerte déclenchée si le stock de miel brut est inférieur à ce seuil.</small>
      </div>

      <h5 class="mb-3 mt-4"><i class="fa fa-jar text-gold me-2"></i>Produits Finis (Bocaux)</h5>
      <?php foreach ($stockPF as $bocal): ?>
        <div class="mb-3">
          <label class="arovia-label" for="seuil_bocal_<?= (int) $bocal['type_bocal_id'] ?>">
            Bocal <?= esc($bocal['nom']) ?> (unité) *
          </label>
          <input type="number" id="seuil_bocal_<?= (int) $bocal['type_bocal_id'] ?>" name="seuil_bocal_<?= (int) $bocal['type_bocal_id'] ?>" class="arovia-input" value="<?= (int) ($bocal['seuil_alerte'] ?? 20) ?>" required/>
        </div>
      <?php endforeach; ?>

      <div class="mt-4 pt-3 border-top d-flex gap-2">
        <button type="submit" class="btn-gold">Enregistrer les seuils</button>
        <a href="/home" class="btn-outline-gold">Annuler</a>
      </div>
    </form>
  </div>
</main>
<script src="<?= base_url('assets/bootstrap/bootstrap.bundle.min.js') ?>"></script>
<script>function toggleSubmenu(el){el.classList.toggle('open');el.nextElementSibling.classList.toggle('open');}</script>
</body>
</html>
