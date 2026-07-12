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
        max-width: 1000px; margin: 0 auto;
        background: var(--arovia-cream);
        border-radius: 6px;
        padding: 36px 44px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.25);
    }
    .entete-page {
        display: flex; justify-content: space-between; align-items: center;
        border-bottom: 3px solid var(--arovia-maroon);
        padding-bottom: 18px; margin-bottom: 24px;
    }
    h1 {
        font-family: 'VT323', monospace;
        font-size: 32px; letter-spacing: 2px;
        color: var(--arovia-maroon);
        margin: 0;
    }
    .btn-nouvelle {
        background: var(--arovia-terracotta); color: #fff;
        padding: 10px 20px; border-radius: 4px;
        text-decoration: none; font-weight: 600; font-size: 14px;
    }
    table { width: 100%; border-collapse: collapse; }
    thead th {
        background: var(--arovia-maroon); color: var(--arovia-cream);
        text-align: left; padding: 10px 14px; font-size: 12px;
        text-transform: uppercase; letter-spacing: 0.5px;
    }
    thead th.num { text-align: right; }
    tbody td {
        padding: 12px 14px; border-bottom: 1px solid #e0d8c5; font-size: 14px;
    }
    tbody td.num { text-align: right; font-variant-numeric: tabular-nums; }
    tbody tr:hover { background: #ece4d2; }
    .lien-facture { color: var(--arovia-maroon); font-weight: 600; text-decoration: none; }
    .lien-facture:hover { text-decoration: underline; }
    .badge {
        padding: 3px 10px; border-radius: 3px; font-size: 11px;
        font-weight: 600; text-transform: uppercase;
    }
    .badge-PAYE { background: #3f5c2e; color: #eaf1e2; }
    .badge-EN_COURS { background: var(--arovia-terracotta); color: #fff; }
    .flash-success { color: #3f5c2e; margin-bottom: 16px; font-size: 14px; }
    .flash-error { color: var(--arovia-terracotta); margin-bottom: 16px; font-size: 14px; }
    .vide { text-align: center; padding: 40px; color: #8a8270; }
</style>
</head>
<body>
<div class="conteneur">

    <?php if (session()->getFlashdata('success')): ?>
        <div class="flash-success"><?= esc(session()->getFlashdata('success')) ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="flash-error"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <div class="entete-page"><h1>FACTURES DE VENTE</h1>
        <div class="page-title-wrap">
          <h1>FACTURES DE VENTE</h1>
          <img src="/assets/images/Pattern simple - 1.png" alt="Pattern" class="header-pattern-img" />
        </div>
        <a href="<?= base_url('factures/creer') ?>" class="btn-nouvelle">+ Nouvelle facture</a>
    </div>

    <?php if (empty($ventes)): ?>
        <div class="vide">Aucune facture enregistrée pour le moment.</div>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>N° Facture</th>
                    <th>Client</th>
                    <th>Date</th>
                    <th class="num">Montant</th>
                    <th>Statut</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ventes as $vente): ?>
                    <tr>
                        <td>#<?= str_pad((string) $vente['id'], 5, '0', STR_PAD_LEFT) ?></td>
                        <td><?= esc($vente['client_nom']) ?></td>
                        <td><?= date('d/m/Y', strtotime($vente['date_vente'])) ?></td>
                        <td class="num"><?= number_format((float) $vente['montant_total'], 0, ',', ' ') ?> Ar</td>
                        <td>
                            <span class="badge badge-<?= esc($vente['statut']) ?>">
                                <?= $vente['statut'] === 'PAYE' ? 'Payée' : 'En cours' ?>
                            </span>
                        </td>
                        <td>
                            <a class="lien-facture" href="<?= base_url('factures/' . $vente['id']) ?>">Voir →</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

</div>
</body>
</html>
