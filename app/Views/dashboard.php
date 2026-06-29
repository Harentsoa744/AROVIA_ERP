<h1>Gestion de stock — Miel Arovia</h1>

<div style="display: flex; gap: 20px; margin-bottom: 30px; flex-wrap: wrap;">
    <div style="border: 1px solid #ccc; padding: 16px; width: 200px;">
        <h4>Stock matière première</h4>
        <p style="font-size: 24px; margin: 0;"><?= number_format($stockMP['quantite_litres'], 2) ?> L</p>
    </div>

    <div style="border: 1px solid #ccc; padding: 16px; width: 200px;">
        <h4>Bocaux disponibles</h4>
        <p style="font-size: 24px; margin: 0;"><?= $totalBocaux ?></p>
    </div>

    <div style="border: 1px solid #ccc; padding: 16px; width: 200px;">
        <h4>CUMP actuel</h4>
        <p style="font-size: 24px; margin: 0;"><?= number_format($stockMP['cump_actuel'], 2) ?> Ar/L</p>
    </div>

    <div style="border: 1px solid #ccc; padding: 16px; width: 200px;">
        <h4>Fournisseurs</h4>
        <p style="font-size: 24px; margin: 0;"><?= $nbFournisseurs ?></p>
    </div>
</div>

<h3>Modules</h3>
<ul style="line-height: 2;">
    <li><a href="/fournisseurs">Fournisseurs</a></li>
    <li><a href="/entrees-matiere-premiere">Entrées matière première</a></li>
    <li><a href="/transformations">Transformations (mise en bocal)</a></li>
    <li><a href="/sorties">Sorties (ventes)</a></li>
    <li><a href="/valeur-stock">Valeur du stock</a></li>
    <li><a href="/statistiques">Statistiques</a></li>
    <li><a href="/finances">Finances</a></li>
    <li><a href="/statistiques/vente">Statistique finances</a></li>
</ul>