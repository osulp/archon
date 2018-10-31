#!/bin/bash
set -e

if [ ! -f config.inc.php ]; then
  echo "config.inc.php not found, copying config.inc.php.example into place."
  cp config.inc.php.example config.inc.php
fi

if [ ! -f packages/core/install/install.php ]; then
  echo "packages/core/install/install.php not found, copying packages/core/install/install_done.php into place."
  cp packages/core/install/install_done.php packages/core/install/install.php
fi

apache2-foreground
