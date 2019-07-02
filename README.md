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


# branch step3
This step belongs to Medium article: https://medium.com/@wuestkamp/unit-functional-test-the-symfony4-messenger-9eef328dce8

We changed the process a little: now in BookingController we directly create a Booking with status
of new, we persist it. Then we pass the ID along with the CreateBookingMessage to the further process it
using a Symfony worker.

We also implemented unit and functional tests, so run with:

```
composer install
bin/phpunit
```

Kim Wüstkamp
www.wuestkamp.com
