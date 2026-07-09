<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title><?= esc($titre) ?></title>
<link href="https://fonts.googleapis.com/css2?family=VT323&family=Jost:wght@400;500;600&display=swap" rel="stylesheet">
<style>
    :root {
        --arovia-maroon:    #3d0f12;
        --arovia-gold:      #d99a1b;
        --arovia-terracotta:#c1440e;
        --arovia-olive:     #4a4419;
        --arovia-cream:     #f4efe6;
    }
    * { box-sizing: border-box; }
    body {
        font-family: 'Jost', Arial, sans-serif;
        background: var(--arovia-olive);
        margin: 0; padding: 40px;
        color: #2b2b2b;
    }
    .conteneur {
        max-width: 820px; margin: 0 auto;
        background: var(--arovia-cream);
        border-radius: 6px;
        padding: 36px 44px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.25);
    }
    h1 {
        font-family: 'VT323', monospace;
        font-size: 30px; letter-spacing: 2px;
        color: var(--arovia-maroon);
        border-bottom: 3px solid var(--arovia-maroon);
        padding-bottom: 14px; margin: 0 0 24px;
    }
    label {
        display: block; font-size: 12px; text-transform: uppercase;
        letter-spacing: 0.5px; font-weight: 600; color: var(--arovia-olive);
        margin-bottom: 6px;
    }
    select, input[type=text], input[type=number] {
        width: 100%; padding: 9px 10px; border: 1px solid #cfc6b2;
        border-radius: 4px; font-size: 14px; background: #fff;
        font-family: 'Jost', Arial, sans-serif;
    }
    .champ { margin-bottom: 20px; }
    .row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }

    table.lignes { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
    table.lignes thead th {
        background: var(--arovia-maroon); color: var(--arovia-cream);
        font-size: 11px; text-transform: uppercase; padding: 8px 10px; text-align: left;
    }
    table.lignes tbody td { padding: 8px 10px; border-bottom: 1px solid #e0d8c5; }
    .btn-suppr-ligne {
        background: transparent; border: none; color: var(--arovia-terracotta);
        font-size: 18px; cursor: pointer;
    }
    .btn-ajout-ligne {
        background: transparent; border: 1px dashed var(--arovia-maroon);
        color: var(--arovia-maroon); padding: 8px 14px; border-radius: 4px;
        cursor: pointer; font-size: 13px; font-weight: 600; margin-bottom: 24px;
    }
    .actions-form { display: flex; justify-content: flex-end; gap: 10px; }
    .btn { padding: 10px 22px; border-radius: 4px; font-size: 14px; font-weight: 600;
        text-decoration: none; border: none; cursor: pointer; }
    .btn-annuler { background: transparent; border: 1px solid var(--arovia-maroon); color: var(--arovia-maroon); }
    .btn-enregistrer { background: var(--arovia-terracotta); color: #fff; }
    .erreurs { background: #fbe4dc; border: 1px solid var(--arovia-terracotta);
        color: #8a2a10; padding: 12px 16px; border-radius: 4px; margin-bottom: 20px; font-size: 13px; }
</style>
</head>
<body>
<div class="conteneur">
    <h1>NOUVELLE FACTURE</h1>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="erreurs"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('errors')): ?>
        <div class="erreurs">
            <?php foreach (session()->getFlashdata('errors') as $err): ?>
                <div><?= esc($err) ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('factures/enregistrer') ?>" method="post" id="form-facture">
        <?= csrf_field() ?>

        <div class="row-2">
            <div class="champ">
                <label for="client_id">Client / Supermarché</label>
                <select name="client_id" id="client_id" required>
                    <option value="">— Sélectionner —</option>
                    <?php foreach ($clients as $client): ?>
                        <option value="<?= $client['id'] ?>" <?= old('client_id') == $client['id'] ? 'selected' : '' ?>>
                            <?= esc($client['nom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="champ">
                <label for="mode_paiement">Mode de paiement</label>
                <select name="mode_paiement" id="mode_paiement">
                    <option value="Cash">Cash</option>
                    <option value="Virement">Virement</option>
                    <option value="Mvola">Mvola</option>
                    <option value="Orange Money">Orange Money</option>
                </select>
            </div>
        </div>

        <label>Articles</label>
        <table class="lignes" id="table-lignes">
            <thead>
                <tr>
                    <th>Bocal</th>
                    <th>Quantité</th>
                    <th>Prix unitaire (Ar)</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="corps-lignes">
                <tr>
                    <td>
                        <select name="type_bocal_id[]" class="select-bocal">
                            <option value="">— Choisir —</option>
                            <?php foreach ($bocaux as $bocal): ?>
                                <option value="<?= $bocal['id'] ?>" data-prix="<?= $bocal['prix_vente'] ?? 0 ?>">
                                    <?= esc($bocal['nom']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td><input type="number" name="quantite[]" min="1" value="1"></td>
                    <td><input type="number" name="prix_unitaire[]" min="0" step="0.01"></td>
                    <td><button type="button" class="btn-suppr-ligne" onclick="supprimerLigne(this)">✕</button></td>
                </tr>
            </tbody>
        </table>

        <button type="button" class="btn-ajout-ligne" onclick="ajouterLigne()">+ Ajouter un article</button>

        <div class="actions-form">
            <a href="<?= base_url('factures') ?>" class="btn btn-annuler">Annuler</a>
            <button type="submit" class="btn btn-enregistrer">Enregistrer la facture</button>
        </div>
    </form>
</div>

<script>
    const bocauxOptions = document.querySelector('.select-bocal').innerHTML;

    function ajouterLigne() {
        const corps = document.getElementById('corps-lignes');
        const ligne = document.createElement('tr');
        ligne.innerHTML = `
            <td><select name="type_bocal_id[]" class="select-bocal">${bocauxOptions}</select></td>
            <td><input type="number" name="quantite[]" min="1" value="1"></td>
            <td><input type="number" name="prix_unitaire[]" min="0" step="0.01"></td>
            <td><button type="button" class="btn-suppr-ligne" onclick="supprimerLigne(this)">✕</button></td>
        `;
        corps.appendChild(ligne);
    }

    function supprimerLigne(btn) {
        const corps = document.getElementById('corps-lignes');
        if (corps.rows.length > 1) {
            btn.closest('tr').remove();
        }
    }

    // Pré-remplissage automatique du prix unitaire selon le bocal choisi
    document.getElementById('table-lignes').addEventListener('change', function (e) {
        if (e.target.classList.contains('select-bocal')) {
            const option = e.target.selectedOptions[0];
            const prix = option ? option.dataset.prix : '';
            const ligne = e.target.closest('tr');
            const champPrix = ligne.querySelector('input[name="prix_unitaire[]"]');
            if (prix && !champPrix.value) {
                champPrix.value = prix;
            }
        }
    });
</script>
</body>
</html>
