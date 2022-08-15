.PHONY: help docker-build composer-install composer-update composer-require composer-require-dev composer-remove composer-clean
.DEFAULT_GOAL := help

help:
    @grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

docker-build:
	docker-compose down
	docker-compose build --pull
	docker-compose up -d

composer-install:
	docker-compose run --rm -T app composer install

composer-update:
	docker-compose run --rm -T app composer update

composer-require:
	docker-compose run --rm -T app composer req $(package)

composer-require-dev:
	docker-compose run --rm -T app composer req --dev $(package)

composer-remove:
	docker-compose run --rm -T app composer rem $(package)

composer-clean:
	rm -Rf ./vendor ./composer.lock

style-check:
	docker-compose run --rm -T app vendor/bin/phpcs --standard=PSR1,PSR12 --exclude=Generic.Files.LineLength $(folders)

style-fix:
	docker-compose run --rm -T app vendor/bin/phpcbf --standard=PSR1,PSR12 --exclude=Generic.Files.LineLength $(folders)

code-check:
	docker-compose run --rm -T app vendor/bin/phpstan analyse -c phpstan.neon --level 8
