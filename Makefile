composer:
	composer validate
	composer update --no-interaction --prefer-dist

phpstan:
	vendor/bin/phpstan analyse -l 5 -c phpstan.neon src/

run-tests:
	vendor/bin/tester tests -d extension=tokenizer.so
