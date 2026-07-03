<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Distribution & Livraisons</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Sora:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/liste.css"> </head>
<body>
<div class="page-wrapper">

    <header class="page-header">
        <div class="header-left">
            <h1>Distribution en cours</h1>
            <span class="employee-count">Suivi temps réel</span>
        </div>
        <div>
            <a href="/livraisons/historique" class="btn-cancel" style="margin-right: 10px;">📋 Historique Global</a>
            <a href="/livraisons/create" class="btn-add"><span class="btn-icon">+</span> Nouvelle Livraison</a>
        </div>
    </header>

    <div class="main-grid" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 30px;">
        <div class="employee-card" style="padding: 20px; text-align: center;">
            <h3 style="font-size: 14px; color: #a0aec0;">⏳ En attente</h3>
            <p style="font-size: 28px; font-weight: bold; font-family: 'Sora'; color: #ecc94b;"><?= $stats['en_attente'] ?></p>
        </div>
        <div class="employee-card" style="padding: 20px; text-align: center;">
            <h3 style="font-size: 14px; color: #a0aec0;">🚴 En cours</h3>
            <p style="font-size: 28px; font-weight: bold; font-family: 'Sora'; color: #3182ce;"><?= $stats['en_cours'] ?></p>
        </div>
        <div class="employee-card" style="padding: 20px; text-align: center;">
            <h3 style="font-size: 14px; color: #a0aec0;">✅ Effectuées</h3>
            <p style="font-size: 28px; font-weight: bold; font-family: 'Sora'; color: #38a169;"><?= $stats['effectuees'] ?></p>
        </div>
        <div class="employee-card" style="padding: 20px; text-align: center;">
            <h3 style="font-size: 14px; color: #a0aec0;">❌ Annulées</h3>
            <p style="font-size: 28px; font-weight: bold; font-family: 'Sora'; color: #e53e3e;"><?= $stats['annulees'] ?></p>
        </div>
    </div>

    <h2>🚀 Livraisons Actives (Aujourd'hui)</h2>
    <div class="cards-grid" style="margin-bottom: 40px;">
        <?php if(empty($livraisons_en_cours)): ?>
            <p style="color: #a0aec0;">Aucune livraison active pour le moment.</p>
        <?php endif; ?>
        <?php foreach ($livraisons_en_cours as $liv): ?>
            <div class="employee-card">
                <div class="card-top">
                    <span class="emp-matricule">ID Vente : #<?= $liv['vente_id'] ?></span>
                    <span class="status-badge <?= strtolower($liv['statut']) ?>"><?= $liv['statut'] ?></span>
                </div>
                <div class="card-body" style="margin-top: 15px;">
                    <h2 class="emp-name" style="font-size: 16px;"><?= htmlspecialchars($liv['adresse_livraison']) ?></h2>
                    <p class="emp-poste">Livreur : <strong><?= $liv['livreur_nom'] ?? 'Non assigné' ?></strong></p>
                    <p style="font-size: 12px; color: #a0aec0; margin-top: 5px;">Prévu : <?= date('d/m H:i', strtotime($liv['date_prevue'])) ?></p>
                </div>
                <div class="card-actions" style="margin-top: 20px; display: flex; gap: 5px;">
                    <?php if($liv['statut'] === 'EN_ATTENTE'): ?>
                        <a href="/livraisons/status/<?= $liv['id'] ?>/en_cours" class="btn-action btn-view" style="background: #3182ce; color: #fff;">Lancer</a>
                    <?php endif; ?>
                    <a href="/livraisons/status/<?= $liv['id'] ?>/effectuer" class="btn-action btn-edit" style="background: #38a169; color: #fff;">Terminer</a>
                    <a href="/livraisons/status/<?= $liv['id'] ?>/annulee" class="btn-action btn-delete" onclick="return confirm('Annuler la livraison ?')">Annuler</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <h2>✅ Validées aujourd'hui</h2>
    <div class="cards-grid">
        <?php foreach ($livraisons_faites as $liv): ?>
            <div class="employee-card" style="opacity: 0.85;">
                <div class="card-top">
                    <span class="emp-matricule">Vente #<?= $liv['vente_id'] ?></span>
                    <span class="status-badge effectuee">EFFECTUÉE</span>
                </div>
                <div class="card-body" style="margin-top: 15px;">
                    <h2 class="emp-name" style="font-size: 16px; text-decoration: line-through; color: #718096;"><?= htmlspecialchars($liv['adresse_livraison']) ?></h2>
                    <p class="emp-poste">Livreur : <?= $liv['livreur_nom'] ?></p>
                    <p style="font-size: 12px; color: #48bb78;">Livré à : <?= date('H:i', strtotime($liv['date_effective'])) ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

</div>
</body>
</html>