Requirements:
-------------
PHP >= 7.2.* 
MYSQL >= 

Dependencies:
-------------

    composer require symfony/orm-pack 
    composer require sensio/framework-extra-bundle 
    composer require symfony/maker-bundle --dev
    composer require --dev doctrine/doctrine-fixtures-bundle 
    composer require --dev hautelook/alice-bundle 
    composer require --dev theofidry/alice-data-fixtures
    composer require symfony/finder
    composer require symfony/security-bundle
    composer requiere security
    composer requiere twig
    composer require symfony/asset


Steps:
------
   . git clone .....
   . Install  Dependencies
   . config Database /env 
        example: DATABASE_URL="mysql://root@127.0.0.1:3306/test_php_developer"
   . Create Database 
        php bin/console doctrine:database:create
   . Run Migrate
        php bin/console doctrine:migrations:migrate
   . Load Fixture DATA
        php bin/console hautelook:fixtures:load
   . config DIR UPLOAD /env 
         example: UPLOAD_DIR=public/uploads   
   . RUN command IMPORT MEDIA
        php bin/console app:import-media
   . RUN PROJECT
        cd PROJECT
        php -S 127.0.0.1:8000 -t public


