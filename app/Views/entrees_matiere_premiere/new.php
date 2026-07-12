<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Nouvelle entrée — Miel Arovia</title>
  <link rel="stylesheet" href="assets/bootstrap/bootstrap.min.css"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <link rel="stylesheet" href="assets/css/global.css"/>
</head>
<body>
<?php include 'utils/header.php'; ?>
<?php include 'utils/side_bar.php'; ?>
<main class="main-wrapper">
  <div class="breadcrumb-bar"><a href="/entrees-matiere-premiere">Gestion de stock</a> <span>›</span> Nouvelle entrée</div>
  <div class="page-header">
    <div class="page-title-wrap">
      <h1 class="page-title">Nouvelle entrée matière première</h1>
      <img src="<?= base_url('assets/images/Pattern combi.png') ?>" alt="Pattern" class="header-pattern-img" />
    </div>
    <a href="/entrees-matiere-premiere" class="btn-outline-gold"><i class="fa fa-arrow-left"></i> Retour</a>
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

    <form action="/entrees-matiere-premiere" method="post">
      <?= csrf_field() ?>
      <div class="mb-3">
        <label class="arovia-label" for="fournisseur_id">Fournisseur *</label>
        <select id="fournisseur_id" name="fournisseur_id" class="arovia-input" required>
          <option value="">Sélectionner...</option>
          <?php foreach ($fournisseurs as $f): ?>
            <option value="<?= (int) ($f['id'] ?? 0) ?>" <?= old('fournisseur_id') == ($f['id'] ?? 0) ? 'selected' : '' ?>><?= esc($f['nom'] ?? 'Fournisseur') ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="mb-3">
        <label class="arovia-label" for="quantite">Quantité (L) *</label>
        <input id="quantite" name="quantite" step="0.01" type="number" class="arovia-input" value="<?= esc(old('quantite') ?? '') ?>" required/>
      </div>
      <div class="mb-3">
        <label class="arovia-label" for="prix_unitaire">Prix unitaire (Ar/L) *</label>
        <input id="prix_unitaire" name="prix_unitaire" step="0.01" type="number" class="arovia-input" value="<?= esc(old('prix_unitaire') ?? '') ?>" required/>
      </div>
      <div class="d-flex gap-2">
        <button type="submit" class="btn-gold">Enregistrer l'entrée</button>
        <a href="/entrees-matiere-premiere" class="btn-outline-gold">Annuler</a>
      </div>
    </form>
  </div>
</main>
<script src="assets/bootstrap/bootstrap.bundle.min.js"></script>
</body>
</html>