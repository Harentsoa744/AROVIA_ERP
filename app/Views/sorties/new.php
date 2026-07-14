<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
  <title>Enregistrer une vente — Miel Arovia</title>
  <link rel="stylesheet" href="<?= base_url('assets/bootstrap/bootstrap.min.css') ?>"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <link rel="stylesheet" href="<?= base_url('assets/css/global.css') ?>"/>
</head>
<body>
<?php include 'utils/header.php'; ?>
<?php include 'utils/side_bar.php'; ?>
<main class="main-wrapper">
  <div class="breadcrumb-bar"><a href="/sorties">Sorties (ventes)</a> <span>›</span> Enregistrer une vente</div>
  <div class="page-header">
    <div class="page-title-wrap">
      <h1 class="page-title">Enregistrer une vente</h1>
      <img src="<?= base_url('assets/images/Pattern combi.png') ?>" alt="Pattern" class="header-pattern-img" />
    </div>
    <a href="/sorties" class="btn-outline-gold"><i class="fa fa-arrow-left"></i> Retour</a>
  </div>

  <div class="content-card" style="max-width: 760px;">
    <?php if (session()->getFlashdata('errors')): ?>
      <div class="alert alert-danger">
        <ul class="mb-0">
          <?php foreach (session()->getFlashdata('errors') as $error): ?>
            <li><?= esc($error) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <form action="/sorties" method="post">
      <?= csrf_field() ?>

      <div class="mb-3">
        <label class="arovia-label" for="type-bocal-select">Type de bocal *</label>
        <select name="type_bocal_id" id="type-bocal-select" class="arovia-input" required>
          <option value="">Sélectionner...</option>
          <?php foreach ($typesBocaux as $type): ?>
            <option
                value="<?= $type['id'] ?>"
                data-prix-vente="<?= $type['prix_vente'] ?>"
                data-volume="<?= $type['volume_litres'] ?>"
            >
                <?= esc($type['nom']) ?> (<?= (float) $type['volume_litres'] ?> L)
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="mb-3">
        <label class="arovia-label" for="supermarche-select">Supermarché *</label>
        <select name="supermarche_id" id="supermarche-select" class="arovia-input" required>
          <option value="">Sélectionner...</option>
          <?php foreach ($supermarches as $sm): ?>
            <option value="<?= $sm['id'] ?>"><?= esc($sm['nom']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="mb-3">
        <label class="arovia-label" for="livraison_ou_point_vente">Type de distribution *</label>
        <select name="livraison_ou_point_vente" id="livraison_ou_point_vente" class="arovia-input" required onchange="updateStatutOptions()">
          <option value="">Sélectionner...</option>
          <option value="LIVRAISON">À livrer</option>
          <option value="POINT_VENTE">Sur place</option>
        </select>
      </div>

      <div class="mb-3">
        <label class="arovia-label" for="statut">Statut *</label>
        <select name="statut" id="statut" class="arovia-input" required onchange="toggleDateLivraison()">
          <option value="">Sélectionner...</option>
          <option value="A_LIVRER">À livrer</option>
          <option value="PRIS">Pris</option>
          <option value="ANNULE">Annulé</option>
        </select>
      </div>

      <div class="mb-3" id="date-livraison-container" style="display: none;">
        <label class="arovia-label" for="date_livraison">Date et heure de livraison *</label>
        <input type="datetime-local" name="date_livraison" id="date_livraison" class="arovia-input">
      </div>

      <div class="mb-3">
        <label class="arovia-label" for="quantite-input">Nombre de bocaux *</label>
        <input type="number" min="1" name="quantite" id="quantite-input" class="arovia-input" value="<?= old('quantite') ?>" placeholder="0" required>
      </div>

      <div class="mb-3">
        <label class="arovia-label" for="prix-input">Prix de vente unitaire (Ar) *</label>
        <input type="number" step="0.01" name="prix_vente_unitaire" id="prix-input" class="arovia-input" value="<?= old('prix_vente_unitaire') ?>" placeholder="0" required>
        <small id="cump-info" class="text-muted d-block mt-1"></small>
      </div>

      <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn-gold">Valider la vente</button>
        <a href="/sorties" class="btn-outline-gold">Annuler</a>
      </div>
    </form>
  </div>
</main>

<script src="<?= base_url('assets/bootstrap/bootstrap.bundle.min.js') ?>"></script>
<script>
const cumpActuel = <?= (float) ($stockMP['cump_actuel'] ?? 0) ?>;

const select = document.getElementById('type-bocal-select');
const prixInput = document.getElementById('prix-input');
const cumpInfo = document.getElementById('cump-info');

function updateStatutOptions() {
  const typeDistribution = document.getElementById('livraison_ou_point_vente').value;
  const statutSelect = document.getElementById('statut');
  
  // Reset options
  statutSelect.innerHTML = '<option value="">Sélectionner...</option>';
  
  if (typeDistribution === 'LIVRAISON') {
    statutSelect.innerHTML += '<option value="A_LIVRER">À livrer</option>';
  } else if (typeDistribution === 'POINT_VENTE') {
    statutSelect.innerHTML += '<option value="PRIS">Pris</option>';
  }
  
  toggleDateLivraison();
}

function toggleDateLivraison() {
  const statut = document.getElementById('statut').value;
  const dateContainer = document.getElementById('date-livraison-container');
  const dateInput = document.getElementById('date_livraison');
  
  if (statut === 'A_LIVRER') {
    dateContainer.style.display = 'block';
    dateInput.required = true;
  } else {
    dateContainer.style.display = 'none';
    dateInput.required = false;
    dateInput.value = '';
  }
}

function majPrixEtCout() {
    const option = select.options[select.selectedIndex];
    if (!option || !option.value) {
        cumpInfo.textContent = '';
        return;
    }
    const prixVente = parseFloat(option.dataset.prixVente) || 0;
    const volume = parseFloat(option.dataset.volume) || 0;

    prixInput.value = prixVente;

    const coutMatierePremiere = (cumpActuel * volume).toFixed(2);
    cumpInfo.textContent = 'Coût matière première estimé : ' + coutMatierePremiere + ' Ar/bocal (CUMP actuel: ' + cumpActuel.toFixed(2) + ' Ar/L)';
}

select.addEventListener('change', majPrixEtCout);
</script>
</body>
</html>