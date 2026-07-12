<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>Détail du contrat — Miel Arovia</title>
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
    <span>›</span> Détails
  </div>

  <div class="page-header">
    <div class="page-title-wrap">
      <h1 class="page-title">Contrat #<?= esc($contrat['id']) ?> — <?= esc($contrat['sujet']) ?></h1>
      <img src="/assets/images/Pattern simple - 1.png" alt="Pattern" class="header-pattern-img" />
    </div>
    <div class="d-flex gap-2">
      <a href="<?= base_url('contrat/modifier/' . $contrat['id']) ?>" class="btn-gold"><i class="fa fa-pen"></i> Modifier</a>
      <a href="<?= base_url('contrat/pdf/' . $contrat['id']) ?>" class="btn-outline-gold"><i class="fa fa-file-pdf"></i> Télécharger PDF</a>
      <a href="<?= base_url('contrat') ?>" class="btn-outline-gold">Retour</a>
    </div>
  </div>

  <div class="row g-4">
    <div class="col-md-8">
      <div class="content-card mb-4">
        <h3 class="content-card-title"><i class="fa fa-align-left text-gold me-2"></i>Description</h3>
        <p style="white-space: pre-line; color: var(--text-primary); line-height: 1.6;">
          <?= esc($contrat['description'] ?: 'Aucune description disponible.') ?>
        </p>
      </div>
    </div>
    
    <div class="col-md-4">
      <div class="content-card">
        <h3 class="content-card-title"><i class="fa fa-circle-info text-gold me-2"></i>Informations</h3>
        <div class="d-flex flex-column gap-3">
          <div>
            <label class="arovia-label">Entreprise</label>
            <div class="fw-600"><?= esc($contrat['entreprise_nom']) ?></div>
          </div>
          <div>
            <label class="arovia-label">Statut</label>
            <div>
              <?php
                $statut = strtolower(trim((string)$contrat['statut_nom']));
                $badgeClass = 'badge-gold';
                if ($statut === 'actif' || str_contains($statut, 'sign')) {
                  $badgeClass = 'badge-green';
                } elseif ($statut === 'expiré' || $statut === 'expire' || $statut === 'annulé' || $statut === 'annule') {
                  $badgeClass = 'badge-red';
                }
              ?>
              <span class="badge-arovia <?= $badgeClass ?>"><?= esc($contrat['statut_nom']) ?></span>
            </div>
          </div>
          <div>
            <label class="arovia-label">Date de création</label>
            <div class="fw-600"><i class="fa fa-calendar-plus text-muted me-2"></i><?= esc($contrat['date_creation']) ?></div>
          </div>
          <div>
            <label class="arovia-label">Date de signature</label>
            <div class="fw-600"><i class="fa fa-file-signature text-muted me-2"></i><?= esc($contrat['date_signature'] ?? 'Non signé') ?></div>
          </div>
          <div>
            <label class="arovia-label">Date d'expiration</label>
            <div class="fw-600"><i class="fa fa-calendar-xmark text-muted me-2"></i><?= esc($contrat['date_expiration'] ?? 'Pas d\'expiration') ?></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
<script src="<?= base_url('assets/bootstrap/bootstrap.bundle.min.js') ?>"></script>
<script>function toggleSubmenu(el){el.classList.toggle('open');el.nextElementSibling.classList.toggle('open');}</script>
</body>
</html>
