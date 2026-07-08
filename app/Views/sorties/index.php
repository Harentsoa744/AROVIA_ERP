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
  <div class="breadcrumb-bar"><a href="gestion-stock.html">Gestion de stock</a> <span>›</span> Sorties (ventes)</div>
  <div class="page-header">
    <h1 class="page-title">Sorties (ventes)</h1>
    <button class="btn-gold" data-bs-toggle="modal" data-bs-target="#modalSortie"><i class="fa fa-plus"></i> Nouvelle vente</button>
  </div>
  <div class="row g-3 mb-4">
    <div class="col-6 col-md-3"><div class="kpi-card"><div class="kpi-icon-wrap red"><i class="fa fa-cart-shopping"></i></div><div class="kpi-label">Bocaux vendus</div><div class="kpi-value red"><?= array_sum(array_column($sorties ?? [], 'quantite')) ?></div></div></div>
    <div class="col-6 col-md-3"><div class="kpi-card"><div class="kpi-icon-wrap gold"><i class="fa fa-circle-dollar-to-slot"></i></div><div class="kpi-label">Chiffre d'affaires</div><div class="kpi-value gold"><?= number_format(array_sum(array_column($sorties ?? [], 'valeur_totale')), 0, ',', ' ') ?> Ar</div></div></div>
    <div class="col-6 col-md-3"><div class="kpi-card"><div class="kpi-icon-wrap green"><i class="fa fa-jar"></i></div><div class="kpi-label">Stock restant</div><div class="kpi-value green"><?= (int) array_sum(array_column($stockPF ?? [], 'quantite_disponible')) ?></div></div></div>
    <div class="col-6 col-md-3"><div class="kpi-card"><div class="kpi-icon-wrap blue"><i class="fa fa-percent"></i></div><div class="kpi-label">Taux écoulement</div><div class="kpi-value blue"><?= count($sorties ?? []) > 0 ? number_format((array_sum(array_column($sorties ?? [], 'quantite')) / max(1, (int) array_sum(array_column($stockPF ?? [], 'quantite_disponible')) + array_sum(array_column($sorties ?? [], 'quantite')))) * 100, 2) : '0.00' ?>%</div></div></div>
  </div>
  <div class="content-card">
    <div class="mb-3 d-flex justify-content-end">
      <div style="position: relative; width: 250px;">
        <i class="fa fa-search" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
        <input type="text" id="tableSearch" class="arovia-input" placeholder="Rechercher..." style="padding-left: 36px; height: 38px; font-size: 0.9rem;">
      </div>
    </div>
    <table class="arovia-table">
      <thead>
        <tr><th>Date</th><th>Client</th><th>Nb. bocaux</th><th>Prix unit. (Ar)</th><th>Total (Ar)</th><th>Statut</th><th>Actions</th></tr>
      </thead>
      <tbody>
        <?php if (!empty($sorties)): ?>
          <?php foreach ($sorties as $sortie): ?>
            <tr>
              <td><?= esc(date('d/m/Y', strtotime($sortie['date_sortie'] ?? 'now'))) ?></td>
              <td><?= esc($sortie['destinataire_nom'] ?: ucfirst($sortie['destinataire_type'] ?? 'Client')) ?></td>
              <td><?= (int) ($sortie['quantite'] ?? 0) ?></td>
              <td><?= number_format($sortie['prix_vente_unitaire'] ?? 0, 0, ',', ' ') ?></td>
              <td class="fw-600 text-orange"><?= number_format($sortie['valeur_totale'] ?? 0, 0, ',', ' ') ?></td>
              <td><span class="badge-arovia badge-green"><?= esc(ucfirst($sortie['destinataire_type'] ?? 'Client')) ?></span></td>
              <td><button class="btn-icon-edit" type="button"><i class="fa fa-pen"></i></button></td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="7" style="text-align:center;padding:2rem;color:var(--text-muted)"><i class="fa fa-inbox" style="font-size:2rem;margin-bottom:.5rem;display:block"></i>Aucune vente enregistrée</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
    <div class="table-footer">
      <span class="table-info-text">Affichage de 0 à 0 sur 0 résultat</span>
      <div class="arovia-pagination"><a href="#" class="page-btn">«</a><a href="#" class="page-btn active">1</a><a href="#" class="page-btn">»</a></div>
    </div>
  </div>
</main>
<div class="modal fade" id="modalSortie" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Nouvelle vente</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <form method="post" action="/sorties">
        <div class="modal-body">
          <div class="mb-3"><label class="arovia-label" for="type_bocal_id">Type de bocal *</label><select id="type_bocal_id" name="type_bocal_id" class="arovia-input" required>
            <option value="">Sélectionner...</option>
            <?php foreach ($typesBocaux ?? [] as $type): ?>
              <option value="<?= (int) ($type['id'] ?? 0) ?>"><?= esc($type['nom'] ?? 'Bocal') ?></option>
            <?php endforeach; ?>
          </select></div>
          <div class="mb-3"><label class="arovia-label" for="quantite">Nombre de bocaux *</label><input id="quantite" name="quantite" type="number" class="arovia-input" placeholder="0" required/></div>
          <div class="mb-3"><label class="arovia-label" for="destinataire_type">Destinataire *</label><select id="destinataire_type" name="destinataire_type" class="arovia-input" required><option value="touriste">Touriste</option><option value="particulier">Particulier</option><option value="hotel">Hôtel</option></select></div>
          <div class="mb-3"><label class="arovia-label" for="destinataire_nom">Nom du client</label><input id="destinataire_nom" name="destinataire_nom" type="text" class="arovia-input" placeholder="Nom du client"/></div>
          <div class="mb-3"><label class="arovia-label" for="prix_vente_unitaire">Prix unitaire (Ar) *</label><input id="prix_vente_unitaire" name="prix_vente_unitaire" type="number" step="0.01" class="arovia-input" placeholder="0" required/></div>
        </div>
        <div class="modal-footer"><button type="button" class="btn-outline-gold" data-bs-dismiss="modal">Annuler</button><button type="submit" class="btn-gold">Enregistrer</button></div>
      </form>
    </div>
  </div>
</div>
<script src="<?= base_url('assets/bootstrap/bootstrap.bundle.min.js') ?>"></script>
<script>
function toggleSubmenu(el){el.classList.toggle('open');el.nextElementSibling.classList.toggle('open');}

document.getElementById('tableSearch').addEventListener('keyup', function() {
  const query = this.value.toLowerCase();
  const rows = document.querySelectorAll('.arovia-table tbody tr');
  rows.forEach(row => {
    const text = row.textContent.toLowerCase();
    row.style.display = text.includes(query) ? '' : 'none';
  });
});
</script>
</body>
</html>
