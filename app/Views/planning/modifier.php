<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/><meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>Modifier livraison — Miel Arovia</title>
  <link rel="stylesheet" href="<?= base_url('assets/bootstrap/bootstrap.min.css') ?>"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <link rel="stylesheet" href="<?= base_url('assets/css/global.css') ?>"/>
  <link rel="stylesheet" href="<?= base_url('css/planning.css') ?>">
</head>
<body>
<?php include FCPATH . 'utils/header.php'; ?>
<?php include FCPATH . 'utils/side_bar.php'; ?>
<main class="main-wrapper">
  <div class="breadcrumb-bar">
    <a href="<?= base_url('planning/liste') ?>">Planning</a>
    <span>›</span> Modifier livraison
  </div>

  <div class="page-header">
    <h1 class="page-title">Modifier livraison</h1>
    <a href="<?= base_url('planning/liste') ?>" class="btn-outline-gold"><i class="fa fa-arrow-left"></i> Retour</a>
  </div>

  <div class="row g-4">
    <div class="col-lg-8">
      <div class="content-card">
        <h3 class="content-card-title mb-4"><i class="fa fa-pen text-gold me-2"></i>Modifier les détails de la livraison</h3>
        
        <form method="post" action="<?= base_url('planning/update/'.$livraison['id']) ?>">
          
          <div class="row g-3">
            <div class="col-md-6">
              <label class="arovia-label" for="vente_id">Vente / Commande *</label>
              <select id="vente_id" name="vente_id" class="arovia-input" required>
                <?php foreach(($ventes ?? []) as $v){ ?>
                  <option value="<?= (int) $v['id'] ?>" <?= (int) $v['id'] === (int) $livraison['vente_id'] ? 'selected' : '' ?>>
                    Vente #<?= (int) $v['id'] ?> - <?= esc($v['client_nom'] ?? 'Sans client') ?> - <?= number_format((float) ($v['montant_total'] ?? 0), 0, ',', ' ') ?> Ar
                  </option>
                <?php } ?>
              </select>
            </div>

            <div class="col-md-6">
              <label class="arovia-label" for="client_id">Client / Destinataire *</label>
              <select id="client_id" name="client_id" class="arovia-input" required>
                <option value="">— Sélectionner —</option>
                <?php foreach(($clients ?? []) as $client){ ?>
                  <option value="<?= (int) $client['id'] ?>" data-address="<?= esc($client['adresse'] ?? '', 'attr') ?>">
                    <?= esc($client['nom']) ?><?= !empty($client['telephone']) ? ' - ' . esc($client['telephone']) : '' ?>
                  </option>
                <?php } ?>
              </select>
            </div>

            <div class="col-md-6">
              <label class="arovia-label" for="livreur_id">Livreur *</label>
              <select id="livreur_id" name="livreur_id" class="arovia-input" required>
                <?php foreach(($livreurs ?? []) as $l){ ?>
                  <option value="<?= (int) $l['id'] ?>" <?= (int) $l['id'] == (int) $livraison['livreur_id'] ? 'selected' : '' ?>>
                    <?= esc($l['nom']) ?>
                  </option>
                <?php } ?>
              </select>
            </div>

            <div class="col-md-6">
              <label class="arovia-label" for="date_prevue">Date prévue *</label>
              <input type="datetime-local" id="date_prevue" name="date_prevue" class="arovia-input" required
                     value="<?= date('Y-m-d\TH:i', strtotime($livraison['date_prevue'])) ?>">
            </div>

            <div class="col-md-6">
              <label class="arovia-label" for="date_effective">Date effective</label>
              <input type="datetime-local" id="date_effective" name="date_effective" class="arovia-input"
                     value="<?= $livraison['date_effective'] ? date('Y-m-d\TH:i', strtotime($livraison['date_effective'])) : '' ?>">
            </div>

            <div class="col-md-12">
              <label class="arovia-label" for="adresse_livraison">Adresse livraison *</label>
              <textarea id="adresse_livraison" name="adresse_livraison" class="arovia-input" rows="3" required><?= esc($livraison['adresse_livraison']) ?></textarea>
            </div>

            <div class="col-md-12">
              <label class="arovia-label" for="statut">Statut *</label>
              <select id="statut" name="statut" class="arovia-input" required>
                <?php 
                  $statuts = ['EN_ATTENTE', 'EN_COURS', 'LIVREE', 'EFFECTUEE', 'ANNULEE'];
                  // Add current status if not in list
                  if (!in_array($livraison['statut'], $statuts)) {
                      $statuts[] = $livraison['statut'];
                  }
                  foreach ($statuts as $st) {
                      $selected = ($livraison['statut'] === $st) ? 'selected' : '';
                      echo "<option value=\"$st\" $selected>$st</option>";
                  }
                ?>
              </select>
            </div>
          </div>

          <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn-gold">Modifier</button>
            <a href="<?= base_url('planning/liste') ?>" class="btn-outline-gold">Annuler</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</main>
<script src="<?= base_url('assets/bootstrap/bootstrap.bundle.min.js') ?>"></script>
<script>
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