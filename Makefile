PHP_CONTAINER=laravel-app

COMPOSER_CONTAINER=$(PHP_CONTAINER)

up:
	docker-compose up -d

down:
	docker-compose down

restart:
	docker-compose down && docker-compose up -d

build:
	docker-compose build

bash:
	docker-compose exec $(PHP_CONTAINER) bash

artisan:
	docker-compose exec $(PHP_CONTAINER) php artisan $(filter-out $@,$(MAKECMDGOALS))

migrate:
	docker-compose exec $(PHP_CONTAINER) php artisan migrate

migrate-fresh:
	docker-compose exec $(PHP_CONTAINER) php artisan migrate:fresh --seed

seed:
	docker-compose exec $(PHP_CONTAINER) php artisan db:seed

composer:
	docker-compose exec $(COMPOSER_CONTAINER) composer $(filter-out $@,$(MAKECMDGOALS))

npm:
	docker-compose exec node npm $(filter-out $@,$(MAKECMDGOALS))

logs:
	docker-compose logs -f

test:
	docker-compose exec $(PHP_CONTAINER) php artisan test


.PHONY: up down restart build bash artisan migrate seed composer logs test
