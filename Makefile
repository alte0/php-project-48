gendiff:
	./bin/gendiff $(filter-out $@,$(MAKECMDGOALS))

lint:
	composer exec --verbose phpcs -- --standard=PSR12 src bin
test:
	composer exec --verbose phpunit tests
test-coverage:
	XDEBUG_MODE=coverage composer exec --verbose phpunit tests -- --coverage-clover build/logs/clover.xml
test-coverage-text:
	XDEBUG_MODE=coverage composer exec --verbose phpunit tests -- --coverage-text

c-d-a:
	composer dump-autoload
c-i:
	composer install
c-v:
	composer validate
c-r:
	composer require $(filter-out $@,$(MAKECMDGOALS))
c-r-dev:
	composer require --dev $(filter-out $@,$(MAKECMDGOALS))