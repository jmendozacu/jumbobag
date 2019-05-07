# Jumbobag

## Installation

**Actuellement ce projet n'utilise ni composer, ni modman**

- Installez c42 si nécessaire : `sudo gem install c42`
- Sinon assurez-vous que qu'il est à jour `sudo gem update c42`
- Récuperer le dump de la base de données en remplaçant la date par celle du jour:
	* `rsync -avz jbag@wilson.occitech.fr:admin/backup/YYYY-MM-DD/mysql/jbag_mage.sql.gz .c42/tmp/dump.sql.gz`
- Copier le fichier `htdocs/.htaccess-dev` et le renommer `htdocs/.htaccess` (afin d'activer le mode développeur via htaccess)
- `c42 install`

## Récupérer les assets

```bash
rsync -avz --exclude=catalog/product/cache jbag@wilson.occitech.fr:jumbobag/media/ ./htdocs/media/
```

