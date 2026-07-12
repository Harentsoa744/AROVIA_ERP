<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/><meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>Nouvelle livraison — Miel Arovia</title>
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
    <span>›</span> Nouvelle livraison
  </div>

  <div class="page-header">
    <div class="page-title-wrap">
      <h1 class="page-title">Nouvelle livraison</h1>
      <img src="/assets/images/Pattern simple - 1.png" alt="Pattern" class="header-pattern-img" />
    </div>
    <a href="<?= base_url('planning/liste') ?>" class="btn-outline-gold"><i class="fa fa-arrow-left"></i> Retour</a>
  </div>

  <div class="row g-4">
    <div class="col-lg-8">
      <div class="content-card">
        <h3 class="content-card-title mb-4"><i class="fa fa-pencil text-gold me-2"></i>Formulaire de livraison</h3>
        
        <form method="post" action="<?= base_url('planning/save') ?>">
          
          <div class="row g-3">
            <div class="col-md-6">
              <label class="arovia-label" for="vente_id">Vente / Commande *</label>
              <select id="vente_id" name="vente_id" class="arovia-input" required>
                <option value="">— Sélectionner —</option>
                <?php foreach(($ventes ?? []) as $v){ ?>
                  <option value="<?= (int) $v['id'] ?>">
                    Vente #<?= (int) $v['id'] ?><?= !empty($v['client_nom']) ? ' - ' . esc($v['client_nom']) : '' ?>
                    <?= isset($v['montant_total']) ? ' - ' . number_format((float) $v['montant_total'], 0, ',', ' ') . ' Ar' : '' ?>
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
                <option value="">— Sélectionner —</option>
                <?php foreach(($livreurs ?? []) as $l){ ?>
                  <option value="<?= (int) $l['id'] ?>"><?= esc($l['nom']) ?></option>
                <?php } ?>
              </select>
            </div>

            <div class="col-md-12">
              <label class="arovia-label" for="date_prevue">Date prévue *</label>
              <input type="datetime-local" id="date_prevue" name="date_prevue" class="arovia-input" required>
            </div>

            <div class="col-md-12">
              <label class="arovia-label" for="adresse_livraison">Adresse livraison *</label>
              <textarea id="adresse_livraison" name="adresse_livraison" class="arovia-input" rows="3" required></textarea>
            </div>

            <div class="col-md-12">
              <label class="arovia-label" for="statut">Statut *</label>
              <select id="statut" name="statut" class="arovia-input" required>
                <option value="EN_ATTENTE">EN_ATTENTE</option>
                <option value="EN_COURS">EN_COURS</option>
                <option value="LIVREE">LIVREE</option>
                <option value="ANNULEE">ANNULEE</option>
              </select>
            </div>
          </div>

          <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn-gold">Enregistrer</button>
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