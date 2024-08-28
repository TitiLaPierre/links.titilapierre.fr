# Liens — TitiLaPierre
Ce site est une reprise des sites raccourcisseur d'URL permettant de collecter quelques statistiques lorsque des utilisateurs accèdent au lieu et comprenant d'autres fonctionnalités.
## Installer le projet
### Environnement de développement
#### Pré-requis
- NodeJS
- Serveur Apache (exemple : WampServer pour Windows)
- *VS Code (conseillé seulement)*
#### Installation
Commencez par cloner le repository.
```bash
git clone https://github.com/TitiLaPierre/links.titilapierre.fr.git
```
Puis en se plaçant dans le dossier du projet, installez les dépendances NodeJS :
```bash
npm install
```
Vous pouvez maintenant modifier le fichier `config.json`.
```json
    {
    	"version": {  
    	    "type": "stable", // Détermine quelle version est à utiliser parmi celles ci-dessous
    	    "id": "v1.1.1" // Le nom de la version
    	}, "links": { // Les URLs de base pour les différents environnements
    	    "stable": "https://links.titilapierre.fr/",
    	    "beta": "http://links/"
        }, "database": {
    	    "beta": { // Les informations de connexion à la base de données pour les différents environnements
    		    "hostname": "localhost",
    		    "username": "links",
    		    "password": "{{SECRET_PASSWORD_BETA}}",
    		    "database": "links_database"
    	    }, "stable": {
    		    "hostname": "localhost",
    		    "username": "links",
    		    "password": "{{SECRET_PASSWORD_STABLE}}",
    		    "database": "links_database"
    	    }
        }
    }
```
Ensuite, créez un fichier `secret.json` à la racine du projet pour y intégrer les mots de passe. Ce fichier contrairement au fichier `config.json` ne sera pas répertorié par Git.
```json
{
	"SECRET_PASSWORD_BETA": "VOTRE_MOT_DE_PASSE_POUR_L_ENVIRONNEMENT_BETA",
	"SECRET_PASSWORD_STABLE": "VOTRE_MOT_DE_PASSE_POUR_L_ENVIRONNEMENT_STABLE"
}
```
Ensuite, lorsque vous codez, votre projet doit pouvoir build les fichiers SCSS et les placer dans le bon dossier ainsi qu'avec les fichiers JS. C'est ici qu'intervient le projet NodeJS.
##### J'ai VS Code
Le fichier `watch.js` permettant d'automatiquement build les fichiers s'exécute automatiquement en tant que tâche lorsque vous ouvrez le dossier racine du projet.
##### Je n'ai pas VS Code / VS Code ne lance pas la tâche
Lorsque vous souhaitez commencer à coder, vous devez exécuter le fichier `watch.js` en étant placé dans le dossier racine du projet :
```batch
node watch.js
```

> Note : Les fichiers SCSS/JS sont automatiquement déplacés dans les dossiers approprié. Par contre, il faudra préciser dans leur importation leur chemin après avoir été build au niveau du PHP.

> Exemples :
> - Le fichier `src/link/index.scss` devient `css/link/index.css`.
> - Le fichier `global/js/dashboard.scss` devient `js/dashboard.css`.

### Environnement de production
#### Pré-requis
- Serveur Apache
#### Passage de développement à production
Modifiez la clé `version` > `type` du fichier `config.json` par la version de production.
Envoyez vous fichiers sur le serveur de production.

> Vous n'avez pas besoin d'envoyer tous les fichiers en production. La partie servant au build des fichiers SCSS et à la répartition des fichiers JS/SCSS (`node_modules`, `package.json`, `package-lock.json`, `watch.js`, `global/`, `.vscode`) ainsi que les fichiers nécessaire au projet Git (`.git`, `.gitignore`, `README.md`) ne sont pas nécessaires.

