@echo off

echo -----------------------------------
echo The script to build reporter.phar
echo Author: peter^<peter.ziv@hotmail.com^>
echo Date: July 15,2017
echo -----------------------------------

set root=%~dp0
set app=reporter

set package=%app%.phar
php tools\zphar-1.0.1.phar --dir src\reporter --name %package% --default Report.php

echo [INFO] Waiting for some time to repare application...
@ping -n 2 127.0.0.1 >nul

move /y %package% deploy\bin

echo [INFO] Testing...
cd deploy
php bin\%package%

cd %root%
