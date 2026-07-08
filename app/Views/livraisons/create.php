<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>Nouvelle Livraison — Miel Arovia</title>
  <link rel="stylesheet" href="<?= base_url('assets/bootstrap/bootstrap.min.css') ?>"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <link rel="stylesheet" href="<?= base_url('assets/css/global.css') ?>"/>
</head>
<body>
<?php include FCPATH . 'utils/header.php'; ?>
<?php include FCPATH . 'utils/side_bar.php'; ?>
<main class="main-wrapper">
  <div class="breadcrumb-bar">
    <a href="<?= base_url('livraisons') ?>">Suivi des Distributions</a>
    <span>›</span> Planifier une livraison
  </div>

  <div class="page-header">
    <h1 class="page-title">Nouvelle livraison</h1>
    <a href="<?= base_url('livraisons') ?>" class="btn-outline-gold"><i class="fa fa-arrow-left"></i> Retour</a>
  </div>

  <div class="row g-4">
    <div class="col-lg-8">
      <div class="content-card">
        <h3 class="content-card-title"><i class="fa fa-pencil text-gold me-2"></i>Formulaire de livraison</h3>
        
        <form method="post" action="<?= base_url('livraisons/store') ?>">
          <?= csrf_field() ?>
          
          <div class="row g-3">
            <div class="col-md-6">
              <label class="arovia-label" for="vente_id">ID Numéro de Vente *</label>
              <input type="number" id="vente_id" name="vente_id" class="arovia-input" required placeholder="ex: 4502">
            </div>

            <div class="col-md-6">
              <label class="arovia-label" for="livreur_id">Assigner un Livreur *</label>
              <select id="livreur_id" name="livreur_id" class="arovia-input" required>
                <option value="">— Sélectionner —</option>
                <?php foreach($livreurs_dispo as $ld): ?>
                  <option value="<?= $ld['id'] ?>"><?= esc($ld['nom']) ?> (<?= esc($ld['vehicule']) ?>)</option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="col-md-12">
              <label class="arovia-label" for="date_prevue">Date et Heure prévue *</label>
              <input type="datetime-local" id="date_prevue" name="date_prevue" class="arovia-input" required>
            </div>

            <div class="col-md-12">
              <label class="arovia-label" for="adresse_livraison">Adresse de destination précise *</label>
              <textarea id="adresse_livraison" name="adresse_livraison" class="arovia-input" rows="3" required placeholder="Lot, Rue, Enceinte, Ville..."></textarea>
            </div>
          </div>

          <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn-gold">Planifier la livraison</button>
            <a href="<?= base_url('livraisons') ?>" class="btn-outline-gold">Annuler</a>
          </div>
        </form>
      </div>
    </div>

    <div class="col-lg-4">
      <div class="content-card">
        <h3 class="content-card-title"><i class="fa fa-thumbs-up text-gold me-2"></i>Livreurs disponibles</h3>
        <p class="text-muted mb-3" style="font-size: 0.8rem;">Prêts à partir actuellement :</p>
        
        <div class="d-flex flex-column gap-3">
          <?php if (!empty($livreurs_dispo)): ?>
            <?php foreach($livreurs_dispo as $ld): ?>
              <div class="p-3 rounded" style="background: rgba(200, 134, 10, 0.05); border-left: 4px solid var(--accent-green);">
                <div class="fw-600 text-dark-primary"><?= esc($ld['nom']) ?></div>
                <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 2px;">
                  <i class="fa fa-motorcycle me-1 text-gold"></i> <?= esc($ld['vehicule']) ?>
                </div>
                <div style="font-size: 0.8rem; color: var(--text-muted);">
                  <i class="fa fa-phone me-1 text-gold"></i> <?= esc($ld['telephone']) ?>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="text-muted text-center py-3">Aucun livreur disponible actuellement.</div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</main>
<script src="<?= base_url('assets/bootstrap/bootstrap.bundle.min.js') ?>"></script>
<script>function toggleSubmenu(el){el.classList.toggle('open');el.nextElementSibling.classList.toggle('open');}</script>
</body>
</html>