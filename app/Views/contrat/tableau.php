<table class="tableau">
    <thead>
        <tr>
            <th>ID</th>
            <th>Sujet</th>
            <th>Entreprise</th>
            <th>Statut</th>
            <th>Date création</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($contrats)) : ?>
            <tr>
                <td colspan="6" class="tableau-vide">Aucun contrat trouvé.</td>
            </tr>
        <?php else : ?>
            <?php foreach ($contrats as $contrat) : ?>
                <tr>
                    <td>#<?= esc($contrat['id']) ?></td>
                    <td><?= esc($contrat['sujet']) ?></td>
                    <td><?= esc($contrat['entreprise_nom']) ?></td>
                    <td>
                        <span class="badge badge-<?= esc(strtolower(str_replace(' ', '-', $contrat['statut_nom']))) ?>">
                            <?= esc($contrat['statut_nom']) ?>
                        </span>
                    </td>
                    <td><?= esc($contrat['date_creation']) ?></td>
                    <td class="actions">
                        <a href="<?= base_url('contrat/detail/' . $contrat['id']) ?>" class="bouton bouton-petit">Voir</a>
                        <a href="<?= base_url('contrat/modifier/' . $contrat['id']) ?>" class="bouton bouton-petit">Modifier</a>
                        <a href="<?= base_url('contrat/pdf/' . $contrat['id']) ?>" class="bouton bouton-petit bouton-secondaire">PDF</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>
