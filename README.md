This project belongs to Medium article: TODO

This project uses sqlite DB in a file configured through .env

To setup:

```
composer install
bin/console doctrine:schema:create
bin/console doctrine:fixtures:load -n
bin/console server:run
```


Kim Wüstkamp
www.wuestkamp.com