@echo off

set WEBSITE_PATH=sites/cep-saint-maur/test
set IGNORELIST_FILE=.deployignore
set DELETE_FLAG=--delete
REM set DRYRUN_FLAG=--dry-run
set DRYRUN_FLAG=

rsync -az --force --progress --exclude-from=%IGNORELIST_FILE% %DELETE_FLAG% %DRYRUN_FLAG% -e "ssh -p22" ./ bnbox:%WEBSITE_PATH%
