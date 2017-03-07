run-tests:
	make composer.phar
	php composer.phar install --no-interaction
	vendor/bin/phpstan analyse -l 5 src

composer.phar:
	# Download Composer https://getcomposer.org/download/
	php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
	php composer-setup.php
	php -r "unlink('composer-setup.php');"
