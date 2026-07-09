<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/><meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>Détails livraison — Miel Arovia</title>
  <link rel="stylesheet" href="<?= base_url('assets/bootstrap/bootstrap.min.css') ?>"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <link rel="stylesheet" href="<?= base_url('assets/css/global.css') ?>"/>
  <link rel="stylesheet" href="<?= base_url('css/planning.css') ?>">
</head>
<body>
<?php include FCPATH . 'utils/header.php'; ?>
<?php include FCPATH . 'utils/side_bar.php'; ?>
<main class="main-wrapper">
  <div class="breadcrumb-bar">
    <a href="<?= base_url('planning/liste') ?>">Planning</a>
    <span>›</span> Détails livraison
  </div>

  <div class="page-header">
    <h1 class="page-title">Détails livraison</h1>
    <div>
      <a href="<?= base_url('planning/modifier/'.$livraison['id']) ?>" class="btn-gold me-2"><i class="fa fa-pen"></i> Modifier</a>
      <a href="<?= base_url('planning/liste') ?>" class="btn-outline-gold"><i class="fa fa-arrow-left"></i> Retour</a>
    </div>
  </div>

  <div class="row g-4">
    <div class="col-lg-8">
      <div class="content-card">
        <h3 class="content-card-title mb-4"><i class="fa fa-circle-info text-gold me-2"></i>Fiche Livraison #<?= esc($livraison['id']) ?></h3>

        <div class="row g-3">
          <div class="col-6">
            <span class="arovia-label d-block text-muted text-uppercase" style="font-size:0.75rem;">Livreur assigné</span>
            <span class="fw-600 text-dark-primary"><?= esc($livraison['nom_livreur'] ?: 'Non assigné') ?></span>
          </div>

          <div class="col-6">
            <span class="arovia-label d-block text-muted text-uppercase" style="font-size:0.75rem;">Téléphone livreur</span>
            <span><?= esc($livraison['telephone'] ?: '—') ?></span>
          </div>

          <div class="col-6">
            <span class="arovia-label d-block text-muted text-uppercase" style="font-size:0.75rem;">Véhicule</span>
            <span><?= esc($livraison['vehicule'] ?: '—') ?></span>
          </div>

          <div class="col-6">
            <span class="arovia-label d-block text-muted text-uppercase" style="font-size:0.75rem;">Montant Vente</span>
            <span class="fw-700 text-orange"><?= number_format($livraison['montant_total'] ?? 0, 0, ',', ' ') ?> Ar</span>
          </div>

          <div class="col-6">
            <span class="arovia-label d-block text-muted text-uppercase" style="font-size:0.75rem;">Client / Destinataire</span>
            <span><?= esc($livraison['client_nom'] ?: '—') ?></span>
          </div>

          <div class="col-12">
            <hr class="my-2" style="border-color: var(--border-color);"/>
          </div>

          <div class="col-12">
            <span class="arovia-label d-block text-muted text-uppercase" style="font-size:0.75rem;">Adresse de destination</span>
            <p class="mb-0 bg-light p-3 rounded" style="border-left: 4px solid var(--primary-gold);"><?= nl2br(esc($livraison['adresse_livraison'])) ?></p>
          </div>

          <div class="col-6">
            <span class="arovia-label d-block text-muted text-uppercase" style="font-size:0.75rem;">Date prévue</span>
            <span><?= esc($livraison['date_prevue']) ?></span>
          </div>

          <div class="col-6">
            <span class="arovia-label d-block text-muted text-uppercase" style="font-size:0.75rem;">Date effective</span>
            <span><?= esc($livraison['date_effective'] ?: '—') ?></span>
          </div>

          <div class="col-12">
            <span class="arovia-label d-block text-muted text-uppercase" style="font-size:0.75rem;">Statut</span>
            <?php 
              $badge = 'badge-blue';
              if ($livraison['statut'] === 'LIVREE' || $livraison['statut'] === 'EFFECTUEE') $badge = 'badge-green';
              elseif ($livraison['statut'] === 'EN_ATTENTE') $badge = 'badge-gold';
              elseif ($livraison['statut'] === 'ANNULEE') $badge = 'badge-red';
            ?>
            <span class="badge-arovia <?= $badge ?>"><?= esc($livraison['statut']) ?></span>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
<script src="<?= base_url('assets/bootstrap/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>