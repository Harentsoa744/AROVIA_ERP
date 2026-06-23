<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails employé</title>
</head>
<body>

<div class="container">

    <h1>👤 Détails Employé</h1>

    <p><strong>Matricule:</strong> <?= $employe['matricule'] ?></p>
    <p><strong>Nom:</strong> <?= $employe['nom'] ?></p>
    <p><strong>Prénom:</strong> <?= $employe['prenom'] ?></p>
    <p><strong>Poste:</strong> <?= $employe['poste'] ?></p>
    <p><strong>Téléphone:</strong> <?= $employe['telephone'] ?></p>
    <p><strong>Email:</strong> <?= $employe['email'] ?></p>
    <p><strong>Salaire:</strong> <?= $employe['salaire_base'] ?> Ar</p>

    <hr>

    <h2>💰 Paiements</h2>
    <ul>
        <?php foreach ($paiements as $p): ?>
            <li>
                <?= $p['mois'] ?>/<?= $p['annee'] ?> - <?= $p['montant_paye'] ?> Ar
            </li>
        <?php endforeach; ?>
    </ul>

    <hr>

    <h2>📅 Planning</h2>
    <ul>
        <?php foreach ($planning as $pl): ?>
            <li>
                <?= $pl['type_evenement'] ?> : <?= $pl['description'] ?>
            </li>
        <?php endforeach; ?>
    </ul>

</div>

</body>
</html>