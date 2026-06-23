<?= view('partials/header') ?>
<h1>Nouvelle transformation</h1>

<?php if (session()->getFlashdata('errors')): ?>
    <ul style="color: red;">
        <?php foreach (session()->getFlashdata('errors') as $error): ?>
            <li><?= esc($error) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<p>Stock disponible : <strong id="stock-disponible"><?= number_format($stockMP['quantite_litres'], 2) ?></strong> L</p>

<form action="/transformations" method="post">
    <?= csrf_field() ?>

    <?php foreach ($typesBocaux as $type): ?>
        <label>
            <?= esc($type['nom']) ?> (<?= $type['volume_litres'] ?> L/bocal — <?= esc($type['cible']) ?>) :
        </label><br>
        <input
            type="number"
            min="0"
            class="qte-bocal"
            name="quantite_<?= $type['id'] ?>"
            data-volume="<?= $type['volume_litres'] ?>"
            value="0"
        ><br><br>
    <?php endforeach; ?>

    <p>Volume total nécessaire : <strong id="volume-necessaire">0.00</strong> L</p>
    <p id="volume-restant-msg"></p>

    <button type="submit">Valider la transformation</button>
</form>

<br>
<a href="/transformations">Retour</a>

<script>
const stockDisponible = <?= $stockMP['quantite_litres'] ?>;
const champs = document.querySelectorAll('.qte-bocal');
const volumeNecessaireEl = document.getElementById('volume-necessaire');
const messageEl = document.getElementById('volume-restant-msg');

function recalculer() {
    let total = 0;
    champs.forEach(champ => {
        const quantite = parseFloat(champ.value) || 0;
        const volume = parseFloat(champ.dataset.volume);
        total += quantite * volume;
    });

    volumeNecessaireEl.textContent = total.toFixed(2);

    const restant = stockDisponible - total;
    if (restant < 0) {
        messageEl.textContent = 'Stock insuffisant ! Il manque ' + Math.abs(restant).toFixed(2) + ' L.';
        messageEl.style.color = 'red';
    } else {
        messageEl.textContent = 'Il restera ' + restant.toFixed(2) + ' L après cette transformation.';
        messageEl.style.color = 'green';
    }
}

champs.forEach(champ => champ.addEventListener('input', recalculer));
</script>