<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/><meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>Modifier — <?= $employe['prenom'] ?> <?= $employe['nom'] ?></title>
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
    <span>›</span> <a href="/employes/show/<?= $employe['id'] ?>">Détails</a>
    <span>›</span> Modifier
  </div>

  <div class="page-header">
    <div class="page-title-wrap">
      <h1 class="page-title">Modifier l'employé</h1>
      <img src="/assets/images/Pattern simple - 1.png" alt="Pattern" class="header-pattern-img" />
    </div>
    <div class="d-flex gap-2">
      <a href="/employes/show/<?= $employe['id'] ?>" class="btn-outline-gold"><i class="fa fa-arrow-left"></i> Retour</a>
    </div>
  </div>

  <div class="row g-4">
    <!-- Photo et aperçu -->
    <div class="col-12 col-md-4">
      <div class="content-card text-center">
        <div class="mb-3">
          <div class="mb-3">
            <?php if (!empty($employe['photo_profil'])): ?>
              <img src="<?= base_url('uploads/employes/' . $employe['photo_profil']) ?>" 
                   alt="Photo de <?= $employe['prenom'] ?>" 
                   class="rounded-circle" 
                   style="width:120px;height:120px;object-fit:cover;border:3px solid var(--primary-gold);"
                   id="previewImg">
            <?php else: ?>
              <div class="table-avatar" style="width:120px;height:120px;font-size:2.5rem" id="previewInitials">
                <?= strtoupper(substr($employe['prenom'],0,1).substr($employe['nom'],0,1)) ?>
              </div>
            <?php endif; ?>
          </div>
          <label for="photo_profil" class="btn-outline-gold" style="cursor:pointer; font-size:0.85rem;">
            <i class="fa fa-camera me-1"></i> Changer la photo
          </label>
        </div>
        <h3 class="fw-700 text-dark-primary" id="previewName"><?= esc($employe['prenom']) ?> <?= esc($employe['nom']) ?></h3>
        <p class="text-muted mb-2" id="previewPoste"><?= esc($employe['poste']) ?></p>
        <span class="badge-arovia badge-<?= strtolower($employe['statut']) === 'actif' ? 'green' : 'red' ?>" id="previewStatus">
          <?= $employe['statut'] ?>
        </span>
        <hr class="my-3" style="border-color: var(--border-color);">
        <div class="text-start">
          <div class="mb-2 text-muted" style="font-size:0.85rem;">
            <i class="fa fa-id-card me-2 text-gold"></i><?= esc($employe['matricule']) ?>
          </div>
          <div class="text-muted" style="font-size:0.85rem;">
            <i class="fa fa-coins me-2 text-gold"></i><span id="previewSalaire"><?= number_format($employe['salaire_base'], 0, ',', ' ') ?> Ar / mois</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Formulaire -->
    <div class="col-12 col-md-8">
      <div class="content-card">
        <form method="post"
              action="<?= base_url('employes/update/'.$employe['id']) ?>"
              enctype="multipart/form-data"
              id="editForm">

          <!-- Photo upload -->
          <input type="file" id="photo_profil" name="photo_profil" accept="image/*" class="d-none">

          <!-- Identité -->
          <h3 class="content-card-title"><i class="fa fa-user text-gold me-2"></i>Identité</h3>
          <div class="row g-3">
            <div class="col-12 col-md-6">
              <label class="arovia-label">Prénom <span class="text-danger">*</span></label>
              <input type="text" id="prenom" name="prenom"
                     class="arovia-input"
                     value="<?= htmlspecialchars($employe['prenom']) ?>"
                     required autocomplete="given-name">
            </div>
            <div class="col-12 col-md-6">
              <label class="arovia-label">Nom <span class="text-danger">*</span></label>
              <input type="text" id="nom" name="nom"
                     class="arovia-input"
                     value="<?= htmlspecialchars($employe['nom']) ?>"
                     required autocomplete="family-name">
            </div>
            <div class="col-12 col-md-6">
              <label class="arovia-label">Date de naissance</label>
              <input type="date" id="date_naissance" name="date_naissance"
                     class="arovia-input"
                     value="<?= $employe['date_naissance'] ?? '' ?>">
            </div>
            <div class="col-12 col-md-6">
              <label class="arovia-label">Genre</label>
              <select id="genre" name="genre" class="arovia-input">
                <option value="">— Sélectionner —</option>
                <option value="M" <?= ($employe['genre'] ?? '') === 'M' ? 'selected' : '' ?>>Masculin</option>
                <option value="F" <?= ($employe['genre'] ?? '') === 'F' ? 'selected' : '' ?>>Féminin</option>
              </select>
            </div>
            <div class="col-12">
              <label class="arovia-label">Numéro CIN</label>
              <input type="text" id="cin" name="cin"
                     class="arovia-input"
                     value="<?= htmlspecialchars($employe['cin'] ?? '') ?>"
                     placeholder="ex : 101 234 567 890">
            </div>
            <div class="col-12">
              <label class="arovia-label">Adresse</label>
              <textarea id="adresse" name="adresse" class="arovia-input" rows="2"
                        placeholder="Rue, ville, région..."><?= htmlspecialchars($employe['adresse'] ?? '') ?></textarea>
            </div>
          </div>

          <!-- Coordonnées -->
          <h3 class="content-card-title mt-4"><i class="fa fa-phone text-gold me-2"></i>Coordonnées</h3>
          <div class="row g-3">
            <div class="col-12 col-md-6">
              <label class="arovia-label">Téléphone <span class="text-danger">*</span></label>
              <input type="tel" id="telephone" name="telephone"
                     class="arovia-input"
                     value="<?= htmlspecialchars($employe['telephone']) ?>"
                     required placeholder="ex : 034 00 000 00">
            </div>
            <div class="col-12 col-md-6">
              <label class="arovia-label">Email <span class="text-danger">*</span></label>
              <input type="email" id="email" name="email"
                     class="arovia-input"
                     value="<?= htmlspecialchars($employe['email']) ?>"
                     required autocomplete="email">
            </div>
          </div>

          <!-- Poste & Contrat -->
          <h3 class="content-card-title mt-4"><i class="fa fa-briefcase text-gold me-2"></i>Poste & Contrat</h3>
          <div class="row g-3">
            <div class="col-12 col-md-6">
              <label class="arovia-label">Matricule</label>
              <input type="text" id="matricule" name="matricule"
                     class="arovia-input"
                     value="<?= htmlspecialchars($employe['matricule']) ?>"
                     readonly style="background: var(--surface-light);">
              <small class="text-muted">Non modifiable</small>
            </div>
            <div class="col-12 col-md-6">
              <label class="arovia-label">Poste <span class="text-danger">*</span></label>
              <input type="text" id="poste" name="poste"
                     class="arovia-input"
                     value="<?= htmlspecialchars($employe['poste']) ?>"
                     required>
            </div>
            <div class="col-12 col-md-6">
              <label class="arovia-label">Département / Service</label>
              <input type="text" id="departement" name="departement"
                     class="arovia-input"
                     value="<?= htmlspecialchars($employe['departement'] ?? '') ?>"
                     placeholder="ex : Ressources Humaines">
            </div>
            <div class="col-12 col-md-6">
              <label class="arovia-label">Type de contrat</label>
              <select id="type_contrat" name="type_contrat" class="arovia-input">
                <option value="">— Sélectionner —</option>
                <?php foreach (['CDI','CDD','Stage','Freelance','Intérim'] as $tc): ?>
                <option value="<?= $tc ?>" <?= ($employe['type_contrat'] ?? '') === $tc ? 'selected' : '' ?>><?= $tc ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-12 col-md-6">
              <label class="arovia-label">Date d'embauche</label>
              <input type="date" id="date_embauche" name="date_embauche"
                     class="arovia-input"
                     value="<?= $employe['date_embauche'] ?? '' ?>">
            </div>
            <div class="col-12 col-md-6">
              <label class="arovia-label">Statut <span class="text-danger">*</span></label>
              <select id="statut" name="statut" class="arovia-input" required>
                <option value="ACTIF"  <?= $employe['statut'] === 'ACTIF'  ? 'selected' : '' ?>>Actif</option>
                <option value="INACTIF"<?= $employe['statut'] === 'INACTIF'? 'selected' : '' ?>>Inactif</option>
              </select>
            </div>
          </div>

          <!-- Rémunération -->
          <h3 class="content-card-title mt-4"><i class="fa fa-money-bill-wave text-gold me-2"></i>Rémunération</h3>
          <div class="row g-3">
            <div class="col-12 col-md-6">
              <label class="arovia-label">Salaire de base (Ar) <span class="text-danger">*</span></label>
              <input type="number" id="salaire_base" name="salaire_base"
                     class="arovia-input"
                     value="<?= $employe['salaire_base'] ?>"
                     required min="0" step="500"
                     placeholder="0">
            </div>
            <div class="col-12 col-md-6">
              <label class="arovia-label">RIB / Compte bancaire</label>
              <input type="text" id="rib" name="rib"
                     class="arovia-input"
                     value="<?= htmlspecialchars($employe['rib'] ?? '') ?>"
                     placeholder="ex : BFV-SG 00000-00000">
            </div>
          </div>

          <!-- Actions -->
          <div class="d-flex gap-2 mt-4">
            <a href="/employes/show/<?= $employe['id'] ?>" class="btn-outline-gold">Annuler</a>
            <button type="submit" class="btn-gold">
              <i class="fa fa-save me-1"></i> Enregistrer les modifications
            </button>
          </div>

        </form>
      </div>
    </div>
  </div>

