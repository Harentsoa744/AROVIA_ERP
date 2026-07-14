Installation necessaires:
    -PHP version 8.2 ou superieur
    -postgreSql
    -dompdf/dompdf(composer require dompdf/dompdf)

Etape pour lancer le projet

    -creer la base gestion_miel
    -y lancer fusion.sql et donnees_test_clean.sql
            ou
    -lancer directement all_in_one.sql avec 

    *psql -U postgres -d gestion_miel -f all_in_one.sql

    lancement app:

    -php -S localhost:9090 -t public


# CodeIgniter 4 Framework

PHP version 8.2 or higher is required, with the following extensions installed:

