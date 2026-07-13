<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Contrat #<?= esc($contrat['id']) ?></title>
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

    .contrat-info {
        text-align: right;
    }
    .contrat-info .num {
        font-family: 'VT323', monospace;
        font-size: 26px;
        color: var(--arovia-maroon);
    }
    .contrat-info .date {
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
        background: var(--arovia-terracotta);
        color: #fff;
    }

    /* -- Entreprise -- */
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

    /* -- Tableau des informations -- */
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
    table.articles tbody td {
        padding: 10px 12px;
        border-bottom: 1px solid #e0d8c5;
        font-size: 14px;
    }
    table.articles tbody tr:nth-child(even) { background: #ece4d2; }

    /* -- Section description -- */
    .section-titre {
        margin-top: 30px;
        margin-bottom: 15px;
        font-size: 15px;
        font-weight: 600;
        color: var(--arovia-maroon);
        text-transform: uppercase;
        letter-spacing: 1px;
        border-bottom: 2px solid var(--arovia-maroon);
        padding-bottom: 8px;
    }
    .description-content {
        font-size: 14px;
        line-height: 1.6;
        color: #555;
        margin-bottom: 30px;
    }

    /* -- Pied de page -- */
    .pied {
        display: flex; justify-content: space-between; align-items: flex-end;
        border-top: 1px solid #cfc6b2; padding-top: 20px;
    }
    .merci { font-family: 'VT323', monospace; font-size: 22px; color: var(--arovia-olive); }
    .entreprise-sign { text-align: right; font-size: 12px; color: #6b6b6b; }
    .entreprise-sign strong { color: var(--arovia-maroon); font-size: 15px; }

    @media print {
        body { background: #fff; padding: 0; }
        .facture-page { box-shadow: none; border: none; max-width: 100%; }
    }
</style>
</head>
<body>

<div class="facture-page">

    <div class="entete">
        <div class="logo-bloc">
            <div class="marque">
                <div class="page-title-wrap">
                  <h1>AROVIA</h1>
                  <img src="/assets/images/Pattern simple - 1.png" alt="Pattern" class="header-pattern-img" style="width: 60%;" />
                </div>
                <div class="slogan">Beelieve in Madagascar</div>
            </div>
        </div>
        <div class="contrat-info">
            <div class="num">CONTRAT N° <?= str_pad((string) $contrat['id'], 5, '0', STR_PAD_LEFT) ?></div>
            <div class="date">Créé le <?= date('d/m/Y', strtotime($contrat['date_creation'])) ?></div>
            <span class="statut-badge"><?= esc($contrat['statut_nom']) ?></span>
        </div>
    </div>

    <div class="bloc-client">
        <div class="label">Entreprise</div>
        <div class="nom-client"><?= esc($contrat['entreprise_nom']) ?></div>
        <?php if (! empty($contrat['entreprise_telephone'])): ?>
            <div class="detail"><?= esc($contrat['entreprise_telephone']) ?></div>
        <?php endif; ?>
        <?php if (! empty($contrat['entreprise_email'])): ?>
            <div class="detail"><?= esc($contrat['entreprise_email']) ?></div>
        <?php endif; ?>
    </div>

    <table class="articles">
        <thead>
            <tr>
                <th>Information</th>
                <th>Détail</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Sujet</td>
                <td><?= esc($contrat['sujet']) ?></td>
            </tr>
            <tr>
                <td>Date de signature</td>
                <td><?= esc($contrat['date_signature'] ?? '-') ?></td>
            </tr>
            <tr>
                <td>Date d'expiration</td>
                <td><?= esc($contrat['date_expiration'] ?? '-') ?></td>
            </tr>
        </tbody>
    </table>

    <div class="section-titre">Description</div>
    <div class="description-content"><?= nl2br(esc($contrat['description'] ?? '-')) ?></div>

    <div class="pied">
        <div class="merci">Misaotra ! Merci !</div>
        <div class="entreprise-sign">
            <strong>Arovia</strong><br>
            Miel 100% Malagasy — Antananarivo, Madagascar
        </div>
    </div>

</div>

</body>
</html>
