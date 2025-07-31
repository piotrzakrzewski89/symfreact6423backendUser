# symfreact6423backendUser

docker compose exec php bin/console d:d:c
docker compose exec php bin/console d:m:m


docker compose exec php bin/console d:d:c --env=test
docker compose exec php bin/console d:m:m --env=test

dphp vendor/bin/phpunit --coverage-text - testy

dphp vendor/bin/phpcs src --standard=PSR12 - code sniffer
dphp vendor/bin/phpcbf src - automatyczna naprawa