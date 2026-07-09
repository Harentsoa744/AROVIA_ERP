<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/><meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>Employés — Miel Arovia</title>
  <link rel="stylesheet" href="<?= base_url('assets/bootstrap/bootstrap.min.css') ?>"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <link rel="stylesheet" href="<?= base_url('assets/css/global.css') ?>"/>
  <link rel="stylesheet" href="<?= base_url('assets/css/employes.css') ?>"/>
</head>
<body>
<?php include 'utils/header.php'; ?>
<?php include 'utils/side_bar.php'; ?>
<main class="main-wrapper">
  <div class="page-header">
    <h1 class="page-title">Gestion des Employés</h1>
    <button class="btn-gold" data-bs-toggle="modal" data-bs-target="#modalEmploye"><i class="fa fa-user-plus"></i> Ajouter</button>
  </div>
  
  <div class="mb-4 d-flex justify-content-end gap-2 flex-wrap align-items-center">
    <!-- Filtre poste -->
    <div style="position:relative;">
      <select id="filterPoste" class="arovia-input" style="height:38px;font-size:.9rem;min-width:160px;">
        <option value="">Tous les postes</option>
        <?php
          $postes = array_unique(array_filter(array_column($employes ?? [], 'poste')));
          sort($postes);
          foreach ($postes as $poste):
        ?>
        <option value="<?= esc(strtolower($poste)) ?>"><?= esc($poste) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <!-- Recherche -->
    <div style="position: relative; width: 250px;">
      <i class="fa fa-search" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
      <input type="text" id="gridSearch" class="arovia-input" placeholder="Rechercher..." style="padding-left: 36px; height: 38px; font-size: 0.9rem;">
    </div>
  </div>
  
  <div class="row g-3" id="employesGrid">
    <?php if (!empty($employes)): ?>
      <?php foreach ($employes as $employe): ?>
        <?php $initiales = strtoupper(substr($employe['prenom'] ?? '', 0, 1) . substr($employe['nom'] ?? '', 0, 1)); ?>
        <div class="col-12 col-md-6 col-lg-4">
          <div class="content-card d-flex flex-column align-items-center text-center">
            <div class="table-avatar mb-3" style="width:72px;height:72px;font-size:1.8rem"><?= esc($initiales ?: '?') ?></div>
            <h4 class="fw-700 text-dark-primary mb-1" style="font-size:1.1rem"><?= esc(($employe['prenom'] ?? '') . ' ' . ($employe['nom'] ?? '')) ?></h4>
            <div class="text-muted" style="font-size:.85rem"><?= esc($employe['poste'] ?? '—') ?></div>
            <div class="d-flex gap-2 mt-3 w-100">
              <a class="btn-outline-gold flex-fill text-center" href="/employes/show/<?= (int) ($employe['id'] ?? 0) ?>"><i class="fa fa-eye"></i> Voir</a>
              <a class="btn-icon-edit" href="/employes/edit/<?= (int) ($employe['id'] ?? 0) ?>" title="Modifier"><i class="fa fa-pen"></i></a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="col-12">
        <div class="content-card text-center text-muted py-5">Aucun employé enregistré pour le moment.</div>
      </div>
    <?php endif; ?>
  </div>
</main>
<!-- Modal -->
<div class="modal fade" id="modalEmploye" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="post" action="/employes/store">
        <div class="modal-header">
          <h5 class="modal-title">Ajouter un employé</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3"><label class="arovia-label" for="matricule">Matricule</label><input id="matricule" name="matricule" type="text" class="arovia-input"/></div>
          <div class="mb-3"><label class="arovia-label" for="nom">Nom *</label><input id="nom" name="nom" type="text" class="arovia-input" required/></div>
          <div class="mb-3"><label class="arovia-label" for="prenom">Prénom *</label><input id="prenom" name="prenom" type="text" class="arovia-input" required/></div>
          <div class="mb-3"><label class="arovia-label" for="poste">Poste *</label><input id="poste" name="poste" type="text" class="arovia-input" required/></div>
          <div class="mb-3"><label class="arovia-label" for="email">Email</label><input id="email" name="email" type="email" class="arovia-input"/></div>
          <div class="mb-3"><label class="arovia-label" for="telephone">Téléphone</label><input id="telephone" name="telephone" type="tel" class="arovia-input"/></div>
          <div class="mb-3"><label class="arovia-label" for="salaire_base">Salaire de base</label><input id="salaire_base" name="salaire_base" type="number" class="arovia-input"/></div>
          <div class="mb-3"><label class="arovia-label" for="date_embauche">Date d'embauche</label><input id="date_embauche" name="date_embauche" type="date" class="arovia-input"/></div>
        </div>
        <div class="modal-footer"><button type="button" class="btn-outline-gold" data-bs-dismiss="modal">Annuler</button><button type="submit" class="btn-gold">Enregistrer</button></div>
      </form>
    </div>
  </div>
</div>
<script src="<?= base_url('assets/bootstrap/bootstrap.bundle.min.js') ?>"></script>
<script>
function toggleSubmenu(el){el.classList.toggle('open');el.nextElementSibling.classList.toggle('open');}

function applyGridFilters() {
  const query  = document.getElementById('gridSearch').value.toLowerCase();
  const filtre = document.getElementById('filterPoste').value.toLowerCase();
  const cards  = document.querySelectorAll('#employesGrid > div');

  cards.forEach(card => {
    const text      = card.textContent.toLowerCase();
    const posteEl   = card.querySelector('.text-muted');
    const posteText = (posteEl?.textContent || '').toLowerCase();
    const matchQ = !query  || text.includes(query);
    const matchF = !filtre || posteText.includes(filtre);
    card.style.display = (matchQ && matchF) ? '' : 'none';
  });
}

document.getElementById('gridSearch').addEventListener('keyup', applyGridFilters);
document.getElementById('filterPoste').addEventListener('change', applyGridFilters);
</script>
</body>
</html>
