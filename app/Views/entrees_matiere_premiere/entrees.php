<?= view('partials/header') ?>
<h1>Entrées de matière première</h1>

<?php if (session()->getFlashdata('message')): ?>
    <p style="color: green;"><?= session()->getFlashdata('message') ?></p>
<?php endif; ?>

<div style="border: 1px solid #ccc; padding: 12px; margin-bottom: 20px; width: 350px;">
    <h3>État actuel du stock</h3>
    <p><strong>Quantité :</strong> <?= number_format($stock['quantite_litres'], 2) ?> L</p>
    <p><strong>Valeur du stock :</strong> <?= number_format($stock['valeur_stock'], 2) ?> Ar</p>
    <p><strong>CUMP actuel :</strong> <?= number_format($stock['cump_actuel'], 2) ?> Ar/L</p>
</div>

<form method="get" action="/entrees-matiere-premiere" style="border: 1px solid #ccc; padding: 12px; margin-bottom: 20px; width: fit-content;">
    <strong>Filtres</strong><br><br>

    <label>Fournisseur :</label>
    <select name="fournisseur_id">
        <option value="">Tous</option>
        <?php foreach ($fournisseurs as $f): ?>
            <option value="<?= $f['id'] ?>" <?= ($filtres['fournisseur_id'] == $f['id']) ? 'selected' : '' ?>>
                <?= esc($f['nom']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label>Du :</label>
    <input type="date" name="date_debut" value="<?= esc($filtres['date_debut'] ?? '') ?>">

    <label>Au :</label>
    <input type="date" name="date_fin" value="<?= esc($filtres['date_fin'] ?? '') ?>">

    <button type="submit">Filtrer</button>
    <a href="/entrees-matiere-premiere">Réinitialiser</a>
</form>


<a href="/entrees-matiere-premiere/new">Ajouter une entrée</a>

<table border="1" cellpadding="8" style="margin-top: 16px;">
    <tr>
        <th>Lot</th>
        <th>Date</th>
        <th>Fournisseur</th>
        <th>Quantité (L)</th>
        <th>Prix unitaire</th>
        <th>Valeur totale</th>
        <th>CUMP après entrée</th>
    </tr>
    <?php foreach ($entrees as $e): ?>
    <tr>
        <td><?= esc($e['numero_lot']) ?></td>
        <td><?= esc($e['date_entree']) ?></td>
        <td><?= esc($e['fournisseur_nom']) ?></td>
        <td><?= number_format($e['quantite_litres'], 2) ?></td>
        <td><?= number_format($e['prix_unitaire'], 2) ?></td>
        <td><?= number_format($e['valeur_totale'], 2) ?></td>
        <td><?= number_format($e['cump_apres_entree'], 2) ?></td>
    </tr>
    <?php endforeach; ?>
</table>