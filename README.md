This project belongs to Medium article: TODO

This project uses sqlite DB in a file configured through .env

To setup:

```
composer install
bin/console doctrine:schema:create
bin/console doctrine:fixtures:load -n
bin/console server:run
```

Then to start the worker/runner:

`bin/console messenger:consume`


Kim Wüstkamp
www.wuestkamp.com