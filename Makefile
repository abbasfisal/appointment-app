include .env


composer-install:
	@docker-compose -f docker-compose.yml run --rm web \
 		composer update --no-scripts


migrate:
	@docker-compose -f docker-compose.yml run --rm web \
		php src/Application/Infrastructure/Persistence/Database/Mysql/Migrator.php

run-test:
	@docker-compose -f docker-compose.yml run --rm web \
    		./vendor/bin/phpunit