</main>
<script src="<?= base_url('assets/bootstrap/bootstrap.bundle.min.js') ?>"></script>
<script>function toggleSubmenu(el){el.classList.toggle('open');el.nextElementSibling.classList.toggle('open');}</script>
<script>
/* Aperçu live */
const previewName     = document.getElementById('previewName');
const previewPoste    = document.getElementById('previewPoste');
const previewStatus   = document.getElementById('previewStatus');
const previewSalaire  = document.getElementById('previewSalaire');
const previewInitials = document.getElementById('previewInitials');
const previewImg      = document.getElementById('previewImg');

function updatePreview() {
    const prenom = document.getElementById('prenom').value.trim();
    const nom    = document.getElementById('nom').value.trim();
    const poste  = document.getElementById('poste').value.trim();
    const statut = document.getElementById('statut').value;
    const sal    = parseInt(document.getElementById('salaire_base').value) || 0;

    if (previewName)    previewName.textContent = (prenom + ' ' + nom).trim() || '—';
    if (previewPoste)   previewPoste.textContent = poste || '—';
    if (previewSalaire) previewSalaire.textContent = sal.toLocaleString('fr-FR') + ' Ar / mois';

    if (previewStatus) {
        previewStatus.textContent = statut;
        previewStatus.className = 'badge-arovia badge-' + (statut.toLowerCase() === 'actif' ? 'green' : 'red');
    }

    if (previewInitials && prenom && nom) {
        previewInitials.textContent = (prenom[0] + nom[0]).toUpperCase();
    }
}

/* Photo preview */
document.getElementById('photo_profil').addEventListener('change', function() {
    if (!this.files[0]) return;
    const url = URL.createObjectURL(this.files[0]);
    
    if (previewImg) {
        previewImg.src = url;
        previewImg.style.display = 'block';
    }
    if (previewInitials) {
        previewInitials.style.display = 'none';
    }
});

/* Écouteurs */
['prenom','nom','poste','statut','salaire_base'].forEach(id => {
    document.getElementById(id)?.addEventListener('input', updatePreview);
    document.getElementById(id)?.addEventListener('change', updatePreview);
});
</script>
</body>
</html>
