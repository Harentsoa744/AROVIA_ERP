<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/><meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>Gestion de contrat — Miel Arovia</title>
  <link rel="stylesheet" href="/assets/bootstrap/bootstrap.min.css"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <link rel="stylesheet" href="/assets/css/global.css"/>
  <link rel="stylesheet" href="/assets/css/employes.css"/>

</head>
<body>
<?php include 'utils/header.php'; ?>
<?php include 'utils/side_bar.php'; ?>

<main class="main-wrapper">
  <div class="page-header">
    <h1 class="page-title">Gestion des contrats</h1>
    <a href="<?= base_url('contrat/ajout') ?>" class="btn-gold"><i class="fa fa-plus"></i> Nouveau contrat</a>
  </div>
  
  <div class="row g-3 mb-4">
    <div class="col-6 col-md-4"><div class="kpi-card"><div class="kpi-icon-wrap blue"><i class="fa fa-file-signature"></i></div><div class="kpi-label">Contrats actifs</div><div class="kpi-value dark"><?= count(array_filter($contrats ?? [], fn($c) => ($c['statut_nom'] ?? '') === 'Actif')) ?></div></div></div>
    <div class="col-6 col-md-4"><div class="kpi-card"><div class="kpi-icon-wrap orange"><i class="fa fa-hourglass-half"></i></div><div class="kpi-label">En attente</div><div class="kpi-value orange"><?= count(array_filter($contrats ?? [], fn($c) => ($c['statut_nom'] ?? '') === 'En attente')) ?></div></div></div>
    <div class="col-6 col-md-4"><div class="kpi-card"><div class="kpi-icon-wrap red"><i class="fa fa-circle-xmark"></i></div><div class="kpi-label">Expirés</div><div class="kpi-value red"><?= count(array_filter($contrats ?? [], fn($c) => ($c['statut_nom'] ?? '') === 'Expiré')) ?></div></div></div>
  </div>

  <div class="content-card">
    <div class="mb-3 d-flex justify-content-end gap-2 flex-wrap align-items-center">
      <!-- Filtre statut -->
      <div style="position:relative;">
        <select id="filterStatut" class="arovia-input" style="height:38px;font-size:.9rem;min-width:160px;">
          <option value="">Tous les statuts</option>
          <?php foreach ($statuts ?? [] as $s): ?>
          <option value="<?= esc(strtolower($s['nom'] ?? '')) ?>"><?= esc($s['nom'] ?? '') ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <!-- Recherche -->
      <div style="position: relative; width: 250px;">
        <i class="fa fa-search" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
        <input type="text" id="tableSearch" class="arovia-input" placeholder="Rechercher..." style="padding-left: 36px; height: 38px; font-size: 0.9rem;">
      </div>
    </div>
    <table class="arovia-table">
      <thead>
        <tr><th>Partenaire</th><th>Type</th><th>Date début</th><th>Date fin</th><th>Statut</th><th>Actions</th></tr>
      </thead>
      <tbody>
        <?php if (!empty($contrats)): ?>
          <?php foreach ($contrats as $contrat): ?>
            <tr>
              <td><span class="table-avatar"><?= esc(strtoupper(substr($contrat['entreprise_nom'] ?? 'C', 0, 1))) ?></span> <?= esc($contrat['entreprise_nom'] ?? '—') ?></td>
              <td><?= esc($contrat['sujet'] ?? '—') ?></td>
              <td><?= esc($contrat['date_signature'] ? date('d/m/Y', strtotime($contrat['date_signature'])) : '—') ?></td>
              <td><?= esc($contrat['date_expiration'] ? date('d/m/Y', strtotime($contrat['date_expiration'])) : '—') ?></td>
              <td><span class="badge-arovia badge-green"><?= esc($contrat['statut_nom'] ?? '—') ?></span></td>
              <td>
                <a class="btn-icon-edit" href="/contrat/detail/<?= (int) ($contrat['id'] ?? 0) ?>" title="Voir"><i class="fa fa-eye"></i></a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="6" class="text-center text-muted" style="padding:2rem">Aucun contrat enregistré.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</main>
<!-- Modal -->
<div class="modal fade" id="modalContrat" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Nouveau contrat</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
        <div class="mb-3"><label class="arovia-label">Partenaire *</label><input type="text" class="arovia-input" placeholder="Nom du fournisseur/client"/></div>
        <div class="mb-3"><label class="arovia-label">Type de contrat</label><select class="arovia-input"><option>Approvisionnement</option><option>Distribution</option><option>Embauche</option></select></div>
        <div class="row">
          <div class="col-6 mb-3"><label class="arovia-label">Date de début *</label><input type="date" class="arovia-input"/></div>
          <div class="col-6 mb-3"><label class="arovia-label">Date de fin</label><input type="date" class="arovia-input"/></div>
        </div>
        <div class="mb-3"><label class="arovia-label">Document joint (PDF)</label><input type="file" class="arovia-input"/></div>
      </div>
      <div class="modal-footer"><button class="btn-outline-gold" data-bs-dismiss="modal">Annuler</button><button class="btn-gold">Enregistrer</button></div>
    </div>
  </div>
</div>
<script src="assets/bootstrap/bootstrap.bundle.min.js"></script>
<script>
function toggleSubmenu(el){el.classList.toggle('open');el.nextElementSibling.classList.toggle('open');}

function applyFilters() {
  const query  = document.getElementById('tableSearch').value.toLowerCase();
  const filtre = document.getElementById('filterStatut').value.toLowerCase();
  const rows   = document.querySelectorAll('.arovia-table tbody tr');

  rows.forEach(row => {
    const text       = row.textContent.toLowerCase();
    const statutCell = (row.cells[4]?.textContent || '').toLowerCase();
    const matchQ = !query  || text.includes(query);
    const matchF = !filtre || statutCell.includes(filtre);
    row.style.display = (matchQ && matchF) ? '' : 'none';
  });
}

document.getElementById('tableSearch').addEventListener('keyup', applyFilters);
document.getElementById('filterStatut').addEventListener('change', applyFilters);
</script>
</body>
</html>
