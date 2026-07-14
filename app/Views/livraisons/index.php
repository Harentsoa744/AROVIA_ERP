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
    <div class="col-6 col-md-4"><div class="kpi-card"><div class="kpi-icon-wrap blue"><i class="fa fa-spinner"></i></div><div class="kpi-label">En cours</div><div class="kpi-value blue"><?= count($livraisons ?? []) ?></div></div></div>
    <div class="col-6 col-md-4"><div class="kpi-card"><div class="kpi-icon-wrap green"><i class="fa fa-check-double"></i></div><div class="kpi-label">Livrées (Mois)</div><div class="kpi-value green"><?= count($livraisons_faites ?? []) ?></div></div></div>
    <div class="col-6 col-md-4"><div class="kpi-card"><div class="kpi-icon-wrap orange"><i class="fa fa-triangle-exclamation"></i></div><div class="kpi-label">Incidents</div><div class="kpi-value orange"><?= (int) ($stats['annulees'] ?? 0) ?></div></div></div>
  </div> -->

  <!-- Tableau des livraisons à faire aujourd'hui -->
  <h3 class="mb-3" style="color: var(--primary-color);">Livraisons à faire aujourd'hui</h3>
  <div class="content-card mb-4">
    <table class="arovia-table">
      <thead>
        <tr><th>ID Sortie</th><th>Supermarché</th><th>Adresse</th><th>Date</th><th>Statut</th><th>Actions</th></tr>
      </thead>
      <tbody>
        <?php if (!empty($sorties_aujourdhui)): ?>
          <?php foreach ($sorties_aujourdhui as $sortie): ?>
            <tr>
              <td class="fw-600">#<?= (int) ($sortie['id'] ?? 0) ?></td>
              <td><?= esc($sortie['supermarche_nom'] ?? '—') ?></td>
              <td><?= esc($sortie['supermarche_adresse'] ?? '—') ?></td>
              <td><?= esc(date('d/m/Y H:i', strtotime($sortie['date_sortie'] ?? 'now'))) ?></td>
              <td><span class="badge bg-warning">À livrer</span></td>
              <td>
                <div class="dropdown">
                  <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="fa fa-ellipsis-v"></i>
                  </button>
                  <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="/livraisons/assigner/<?= (int) ($sortie['id'] ?? 0) ?>"><i class="fa fa-truck me-2"></i>Assigner livreur</a></li>
                    <li><a class="dropdown-item" href="/livraisons/statut_sortie/<?= (int) ($sortie['id'] ?? 0) ?>/PRIS"><i class="fa fa-check me-2"></i>Pris sur place</a></li>
                    <li><a class="dropdown-item" href="/livraisons/statut_sortie/<?= (int) ($sortie['id'] ?? 0) ?>/ANNULE"><i class="fa fa-times me-2"></i>Annuler</a></li>
                  </ul>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="6" class="text-center text-muted" style="padding:2rem">Aucune livraison prévue pour aujourd'hui.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <!-- Tableau de toutes les livraisons -->
  <h3 class="mb-3" style="color: var(--primary-color);">Toutes les livraisons</h3>
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
      <tbody id="livraisonsTableBody">
        <?php if (!empty($livraisons)): ?>
          <?php foreach ($livraisons as $livraison): ?>
            <tr data-statut="<?= esc($livraison['statut'] ?? '') ?>">
              <td class="fw-600">#<?= (int) ($livraison['id'] ?? 0) ?></td>
              <td><?= esc($livraison['adresse_livraison'] ?? '—') ?></td>
              <td><span class="table-avatar" style="width:24px;height:24px;font-size:.7rem;margin-right:.3rem"><?= esc(strtoupper(substr($livraison['livreur_nom'] ?? 'L', 0, 1))) ?></span> <?= esc($livraison['livreur_nom'] ?? '—') ?></td>
              <td><?= esc($livraison['date_prevue'] ?? '—') ?></td>
              <td><span class="badge-arovia badge-<?= strtolower(str_replace('_', '', $livraison['statut'] ?? 'en_cours')) === 'encours' ? 'blue' : (strtolower(str_replace('_', '', $livraison['statut'] ?? '')) === 'effectuee' ? 'green' : (strtolower(str_replace('_', '', $livraison['statut'] ?? '')) === 'enattente' ? 'orange' : 'red')) ?>"><i class="fa fa-truck me-1"></i><?= esc($livraison['statut'] ?? 'EN_COURS') ?></span></td>
              <td>
                <div class="dropdown">
                  <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="fa fa-ellipsis-v"></i>
                  </button>
                  <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="/livraisons/updateStatut/<?= (int) ($livraison['id'] ?? 0) ?>/EFFECTUEE"><i class="fa fa-check me-2"></i>Livré</a></li>
                    <li><a class="dropdown-item" href="/livraisons/updateStatut/<?= (int) ($livraison['id'] ?? 0) ?>/EN_ATTENTE"><i class="fa fa-clock me-2"></i>Mettre en attente</a></li>
                    <li><a class="dropdown-item" href="/livraisons/updateStatut/<?= (int) ($livraison['id'] ?? 0) ?>/ANNULEE"><i class="fa fa-times me-2"></i>Annulé</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="/livraisons/updateStatut/<?= (int) ($livraison['id'] ?? 0) ?>/EN_RETARD"><i class="fa fa-exclamation-triangle me-2"></i>Livré en retard</a></li>
                  </ul>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="6" class="text-center text-muted" style="padding:2rem">Aucune livraison trouvée.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</main>

