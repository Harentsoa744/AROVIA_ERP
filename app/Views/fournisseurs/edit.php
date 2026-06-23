<?= view('partials/header') ?>
<h1>Modifier le fournisseur</h1>

<?php if (session()->getFlashdata('errors')): ?>
    <ul style="color: red;">
        <?php foreach (session()->getFlashdata('errors') as $error): ?>
            <li><?= esc($error) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form action="/fournisseurs/<?= $fournisseur['id'] ?>" method="post">
    <?= csrf_field() ?>

    <label>Nom :</label><br>
    <input type="text" name="nom" value="<?= old('nom') ?? esc($fournisseur['nom']) ?>"><br><br>

    <label>Contact :</label><br>
    <input type="text" name="contact" value="<?= old('contact') ?? esc($fournisseur['contact']) ?>"><br><br>

    <label>Localisation :</label><br>
    <input type="text" name="localisation" value="<?= old('localisation') ?? esc($fournisseur['localisation']) ?>"><br><br>

    <button type="submit">Mettre à jour</button>
</form>

<br>
<a href="/fournisseurs">Retour à la liste</a>