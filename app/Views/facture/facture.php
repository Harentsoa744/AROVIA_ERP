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
        --arovia-text:      #2b2b2b;
    }

    * { box-sizing: border-box; }

    body {
        font-family: 'Jost', Arial, sans-serif;
        background: #ddd8cd;
        color: var(--arovia-text);
        margin: 0;
        padding: 30px;
    }

    .facture-page {
        max-width: 820px;
        margin: 0 auto;
        background: var(--arovia-cream);
        border: 1px solid #cfc6b2;
        box-shadow: 0 6px 24px rgba(0,0,0,0.15);
        padding: 48px 56px;
    }

    /* -- Entête -- */
    .entete {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        border-bottom: 3px solid var(--arovia-maroon);
        padding-bottom: 20px;
        margin-bottom: 30px;
    }

    .logo-bloc { display: flex; align-items: center; gap: 14px; }

    .abeille-pixel {
        width: 46px; height: 40px;
        display: grid;
        grid-template-columns: repeat(11, 1fr);
        grid-template-rows: repeat(9, 1fr);
    }
    .abeille-pixel div.on { background: var(--arovia-maroon); }
    .abeille-pixel div.gold { background: var(--arovia-gold); }

    .marque h1 {
        font-family: 'VT323', monospace;
        font-size: 34px;
        letter-spacing: 3px;
        color: var(--arovia-maroon);
        margin: 0;
        line-height: 1;
    }
    .marque .slogan {
        font-size: 12px;
        font-weight: 600;
        color: var(--arovia-terracotta);
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .facture-info {
        text-align: right;
    }
    .facture-info .num {
        font-family: 'VT323', monospace;
        font-size: 26px;
        color: var(--arovia-maroon);
    }
    .facture-info .date {
        font-size: 13px;
        color: #6b6b6b;
    }
    .statut-badge {
        display: inline-block;
        margin-top: 6px;
        padding: 3px 12px;
        border-radius: 3px;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }
    .statut-PAYE { background: #3f5c2e; color: #eaf1e2; }
    .statut-EN_COURS { background: var(--arovia-terracotta); color: #fff; }

    /* -- Client -- */
    .bloc-client {
        margin-bottom: 30px;
    }
    .bloc-client .label {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--arovia-olive);
        font-weight: 600;
        margin-bottom: 6px;
    }
    .bloc-client .nom-client {
        font-size: 17px;
        font-weight: 600;
        color: var(--arovia-maroon);
    }
    .bloc-client .detail { font-size: 13px; color: #555; }

    /* -- Tableau des articles -- */
    table.articles {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 24px;
    }
    table.articles thead th {
        background: var(--arovia-maroon);
        color: var(--arovia-cream);
        text-align: left;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 10px 12px;
    }
    table.articles thead th.num { text-align: right; }
    table.articles tbody td {
        padding: 10px 12px;
        border-bottom: 1px solid #e0d8c5;
        font-size: 14px;
    }
    table.articles tbody td.num { text-align: right; font-variant-numeric: tabular-nums; }
    table.articles tbody tr:nth-child(even) { background: #ece4d2; }

    /* -- Totaux -- */
    .totaux { width: 280px; margin-left: auto; margin-bottom: 40px; }
    .totaux .ligne {
        display: flex; justify-content: space-between;
        padding: 6px 0; font-size: 14px;
    }
    .totaux .ligne.total {
        border-top: 2px solid var(--arovia-maroon);
        margin-top: 6px; padding-top: 10px;
        font-size: 19px; font-weight: 700;
        color: var(--arovia-maroon);
    }

    /* -- Pied de page -- */
    .pied {
        display: flex; justify-content: space-between; align-items: flex-end;
        border-top: 1px solid #cfc6b2; padding-top: 20px;
    }
    .merci { font-family: 'VT323', monospace; font-size: 22px; color: var(--arovia-olive); }
    .entreprise-sign { text-align: right; font-size: 12px; color: #6b6b6b; }
    .entreprise-sign strong { color: var(--arovia-maroon); font-size: 15px; }

    /* -- Actions (masquées à l'impression) -- */
    .actions {
        max-width: 820px; margin: 20px auto 0; display: flex; gap: 10px; justify-content: flex-end;
    }
    .btn {
        display: inline-block; padding: 9px 18px; border-radius: 4px;
        font-size: 13px; font-weight: 600; text-decoration: none; border: none; cursor: pointer;
    }
    .btn-maroon { background: var(--arovia-maroon); color: #f4efe6; }
    .btn-terracotta { background: var(--arovia-terracotta); color: #fff; }
    .btn-outline { background: transparent; border: 1px solid var(--arovia-maroon); color: var(--arovia-maroon); }

    @media print {
        body { background: #fff; padding: 0; }
        .facture-page { box-shadow: none; border: none; max-width: 100%; }
        .actions { display: none; }
    }
</style>
</head>
<body>

<?php if (session()->getFlashdata('success')): ?>
    <div class="actions" style="justify-content:flex-start;">
        <span style="color:#3f5c2e;font-size:13px;"><?= esc(session()->getFlashdata('success')) ?></span>
    </div>
<?php endif; ?>

<div class="facture-page">

    <div class="entete">
        <div class="logo-bloc">
            <!-- Logo abeille pixel Arovia (peut être remplacé par <img src="<?= base_url('assets/img/arovia-logo.png') ?>">) -->
            <div class="marque"><h1>AROVIA</h1>
                <div class="page-title-wrap">
                  <h1>AROVIA</h1>
                  <img src="/assets/images/Pattern simple - 1.png" alt="Pattern" class="header-pattern-img" style="width: 60%;" />
                </div>
                <div class="slogan">Beelieve in Madagascar</div>
            </div>
        </div>
        <div class="facture-info">
            <div class="num">FACTURE N° <?= str_pad((string) $vente['id'], 5, '0', STR_PAD_LEFT) ?></div>
            <div class="date">Émise le <?= date('d/m/Y', strtotime($vente['date_vente'])) ?></div>
            <span class="statut-badge statut-<?= esc($vente['statut']) ?>">
                <?= $vente['statut'] === 'PAYE' ? 'Payée' : 'En cours' ?>
            </span>
        </div>
    </div>

    <div class="bloc-client">
        <div class="label">Facturé à</div>
        <div class="nom-client"><?= esc($vente['client_nom']) ?></div>
        <?php if (! empty($vente['client_adresse'])): ?>
            <div class="detail"><?= esc($vente['client_adresse']) ?></div>
        <?php endif; ?>
        <?php if (! empty($vente['client_telephone'])): ?>
            <div class="detail"><?= esc($vente['client_telephone']) ?></div>
        <?php endif; ?>
        <?php if (! empty($vente['client_email'])): ?>
            <div class="detail"><?= esc($vente['client_email']) ?></div>
        <?php endif; ?>
    </div>

    <table class="articles">
        <thead>
            <tr>
                <th>Article</th>
                <th class="num">Quantité</th>
                <th class="num">Prix unitaire</th>
                <th class="num">Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($lignes as $ligne): ?>
                <tr>
                    <td>Pot de miel Arovia — <?= esc($ligne['bocal_nom']) ?></td>
                    <td class="num"><?= (int) $ligne['quantite'] ?></td>
                    <td class="num"><?= number_format((float) $ligne['prix_unitaire'], 0, ',', ' ') ?> Ar</td>
                    <td class="num"><?= number_format((float) $ligne['total_ligne'], 0, ',', ' ') ?> Ar</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="totaux">
        <div class="ligne">
            <span>Sous-total</span>
            <span><?= number_format((float) $vente['montant_total'], 0, ',', ' ') ?> Ar</span>
        </div>
        <div class="ligne">
            <span>TVA</span>
            <span>0 Ar</span>
        </div>
        <div class="ligne total">
            <span>Total</span>
            <span><?= number_format((float) $vente['montant_total'], 0, ',', ' ') ?> Ar</span>
        </div>
    </div>

    <div class="pied">
        <div class="merci">Misaotra ! Merci !</div>
        <div class="entreprise-sign">
            <strong>Arovia</strong><br>
            Miel 100% Malagasy — Antananarivo, Madagascar
        </div>
    </div>

</div>

<div class="actions">
    <a href="/sorties" class="btn btn-outline">← Retour</a>

    <?php if ($vente['statut'] !== 'PAYE'): ?>
        <form action="<?= base_url('factures/' . $vente['id'] . '/statut') ?>" method="post" style="display:inline;">
            <?= csrf_field() ?>
            <input type="hidden" name="statut" value="PAYE">
            <button type="submit" class="btn btn-terracotta">Marquer comme payée</button>
        </form>
    <?php endif; ?>

    <button onclick="window.print()" class="btn btn-maroon">🖨 Imprimer</button>
</div>

</body>
</html>
