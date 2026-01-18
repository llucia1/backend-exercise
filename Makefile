docker-up:
	@echo "Up All Services"
	docker-compose up -d

docker-composer-install:
	@echo "Execute Composer"
	docker exec -ti books-api sh -c "composer install"
docker-access-api:
	@echo "Access to container API"
	docker exec -ti books-api bash
docker-down:
	@echo "Down docker-compose"
	rm -r ./app/vendor
	rm -r ./app/var
	docker-compose down

docker-logs:
	@echo "Watch log in books-api"
	docker logs -f books-api

docker-clear-all:
	@echo "Warning !!!! Delete ALL volumes, containers and images"
	docker volume prune
	docker system prune -accesssymfony-test:

symfony-test:
	@echo "Execute Testing"
	docker exec -ti books-api sh -c "APP_ENV=test php bin/phpunit --verbose --configuration phpunit.dist.xml"

symfony-coverage:
	@echo "Execute Coverage Testing"
	docker exec -ti books-api sh -c "APP_ENV=test XDEBUG_MODE=coverage php bin/phpunit --coverage-text --configuration phpunit.dist.xml"

symfony-router:
	@echo "View Routes"
	docker exec -ti books-api sh -c "php bin/console debug:router"

symfony-logs:
	@echo "Symfony Logs"
	docker exec -ti books-api sh -c "tail -f var/log/dev.log"