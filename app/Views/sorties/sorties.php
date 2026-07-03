<?= view('partials/header') ?>
<h1>Sorties (ventes/distribution)</h1>

<?php if (session()->getFlashdata('message')): ?>
    <p style="color: green;"><?= session()->getFlashdata('message') ?></p>
<?php endif; ?>

<div style="border: 1px solid #ccc; padding: 12px; margin-bottom: 20px; width: 350px;">
    <h3>Stock produit fini disponible</h3>
    <?php foreach ($stockPF as $s): ?>
        <p><?= esc($s['nom']) ?> : <strong><?= $s['quantite_disponible'] ?></strong> bocaux</p>
    <?php endforeach; ?>
</div>

<form method="get" action="/sorties" style="border: 1px solid #ccc; padding: 12px; margin-bottom: 20px; width: fit-content;">
    <strong>Filtres</strong><br><br>

    <label>Type de bocal :</label>
    <select name="type_bocal_id">
        <option value="">Tous</option>
        <?php foreach ($typesBocaux as $type): ?>
            <option value="<?= $type['id'] ?>" <?= ($filtres['type_bocal_id'] == $type['id']) ? 'selected' : '' ?>>
                <?= esc($type['nom']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label>Destinataire :</label>
    <select name="destinataire_type">
        <option value="">Tous</option>
        <option value="touriste" <?= ($filtres['destinataire_type'] == 'touriste') ? 'selected' : '' ?>>Touriste</option>
        <option value="particulier" <?= ($filtres['destinataire_type'] == 'particulier') ? 'selected' : '' ?>>Particulier</option>
        <option value="hotel" <?= ($filtres['destinataire_type'] == 'hotel') ? 'selected' : '' ?>>Hôtel</option>
    </select>

    <label>Du :</label>
    <input type="date" name="date_debut" value="<?= esc($filtres['date_debut'] ?? '') ?>">

    <label>Au :</label>
    <input type="date" name="date_fin" value="<?= esc($filtres['date_fin'] ?? '') ?>">

    <button type="submit">Filtrer</button>
    <a href="/sorties">Réinitialiser</a>
</form>

<a href="/sorties/new">Enregistrer une vente</a>

<table border="1" cellpadding="8" style="margin-top: 16px;">
    <tr>
        <th>Date</th>
        <th>Bocal</th>
        <th>Quantité</th>
        <th>Destinataire</th>
        <th>Nom</th>
        <th>Valeur totale</th>
    </tr>
    <?php foreach ($sorties as $s): ?>
    <tr>
        <td><?= esc($s['date_sortie']) ?></td>
        <td><?= esc($s['bocal_nom']) ?></td>
        <td><?= $s['quantite'] ?></td>
        <td><?= esc($s['destinataire_type']) ?></td>
        <td><?= esc($s['destinataire_nom'] ?? '-') ?></td>
        <td><?= number_format($s['valeur_totale'], 2) ?> Ar</td>
    </tr>
    <?php endforeach; ?>
</table>