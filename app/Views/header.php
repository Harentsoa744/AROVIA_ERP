<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($titre ?? 'Gestion des contrats') ?></title>
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>
<body>

    <nav class="navbar">
        <div class="navbar-brand">Gestion des contrats</div>
        <ul class="navbar-links">
            <li><a href="<?= base_url('contrat') ?>">Contrats</a></li>
            <li><a href="<?= base_url('entreprise') ?>">Entreprises</a></li>
        </ul>
    </nav>

    <main class="conteneur">

        <?php if (session()->getFlashdata('succes')) : ?>
            <div class="message message-succes"><?= esc(session()->getFlashdata('succes')) ?></div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('erreur')) : ?>
            <div class="message message-erreur"><?= esc(session()->getFlashdata('erreur')) ?></div>
        <?php endif; ?>
