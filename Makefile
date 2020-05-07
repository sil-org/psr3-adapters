it-now: clean install test

clean:
	docker-compose kill
	docker system prune -f

install:
	docker-compose run --rm cli bash -c "composer install"

update:
	docker-compose run --rm cli bash -c "composer update"

test:
	docker-compose run --rm cli bash -c "cd /data/tests; ./phpunit ."
