#!/bin/sh

npm run check-js
ESLINT_EXIT_CODE=$?

if [ $ESLINT_EXIT_CODE -ne 0 ]
then
	npm run format-js
fi


npm run check-css
STYLELINT_EXIT_CODE=$?

if [ $STYLELINT_EXIT_CODE -ne 0 ]
then
	npm run format-css
fi


composer check-normalize
COMPOSER_NORMALIZE_EXIT_CODE=$?

if [ $COMPOSER_NORMALIZE_EXIT_CODE -ne 0 ]
then
	composer run-normalize
fi


composer check-format
CS_FIXER_EXIT_CODE=$?

if [ $CS_FIXER_EXIT_CODE -ne 0 ]
then
	composer format
fi


composer analyse
PHP_STAN_EXIT_CODE=$?


if [ $ESLINT_EXIT_CODE -ne 0 ] || [ $STYLELINT_EXIT_CODE -ne 0 ] || [ $COMPOSER_NORMALIZE_EXIT_CODE -ne 0 ] || [ $CS_FIXER_EXIT_CODE -ne 0 ] || [ $PHP_STAN_EXIT_CODE -ne 0 ]
then
	exit 1
fi


php artisan test
PHP_ARTISAN_EXIT_CODE=$?

if [ $PHP_ARTISAN_EXIT_CODE -ne 0 ]
then
	exit 1
fi