<script src="<?= base_url('assets/bootstrap/bootstrap.bundle.min.js') ?>"></script>
<script>
function toggleSubmenu(el){el.classList.toggle('open');el.nextElementSibling.classList.toggle('open');}

function applyFilters() {
  const query  = document.getElementById('tableSearch').value;
  const filtre = document.getElementById('filterStatut').value;
  const tbody  = document.getElementById('livraisonsTableBody');
  
  // Build URL with parameters
  const params = new URLSearchParams();
  if (query) params.append('search', query);
  if (filtre) params.append('statut', filtre);
  
  // Fetch filtered data via AJAX
  fetch('/livraisons/ajax?' + params.toString())
    .then(response => response.json())
    .then(data => {
      tbody.innerHTML = '';
      if (data.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted" style="padding:2rem">Aucune livraison trouvée.</td></tr>';
        return;
      }
      
      data.forEach(livraison => {
        const statutClass = livraison.statut === 'EN_COURS' ? 'blue' : 
                           (livraison.statut === 'EFFECTUEE' ? 'green' : 
                           (livraison.statut === 'EN_ATTENTE' ? 'orange' : 'red'));
        const initials = livraison.livreur_nom ? livraison.livreur_nom.charAt(0).toUpperCase() : 'L';
        
        const row = document.createElement('tr');
        row.innerHTML = `
          <td class="fw-600">#${livraison.id}</td>
          <td>${livraison.adresse_livraison || '—'}</td>
          <td><span class="table-avatar" style="width:24px;height:24px;font-size:.7rem;margin-right:.3rem">${initials}</span> ${livraison.livreur_nom || '—'}</td>
          <td>${livraison.date_prevue || '—'}</td>
          <td><span class="badge-arovia badge-${statutClass}"><i class="fa fa-truck me-1"></i>${livraison.statut}</span></td>
          <td>
            <div class="dropdown">
              <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <i class="fa fa-ellipsis-v"></i>
              </button>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="/livraisons/updateStatut/${livraison.id}/EFFECTUEE"><i class="fa fa-check me-2"></i>Livré</a></li>
                <li><a class="dropdown-item" href="/livraisons/updateStatut/${livraison.id}/EN_ATTENTE"><i class="fa fa-clock me-2"></i>Mettre en attente</a></li>
                <li><a class="dropdown-item" href="/livraisons/updateStatut/${livraison.id}/ANNULEE"><i class="fa fa-times me-2"></i>Annulé</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="/livraisons/updateStatut/${livraison.id}/EN_RETARD"><i class="fa fa-exclamation-triangle me-2"></i>Livré en retard</a></li>
              </ul>
            </div>
          </td>
        `;
        tbody.appendChild(row);
      });
    })
    .catch(error => console.error('Error:', error));
}

document.getElementById('tableSearch').addEventListener('keyup', applyFilters);
document.getElementById('filterStatut').addEventListener('change', applyFilters);
</script>
</body>
</html>
