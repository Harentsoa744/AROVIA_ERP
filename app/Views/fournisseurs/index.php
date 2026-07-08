<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>Fournisseurs — Miel Arovia</title>
  <link rel="stylesheet" href="<?= base_url('assets/bootstrap/bootstrap.min.css') ?>"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <link rel="stylesheet" href="<?= base_url('assets/css/global.css') ?>"/>
  <link rel="stylesheet" href="<?= base_url('assets/css/fournisseurs.css') ?>"/>
</head>
<body>

<!-- TOPBAR -->
<?php include 'utils/header.php'; ?>
<?php include 'utils/side_bar.php'; ?>
<!-- MAIN -->
<main class="main-wrapper">
  <div class="breadcrumb-bar">
    <a href="gestion-stock.html">Gestion de stock</a>
    <span>›</span> Fournisseurs
  </div>

  <div class="page-header">
    <h1 class="page-title">Liste des fournisseurs</h1>
    <button class="btn-gold" data-bs-toggle="modal" data-bs-target="#modalAjout">
      <i class="fa fa-plus"></i> Ajouter un fournisseur
    </button>
  </div>

  <!-- Table Card -->
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
          <th>Nom <i class="fa fa-sort ms-1" style="font-size:.7rem"></i></th>
          <th>Contact</th>
          <th>Localisation</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($fournisseurs)): ?>
          <?php foreach ($fournisseurs as $fournisseur): ?>
            <tr>
              <td>
                <div style="display:flex;align-items:center;gap:.5rem">
                  <span class="table-avatar"><?= esc(strtoupper(substr($fournisseur['nom'] ?? 'F', 0, 1))) ?></span>
                  <?= esc($fournisseur['nom'] ?? '—') ?>
                </div>
              </td>
              <td><i class="fa fa-envelope table-info-icon"></i> <?= esc($fournisseur['contact'] ?? '—') ?></td>
              <td><i class="fa fa-location-dot table-info-icon"></i> <?= esc($fournisseur['localisation'] ?? '—') ?></td>
              <td>
                <a class="btn-icon-edit" href="/fournisseurs/<?= (int) ($fournisseur['id'] ?? 0) ?>/edit" title="Modifier"><i class="fa fa-pen"></i></a>
                <a class="btn-icon-delete ms-1" href="/fournisseurs/<?= (int) ($fournisseur['id'] ?? 0) ?>/delete" title="Supprimer" onclick="return confirm('Supprimer ce fournisseur ?')"><i class="fa fa-trash"></i></a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="4" class="text-center text-muted" style="padding:2rem">Aucun fournisseur enregistré.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>

    <!-- Pagination -->
    <div class="table-footer">
      <span class="table-info-text">Affichage de 1 à 2 sur 2 résultats</span>
      <div class="d-flex align-items-center gap-2">
        <select class="arovia-input" style="width:120px;padding:.3rem .7rem">
          <option>10 par page</option>
          <option>25 par page</option>
          <option>50 par page</option>
        </select>
        <div class="arovia-pagination">
          <a href="#" class="page-btn">«</a>
          <a href="#" class="page-btn active">1</a>
          <a href="#" class="page-btn">»</a>
        </div>
      </div>
    </div>
  </div>
</main>

<!-- Modal Ajout Fournisseur -->
<div class="modal fade" id="modalAjout" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Ajouter un fournisseur</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form method="post" action="/fournisseurs">
        <div class="modal-body">
          <div class="mb-3">
            <label class="arovia-label" for="nom">Nom *</label>
            <input id="nom" name="nom" type="text" class="arovia-input" placeholder="Nom du fournisseur" required/>
          </div>
          <div class="mb-3">
            <label class="arovia-label" for="contact">Contact</label>
            <input id="contact" name="contact" type="text" class="arovia-input" placeholder="email@exemple.com"/>
          </div>
          <div class="mb-3">
            <label class="arovia-label" for="localisation">Localisation</label>
            <input id="localisation" name="localisation" type="text" class="arovia-input" placeholder="Ville / Quartier"/>
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
