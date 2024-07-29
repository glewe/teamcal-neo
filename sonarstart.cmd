:: ============================================================================
::  SonarQube Server
::  ---------------------------------------------------------------------------
::  Starts a local SonarQube server assuming it is installed. Check the path.
::  Details: https://docs.sonarsource.com/sonarqube/latest/try-out-sonarqube/
:: ============================================================================
::
@echo off
set year=%date:~0,4%
set month=%date:~5,2%
if "%month:~0,1%" == " " set month=0%month:~1,1%
set day=%date:~8,2%
if "%day:~0,1%" == " " set day=0%day:~1,1%
set hour=%time:~0,2%
if "%hour:~0,1%" == " " set hour=0%hour:~1,1%
set min=%time:~3,2%
if "%min:~0,1%" == " " set min=0%min:~1,1%
set tstamp=%year%%month%%day%%hour%%min%

:: ----------------------------------------------------------------------------
:: Sonar Scan
::
echo ================================================================= START
echo  %date% %time% =^> Starting SonarQube server...
echo -------------------------------------------------------------------------------
echo.
set JAVA_HOME=C:\Program Files\Java\jdk-17\
D:\Programs\sonarqube\bin\windows-x86-64\StartSonar.bat

echo  Done
echo ================================================================= END
echo.
echo.
