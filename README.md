## SMS ALERT

Simulation d'envoi de SMS en tant que log
à une liste de numéros de téléphones assiciés à un code insee

# docs repository
Documents de l'exercice, 
- list.csv, fichier contenant les codes insee et les numéros de télépones


## INSTALL

-Lancement de Docker 
docker compose up -d

-Installation des dépendances
docker exec -it sms_alert composer install

-Migration Doelia
symfony console sql-migrations:execute

-Importer un fichier .csv 
symfony console app:csv-import ./docs/list.csv





## DEBUG

-Conexion au conteneur
docker exec -it sms_alert bash

-Vérifier la connexion à la BDD (depuis le conteneur)
docker exec -it sms_alert php bin/console doctrine:query:sql "SELECT 1"

-Droits sur les fichiers (si necessaire depuis le conteneur) 
chmod -R 777 .



## ADMINER
http://localhost:8081
sms_alert_db
app
app
app