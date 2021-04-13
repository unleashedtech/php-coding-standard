.PHONY: test phpcs test-report test-fix

PHP_74:=$(shell php -r "echo (int) (version_compare(PHP_VERSION, '7.4', '>=') && version_compare(PHP_VERSION, '8.0', '<'));")
PHP_80:=$(shell php -r "echo (int) version_compare(PHP_VERSION, '8.0', '>=');")

test: test-report test-fix

phpcs:
	@if [ $(PHP_74) -eq 1 ]; then git apply tests/php74-compatibility.patch; fi
	@if [ $(PHP_80) -eq 1 ]; then git apply tests/php80-compatibility.patch; fi
	@vendor/bin/phpcs src
	@if [ $(PHP_74) -eq 1 ]; then git apply -R tests/php74-compatibility.patch; fi
	@if [ $(PHP_80) -eq 1 ]; then git apply -R tests/php80-compatibility.patch; fi

test-report: vendor
	@if [ $(PHP_74) -eq 1 ]; then git apply tests/php74-compatibility.patch; fi
	@if [ $(PHP_80) -eq 1 ]; then git apply tests/php80-compatibility.patch; fi
	@vendor/bin/phpcs `find tests/input/* | sort` --report=summary --report-file=phpcs.log; diff -u tests/expected_report.txt phpcs.log; if [ $$? -ne 0 ]; then if [ $(PHP_74) -eq 1 ]; then git apply -R tests/php74-compatibility.patch; elif [ $(PHP_80) -eq 1 ]; then git apply -R tests/php80-compatibility.patch; fi; exit 1; fi
	@if [ $(PHP_74) -eq 1 ]; then git apply -R tests/php74-compatibility.patch; fi
	@if [ $(PHP_80) -eq 1 ]; then git apply -R tests/php80-compatibility.patch; fi

test-fix: vendor
	@if [ $(PHP_74) -eq 1 ]; then git apply tests/php74-compatibility.patch; fi
	@if [ $(PHP_80) -eq 1 ]; then git apply tests/php80-compatibility.patch; fi
	@cp -R tests/input/ tests/input2/
	@vendor/bin/phpcbf tests/input2; diff -u tests/input2 tests/fixed; if [ $$? -ne 0 ]; then rm -rf tests/input2/ && if [ $(PHP_74) -eq 1 ]; then git apply -R tests/php74-compatibility.patch; elif [ $(PHP_80) -eq 1 ]; then git apply -R tests/php80-compatibility.patch; fi; exit 1; fi
	@rm -rf tests/input2/ && if [ $(PHP_74) -eq 1 ]; then git apply -R tests/php74-compatibility.patch; elif [ $(PHP_80) -eq 1 ]; then git apply -R tests/php80-compatibility.patch; fi


vendor: composer.json
	composer update
	touch -c vendor
