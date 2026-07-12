<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/><meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>Liste des livraisons — Miel Arovia</title>
  <link rel="stylesheet" href="<?= base_url('assets/bootstrap/bootstrap.min.css') ?>"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <link rel="stylesheet" href="<?= base_url('assets/css/global.css') ?>"/>
  <link rel="stylesheet" href="<?= base_url('css/planning.css') ?>">
</head>
<body>
<?php include FCPATH . 'utils/header.php'; ?>
<?php include FCPATH . 'utils/side_bar.php'; ?>
<main class="main-wrapper">
  <div class="page-header">
    <div class="page-title-wrap">
      <h1 class="page-title">Liste des livraisons</h1>
      <img src="/assets/images/Pattern simple - 1.png" alt="Pattern" class="header-pattern-img" />
    </div>
    <div>
      <a href="<?= base_url('planning/calendrier') ?>" class="btn-outline-gold me-2"><i class="fa fa-calendar-days"></i> Voir calendrier</a>
      <a href="<?= base_url('planning/ajouter') ?>" class="btn-gold"><i class="fa fa-plus"></i> Nouvelle livraison</a>
    </div>
  </div>

  <div class="content-card">
    <table class="arovia-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Livreur</th>
          <th>Adresse / Destination</th>
          <th>Date prévue</th>
          <th>Statut</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($livraisons)): ?>
          <?php foreach($livraisons as $l): ?>
            <tr>
              <td class="fw-600">#<?= esc($l['id']) ?></td>
              <td><?= esc($l['nom_livreur'] ?: '—') ?></td>
              <td><?= esc($l['adresse_livraison'] ?: '—') ?></td>
              <td><?= esc($l['date_prevue']) ?></td>
              <td>
                <?php 
                  $badge = 'badge-blue';
                  if ($l['statut'] === 'LIVREE' || $l['statut'] === 'EFFECTUEE') $badge = 'badge-green';
                  elseif ($l['statut'] === 'EN_ATTENTE') $badge = 'badge-gold';
                  elseif ($l['statut'] === 'ANNULEE') $badge = 'badge-red';
                ?>
                <span class="badge-arovia <?= $badge ?>"><?= esc($l['statut']) ?></span>
              </td>
              <td>
                <a class="btn-icon-edit text-decoration-none" href="<?= base_url('planning/details/'.$l['id']) ?>" title="Détails" style="color: var(--primary-gold);"><i class="fa fa-eye"></i></a>
                <a class="btn-icon-edit text-decoration-none ms-2" href="<?= base_url('planning/modifier/'.$l['id']) ?>" title="Modifier" style="color: var(--primary-gold);"><i class="fa fa-pen"></i></a>
                <a class="btn-icon-edit text-decoration-none ms-2" href="<?= base_url('planning/delete/'.$l['id']) ?>" title="Supprimer" onclick="return confirm('Supprimer cette livraison ?')" style="color: var(--accent-red);"><i class="fa fa-trash"></i></a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="6" class="text-center text-muted py-4">Aucune livraison planifiée.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</main>
<script src="<?= base_url('assets/bootstrap/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>