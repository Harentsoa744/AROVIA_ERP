<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/><meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>Distribution — Miel Arovia</title>
  <link rel="stylesheet" href="<?= base_url('assets/bootstrap/bootstrap.min.css') ?>"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <link rel="stylesheet" href="<?= base_url('assets/css/global.css') ?>"/>
  <link rel="stylesheet" href="<?= base_url('assets/css/distribution.css') ?>"/>
</head>
<body>
<?php include 'utils/header.php'; ?>
<?php include 'utils/side_bar.php'; ?>
<main class="main-wrapper">
  <div class="page-header">
    <div class="page-title-wrap">
      <h1 class="page-title">Suivi des Distributions</h1>
      <img src="/assets/images/Pattern simple - 1.png" alt="Pattern" class="header-pattern-img" />
    </div>
    <button class="btn-gold" data-bs-toggle="modal" data-bs-target="#modalLivraison"><i class="fa fa-plus"></i> Nouvelle Livraison</button>
  </div>
  <div class="row g-3 mb-4">
    <div class="col-6 col-md-8">
      <div class="kpi-card col-md-9 d-flex flex-row g-5 align-items-center" style="height: 100%; gap:70px">
        <div class="image col-md-3">
        <img src="/assets/images/camion-livraison.png" style="width: 100%">
      </div>

        <div class="d-flex flex-column">

          <div class="row-1 d-flex align-items-center"><div class="kpi-icon-wrap blue flex-column justify-content-center "><i class="fa fa-spinner"></i></div><span class="kpi-label d-flex justify-content-center"> En cours </span><span class="kpi-value d-flex justify-content-center blue"><?= count($livraisons_en_cours ?? []) ?></span></div>
          <div class="row-1 d-flex align-items-center"><div class="kpi-icon-wrap green flex-column justify-content-center "><i class="fa fa-check-double"></i></div><span class="kpi-label d-flex justify-content-center"> Livrées (Mois) </span><span class="kpi-value d-flex justify-content-center green"><?= count($livraisons_faites ?? []) ?></span></div>
          <div class="row-1 d-flex align-items-center"><div class="kpi-icon-wrap orange flex-column justify-content-center "><i class="fa fa-triangle-exclamation"></i></div><span class="kpi-label d-flex justify-content-center"> Incidents </span><span class="kpi-value d-flex justify-content-center orange"><?= (int) ($stats['annulees'] ?? 0) ?></span></div>

        </div>
        </div>

        </div>
        </div>

        
  </div>
  <!-- <div class="row g-3 mb-4">
    <div class="col-6 col-md-4"><div class="kpi-card"><div class="kpi-icon-wrap blue"><i class="fa fa-spinner"></i></div><div class="kpi-label">En cours</div><div class="kpi-value blue"><?= count($livraisons_en_cours ?? []) ?></div></div></div>
    <div class="col-6 col-md-4"><div class="kpi-card"><div class="kpi-icon-wrap green"><i class="fa fa-check-double"></i></div><div class="kpi-label">Livrées (Mois)</div><div class="kpi-value green"><?= count($livraisons_faites ?? []) ?></div></div></div>
    <div class="col-6 col-md-4"><div class="kpi-card"><div class="kpi-icon-wrap orange"><i class="fa fa-triangle-exclamation"></i></div><div class="kpi-label">Incidents</div><div class="kpi-value orange"><?= (int) ($stats['annulees'] ?? 0) ?></div></div></div>
  </div> -->

  <div class="content-card">
    <div class="mb-3 d-flex justify-content-end gap-2 flex-wrap align-items-center">
      <!-- Filtre statut -->
      <div style="position:relative;">
        <select id="filterStatut" class="arovia-input" style="height:38px;font-size:.9rem;min-width:170px;">
          <option value="">Tous les statuts</option>
          <option value="EN_COURS">En cours</option>
          <option value="EN_ATTENTE">En attente</option>
          <option value="EFFECTUEE">Effectuée</option>
          <option value="ANNULEE">Annulée</option>
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
        <tr><th>ID</th><th>Client/Destination</th><th>Livreur</th><th>Date prévue</th><th>Statut</th><th>Actions</th></tr>
      </thead>
      <tbody>
        <?php if (!empty($livraisons_en_cours)): ?>
          <?php foreach ($livraisons_en_cours as $livraison): ?>
            <tr>
              <td class="fw-600">#<?= (int) ($livraison['id'] ?? 0) ?></td>
              <td><?= esc($livraison['adresse_livraison'] ?? '—') ?></td>
              <td><span class="table-avatar" style="width:24px;height:24px;font-size:.7rem;margin-right:.3rem"><?= esc(strtoupper(substr($livraison['livreur_nom'] ?? 'L', 0, 1))) ?></span> <?= esc($livraison['livreur_nom'] ?? '—') ?></td>
              <td><?= esc($livraison['date_prevue'] ?? '—') ?></td>
              <td><span class="badge-arovia badge-blue"><i class="fa fa-truck me-1"></i><?= esc($livraison['statut'] ?? 'EN_COURS') ?></span></td>
              <td>
                <a class="btn-icon-edit" href="/livraisons/status/<?= (int) ($livraison['id'] ?? 0) ?>/EFFECTUEE" title="Marquer livré"><i class="fa fa-check"></i></a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="6" class="text-center text-muted" style="padding:2rem">Aucune livraison en cours.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</main>
