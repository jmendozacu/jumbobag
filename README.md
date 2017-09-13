# Jumbobag

## Installation

**Actuellement ce projet n'utilise ni composer, ni modman**

- Installez c42 si nécessaire : `sudo gem install c42`
- Sinon assurez-vous que qu'il est à jour `sudo gem update c42`
- Récuperer le dump de la base de données
	* `rsync -avz jbag@wilson.occitech.fr:admin/backup/YYYY-MM-DD/mysql/jbag_mage.sql.gz ./tmp/dump.sql.gz`
- Créer le fichier `docker-compose.yml` en se basant sur `docker-compose.yml.dist`
- Créer le fichier `docker/entrypoint.sh` en se basant sur `docker/entrypoint.sh.dist`
- Créer le fichier `htdocs/.htaccess` en se basant sur `htdocs/.htaccess-dev` (afin d'activer que le mode développeur via htaccess car le projet n'utilise pas composer)
- `c42 install`

## Récupérer les assets

```bash
rsync -avz --exclude=catalog/product/cache jbag@wilson.occitech.fr:jumbobag/media/ ./htdocs/media/
```

