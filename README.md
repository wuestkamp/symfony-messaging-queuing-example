# branch step1
simple booking API using messenges from Symfony Messenger

This step belongs to Medium article:
https://medium.com/faun/say-yes-to-the-symfony4-messenger-queue-f6a4fe16ee4


This project uses sqlite DB in a file configured through .env

To setup:

```
composer install
bin/console doctrine:schema:create
bin/console doctrine:fixtures:load -n
bin/console server:run
```

Then to start the worker/runner:

# branch step2
Now we use an async doctrine messenger queue to create a booking.

This step belongs to Medium article:
https://medium.com/faun/say-yes-to-the-symfony4-messenger-queue-f6a4fe16ee4

setup like with branch step1, then run the worker:

``bin/console messenger:consume``



Kim Wüstkamp
www.wuestkamp.com
