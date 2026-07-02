<?= view('header', ['titre' => $titre]) ?>

<div class="page-entete">
    <h1>Contrats</h1>
    <a href="<?= base_url('contrat/ajout') ?>" class="bouton bouton-principal">+ Ajouter un contrat</a>
</div>

<div class="barre-filtres" data-recherche-url="<?= base_url('contrat/recherche') ?>">
    <input type="text" id="recherche" class="champ-recherche" placeholder="Rechercher par ID, sujet ou entreprise...">

    <select id="filtre-statut" class="filtre-statut">
        <option value="">Tous les statuts</option>
        <?php foreach ($statuts as $statut) : ?>
            <option value="<?= $statut['id'] ?>"><?= esc($statut['nom']) ?></option>
        <?php endforeach; ?>
    </select>
</div>

<div id="conteneur-tableau">
    <?= view('contrat/tableau', ['contrats' => $contrats]) ?>
</div>

<script src="<?= base_url('js/contrat-recherche.js') ?>"></script>

<?= view('footer') ?>
