<?= view('partials/header') ?>
<h1>Transformations (mise en bocal)</h1>

<?php if (session()->getFlashdata('message')): ?>
    <p style="color: green;"><?= session()->getFlashdata('message') ?></p>
<?php endif; ?>

<div style="border: 1px solid #ccc; padding: 12px; margin-bottom: 20px; width: 350px;">
    <h3>Stock matière première</h3>
    <p><?= number_format($stockMP['quantite_litres'], 2) ?> L disponibles</p>
    <p>CUMP actuel : <?= number_format($stockMP['cump_actuel'], 2) ?> Ar/L</p>
</div>

<div style="border: 1px solid #ccc; padding: 12px; margin-bottom: 20px; width: 350px;">
    <h3>Stock produit fini</h3>
    <?php foreach ($stockPF as $s): ?>
        <p><?= esc($s['nom']) ?> (<?= esc($s['cible']) ?>) : <strong><?= $s['quantite_disponible'] ?></strong> bocaux</p>
    <?php endforeach; ?>
</div>

<a href="/transformations/new">Faire une nouvelle transformation</a>