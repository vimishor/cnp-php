PHP_BIN ?= "php"

test:
	@$(PHP_BIN) --version
	@$(PHP_BIN) vendor/bin/phpunit

check-style:
	@$(PHP_BIN) vendor/bin/phpcs --standard=psr2 src/

clean:
	@rm -rf ./build ./vendor ./composer.lock

.PHONY: clean test style
