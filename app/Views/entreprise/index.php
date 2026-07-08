<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>Entreprises — Miel Arovia</title>
  <link rel="stylesheet" href="<?= base_url('assets/bootstrap/bootstrap.min.css') ?>"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <link rel="stylesheet" href="<?= base_url('assets/css/global.css') ?>"/>
</head>
<body>
<?php include FCPATH . 'utils/header.php'; ?>
<?php include FCPATH . 'utils/side_bar.php'; ?>

<main class="main-wrapper">
  <div class="page-header">
    <h1 class="page-title">Entreprises partenaires</h1>
    <a href="<?= base_url('entreprise/ajout') ?>" class="btn-gold"><i class="fa fa-plus"></i> Ajouter une entreprise</a>
  </div>

  <?php if (session()->getFlashdata('succes')) : ?>
    <div class="alert alert-success py-2 px-3 mb-4" style="font-size:0.9rem; border-radius:8px; border:none; background: rgba(93,122,46,0.12); color: var(--accent-green);">
      <i class="fa fa-check-circle me-1"></i> <?= esc(session()->getFlashdata('succes')) ?>
    </div>
  <?php endif; ?>

  <?php if (session()->getFlashdata('erreur')) : ?>
    <div class="alert alert-danger py-2 px-3 mb-4" style="font-size:0.9rem; border-radius:8px; border:none; background: rgba(192,57,43,0.12); color: var(--accent-red);">
      <i class="fa fa-exclamation-circle me-1"></i> <?= esc(session()->getFlashdata('erreur')) ?>
    </div>
  <?php endif; ?>

  <div class="content-card">
    <div class="mb-3 d-flex justify-content-end">
      <div style="position: relative; width: 250px;">
        <i class="fa fa-search" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
        <input type="text" id="tableSearch" class="arovia-input" placeholder="Rechercher..." style="padding-left: 36px; height: 38px; font-size: 0.9rem;">
      </div>
    </div>
    <table class="arovia-table">
      <thead>
        <tr>
          <th>Nom</th>
          <th>Téléphone</th>
          <th>Email</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($entreprises)) : ?>
          <tr>
            <td colspan="4" class="text-center text-muted" style="padding:2rem">Aucune entreprise enregistrée.</td>
          </tr>
        <?php else : ?>
          <?php foreach ($entreprises as $entreprise) : ?>
            <tr>
              <td class="fw-600">
                <span class="table-avatar"><?= esc(strtoupper(substr($entreprise['nom'] ?? 'E', 0, 1))) ?></span>
                <?= esc($entreprise['nom']) ?>
              </td>
              <td><i class="fa fa-phone table-info-icon"></i> <?= esc($entreprise['telephone'] ?? '-') ?></td>
              <td><i class="fa fa-envelope table-info-icon"></i> <?= esc($entreprise['email'] ?? '-') ?></td>
              <td>
                <div class="d-flex gap-2">
                  <a href="<?= base_url('entreprise/modifier/' . $entreprise['id']) ?>" class="btn-icon-edit" title="Modifier"><i class="fa fa-pen"></i></a>
                  <form action="<?= base_url('entreprise/supprimer/' . $entreprise['id']) ?>" method="post" onsubmit="return confirm('La suppression d\'une entreprise est définitive. Continuer ?')">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn-icon-delete" title="Supprimer"><i class="fa fa-trash"></i></button>
                  </form>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</main>

<script src="<?= base_url('assets/bootstrap/bootstrap.bundle.min.js') ?>"></script>
<script>
function toggleSubmenu(el){el.classList.toggle('open');el.nextElementSibling.classList.toggle('open');}

document.getElementById('tableSearch').addEventListener('keyup', function() {
  const query = this.value.toLowerCase();
  const rows = document.querySelectorAll('.arovia-table tbody tr');
  rows.forEach(row => {
    const text = row.textContent.toLowerCase();
    row.style.display = text.includes(query) ? '' : 'none';
  });
});
</script>
</body>
</html>
