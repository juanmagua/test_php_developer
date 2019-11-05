
# Proyecto Backend Syfmony

Symfony 4
JWT
REST API


##  GET REPO

```
git clone https://github.com/juanmagua/test_php_developer.git

```

## Chequear Requerimientos

symfony check:requirements

## RUN PROJECT

```
cd project_name

php -S 127.0.0.1:8000 -t public
```

# INSTALL PACKAGE

```
composer install
```

## Config .ENV 

```
#DATABASE_URL="mysql://root@127.0.0.1:3306/test_php_developer"
```

## Create DB

```
php bin/console doctrine:database:create
```

## Run Migration

```
php bin/console doctrine:migrations:migrate
```

## RUN FIXTURE

```
php bin/console hautelook:fixtures:load
```

## RUN COMMAND UPLOAD IMAGE

```
php bin/console app:import-media
```

## Documentación REST API


[servername]/api/doc




