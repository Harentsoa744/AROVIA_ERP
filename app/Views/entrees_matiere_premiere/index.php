<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/><meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>Entrées matière première — Miel Arovia</title>
  <link rel="stylesheet" href="<?= base_url('assets/bootstrap/bootstrap.min.css') ?>"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <link rel="stylesheet" href="<?= base_url('assets/css/global.css') ?>"/>
  <link rel="stylesheet" href="<?= base_url('assets/css/entrees.css') ?>"/>
</head>
<body>
<?php include 'utils/header.php'; ?>
<?php include 'utils/side_bar.php'; ?>
<main class="main-wrapper">
  <div class="breadcrumb-bar"><a href="gestion-stock.html">Gestion de stock</a> <span>›</span> Entrées matière première</div>
  <div class="page-header">
    <div class="page-title-wrap">
      <h1 class="page-title">Entrées matière première</h1>
      <img src="<?= base_url('assets/images/Pattern combi.png') ?>" alt="Pattern" class="header-pattern-img" />
    </div>
    <button class="btn-gold" data-bs-toggle="modal" data-bs-target="#modalEntree"><i class="fa fa-plus"></i> Nouvelle entrée</button>
  </div>
  <!-- KPIs -->
  <div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
      <div class="kpi-card"><div class="kpi-icon-wrap green"><i class="fa fa-droplet"></i></div><div class="kpi-label">Total litres reçus</div><div class="kpi-value green"><?= number_format(array_sum(array_column($entrees ?? [], 'quantite_litres')), 2) ?> L</div></div>
    </div>
    <div class="col-6 col-md-3">
      <div class="kpi-card"><div class="kpi-icon-wrap gold"><i class="fa fa-receipt"></i></div><div class="kpi-label">Nb. entrées</div><div class="kpi-value dark"><?= count($entrees ?? []) ?></div></div>
    </div>
    <div class="col-6 col-md-3">
      <div class="kpi-card"><div class="kpi-icon-wrap orange"><i class="fa fa-circle-dollar-to-slot"></i></div><div class="kpi-label">Coût total</div><div class="kpi-value orange"><?= number_format(array_sum(array_column($entrees ?? [], 'valeur_totale')), 0, ',', ' ') ?> Ar</div></div>
    </div>
    <div class="col-6 col-md-3">
      <div class="kpi-card"><div class="kpi-icon-wrap blue"><i class="fa fa-calculator"></i></div><div class="kpi-label">CUMP</div><div class="kpi-value blue"><?= number_format($stock['cump_actuel'] ?? 0, 0, ',', ' ') ?> Ar/L</div></div>
    </div>
  </div>
  <div class="content-card">
    <div class="mb-3 d-flex justify-content-end gap-2 flex-wrap align-items-center">
      <!-- Filtre fournisseur -->
      <div style="position:relative;">
        <select id="filterFournisseur" class="arovia-input" style="height:38px;font-size:.9rem;min-width:180px;">
          <option value="">Tous les fournisseurs</option>
          <?php foreach ($fournisseurs ?? [] as $f): ?>
          <option value="<?= esc(strtolower($f['nom'] ?? '')) ?>"><?= esc($f['nom'] ?? '') ?></option>
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
        <tr><th>Date</th><th>Fournisseur</th><th>Quantité (L)</th><th>Prix unit. (Ar)</th><th>Total (Ar)</th><th>Actions</th></tr>
      </thead>
      <tbody>
        <?php if (!empty($entrees)): ?>
          <?php foreach ($entrees as $entree): ?>
            <tr>
              <td><?= esc(date('d/m/Y', strtotime($entree['date_entree'] ?? 'now'))) ?></td>
              <td><span class="table-avatar"><?= esc(strtoupper(substr($entree['fournisseur_nom'] ?? 'F', 0, 1))) ?></span> <?= esc($entree['fournisseur_nom'] ?? '—') ?></td>
              <td><span class="badge-arovia badge-green"><?= number_format($entree['quantite_litres'] ?? 0, 2) ?> L</span></td>
              <td><?= number_format($entree['prix_unitaire'] ?? 0, 0, ',', ' ') ?></td>
              <td class="fw-600 text-orange"><?= number_format($entree['valeur_totale'] ?? 0, 0, ',', ' ') ?></td>
              <td>
                <button class="btn-icon-edit" type="button"><i class="fa fa-pen"></i></button>
                <button class="btn-icon-delete ms-1" type="button"><i class="fa fa-trash"></i></button>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="6" class="text-center text-muted" style="padding:2rem">Aucune entrée enregistrée.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
    <div class="table-footer">
      <span class="table-info-text">Affichage de 1 à 1 sur 1 résultat</span>
      <div class="d-flex align-items-center gap-2">
        <select class="arovia-input" style="width:120px;padding:.3rem .7rem"><option>10 par page</option></select>
        <div class="arovia-pagination"><a href="#" class="page-btn">«</a><a href="#" class="page-btn active">1</a><a href="#" class="page-btn">»</a></div>
      </div>
    </div>
  </div>
</main>
<!-- Modal -->
<div class="modal fade" id="modalEntree" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Nouvelle entrée matière première</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <form method="post" action="/entrees-matiere-premiere">
        <div class="modal-body">
          <div class="mb-3"><label class="arovia-label" for="fournisseur_id">Fournisseur *</label>
            <select id="fournisseur_id" name="fournisseur_id" class="arovia-input" required>
              <option value="">Sélectionner...</option>
              <?php foreach ($fournisseurs ?? [] as $fournisseur): ?>
                <option value="<?= (int) ($fournisseur['id'] ?? 0) ?>"><?= esc($fournisseur['nom'] ?? 'Fournisseur') ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="mb-3"><label class="arovia-label" for="quantite">Quantité (L) *</label><input id="quantite" name="quantite" type="number" step="0.01" class="arovia-input" placeholder="0.00" required/></div>
          <div class="mb-3"><label class="arovia-label" for="prix_unitaire">Prix unitaire (Ar/L) *</label><input id="prix_unitaire" name="prix_unitaire" type="number" step="0.01" class="arovia-input" placeholder="0" required/></div>
        </div>
        <div class="modal-footer"><button type="button" class="btn-outline-gold" data-bs-dismiss="modal">Annuler</button><button type="submit" class="btn-gold">Enregistrer</button></div>
      </form>
    </div>
  </div>
</div>
<script src="<?= base_url('assets/bootstrap/bootstrap.bundle.min.js') ?>"></script>
<script>
function toggleSubmenu(el){el.classList.toggle('open');el.nextElementSibling.classList.toggle('open');}

function applyFilters() {
  const query  = document.getElementById('tableSearch').value.toLowerCase();
  const filtre = document.getElementById('filterFournisseur').value.toLowerCase();
  const rows   = document.querySelectorAll('.arovia-table tbody tr');

  rows.forEach(row => {
    const text      = row.textContent.toLowerCase();
    const fournCell = (row.cells[1]?.textContent || '').toLowerCase();
    const matchQ = !query  || text.includes(query);
    const matchF = !filtre || fournCell.includes(filtre);
    row.style.display = (matchQ && matchF) ? '' : 'none';
  });
}

document.getElementById('tableSearch').addEventListener('keyup', applyFilters);
document.getElementById('filterFournisseur').addEventListener('change', applyFilters);
</script>
</body>
</html>
