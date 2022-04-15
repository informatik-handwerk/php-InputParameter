#!/bin/bash -eu
#-o pipefail not available for windows

set -x
#set -v

composer config \
  repositories.ihde-inputParam \
  "github" \
  "git@github.com:informatik-handwerk/php-InputParameter.git"

composer require ihde/input-parameter \
  --no-plugins \
  --no-scripts \
