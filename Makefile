down: docker-down
run: docker-run
init: docker-down-clear docker-pull docker-build composer-install migrations-migrate fixtures docker-run

docker-run:
	docker-compose up

docker-down:
	docker-compose down --remove-orphans

docker-down-clear:
	docker-compose down -v --remove-orphans

docker-pull:
	docker-compose pull

docker-build:
	docker-compose build

migrations-migrate:
	until docker-compose exec mariadb mysql -h 127.0.0.1 -u bobby -ptables -D myapp --silent -e "show databases;" ; do sleep 5 ; done ; docker-compose run --rm myapp php bin/console doctrine:migrations:migrate --no-interaction

composer-install:
	docker-compose run --rm myapp composer install --no-interaction

fixtures:
	docker-compose run --rm myapp php bin/console doctrine:fixtures:load --no-interaction
