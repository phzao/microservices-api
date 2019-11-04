up:
	docker-compose up -d && sleep 5
	cp user-api/.env.example user-api/.env
	chmod -R 777 user-api/storage
	docker-compose -f user-api/docker-compose.yml up -d  && sleep 5
	docker exec user-php composer install
	docker exec user-php php artisan migrate
	docker exec user-php php artisan vendor:publish --provider="Cviebrock\LaravelElasticsearch\ServiceProvider"
	cp order-api/.env.example order-api/.env
	chmod -R 777 order-api/storage
	docker-compose -f order-api/docker-compose.yml up -d && sleep 5
	docker exec order-php composer install
	docker exec order-php php artisan migrate
	docker exec order-php php artisan vendor:publish --provider="Cviebrock\LaravelElasticsearch\ServiceProvider"
