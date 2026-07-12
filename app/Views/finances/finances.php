<?= view('partials/header') ?><h1>Recettes & Dépenses</h1>
<div class="page-title-wrap">
  <h1>Recettes & Dépenses</h1>
  <img src="/assets/images/Pattern simple - 1.png" alt="Pattern" class="header-pattern-img" />
</div>

<a href="/finances/tresorerie">Trésorerie</a> |
<a href="/finances/rapport">Rapports & Analyses</a>

<!-- Solde actuel -->
<p><strong>Solde actuel :</strong> <?= number_format($solde, 2) ?> Ar</p>

<!-- Formulaire d'ajout -->
<h2>Ajouter une transaction</h2>
<form action="/finances/store" method="post">
    <?= csrf_field() ?>

    <label>Type</label>
    <select name="type" required>
        <option value="recette">Recette</option>
        <option value="depense">Dépense</option>
    </select>

    <label>Catégorie</label>
    <input type="text" name="categorie" placeholder="ex: Vente au comptoir" required>

    <label>Montant (Ar)</label>
    <input type="number" name="montant" step="0.01" min="0" required>

    <label>Description</label>
    <textarea name="description"></textarea>

    <label>Date</label>
    <input type="date" name="date_transaction" required>

    <button type="submit">Enregistrer</button>
</form>

<!-- Filtres -->
<h2>Historique</h2>
<form method="get" action="/finances">
    <select name="type">
        <option value="">Tous</option>
        <option value="recette" <?= $filtre_type === 'recette' ? 'selected' : '' ?>>Recettes</option>
        <option value="depense" <?= $filtre_type === 'depense' ? 'selected' : '' ?>>Dépenses</option>
    </select>
    <input type="date" name="date_debut">
    <input type="date" name="date_fin">
    <button type="submit">Filtrer</button>
</form>

<!-- Tableau -->
<table>
    <thead>
        <tr>
            <th>Date</th>
            <th>Type</th>
            <th>Catégorie</th>
            <th>Montant</th>
            <th>Description</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($transactions as $t) : ?>
        <tr>
            <td><?= $t['date_transaction'] ?></td>
            <td><?= ucfirst($t['type']) ?></td>
            <td><?= esc($t['categorie']) ?></td>
            <td><?= number_format($t['montant'], 2) ?> Ar</td>
            <td><?= esc($t['description']) ?></td>
            <td><a href="/finances/delete/<?= $t['id'] ?>">Supprimer</a></td>
        </tr>
        <?php endforeach ?>
    </tbody>
</table>