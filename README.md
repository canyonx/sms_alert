# SMS ALERT
Application backend qui permet d’importer des destinataires depuis un 
fichier CSV (docs.list.csv) et d’envoyer des alertes par SMS à des particuliers. 
Pour simplifier l’envoi, l’API SMS sera simulée via un service qui se contentera 
de logger chaque envoi (var/log/sms_dev*.log). 
La file d’envoi sera gérée par Symfony Messenger.

## NECESSAIRES
- Docker, docker compose

## TECHNOS
- Symfony 6.4, php8.4, Apache, Composer, Symfony CLI, Adminer, Git

## INSTALL
- Construction de l'image docker sans utiliser le cache  
`docker compose build --no-cache`

- Lancement de Docker  
`docker compose up -d`

- Installation des dépendances  
`docker exec -it sms_alert composer install`

- Migration Doelia  
`symfony console sql-migrations:execute`

- Importer un fichier .csv  
`symfony console app:csv-import ./docs/list.csv`

- Tester l'endpoint http://localhost:9000/alerter/insee/apiKey  
Depuis un navigateur web  
OU  
`curl http://localhost:9000/alerter/75056/1234567890`

- Lancer le worker messenger pour envoyer les messages (Ctrl+C pour stopper)  
`docker exec -it sms_alert php bin/console messenger:consume async -vv`

- Consulter les log dans /var/log/sms_dev-YYYY-mm-dd.log (adapter la date)  
`cat ./var/log/sms_dev-*.log`  
ou en temps réel  
`tail -f var/log/sms_dev-*.log | grep -a --color=auto "INFO"`

- Destruction de l'image docker et suppression des volumes  
`docker compose down -v`

## ADMINER
http://localhost:8081  
Système	: PostgreSQL  
Serveur	: sms_alert_db  
Utilisateur	: app  
Mot de passe : app  
Base de données	: app  

## DEBUG
- Conexion au conteneur  
`docker exec -it sms_alert bash`

- Vérifier la connexion à la BDD (depuis le conteneur)  
`docker exec -it sms_alert php bin/console doctrine:query:sql "SELECT 1"`