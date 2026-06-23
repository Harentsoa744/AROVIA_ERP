<?= view('partials/header') ?>
<h1>Ajouter une entrée de matière première</h1>

<?php if (session()->getFlashdata('errors')): ?>
    <ul style="color: red;">
        <?php foreach (session()->getFlashdata('errors') as $error): ?>
            <li><?= esc($error) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form action="/entrees-matiere-premiere" method="post">
    <?= csrf_field() ?>

    <label>Fournisseur :</label><br>
    <select name="fournisseur_id">
        <?php foreach ($fournisseurs as $f): ?>
            <option value="<?= $f['id'] ?>"><?= esc($f['nom']) ?></option>
        <?php endforeach; ?>
    </select><br><br>

    <label>Quantité (en litres) :</label><br>
    <input type="number" step="0.01" name="quantite" value="<?= old('quantite') ?>"><br><br>

    <label>Prix unitaire (Ar/L) :</label><br>
    <input type="number" step="0.01" name="prix_unitaire" value="<?= old('prix_unitaire') ?>"><br><br>

    <button type="submit">Enregistrer l'entrée</button>
</form>

<br>
<a href="/entrees-matiere-premiere">Retour à la liste</a>