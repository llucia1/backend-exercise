DOCKER_COMPOSE ?= docker-compose
API_CONTAINER ?= books-api

docker-up:
	@echo "Up All Services"
	$(DOCKER_COMPOSE) up -d

docker-down:
	@echo "Down docker-compose"
	rm -rf ./app/vendor ./app/var
	$(DOCKER_COMPOSE) down

docker-logs:
	@echo "Watch log in $(API_CONTAINER)"
	docker logs -f $(API_CONTAINER)

docker-clear-all:
	@echo "Warning !!!! Delete ALL volumes, containers and images"
	docker system prune -a -f
	docker volume prune -f

docker-composer-install:
	@echo "Execute Composer"
	docker exec -ti $(API_CONTAINER) sh -c "composer install"

docker-access-api:
	@echo "Access to container API"
	docker exec -ti $(API_CONTAINER) bash

symfony-test:
	@echo "Execute Testing"
	docker exec -ti $(API_CONTAINER) sh -c "APP_ENV=test php bin/phpunit --testdox --configuration phpunit.dist.xml"

symfony-coverage:
	@echo "Execute Coverage Testing"
	docker exec -ti $(API_CONTAINER) sh -c "APP_ENV=test XDEBUG_MODE=coverage php bin/phpunit --coverage-text --configuration phpunit.dist.xml"

symfony-behat:
	@echo "Execute Behat"
	docker exec -ti $(API_CONTAINER) sh -c "APP_ENV=test vendor/bin/behat -vvv"

symfony-router:
	@echo "View Routes"
	docker exec -ti $(API_CONTAINER) sh -c "php bin/console debug:router"

symfony-logs:
	@echo "Symfony Logs"
	docker exec -ti $(API_CONTAINER) sh -c "tail -f var/log/dev.log"