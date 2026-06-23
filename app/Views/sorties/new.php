<?= view('partials/header') ?>
<h1>Enregistrer une vente</h1>

<?php if (session()->getFlashdata('errors')): ?>
    <ul style="color: red;">
        <?php foreach (session()->getFlashdata('errors') as $error): ?>
            <li><?= esc($error) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form action="/sorties" method="post">
    <?= csrf_field() ?>

    <label>Type de bocal :</label><br>
    <select name="type_bocal_id" id="type-bocal-select">
        <?php foreach ($typesBocaux as $type): ?>
            <option
                value="<?= $type['id'] ?>"
                data-prix-vente="<?= $type['prix_vente'] ?>"
                data-volume="<?= $type['volume_litres'] ?>"
            >
                <?= esc($type['nom']) ?> (<?= esc($type['cible']) ?>)
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <label>Quantité :</label><br>
    <input type="number" min="1" name="quantite" id="quantite-input" value="<?= old('quantite') ?>"><br><br>

    <label>Destinataire :</label><br>
    <select name="destinataire_type">
        <option value="touriste">Touriste</option>
        <option value="particulier">Particulier</option>
        <option value="hotel">Hôtel</option>
    </select><br><br>

    <label>Nom du destinataire (optionnel) :</label><br>
    <input type="text" name="destinataire_nom" value="<?= old('destinataire_nom') ?>"><br><br>

    <label>Prix de vente unitaire (Ar) :</label><br>
    <input type="number" step="0.01" name="prix_vente_unitaire" id="prix-input" value="<?= old('prix_vente_unitaire') ?>"><br>
    <small id="cump-info" style="color: #666;"></small><br><br>

    <button type="submit">Valider la vente</button>
</form>

<br>
<a href="/sorties">Retour</a>

<script>
const cumpActuel = <?= $stockMP['cump_actuel'] ?? 0 ?>; // coût matière première par litre

const select = document.getElementById('type-bocal-select');
const prixInput = document.getElementById('prix-input');
const cumpInfo = document.getElementById('cump-info');

function majPrixEtCout() {
    const option = select.options[select.selectedIndex];
    const prixVente = parseFloat(option.dataset.prixVente) || 0;
    const volume = parseFloat(option.dataset.volume) || 0;

    prixInput.value = prixVente;

    const coutMatierePremiere = (cumpActuel * volume).toFixed(2);
    cumpInfo.textContent = 'Coût matière première estimé : ' + coutMatierePremiere + ' Ar/bocal';
}

select.addEventListener('change', majPrixEtCout);
majPrixEtCout(); // au chargement
</script>