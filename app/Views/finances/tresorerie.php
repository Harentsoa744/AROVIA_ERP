<?= view('partials/header') ?>

<h1>Trésorerie</h1>

<!-- Alerte solde négatif -->
<?php if ($alerte) : ?>
    <p><strong>⚠️ Attention : solde négatif !</strong></p>
<?php endif ?>

<!-- Solde -->
<p><strong>Solde disponible :</strong> <?= number_format($solde, 2) ?> Ar</p>

<!-- Filtre par date -->
<form method="get" action="/finances/tresorerie">
    <label>Du</label>
    <input type="date" name="date_debut">
    <label>Au</label>
    <input type="date" name="date_fin">
    <button type="submit">Rechercher</button>
</form>

<!-- Mouvements -->
<h2>Mouvements</h2>
<table>
    <thead>
        <tr>
            <th>Date</th>
            <th>Type</th>
            <th>Catégorie</th>
            <th>Montant</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($mouvements as $m) : ?>
        <tr>
            <td><?= $m['date_transaction'] ?></td>
            <td><?= ucfirst($m['type']) ?></td>
            <td><?= esc($m['categorie']) ?></td>
            <td><?= number_format($m['montant'], 2) ?> Ar</td>
        </tr>
        <?php endforeach ?>
    </tbody>
</table>