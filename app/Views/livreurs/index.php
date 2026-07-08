<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>Livreurs — Miel Arovia</title>
  <link rel="stylesheet" href="<?= base_url('assets/bootstrap/bootstrap.min.css') ?>"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <link rel="stylesheet" href="<?= base_url('assets/css/global.css') ?>"/>
</head>
<body>
<?php include FCPATH . 'utils/header.php'; ?>
<?php include FCPATH . 'utils/side_bar.php'; ?>
<main class="main-wrapper">
  <div class="page-header">
    <h1 class="page-title">Gestion de l'équipe des livreurs</h1>
    <button class="btn-gold" data-bs-toggle="modal" data-bs-target="#modalLivreur"><i class="fa fa-user-plus"></i> Ajouter un livreur</button>
  </div>

  <div class="content-card">
    <table class="arovia-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nom</th>
          <th>Véhicule / Moyen</th>
          <th>Téléphone</th>
          <th>Statut</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($livreurs)): ?>
          <?php foreach ($livreurs as $liv): ?>
            <tr>
              <td class="fw-600">#<?= (int) ($liv['id'] ?? 0) ?></td>
              <td>
                <span class="table-avatar"><?= esc(strtoupper(substr($liv['nom'] ?? 'L', 0, 1))) ?></span>
                <?= esc($liv['nom'] ?? '—') ?>
              </td>
              <td><i class="fa fa-motorcycle table-info-icon"></i> <?= esc($liv['vehicule'] ?? '—') ?></td>
              <td><i class="fa fa-phone table-info-icon"></i> <?= esc($liv['telephone'] ?? '—') ?></td>
              <td>
                <?php if (!empty($liv['disponible'])): ?>
                  <span class="badge-arovia badge-green"><i class="fa fa-check me-1"></i>Disponible</span>
                <?php else: ?>
                  <span class="badge-arovia badge-red"><i class="fa fa-spinner me-1"></i>En course / Absent</span>
                <?php endif; ?>
              </td>
              <td>
                <a class="btn-icon-edit" href="<?= base_url('livreurs/edit/' . $liv['id']) ?>" title="Modifier"><i class="fa fa-pen"></i></a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="6" class="text-center text-muted" style="padding:2rem">Aucun livreur enregistré pour le moment.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</main>

<!-- Modal Ajout Livreur -->
<div class="modal fade" id="modalLivreur" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="post" action="<?= base_url('livreurs/store') ?>">
        <?= csrf_field() ?>
        <div class="modal-header">
          <h5 class="modal-title">Ajouter un livreur</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="arovia-label" for="nom">Nom complet *</label>
            <input id="nom" name="nom" type="text" class="arovia-input" required/>
          </div>
          <div class="mb-3">
            <label class="arovia-label" for="telephone">Téléphone *</label>
            <input id="telephone" name="telephone" type="text" class="arovia-input" required/>
          </div>
          <div class="mb-3">
            <label class="arovia-label" for="vehicule">Véhicule / Moyen *</label>
            <input id="vehicule" name="vehicule" type="text" class="arovia-input" placeholder="ex: Moto Scooter, Camionnette" required/>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn-outline-gold" data-bs-dismiss="modal">Annuler</button>
          <button type="submit" class="btn-gold">Enregistrer</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="<?= base_url('assets/bootstrap/bootstrap.bundle.min.js') ?>"></script>
<script>function toggleSubmenu(el){el.classList.toggle('open');el.nextElementSibling.classList.toggle('open');}</script>
</body>
</html>