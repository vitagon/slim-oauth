docker-up:
	docker-compose build
	docker-compose up
docker-up-d:
	docker-compose up -d
docker-down:
	docker-compose down --remove-orphans

composer-install:
	docker-compose exec backend_php-fpm sh -c "composer install"

npm-install: frontend-npm-install frontend-ready

frontend-npm-install:
	docker-compose run --rm frontend_node-cli npm install

frontend-ready:
	docker run --rm -v ${PWD}/oauth-front:/app -w /app alpine touch .ready

client-ready:
	docker run --rm -v ${PWD}/oauth-client:/app -w /app alpine touch .ready
