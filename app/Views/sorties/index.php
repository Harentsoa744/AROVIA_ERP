<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/><meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>Sorties (ventes) — Miel Arovia</title>
  <link rel="stylesheet" href="<?= base_url('assets/bootstrap/bootstrap.min.css') ?>"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <link rel="stylesheet" href="<?= base_url('assets/css/global.css') ?>"/>
  <link rel="stylesheet" href="<?= base_url('assets/css/sorties.css') ?>"/>
</head>
<body>
<?php include 'utils/header.php'; ?>
<?php include 'utils/side_bar.php'; ?>
<main class="main-wrapper">
  <div class="breadcrumb-bar"><a href="/valeur-stock">Gestion de stock</a> <span>›</span> Sorties (ventes)</div>
  <div class="page-header">
    <h1 class="page-title">Sorties (ventes aux supermarchés)</h1>
    <button class="btn-gold" data-bs-toggle="modal" data-bs-target="#modalSortie"><i class="fa fa-plus"></i> Nouvelle vente</button>
  </div>

  <?php if (session()->getFlashdata('message')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <?= esc(session()->getFlashdata('message')) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>

  <?php if (session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <ul class="mb-0">
        <?php foreach (session()->getFlashdata('errors') as $error): ?>
          <li><?= esc($error) ?></li>
        <?php endforeach; ?>
      </ul>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>

  <div class="row g-3 mb-4">
    <div class="col-6 col-md-3"><div class="kpi-card"><div class="kpi-icon-wrap red"><i class="fa fa-cart-shopping"></i></div><div class="kpi-label">Bocaux vendus</div><div class="kpi-value red"><?= array_sum(array_column($sorties ?? [], 'quantite')) ?></div></div></div>
    <div class="col-6 col-md-3"><div class="kpi-card"><div class="kpi-icon-wrap gold"><i class="fa fa-circle-dollar-to-slot"></i></div><div class="kpi-label">Chiffre d'affaires</div><div class="kpi-value gold"><?= number_format(array_sum(array_column($sorties ?? [], 'valeur_totale')), 0, ',', ' ') ?> Ar</div></div></div>
    <div class="col-6 col-md-3"><div class="kpi-card"><div class="kpi-icon-wrap green"><i class="fa fa-jar"></i></div><div class="kpi-label">Stock restant</div><div class="kpi-value green"><?= (int) array_sum(array_column($stockPF ?? [], 'quantite_disponible')) ?></div></div></div>
    <div class="col-6 col-md-3">
      <?php
        $totalCA = array_sum(array_column($sorties ?? [], 'valeur_totale'));
        $totalMarge = 0;
        foreach ($sorties ?? [] as $s) {
            $totalMarge += ($s['valeur_totale'] - ($s['quantite'] * ($s['cump_applique'] ?? 0) * ($s['bocal_volume'] ?? 0)));
        }
        $tauxMargeGlobale = $totalCA > 0 ? ($totalMarge / $totalCA) * 100 : 0;
      ?>
      <div class="kpi-card"><div class="kpi-icon-wrap blue"><i class="fa fa-percent"></i></div><div class="kpi-label">Marge globale</div><div class="kpi-value blue"><?= number_format($tauxMargeGlobale, 2) ?>%</div></div>
    </div>
  </div>

  <!-- Filtres -->
  <div class="content-card mb-4" style="padding: 1.25rem;">
    <form method="get" action="/sorties" class="row g-2 align-items-end">
      <div class="col-12 col-sm-3">
        <label class="arovia-label" for="filter_bocal">Type de bocal</label>
        <select id="filter_bocal" name="type_bocal_id" class="arovia-input">
          <option value="">Tous</option>
          <?php foreach ($typesBocaux ?? [] as $type): ?>
            <option value="<?= (int) $type['id'] ?>" <?= ($filtres['type_bocal_id'] == $type['id']) ? 'selected' : '' ?>>
              <?= esc($type['nom']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-12 col-sm-3">
        <label class="arovia-label" for="filter_supermarche">Supermarché</label>
        <select id="filter_supermarche" name="supermarche_id" class="arovia-input">
          <option value="">Tous</option>
          <?php foreach ($supermarches ?? [] as $sm): ?>
            <option value="<?= (int) $sm['id'] ?>" <?= ($filtres['supermarche_id'] == $sm['id']) ? 'selected' : '' ?>>
              <?= esc($sm['nom']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-6 col-sm-2">
        <label class="arovia-label" for="filter_debut">Date début</label>
        <input type="date" id="filter_debut" name="date_debut" class="arovia-input" value="<?= esc($filtres['date_debut'] ?? '') ?>"/>
      </div>
      <div class="col-6 col-sm-2">
        <label class="arovia-label" for="filter_fin">Date fin</label>
        <input type="date" id="filter_fin" name="date_fin" class="arovia-input" value="<?= esc($filtres['date_fin'] ?? '') ?>"/>
      </div>
      <div class="col-12 col-sm-2 d-flex gap-2">
        <button type="submit" class="btn-gold w-100" style="height: 38px;">Filtrer</button>
        <a href="/sorties" class="btn-outline-gold w-100 text-center d-flex align-items-center justify-content-center" style="height: 38px;"><i class="fa fa-rotate-right"></i></a>
      </div>
    </form>
  </div>

  <!-- Table Card -->
  <div class="content-card">
    <div class="mb-3 d-flex justify-content-end gap-2 flex-wrap align-items-center">
      <!-- Filtre rapide supermarché -->
      <div style="position:relative;">
        <select id="filterSupermarche" class="arovia-input" style="height:38px;font-size:.9rem;min-width:170px;">
          <option value="">Tous supermarchés</option>
          <?php foreach ($supermarches ?? [] as $sm): ?>
          <option value="<?= esc(strtolower($sm['nom'] ?? '')) ?>"><?= esc($sm['nom'] ?? '') ?></option>
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
        <tr>
          <th>Date</th>
          <th>Supermarché</th>
          <th>Type bocal</th>
          <th>Quantité</th>
          <th>Prix unit. (Ar)</th>
          <th>Total (Ar)</th>
          <th>CUMP (Ar/L)</th>
          <th>Marge (%)</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($sorties)): ?>
          <?php foreach ($sorties as $sortie): ?>
            <?php
              $coutMP = $sortie['quantite'] * ($sortie['cump_applique'] ?? 0) * ($sortie['bocal_volume'] ?? 0);
              $marge = $sortie['valeur_totale'] - $coutMP;
              $tauxMarge = $sortie['valeur_totale'] > 0 ? ($marge / $sortie['valeur_totale']) * 100 : 0;
            ?>
            <tr>
              <td><?= esc(date('d/m/Y', strtotime($sortie['date_sortie'] ?? 'now'))) ?></td>
              <td><?= esc($sortie['supermarche_nom'] ?? 'Inconnu') ?></td>
              <td><?= esc($sortie['bocal_nom'] ?? 'Bocal') ?></td>
              <td><?= (int) ($sortie['quantite'] ?? 0) ?></td>
              <td><?= number_format($sortie['prix_vente_unitaire'] ?? 0, 0, ',', ' ') ?></td>
              <td class="fw-600 text-orange"><?= number_format($sortie['valeur_totale'] ?? 0, 0, ',', ' ') ?> Ar</td>
              <td><?= number_format($sortie['cump_applique'] ?? 0, 2, ',', ' ') ?></td>
              <td class="fw-600 <?= $marge >= 0 ? 'text-green' : 'text-danger' ?>">
                <?= number_format($marge, 0, ',', ' ') ?> Ar
                <small class="d-block text-muted"><?= number_format($tauxMarge, 2) ?> %</small>
              </td>
              <td>
                <a class="btn-icon-edit" href="/factures/<?= (int) ($sortie['id'] ?? 0) ?>" title="Voir"><i class="fa fa-eye"></i></a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="9" style="text-align:center;padding:2rem;color:var(--text-muted)"><i class="fa fa-inbox" style="font-size:2rem;margin-bottom:.5rem;display:block"></i>Aucune vente enregistrée</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</main>

<!-- Modal Vente -->
<div class="modal fade" id="modalSortie" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Enregistrer une vente</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <form method="post" action="/sorties">
        <?= csrf_field() ?>
        <div class="modal-body">
          <div class="mb-3">
            <label class="arovia-label" for="type_bocal_id">Type de bocal *</label>
            <select id="type_bocal_id" name="type_bocal_id" class="arovia-input" required>
              <option value="">Sélectionner...</option>
              <?php foreach ($typesBocaux ?? [] as $type): ?>
                <option value="<?= (int) ($type['id'] ?? 0) ?>" data-prix="<?= (float) ($type['prix_vente'] ?? 0) ?>" data-volume="<?= (float) ($type['volume_litres'] ?? 0) ?>">
                  <?= esc($type['nom'] ?? 'Bocal') ?> (<?= (float) ($type['volume_litres'] ?? 0) ?> L)
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="mb-3">
            <label class="arovia-label" for="supermarche_id">Supermarché *</label>
            <select id="supermarche_id" name="supermarche_id" class="arovia-input" required>
              <option value="">Sélectionner...</option>
              <?php foreach ($supermarches ?? [] as $sm): ?>
                <option value="<?= (int) ($sm['id'] ?? 0) ?>"><?= esc($sm['nom'] ?? 'Supermarché') ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="mb-3">
            <label class="arovia-label" for="quantite">Nombre de bocaux *</label>
            <input id="quantite" name="quantite" type="number" min="1" class="arovia-input" placeholder="0" required/>
          </div>
          <div class="mb-3">
            <label class="arovia-label" for="prix_vente_unitaire">Prix unitaire (Ar) *</label>
            <input id="prix_vente_unitaire" name="prix_vente_unitaire" type="number" step="0.01" class="arovia-input" placeholder="0" required/>
          </div>

          <!-- Real-time estimation box -->
          <div class="p-3 bg-light rounded border">
            <h6 class="mb-2 text-primary"><i class="fa fa-calculator me-1"></i> Estimation de la marge</h6>
            <div class="row g-2 text-muted" style="font-size: 0.85rem;">
              <div class="col-6">Total vente:</div>
              <div class="col-6 text-end fw-600 text-dark"><span id="est-total">0</span> Ar</div>
              <div class="col-6">Coût mat. première:</div>
              <div class="col-6 text-end"><span id="est-cout">0</span> Ar</div>
              <div class="col-6 fw-600 text-dark">Marge nette:</div>
              <div class="col-6 text-end fw-600 text-green"><span id="est-marge">0</span> Ar (<span id="est-taux">0</span>%)</div>
            </div>
          </div>
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
  const filtre = document.getElementById('filterSupermarche').value.toLowerCase();
  const rows   = document.querySelectorAll('.arovia-table tbody tr');

  rows.forEach(row => {
    const text      = row.textContent.toLowerCase();
    const smCell    = (row.cells[1]?.textContent || '').toLowerCase();
    const matchQ = !query  || text.includes(query);
    const matchF = !filtre || smCell.includes(filtre);
    row.style.display = (matchQ && matchF) ? '' : 'none';
  });
}

document.getElementById('tableSearch').addEventListener('keyup', applyFilters);
document.getElementById('filterSupermarche').addEventListener('change', applyFilters);

// Real-time calculation logic
const cumpActuel = <?= (float) ($stockMP['cump_actuel'] ?? 0) ?>;
const selectBocal = document.getElementById('type_bocal_id');
const inputQte = document.getElementById('quantite');
const inputPrix = document.getElementById('prix_vente_unitaire');

const estTotal = document.getElementById('est-total');
const estCout = document.getElementById('est-cout');
const estMarge = document.getElementById('est-marge');
const estTaux = document.getElementById('est-taux');

function updateEstimation() {
  const qte = parseInt(inputQte.value) || 0;
  const prix = parseFloat(inputPrix.value) || 0;
  const selectedOption = selectBocal.options[selectBocal.selectedIndex];
  
  if (!selectedOption || !selectedOption.value) {
    estTotal.textContent = '0';
    estCout.textContent = '0';
    estMarge.textContent = '0';
    estTaux.textContent = '0';
    return;
  }

  const volume = parseFloat(selectedOption.dataset.volume) || 0;
  const totalVente = qte * prix;
  const totalCout = qte * cumpActuel * volume;
  const marge = totalVente - totalCout;
  const taux = totalVente > 0 ? (marge / totalVente) * 100 : 0;

  estTotal.textContent = totalVente.toLocaleString('fr-FR', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
  estCout.textContent = totalCout.toLocaleString('fr-FR', { minimumFractionDigits: 0, maximumFractionDigits: 2 });
  estMarge.textContent = marge.toLocaleString('fr-FR', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
  estTaux.textContent = taux.toFixed(2);
}

selectBocal.addEventListener('change', () => {
  const option = selectBocal.options[selectBocal.selectedIndex];
  if (option && option.dataset.prix) {
    inputPrix.value = option.dataset.prix;
  }
  updateEstimation();
});
inputQte.addEventListener('input', updateEstimation);
inputPrix.addEventListener('input', updateEstimation);
</script>
</body>
</html>
