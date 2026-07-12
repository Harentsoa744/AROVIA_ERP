<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #222222;
            font-size: 13px;
        }
        h1 {
            color: #D4A017;
            border-bottom: 2px solid #D4A017;
            padding-bottom: 8px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        td {
            padding: 6px 4px;
            vertical-align: top;
        }
        .etiquette {
            font-weight: bold;
            width: 160px;
            color: #555555;
        }
        .section-titre {
            margin-top: 20px;
            color: #D4A017;
            font-size: 15px;
            border-bottom: 1px solid #dddddd;
            padding-bottom: 4px;
        }
    </style>
</head>
<body><h1>Contrat #<?= esc($contrat['id']) ?> — <?= esc($contrat['sujet']) ?></h1>
    <div class="page-title-wrap">
      <h1>Contrat #<?= esc($contrat['id']) ?> — <?= esc($contrat['sujet']) ?></h1>
      <img src="/assets/images/Pattern simple - 1.png" alt="Pattern" class="header-pattern-img" />
    </div>

    <table>
        <tr><td class="etiquette">Entreprise</td><td><?= esc($contrat['entreprise_nom']) ?></td></tr>
        <tr><td class="etiquette">Téléphone</td><td><?= esc($contrat['entreprise_telephone'] ?? '-') ?></td></tr>
        <tr><td class="etiquette">Email</td><td><?= esc($contrat['entreprise_email'] ?? '-') ?></td></tr>
        <tr><td class="etiquette">Statut</td><td><?= esc($contrat['statut_nom']) ?></td></tr>
        <tr><td class="etiquette">Date de création</td><td><?= esc($contrat['date_creation']) ?></td></tr>
        <tr><td class="etiquette">Date de signature</td><td><?= esc($contrat['date_signature'] ?? '-') ?></td></tr>
        <tr><td class="etiquette">Date d'expiration</td><td><?= esc($contrat['date_expiration'] ?? '-') ?></td></tr>
    </table>

    <div class="section-titre">Description</div>
    <p><?= nl2br(esc($contrat['description'] ?? '-')) ?></p>

</body>
</html>
