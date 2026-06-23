<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails employé — <?= $employe['prenom'] ?> <?= $employe['nom'] ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Sora:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/show.css">
</head>
<body>

<div class="page-wrapper">

    <!-- NAV RETOUR -->
    <nav class="breadcrumb">
        <a href="/employes" class="back-link">
            <svg viewBox="0 0 20 20" fill="none">
                <path d="M12 15l-5-5 5-5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Retour à la liste
        </a>
    </nav>

    <!-- HERO -->
    <div class="hero">
        <div class="hero-bg"></div>
        <div class="hero-content">

            <div class="avatar-wrap">
                <?php if (!empty($employe['photo'])): ?>
                    <img src="<?= $employe['photo'] ?>" alt="Photo de <?= $employe['prenom'] ?>" class="avatar-img">
                <?php else: ?>
                    <div class="avatar-initials">
                        <?= strtoupper(substr($employe['prenom'],0,1) . substr($employe['nom'],0,1)) ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="hero-info">
                <span class="hero-matricule"><?= $employe['matricule'] ?></span>
                <h1><?= $employe['prenom'] ?> <?= $employe['nom'] ?></h1>
                <p class="hero-poste"><?= $employe['poste'] ?></p>
                <div class="hero-meta">
                    <span class="hero-dept">
                        <svg viewBox="0 0 16 16" fill="none">
                            <path d="M2 13V6l6-4 6 4v7H2z" stroke="currentColor" stroke-width="1.3" stroke-linejoin="round"/>
                            <rect x="6" y="9" width="4" height="4" rx=".5" stroke="currentColor" stroke-width="1.3"/>
                        </svg>
                        <?= $employe['departement'] ?? 'Département' ?>
                    </span>
                    <span class="status-pill <?= strtolower($employe['statut']) ?>">
                        <?= $employe['statut'] ?>
                    </span>
                </div>
            </div>

            <div class="hero-actions">
                <a href="/employes/edit/<?= $employe['id'] ?>" class="btn-hero btn-edit">
                    <svg viewBox="0 0 20 20" fill="none">
                        <path d="M14.5 3.5l2 2L7 15H5v-2L14.5 3.5z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
                    </svg>
                    Modifier
                </a>
                <a href="/employes/delete/<?= $employe['id'] ?>"
                   class="btn-hero btn-delete"
                   onclick="return confirm('Supprimer cet employé définitivement ?')">
                    <svg viewBox="0 0 20 20" fill="none">
                        <path d="M5 7h10l-1 9H6L5 7z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
                        <path d="M3 7h14M8 7V5h4v2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                    Supprimer
                </a>
            </div>

        </div>
    </div><!-- /hero -->

    <!-- GRILLE PRINCIPALE -->
    <div class="main-grid">

        <!-- COLONNE GAUCHE : informations -->
        <div class="col-left">

            <!-- Informations personnelles -->
            <section class="card">
                <h2 class="card-title">
                    <svg viewBox="0 0 20 20" fill="none">
                        <circle cx="10" cy="7" r="3.5" stroke="currentColor" stroke-width="1.5"/>
                        <path d="M3 17c0-3.3 3.1-6 7-6s7 2.7 7 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                    Informations personnelles
                </h2>
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">Nom complet</span>
                        <span class="info-value"><?= $employe['prenom'] ?> <?= $employe['nom'] ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Date de naissance</span>
                        <span class="info-value"><?= isset($employe['date_naissance']) ? date('d/m/Y', strtotime($employe['date_naissance'])) : '—' ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">CIN</span>
                        <span class="info-value"><?= $employe['cin'] ?? '—' ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Genre</span>
                        <span class="info-value"><?= $employe['genre'] ?? '—' ?></span>
                    </div>
                    <div class="info-item info-item--full">
                        <span class="info-label">Adresse</span>
                        <span class="info-value"><?= $employe['adresse'] ?? '—' ?></span>
                    </div>
                </div>
            </section>

            <!-- Informations professionnelles -->
            <section class="card">
                <h2 class="card-title">
                    <svg viewBox="0 0 20 20" fill="none">
                        <rect x="3" y="6" width="14" height="11" rx="1.5" stroke="currentColor" stroke-width="1.5"/>
                        <path d="M7 6V4.5A1.5 1.5 0 018.5 3h3A1.5 1.5 0 0113 4.5V6" stroke="currentColor" stroke-width="1.5"/>
                        <path d="M3 10h14" stroke="currentColor" stroke-width="1.5" stroke-dasharray="2 2"/>
                    </svg>
                    Informations professionnelles
                </h2>
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">Matricule</span>
                        <span class="info-value mono"><?= $employe['matricule'] ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Poste</span>
                        <span class="info-value"><?= $employe['poste'] ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Département</span>
                        <span class="info-value"><?= $employe['departement'] ?? '—' ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Date d'embauche</span>
                        <span class="info-value"><?= isset($employe['date_embauche']) ? date('d/m/Y', strtotime($employe['date_embauche'])) : '—' ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Type de contrat</span>
                        <span class="info-value"><?= $employe['type_contrat'] ?? '—' ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Salaire de base</span>
                        <span class="info-value salary"><?= number_format($employe['salaire_base'], 0, ',', ' ') ?> Ar</span>
                    </div>
                </div>
            </section>

            <!-- Coordonnées -->
            <section class="card">
                <h2 class="card-title">
                    <svg viewBox="0 0 20 20" fill="none">
                        <path d="M14.5 13.5l-2.5 2.5a11 11 0 01-8-8l2.5-2.5L5 2H2a1 1 0 00-1 1 15 15 0 0016 16 1 1 0 001-1v-3l-3.5-1.5z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/>
                    </svg>
                    Coordonnées
                </h2>
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">Téléphone</span>
                        <span class="info-value">
                            <a href="tel:<?= $employe['telephone'] ?>"><?= $employe['telephone'] ?></a>
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Email</span>
                        <span class="info-value">
                            <a href="mailto:<?= $employe['email'] ?>"><?= $employe['email'] ?></a>
                        </span>
                    </div>
                </div>
            </section>

        </div><!-- /col-left -->

        <!-- COLONNE DROITE : paiements + planning -->
        <div class="col-right">

            <!-- Paiements -->
            <section class="card card--flush">
                <h2 class="card-title card-title--padded">
                    <svg viewBox="0 0 20 20" fill="none">
                        <rect x="2" y="5" width="16" height="11" rx="1.5" stroke="currentColor" stroke-width="1.5"/>
                        <path d="M2 9h16" stroke="currentColor" stroke-width="1.5"/>
                        <circle cx="6" cy="13" r="1" fill="currentColor"/>
                    </svg>
                    Historique des paiements
                    <span class="count-badge"><?= count($paiements) ?></span>
                </h2>

                <?php if (empty($paiements)): ?>
                    <p class="empty-msg">Aucun paiement enregistré.</p>
                <?php else: ?>
                <div class="table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Période</th>
                                <th>Brut</th>
                                <th>Net payé</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($paiements as $p): ?>
                            <tr>
                                <td class="period">
                                    <?= str_pad($p['mois'], 2, '0', STR_PAD_LEFT) ?>/<?= $p['annee'] ?>
                                </td>
                                <td><?= number_format($p['salaire_brut'] ?? $employe['salaire_base'], 0, ',', ' ') ?> Ar</td>
                                <td class="amount"><?= number_format($p['montant_paye'], 0, ',', ' ') ?> Ar</td>
                                <td>
                                    <span class="pay-status <?= strtolower($p['statut'] ?? 'paye') ?>">
                                        <?= $p['statut'] ?? 'Payé' ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </section>

            <!-- Planning -->
            <section class="card">
                <h2 class="card-title">
                    <svg viewBox="0 0 20 20" fill="none">
                        <rect x="3" y="4" width="14" height="13" rx="1.5" stroke="currentColor" stroke-width="1.5"/>
                        <path d="M7 2v4M13 2v4M3 9h14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                    Planning
                    <span class="count-badge"><?= count($planning) ?></span>
                </h2>

                <?php if (empty($planning)): ?>
                    <p class="empty-msg">Aucun événement dans le planning.</p>
                <?php else: ?>
                <div class="timeline">
                    <?php foreach ($planning as $pl): ?>
                    <div class="timeline-item">
                        <span class="event-dot event-<?= strtolower(str_replace(' ', '-', $pl['type_evenement'])) ?>"></span>
                        <div class="event-body">
                            <div class="event-header">
                                <span class="event-type"><?= $pl['type_evenement'] ?></span>
                                <?php if (!empty($pl['date_debut'])): ?>
                                <span class="event-date">
                                    <?= date('d/m/Y', strtotime($pl['date_debut'])) ?>
                                    <?php if (!empty($pl['date_fin'])): ?>
                                        → <?= date('d/m/Y', strtotime($pl['date_fin'])) ?>
                                    <?php endif; ?>
                                </span>
                                <?php endif; ?>
                            </div>
                            <p class="event-desc"><?= $pl['description'] ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </section>

        </div><!-- /col-right -->

    </div><!-- /main-grid -->

</div><!-- /page-wrapper -->

</body>
</html>
