<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter employé</title>
</head>
<body>

<h1>➕ Ajouter un employé</h1>

<form method="post" action="<?= base_url('employes/store') ?>">

    <input type="text" name="matricule" placeholder="Matricule"><br>
    <input type="text" name="nom" placeholder="Nom"><br>
    <input type="text" name="prenom" placeholder="Prénom"><br>
    <input type="text" name="telephone" placeholder="Téléphone"><br>
    <input type="email" name="email" placeholder="Email"><br>
    <input type="text" name="poste" placeholder="Poste"><br>
    <input type="number" name="salaire_base" placeholder="Salaire"><br>
    <input type="date" name="date_embauche"><br>

    <button type="submit">Enregistrer</button>

</form>

</body>
</html>