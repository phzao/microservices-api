up:
	cp .env.example .env
	chmod -R 777 storage
	docker-compose up -d && sleep 5
	docker exec user-php composer install
	docker exec user-php php artisan migrate
	docker exec user-php php artisan vendor:publish --provider="Cviebrock\LaravelElasticsearch\ServiceProvider"
