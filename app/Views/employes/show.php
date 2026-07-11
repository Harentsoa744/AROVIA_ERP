<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/><meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>Détails employé — <?= $employe['prenom'] ?> <?= $employe['nom'] ?></title>
  <link rel="stylesheet" href="<?= base_url('assets/bootstrap/bootstrap.min.css') ?>"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <link rel="stylesheet" href="<?= base_url('assets/css/global.css') ?>"/>
  <link rel="stylesheet" href="<?= base_url('assets/css/employes.css') ?>"/>
</head>
<body>
<?php include 'utils/header.php'; ?>
<?php include 'utils/side_bar.php'; ?>
<main class="main-wrapper">
  <div class="breadcrumb-bar">
    <a href="/employes">Employés</a>
    <span>›</span> Détails
  </div>

  <div class="page-header">
    <h1 class="page-title">Détails employé</h1>
    <div class="d-flex gap-2">
      <a href="/employes/edit/<?= $employe['id'] ?>" class="btn-gold"><i class="fa fa-pen"></i> Modifier</a>
      <a href="/employes" class="btn-outline-gold"><i class="fa fa-arrow-left"></i> Retour</a>
    </div>
  </div>

  <div class="row g-4">
    <!-- Photo et informations principales -->
    <div class="col-12 col-md-4">
      <div class="content-card text-center">
        <div class="mb-3">
          <?php if (!empty($employe['photo_profil'])): ?>
            <img src="<?= base_url('uploads/employes/' . $employe['photo_profil']) ?>" 
                 alt="Photo de <?= $employe['prenom'] ?>" 
                 class="rounded-circle" 
                 style="width:120px;height:120px;object-fit:cover;border:3px solid var(--primary-gold);">
          <?php else: ?>
            <div class="table-avatar" style="width:120px;height:120px;font-size:2.5rem">
              <?= strtoupper(substr($employe['prenom'] ?? '', 0, 1) . substr($employe['nom'] ?? '', 0, 1)) ?>
            </div>
          <?php endif; ?>
        </div>
        <h3 class="fw-700 text-dark-primary"><?= esc(($employe['prenom'] ?? '') . ' ' . ($employe['nom'] ?? '')) ?></h3>
        <p class="text-muted mb-2"><?= esc($employe['poste'] ?? '—') ?></p>
        <span class="badge-arovia badge-<?= strtolower($employe['statut']) === 'actif' ? 'green' : 'red' ?>">
          <?= $employe['statut'] ?>
        </span>
      </div>
    </div>

    <!-- Informations détaillées -->
    <div class="col-12 col-md-8">
      <div class="content-card">
        <h3 class="content-card-title"><i class="fa fa-user text-gold me-2"></i>Informations personnelles</h3>
        <div class="row g-3">
          <div class="col-12 col-md-6">
            <label class="arovia-label">Matricule</label>
            <div class="fw-600"><?= esc($employe['matricule'] ?? '—') ?></div>
          </div>
          <div class="col-12 col-md-6">
            <label class="arovia-label">Email</label>
            <div class="fw-600">
              <?php if (!empty($employe['email'])): ?>
                <a href="mailto:<?= esc($employe['email']) ?>"><?= esc($employe['email']) ?></a>
              <?php else: ?>
                —
              <?php endif; ?>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <label class="arovia-label">Téléphone</label>
            <div class="fw-600">
              <?php if (!empty($employe['telephone'])): ?>
                <a href="tel:<?= esc($employe['telephone']) ?>"><?= esc($employe['telephone']) ?></a>
              <?php else: ?>
                —
              <?php endif; ?>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <label class="arovia-label">Adresse</label>
            <div class="fw-600"><?= esc($employe['adresse'] ?? '—') ?></div>
          </div>
          <div class="col-12 col-md-6">
            <label class="arovia-label">Date d'embauche</label>
            <div class="fw-600"><i class="fa fa-calendar-plus text-muted me-2"></i><?= isset($employe['date_embauche']) ? date('d/m/Y', strtotime($employe['date_embauche'])) : '—' ?></div>
          </div>
          <div class="col-12 col-md-6">
            <label class="arovia-label">Salaire de base</label>
            <div class="fw-600 text-gold"><?= number_format($employe['salaire_base'] ?? 0, 0, ',', ' ') ?> Ar</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Paiements -->
  <?php if (!empty($paiements)): ?>
  <div class="row g-4 mt-2">
    <div class="col-12">
      <div class="content-card">
        <h3 class="content-card-title"><i class="fa fa-money-bill-wave text-gold me-2"></i>Historique des paiements</h3>
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>Période</th>
                <th>Brut</th>
                <th>Net payé</th>
                <th>Statut</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($paiements as $p): ?>
              <tr>
                <td><?= str_pad($p['mois'], 2, '0', STR_PAD_LEFT) ?>/<?= $p['annee'] ?></td>
                <td><?= number_format($p['salaire_brut'] ?? $employe['salaire_base'], 0, ',', ' ') ?> Ar</td>
                <td class="gold"><?= number_format($p['montant_paye'], 0, ',', ' ') ?> Ar</td>
                <td>
                  <span class="badge-arovia badge-<?= strtolower($p['statut'] ?? 'paye') === 'paye' ? 'green' : 'orange' ?>">
                    <?= $p['statut'] ?? 'Payé' ?>
                  </span>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <?php endif; ?>

  <!-- Planning -->
  <?php if (!empty($planning)): ?>
  <div class="row g-4 mt-2">
    <div class="col-12">
      <div class="content-card">
        <h3 class="content-card-title"><i class="fa fa-calendar text-gold me-2"></i>Planning</h3>
        <?php foreach ($planning as $pl): ?>
        <div class="d-flex align-items-start mb-3 pb-3 border-bottom">
          <div class="me-3">
            <i class="fa fa-calendar-check text-gold fa-lg"></i>
          </div>
          <div>
            <div class="fw-700"><?= esc($pl['type_evenement']) ?></div>
            <div class="text-muted small">
              <?php if (!empty($pl['date_debut'])): ?>
                <?= date('d/m/Y', strtotime($pl['date_debut'])) ?>
                <?php if (!empty($pl['date_fin'])): ?>
                  → <?= date('d/m/Y', strtotime($pl['date_fin'])) ?>
                <?php endif; ?>
              <?php endif; ?>
            </div>
            <div class="text-muted"><?= esc($pl['description']) ?></div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
  <?php endif; ?>

</main>
<script src="<?= base_url('assets/bootstrap/bootstrap.bundle.min.js') ?>"></script>
<script>function toggleSubmenu(el){el.classList.toggle('open');el.nextElementSibling.classList.toggle('open');}</script>
</body>
</html>
