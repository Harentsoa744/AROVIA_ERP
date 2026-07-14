<?php
$host = '127.0.0.1';
$db = 'gestion_miel';
$user = 'postgres';
$pass = '1234';

$dsn = "pgsql:host=$host;port=5432;dbname=$db;user=$user;password=$pass";

try {
    $pdo = new PDO($dsn);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $sql = "
    CREATE TABLE IF NOT EXISTS historique_livraison (
        id                  SERIAL PRIMARY KEY,
        livraison_id        INTEGER REFERENCES livraisons(id) ON DELETE CASCADE,
        statut_precedent    VARCHAR(20),
        statut_nouveau      VARCHAR(20) NOT NULL,
        date_changement     TIMESTAMP NOT NULL DEFAULT NOW(),
        utilisateur_id      INTEGER,
        commentaire         TEXT
    );";
    
    $pdo->exec($sql);
    echo "Table created successfully.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
