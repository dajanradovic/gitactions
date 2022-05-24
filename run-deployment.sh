#!/bin/sh

if [ $# -eq 0 ]
then
	git pull -v
else
	git pull -v origin $1
fi

composer update-prod -n
