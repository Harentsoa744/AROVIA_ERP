<?= view('header', ['titre' => $titre]) ?>

<div class="page-entete">
    <h1>Entreprises</h1>
    <a href="<?= base_url('entreprise/ajout') ?>" class="bouton bouton-principal">+ Ajouter une entreprise</a>
</div>

<p class="note-avertissement">La suppression d'une entreprise est définitive.</p>

<div class="carte">
    <table class="tableau">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Téléphone</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($entreprises)) : ?>
                <tr>
                    <td colspan="4" class="tableau-vide">Aucune entreprise enregistrée.</td>
                </tr>
            <?php else : ?>
                <?php foreach ($entreprises as $entreprise) : ?>
                    <tr>
                        <td><?= esc($entreprise['nom']) ?></td>
                        <td><?= esc($entreprise['telephone'] ?? '-') ?></td>
                        <td><?= esc($entreprise['email'] ?? '-') ?></td>
                        <td class="actions">
                            <a href="<?= base_url('entreprise/modifier/' . $entreprise['id']) ?>" class="bouton bouton-petit">Modifier</a>
                            <form action="<?= base_url('entreprise/supprimer/' . $entreprise['id']) ?>" method="post" class="form-inline">
                                <button type="submit" class="bouton bouton-petit bouton-danger">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?= view('footer') ?>
