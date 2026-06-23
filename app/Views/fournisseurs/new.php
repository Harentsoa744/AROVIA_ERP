<?= view('partials/header') ?>
<h1>Ajouter un fournisseur</h1>

<?php if (session()->getFlashdata('errors')): ?>
    <ul style="color: red;">
        <?php foreach (session()->getFlashdata('errors') as $error): ?>
            <li><?= esc($error) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form action="/fournisseurs" method="post">
    <?= csrf_field() ?>

    <label>Nom :</label><br>
    <input type="text" name="nom" value="<?= old('nom') ?>"><br><br>

    <label>Contact :</label><br>
    <input type="text" name="contact" value="<?= old('contact') ?>"><br><br>

    <label>Localisation :</label><br>
    <input type="text" name="localisation" value="<?= old('localisation') ?>"><br><br>

    <button type="submit">Enregistrer</button>
</form>

<br>
<a href="/fournisseurs">Retour à la liste</a>