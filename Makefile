setup:
	docker-compose build
	docker-compose up -d
	docker-compose exec -T app php /bin/composer install --no-scripts
	docker-compose exec -T app php ./bin/console system:install --create-database --basic-setup