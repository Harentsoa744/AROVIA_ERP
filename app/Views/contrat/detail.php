<?= view('header', ['titre' => $titre]) ?>

<div class="page-entete">
    <h1>Contrat #<?= esc($contrat['id']) ?> — <?= esc($contrat['sujet']) ?></h1>
    <div class="actions">
        <a href="<?= base_url('contrat/modifier/' . $contrat['id']) ?>" class="bouton bouton-secondaire">Modifier</a>
        <a href="<?= base_url('contrat/pdf/' . $contrat['id']) ?>" class="bouton bouton-principal">Télécharger PDF</a>
    </div>
</div>

<div class="carte">
    <div class="grille-detail">
        <div>
            <span class="etiquette">Entreprise</span>
            <p><?= esc($contrat['entreprise_nom']) ?></p>
        </div>
        <div>
            <span class="etiquette">Téléphone</span>
            <p><?= esc($contrat['entreprise_telephone'] ?? '-') ?></p>
        </div>
        <div>
            <span class="etiquette">Email</span>
            <p><?= esc($contrat['entreprise_email'] ?? '-') ?></p>
        </div>
        <div>
            <span class="etiquette">Statut</span>
            <p><span class="badge badge-<?= esc(strtolower(str_replace(' ', '-', $contrat['statut_nom']))) ?>"><?= esc($contrat['statut_nom']) ?></span></p>
        </div>
        <div>
            <span class="etiquette">Date de création</span>
            <p><?= esc($contrat['date_creation']) ?></p>
        </div>
        <div>
            <span class="etiquette">Date de signature</span>
            <p><?= esc($contrat['date_signature'] ?? '-') ?></p>
        </div>
        <div>
            <span class="etiquette">Date d'expiration</span>
            <p><?= esc($contrat['date_expiration'] ?? '-') ?></p>
        </div>
    </div>

    <div class="description-bloc">
        <span class="etiquette">Description</span>
        <p><?= nl2br(esc($contrat['description'] ?? '-')) ?></p>
    </div>

</div>

<?= view('footer') ?>
