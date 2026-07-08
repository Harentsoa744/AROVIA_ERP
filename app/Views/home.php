<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/><meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>Accueil — Miel Arovia</title>
  <link rel="stylesheet" href="<?= base_url('assets/bootstrap/bootstrap.min.css') ?>"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <link rel="stylesheet" href="<?= base_url('assets/css/global.css') ?>"/>
  <link rel="stylesheet" href="<?= base_url('assets/css/index.css') ?>"/>
</head>
<body>
<?php include 'utils/header.php'; ?>
<?php include 'utils/side_bar.php'; ?>
<main class="main-wrapper">
  <div class="page-header">
    <h1 class="page-title">Tableau de bord Général</h1>
    <div class="text-muted">Bonjour, Admin Arovia. Voici le résumé de vos activités.</div>
  </div>

  <div class="row g-3 mb-4">
    <div class="col-12 col-md-8">
      <div class="hero-banner">
        <div class="hero-text">
          <h2>Vue d'ensemble de<br/>l'activité</h2>
          <div class="hero-line"></div>
          <p>Supervisez votre stock, vos employés, vos finances et vos ventes en un coup d'œil.</p>
          <a href="/valeur-stock" class="btn-gold mt-2"><i class="fa fa-arrow-right"></i> Aller au stock</a>
        </div>
        <div class="hero-img">
          <img src="<?= base_url('assets/images/honey-jar.png') ?>" alt="Bocal de miel"/>
        </div>
      </div>
    </div>
    <div class="col-12 col-md-4">
      <div class="content-card h-100 d-flex flex-column justify-content-center text-center">
        <div class="kpi-icon-wrap gold mx-auto mb-3" style="width:64px;height:64px;font-size:2rem"><i class="fa fa-coins"></i></div>
        <div class="kpi-label">Stock matière première</div>
        <div class="kpi-value gold mt-2"><?= number_format($stockMP['quantite_litres'] ?? 0, 2) ?> L</div>
        <div class="mt-3"><span class="badge-arovia badge-green"><i class="fa fa-boxes-stacked"></i> <?= (int) ($totalBocaux ?? 0) ?> bocal(s) disponible(s)</span></div>
      </div>
    </div>
  </div>

  <div class="row g-3">
    <div class="col-6 col-lg-3"><a href="/valeur-stock" class="module-card"><div class="module-icon orange"><i class="fa fa-boxes-stacked"></i></div><div class="module-name">Stock : <?= number_format($stockMP['quantite_litres'] ?? 0, 2) ?> L</div></a></div>
    <div class="col-6 col-lg-3"><a href="/employes" class="module-card"><div class="module-icon blue"><i class="fa fa-users"></i></div><div class="module-name"><?= (int) ($nbEmployes ?? 0) ?> Employé(s) actif(s)</div></a></div>
    <div class="col-6 col-lg-3"><a href="/livraisons" class="module-card"><div class="module-icon green"><i class="fa fa-truck"></i></div><div class="module-name">Distribution en cours</div></a></div>
    <div class="col-6 col-lg-3"><a href="/statistiques" class="module-card"><div class="module-icon red"><i class="fa fa-chart-pie"></i></div><div class="module-name">Performances ventes</div></a></div>
  </div>
</main>
<script src="<?= base_url('assets/bootstrap/bootstrap.bundle.min.js') ?>"></script>
<script>function toggleSubmenu(el){el.classList.toggle('open');el.nextElementSibling.classList.toggle('open');}</script>
</body>
</html>
