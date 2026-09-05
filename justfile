COMPOSER := 'composer --no-interaction'
NPM := 'npm --prefix js'

install:
	{{COMPOSER}} install
	{{NPM}} ci

test:
	{{COMPOSER}} test

check:
	{{COMPOSER}} validate --strict --no-check-lock
	vendor/bin/phpcs
	{{COMPOSER}} analyse:phpstan

check-frontend:
	{{NPM}} run format-check
	{{NPM}} run build-typings
	{{NPM}} run check-typings
	{{NPM}} run build
