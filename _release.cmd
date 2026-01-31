::
:: TeamCal Neo Release Script.
::
:: Automates the git commands to tag and push a release.
::
:: @author    George Lewe <george@lewe.com>
:: @copyright Copyright (c) 2014-2026 by George Lewe
:: @link      https://www.lewe.com
::
:: @package   TeamCal Neo
:: @since     5.0.0
::
@echo off
setlocal

:: Get version from composer.json using a temporary PowerShell script
for /f "delims=" %%i in ('powershell -Command "Get-Content composer.json | ConvertFrom-Json | Select-Object -ExpandProperty version"') do set VERSION=%%i

if "%VERSION%"=="" (
  echo Error: Could not detect version from composer.json
  pause
  exit /b 1
)

:: Check if tag already exists on remote
echo Checking if tag v%VERSION% exists on remote...
git ls-remote --tags origin v%VERSION% | findstr "v%VERSION%" >nul
if %errorlevel%==0 (
  echo.
  echo [WARNING] Tag v%VERSION% already exists on remote!
  echo Aborting release process to avoid conflicts.
  echo.
  pause
  exit /b 1
)

echo.
echo ========================================================
echo  PREPARING RELEASE v%VERSION%
echo ========================================================
echo.
echo  This script will commit all current changes and trigger a release.
echo.
echo  Steps to be performed:
echo  1. git add .
echo  2. git commit -m "Release v%VERSION%"
echo  3. git push origin master
echo  4. git tag v%VERSION%
echo  5. git push origin v%VERSION%
echo.
echo  IMPORTANT: Ensure you have updated doc/releaseinfo.php!
echo.
set /p DUMMY=Press Enter to continue or Ctrl+C to cancel...

echo.
echo [1/5] Adding files...
git add .

echo.
echo [2/5] Committing...
git commit -m "Release v%VERSION%"

echo.
echo [3/5] Pushing changes to master...
git push origin master

echo.
echo [4/5] Creating tag v%VERSION%...
git tag v%VERSION%

echo.
echo [5/5] Pushing tag...
git push origin v%VERSION%

echo.
echo ========================================================
echo  DONE! GitHub Action should now build the release.
echo ========================================================
pause
