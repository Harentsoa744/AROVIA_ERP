<?php include 'utils/header.php'; ?>
<?php include 'utils/side_bar.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Trésorerie — Miel Arovia</title>
  <link rel="stylesheet" href="<?= base_url('assets/bootstrap/bootstrap.min.css') ?>"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <link rel="stylesheet" href="<?= base_url('assets/css/global.css') ?>"/>
  <link rel="stylesheet" href="<?= base_url('assets/css/finances.css') ?>"/>
  <link rel="stylesheet" href="<?= base_url('assets/css/tresorerie.css') ?>"/>
</head>
<body>
<main class="main-wrapper">
  <div class="breadcrumb-bar"><a href="/finances">Finance</a> <span>›</span> Trésorerie</div>
  <div class="page-header">
    <div class="page-title-wrap">
      <h1 class="page-title">Trésorerie</h1>
      <img src="<?= base_url('assets/images/Pattern simple - 1.png') ?>" alt="" class="page-title-pattern">
    </div>
    <a href="/finances" class="btn-outline-gold"><i class="fa fa-arrow-left"></i> Retour</a>
  </div>

  <?php if ($alerte) : ?>
    <div class="tresorerie-alert"><strong>⚠️ Attention :</strong> solde négatif !</div>
  <?php endif ?>

  <div class="row g-3 mb-4">
    <div class="col-md-4">
      <div class="kpi-card">
        <div class="kpi-label">Solde disponible</div>
        <div class="kpi-value <?= ($solde < 0 ? 'red' : 'green') ?>"><?= number_format($solde, 2) ?> Ar</div>
      </div>
    </div>
  </div>

  <div class="content-card mb-4">
    <div class="content-card-title">Filtrer les mouvements</div>
    <form method="get" action="/finances/tresorerie" class="row g-3 align-items-end tresorerie-form">
      <div class="col-md-3">
        <label class="arovia-label" for="date_debut">Du</label>
        <input id="date_debut" type="date" name="date_debut" class="arovia-input" value="<?= esc($date_debut ?? '') ?>"/>
      </div>
      <div class="col-md-3">
        <label class="arovia-label" for="date_fin">Au</label>
        <input id="date_fin" type="date" name="date_fin" class="arovia-input" value="<?= esc($date_fin ?? '') ?>"/>
      </div>
      <div class="col-md-3">
        <button type="submit" class="btn-gold"><i class="fa fa-filter"></i> Rechercher</button>
      </div>
    </form>
  </div>

  <div class="content-card tresorerie-table">
    <div class="content-card-title">Mouvements</div>
    <div class="table-responsive">
      <table class="arovia-table">
        <thead>
          <tr>
            <th>Date</th>
            <th>Type</th>
            <th>Catégorie</th>
            <th>Montant</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($mouvements)): ?>
            <?php foreach ($mouvements as $m) : ?>
              <tr>
                <td><?= esc($m['date_transaction']) ?></td>
                <td><?= ucfirst(esc($m['type'])) ?></td>
                <td><?= esc($m['categorie']) ?></td>
                <td><?= number_format($m['montant'], 2) ?> Ar</td>
              </tr>
            <?php endforeach ?>
          <?php else: ?>
            <tr>
              <td colspan="4">Aucun mouvement trouvé pour cette période.</td>
            </tr>
          <?php endif ?>
        </tbody>
      </table>
    </div>
  </div>
</main>
</body>
</html>
