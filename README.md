## SMS ALERT
Simulation d'envoi de SMS, visibles dans les logs
les destinataires sont une liste de numéros de téléphones associés à un code insee
disponible sous forme d'un fichier .csv 

## NECESSAIRES
- Docker, docker compose

## TECHNOS
- Symfony 6.4, php8.4, Apache, Composer, Symfony CLI, Adminer, Git

## INSTALL
- Lancement de Docker 
docker compose up -d

- Installation des dépendances
docker exec -it sms_alert composer install

- Migration Doelia
symfony console sql-migrations:execute

- Importer un fichier .csv 
symfony console app:csv-import ./docs/list.csv

- Tester l'endpoint alerter/insee/apiKey
curl http://localhost:9000/alerter/75056/1234567890

- Lancer le worker messenger pour envoyer les messages (Ctrl+C pour stopper)
docker exec -it sms_alert php bin/console messenger:consume async -vv

- Consulter les log dans /var/log/sms_dev-YYYY-mm-dd.log (adapter la date)
cat /var/log/sms_dev-*.log
ou en temps réel
tail -f var/log/sms_dev-*.log | grep -a --color=auto "INFO"


## ADMINER
http://localhost:8081
sms_alert_db
app
app
app


## DEBUG
- Conexion au conteneur
docker exec -it sms_alert bash

- Vérifier la connexion à la BDD (depuis le conteneur)
docker exec -it sms_alert php bin/console doctrine:query:sql "SELECT 1"