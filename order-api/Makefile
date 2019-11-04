up:
	cp .env.example .env
	chmod -R 777 storage
	docker-compose up -d && sleep 5
	docker exec order-php composer install
	docker exec order-php php artisan migrate
	docker exec order-php php artisan vendor:publish --provider="Cviebrock\LaravelElasticsearch\ServiceProvider"
