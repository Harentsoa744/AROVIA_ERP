<?= view('partials/header') ?>
<h1>Liste des fournisseurs</h1>

<?php if (session()->getFlashdata('message')): ?>
    <p style="color: green;"><?= session()->getFlashdata('message') ?></p>
<?php endif; ?>

<a href="/fournisseurs/new">Ajouter un fournisseur</a>

<table border="1" cellpadding="8">
    <tr>
        <th>Nom</th>
        <th>Contact</th>
        <th>Localisation</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($fournisseurs as $f): ?>
    <tr>
        <td><?= esc($f['nom']) ?></td>
        <td><?= esc($f['contact']) ?></td>
        <td><?= esc($f['localisation']) ?></td>
        <td>
            <a href="/fournisseurs/<?= $f['id'] ?>/edit">Modifier</a> |
            <a href="/fournisseurs/<?= $f['id'] ?>/delete">Supprimer</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>