#!/bin/bash -eux

rm -rf ./vendor
rm -f composer.json
rm -f composer.lock

composer init --no-interaction --name="test/test"
. ../../bin/composer_require_https.sh

rm -rf ./vendor
rm -f composer.json
rm -f composer.lock

composer init --no-interaction --name="test/test"
. ../../bin/composer_require_ssh.sh

rm -rf ./vendor
rm -f composer.json
rm -f composer.lock

