<?= view('header', ['titre' => $titre]) ?>

<div class="page-entete">
    <h1>Ajouter une entreprise</h1>
</div>

<div class="carte carte-formulaire">
    <form action="<?= base_url('entreprise/save') ?>" method="post">

        <div class="champ">
            <label for="nom">Nom *</label>
            <input type="text" id="nom" name="nom" value="<?= esc($entreprise['nom'] ?? '') ?>">
            <?php if (isset($validation) && $validation->getError('nom')) : ?>
                <span class="erreur-champ"><?= esc($validation->getError('nom')) ?></span>
            <?php endif; ?>
        </div>

        <div class="champ">
            <label for="telephone">Téléphone</label>
            <input type="text" id="telephone" name="telephone" value="<?= esc($entreprise['telephone'] ?? '') ?>">
        </div>

        <div class="champ">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?= esc($entreprise['email'] ?? '') ?>">
            <?php if (isset($validation) && $validation->getError('email')) : ?>
                <span class="erreur-champ"><?= esc($validation->getError('email')) ?></span>
            <?php endif; ?>
        </div>

        <div class="actions-formulaire">
            <button type="submit" class="bouton bouton-principal">Enregistrer</button>
            <a href="<?= base_url('entreprise') ?>" class="bouton bouton-secondaire">Annuler</a>
        </div>
    </form>
</div>

<?= view('footer') ?>
