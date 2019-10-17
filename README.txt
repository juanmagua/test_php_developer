
# Commit 1



# Commit 2

# Commit 3

```
Install => composer require symfony/orm-pack
Install => composer require symfony/maker-bundle --dev
```


Config .ENV 
#
DATABASE_URL="mysql://root@127.0.0.1:3306/test_php_developer"

Create DB => php bin/console doctrine:database:create

Create Entity 

php bin/console make:entity "Entity"

Create Migration

php bin/console make:migration

Run Migration

php bin/console doctrine:migrations:migrate

If you add new property

php bin/console doctrine:migrations:diff
	


** COMMIT 4  

LISTO!

** COMMIT 5

Creacion de un Generador de contenido aletorio para User, Group, Event

Popular las entidades hacia la BBDD 

Install => composer require --dev doctrine/doctrine-fixtures-bundle 
Install => composer require --dev hautelook/alice-bundle 
install => composer require --dev theofidry/alice-data-fixtures


Run Fixture => php bin/console hautelook:fixtures:load


** COMMIT 6

INSTALL => composer require symfony/finder

RUN =>  php bin/console app:import-media

** COMMIT 7

INSTALL => composer require symfony/security-bundle
           composer requiere security
           composer requiere twig

ADD AUTH => php bin/console make:auth

RUN => php bin/console make:migration
RUN =>   php bin/console cache:clear
RUN => composer require symfony/asset


** COMMIT 8

Restringir Acceso


** Commit 9

readme.txt
instal.txt
changelog.tax
humans.txt
