<?= view('header', ['titre' => $titre]) ?>

<div class="page-entete">
    <h1>Ajouter un contrat</h1>
</div>

<div class="carte carte-formulaire">
    <form action="<?= base_url('contrat/save') ?>" method="post">

        <div class="champ">
            <label for="sujet">Sujet</label>
            <input type="text" id="sujet" name="sujet" value="<?= esc($contrat['sujet'] ?? '') ?>">
            <?php if (isset($validation) && $validation->getError('sujet')) : ?>
                <span class="erreur-champ"><?= esc($validation->getError('sujet')) ?></span>
            <?php endif; ?>
        </div>

        <div class="champ">
            <label for="entreprise_id">Entreprise</label>
            <select id="entreprise_id" name="entreprise_id">
                <option value="">-- Sélectionner --</option>
                <?php foreach ($entreprises as $entreprise) : ?>
                    <option value="<?= $entreprise['id'] ?>" <?= (($contrat['entreprise_id'] ?? '') == $entreprise['id']) ? 'selected' : '' ?>>
                        <?= esc($entreprise['nom']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if (isset($validation) && $validation->getError('entreprise_id')) : ?>
                <span class="erreur-champ"><?= esc($validation->getError('entreprise_id')) ?></span>
            <?php endif; ?>
        </div>

        <div class="champ">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="4"><?= esc($contrat['description'] ?? '') ?></textarea>
        </div>

        <div class="champ">
            <label for="statut_id">Statut</label>
            <select id="statut_id" name="statut_id">
                <?php foreach ($statuts as $statut) : ?>
                    <option value="<?= $statut['id'] ?>" <?= (($contrat['statut_id'] ?? '') == $statut['id']) ? 'selected' : '' ?>>
                        <?= esc($statut['nom']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if (isset($validation) && $validation->getError('statut_id')) : ?>
                <span class="erreur-champ"><?= esc($validation->getError('statut_id')) ?></span>
            <?php endif; ?>
        </div>

        <div class="champ">
            <label for="date_expiration">Date d'expiration</label>
            <input type="date" id="date_expiration" name="date_expiration" value="<?= esc($contrat['date_expiration'] ?? '') ?>">
        </div>

        <div class="actions-formulaire">
            <button type="submit" class="bouton bouton-principal">Enregistrer</button>
            <a href="<?= base_url('contrat') ?>" class="bouton bouton-secondaire">Annuler</a>
        </div>
    </form>
</div>

<?= view('footer') ?>
