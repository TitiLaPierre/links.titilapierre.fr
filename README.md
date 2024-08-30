# Liens — TitiLaPierre

Ce site est une reprise des sites raccourcisseur d'URL permettant de collecter quelques statistiques lorsque des utilisateurs accèdent à un lien et comprenant d'autres fonctionnalités.

## Installer le projet

### Environnement de développement

#### Pré-requis

- NodeJS
- Serveur Apache (exemple : WampServer pour Windows)
- Serveur MySQL
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
		"environment": "stable", // Détermine quelle environnement est à utiliser parmi ceux ci-dessous
		"id": "vX.X.X" // Le nom de la version
	}, "environments": { // Ici, vous pouvez créer plusieurs environnements qui permettront d'exécuter le projet dans des conditions différentes 
		"stable": {
			"base_url": "https://links.titilapierre.fr/", // L'URL de base pour l'environnement 
			"database": { // Les informations de connexion à la base de données
				"hostname": "localhost",
				"username": "links",
				"password": "{{SECRET_PASSWORD_STABLE}}", // Ne pas indiquer le mot de passe directement (voir plus bas)
				"database": "links_database"
			}
		},
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

Vous pouvez maintenant importer le fichier `structure.sql` dans votre base de données MySQL.

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
- Serveur MySQL

#### Passage de développement à production

Modifiez la clé `version` > `environment` du fichier `config.json` par l'environnement de production.
Envoyez vos fichiers sur le serveur de production.

> Vous n'avez pas besoin d'envoyer tous les fichiers en production. La partie servant au build des fichiers SCSS et à la répartition des fichiers JS/SCSS (`node_modules`, `package.json`, `package-lock.json`, `watch.js`, `global/`, `.vscode`) ainsi que les fichiers nécessaire au projet Git (`.git`, `.gitignore`, `README.md`) ne sont pas nécessaires.
