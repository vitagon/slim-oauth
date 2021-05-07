stop-local-apps:
	-sudo systemctl stop nginx.service
	-sudo systemctl stop openresty.service
	-sudo systemctl stop mysql.service

start-local-apps:
	-sudo systemctl start nginx.service
	-sudo systemctl start openresty.service
	-sudo systemctl start mysql.service

docker-up:
	docker-compose build
	docker-compose up
docker-up-d:
	docker-compose up -d
docker-down:
	docker-compose down --remove-orphans

composer-install:
	docker-compose exec backend_php-fpm sh -c "composer install"

frontend-install: frontend-npm-install frontend-ready

frontend-npm-install:
	docker-compose run --rm frontend_node-cli npm install

frontend-ready:
	docker run --rm -v ${PWD}/oauth-front:/app -w /app alpine touch .ready

client-install: client-npm-install client-ready

client-npm-install:
	docker-compose run --rm frontend_node-cli npm install

client-ready:
	docker run --rm -v ${PWD}/oauth-client:/app -w /app alpine touch .ready