<!-- Modal -->
<div class="modal fade" id="modalLivraison" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Planifier une livraison</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <form method="post" action="/livraisons/store">
        <div class="modal-body">
          <div class="mb-3">
            <label class="arovia-label" for="vente_id">Vente / commande *</label>
            <select id="vente_id" name="vente_id" class="arovia-input" required>
              <option value="">— Sélectionner —</option>
              <?php foreach (($ventes ?? []) as $vente): ?>
                <option value="<?= (int) $vente['id'] ?>">
                  Vente #<?= (int) $vente['id'] ?><?= !empty($vente['client_nom']) ? ' - ' . esc($vente['client_nom']) : '' ?>
                  <?= isset($vente['montant_total']) ? ' - ' . number_format((float) $vente['montant_total'], 0, ',', ' ') . ' Ar' : '' ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="mb-3">
            <label class="arovia-label" for="client_id">Client / Destinataire *</label>
            <select id="client_id" name="client_id" class="arovia-input" required>
              <option value="">— Sélectionner —</option>
              <?php foreach (($clients ?? []) as $client): ?>
                <option value="<?= (int) $client['id'] ?>" data-address="<?= esc($client['adresse'] ?? '', 'attr') ?>">
                  <?= esc($client['nom']) ?><?= !empty($client['telephone']) ? ' - ' . esc($client['telephone']) : '' ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="mb-3"><label class="arovia-label" for="adresse_livraison">Adresse de destination *</label><input id="adresse_livraison" name="adresse_livraison" type="text" class="arovia-input" required/></div>
          <div class="mb-3"><label class="arovia-label" for="livreur_id">Livreur assigné *</label><select id="livreur_id" name="livreur_id" class="arovia-input" required>
            <?php foreach ($livreurs_dispo ?? [] as $livreur): ?>
              <option value="<?= (int) ($livreur['id'] ?? 0) ?>"><?= esc($livreur['nom'] ?? 'Livreur') ?></option>
            <?php endforeach; ?>
          </select></div>
          <div class="row">
            <div class="col-6 mb-3"><label class="arovia-label" for="date_prevue">Date</label><input id="date_prevue" name="date_prevue" type="datetime-local" class="arovia-input"/></div>
          </div>
        </div>
        <div class="modal-footer"><button type="button" class="btn-outline-gold" data-bs-dismiss="modal">Annuler</button><button type="submit" class="btn-gold">Planifier</button></div>
      </form>
    </div>
  </div>
</div>
<script src="<?= base_url('assets/bootstrap/bootstrap.bundle.min.js') ?>"></script>
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
    const matchF = !filtre || statutCell.includes(filtre.replace('_', ' '));
    row.style.display = (matchQ && matchF) ? '' : 'none';
  });
}

document.getElementById('tableSearch').addEventListener('keyup', applyFilters);
document.getElementById('filterStatut').addEventListener('change', applyFilters);

const clientSelect = document.getElementById('client_id');
const addressField = document.getElementById('adresse_livraison');

if (clientSelect && addressField) {
  clientSelect.addEventListener('change', function () {
    const option = this.options[this.selectedIndex];
    addressField.value = option ? (option.dataset.address || '') : '';
  });
}
</script>
</body>
</html>
