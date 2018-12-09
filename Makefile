install:
	brew install composer docker docker-compose
	composer install

up:
	docker-compose -p faking-geolocation-sample up -d --build

down:
	docker-compose -p faking-geolocation-sample down

ssh:
	docker exec -it `docker ps -ql -f name=faking-geolocation-sample_php` sh

firefox:
	open vnc://localhost:15905  # パスワードはsecret

travel:
	docker exec -it `docker ps -ql -f name=faking-geolocation-sample_php` sh -c "php artisan travel"
