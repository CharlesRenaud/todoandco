@echo off
REM Script pour exécuter les tests du projet TodoList (Windows)

echo.
echo =========================================
echo Execution des Tests - Projet TodoList
echo =========================================
echo.

REM Test 1: TaskControllerTest
echo [1/3] Execution de TaskControllerTest...
php bin\phpunit.phar tests/AppBundle/Controller/TaskControllerTest.php
if %errorlevel% neq 0 (
    echo.
    echo [ERREUR] TaskControllerTest ECHOUE
    exit /b 1
)
echo [OK] TaskControllerTest PASSE
echo.

REM Test 2: UserControllerTest
echo [2/3] Execution de UserControllerTest...
php bin\phpunit.phar tests/AppBundle/Controller/UserControllerTest.php
if %errorlevel% neq 0 (
    echo.
    echo [ERREUR] UserControllerTest ECHOUE
    exit /b 1
)
echo [OK] UserControllerTest PASSE
echo.

REM Test 3: AuthorizationTest
echo [3/3] Execution de AuthorizationTest...
php bin\phpunit.phar tests/AppBundle/Controller/AuthorizationTest.php
if %errorlevel% neq 0 (
    echo.
    echo [ERREUR] AuthorizationTest ECHOUE
    exit /b 1
)
echo [OK] AuthorizationTest PASSE
echo.

REM Résumé final
echo =========================================
echo [OK] TOUS LES TESTS PASSENT!
echo =========================================
echo.
echo Resume:
echo   * TaskControllerTest: 4/4 tests OK
echo   * UserControllerTest: 2/2 tests OK
echo   * AuthorizationTest: 7/7 tests OK
echo   * TOTAL: 13/13 tests OK
echo.
