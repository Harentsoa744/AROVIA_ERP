<?= view('partials/header') ?>
<h1>Valeur du stock</h1>

<div style="border: 2px solid #4CAF50; padding: 16px; margin-bottom: 24px; width: fit-content;">
    <h2>Valeur comptable totale : <?= number_format($valeurTotaleComptable, 2) ?> Ar</h2>
    <p>(Matière première + produit fini, valorisés au coût)</p>
</div>

<h3>Stock matière première</h3>
<table border="1" cellpadding="8">
    <tr>
        <th>Quantité (L)</th>
        <th>CUMP actuel (Ar/L)</th>
        <th>Valeur</th>
    </tr>
    <tr>
        <td><?= number_format($stockMP['quantite_litres'], 2) ?></td>
        <td><?= number_format($stockMP['cump_actuel'], 2) ?></td>
        <td><?= number_format($stockMP['valeur_stock'], 2) ?> Ar</td>
    </tr>
</table>

<h3 style="margin-top: 24px;">Stock produit fini</h3>
<table border="1" cellpadding="8">
    <tr>
        <th>Bocal</th>
        <th>Quantité disponible</th>
        <th>Coût unitaire</th>
        <th>Valeur comptable</th>
        <th>Prix de vente catalogue</th>
        <th>Valeur de vente potentielle</th>
    </tr>
    <?php foreach ($stockPF as $bocal): ?>
    <tr>
        <td><?= esc($bocal['nom']) ?> (<?= esc($bocal['cible']) ?>)</td>
        <td><?= $bocal['quantite_disponible'] ?></td>
        <td><?= number_format($bocal['cout_unitaire'], 2) ?> Ar</td>
        <td><?= number_format($bocal['valeur_comptable'], 2) ?> Ar</td>
        <td><?= number_format($bocal['prix_vente'] ?? 0, 2) ?> Ar</td>
        <td><?= number_format($bocal['valeur_vente'], 2) ?> Ar</td>
    </tr>
    <?php endforeach; ?>
    <tr>
        <td colspan="3"><strong>Total produit fini</strong></td>
        <td><strong><?= number_format($valeurComptablePF, 2) ?> Ar</strong></td>
        <td></td>
        <td><strong><?= number_format($valeurVentePF, 2) ?> Ar</strong></td>
    </tr>
</table>