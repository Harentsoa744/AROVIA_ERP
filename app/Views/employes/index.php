<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des employés</title>
    <link rel="stylesheet" href="/assets/css/liste.css">
</head>

<body>

<div class="container">

    <h1>👨‍💼 Liste des employés</h1>

    <div class="top-bar">
        <a href="<?= base_url('/employes/create') ?>" class="btn-add">+ Ajouter un employé</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Matricule</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Poste</th>
                <th>Téléphone</th>
                <th>Salaire</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($employes as $emp): ?>
                <tr>
                    <td><?= $emp['id'] ?></td>
                    <td><?= $emp['matricule'] ?></td>
                    <td><?= $emp['nom'] ?></td>
                    <td><?= $emp['prenom'] ?></td>
                    <td><?= $emp['poste'] ?></td>
                    <td><?= $emp['telephone'] ?></td>
                    <td><?= number_format($emp['salaire_base'], 2, ',', ' ') ?> Ar</td>
                    <td>
                        <span class="status <?= strtolower($emp['statut']) ?>">
                            <?= $emp['statut'] ?>
                        </span>
                    </td>
                    <td class="actions">
                        <a href="/employes/show/<?=$emp['id'] ?>" class="btn-view">Voir</a>
                        <a href="/employes/edit/<?=$emp['id'] ?>" class="btn-edit">Modifier</a>
                        <a href="/employes/delete/<?=$emp['id'] ?>" 
                           class="btn-delete"
                           onclick="return confirm('Supprimer cet employé ?')">
                           Supprimer
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>

    </table>

</div>

</body>
</html>