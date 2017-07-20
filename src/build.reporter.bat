@echo off

echo -----------------------------------
echo The script to build reporter.phar
echo Author: peter^<peter.ziv@hotmail.com^>
echo Date: July 15,2017
echo -----------------------------------

set app=reporter
set version=1.0.alpha2

set package=%app%-%version%.phar
php zphar-1.0.1.phar --dir reporter --name %package% --default Report.php

echo [INFO] Waiting for some time
@ping -n 2 127.0.0.1 >nul

move /y %package% ..\deploy\bin

echo [INFO] Testing...
cd ..\deploy
php bin\%package%

cd ..\src
