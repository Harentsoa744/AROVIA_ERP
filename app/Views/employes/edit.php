<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier employé</title>
</head>
<body>

<h1>Modifier employé</h1>

<form method="post" action="<?= base_url('employes/update/'.$employe['id']) ?>">

    <input type="text" name="nom" value="<?= $employe['nom'] ?>"><br>
    <input type="text" name="prenom" value="<?= $employe['prenom'] ?>"><br>
    <input type="text" name="telephone" value="<?= $employe['telephone'] ?>"><br>
    <input type="email" name="email" value="<?= $employe['email'] ?>"><br>
    <input type="text" name="poste" value="<?= $employe['poste'] ?>"><br>
    <input type="number" name="salaire_base" value="<?= $employe['salaire_base'] ?>"><br>

    <select name="statut">
        <option value="ACTIF">ACTIF</option>
        <option value="INACTIF">INACTIF</option>
    </select>

    <button type="submit">Modifier</button>

</form>

</body>
</html>