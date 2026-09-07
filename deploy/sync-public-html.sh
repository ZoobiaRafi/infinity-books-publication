#!/bin/bash
# Copies everything from Laravel's public/ folder into the sibling
# public_html/ folder that the webserver actually serves from, since
# public_html is a plain copy rather than a symlink to public/ on this
# host and does not update on its own after a `git pull`.
#
# index.php is skipped: public_html's copy has hosting-specific require
# paths (pointing at ../ibp-app/vendor/autoload.php etc.) that must not
# be overwritten by public/index.php's own paths.
#
# Run manually, or see ../.git/hooks/post-merge for the automatic hook
# that runs this after every `git pull`.
set -e
SRC="$(cd "$(dirname "${BASH_SOURCE[0]}")/../public" && pwd)"
DEST="$(cd "$(dirname "${BASH_SOURCE[0]}")/../../public_html" && pwd)"

find "$SRC" -mindepth 1 -maxdepth 1 ! -name 'index.php' -exec cp -rf {} "$DEST/" \;

echo "Synced $SRC -> $DEST (except index.php)"
