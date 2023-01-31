.PHONY: test phpcs test-report test-fix

test: test-report test-fix

phpcs:
	./bin/phpcs

test-report: vendor
	./bin/test-report

test-fix: vendor
	./bin/test-fix

vendor: composer.json
	composer update
	touch -c vendor
