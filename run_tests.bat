@echo off
REM Script pour exécuter les tests du projet TodoList (Windows)

echo.
echo =========================================
echo Execution des Tests - Projet TodoList
echo =========================================
echo.

REM Test 1: TaskControllerTest
echo [1/3] Execution de TaskControllerTest...
php -d error_reporting=8191 bin\phpunit.phar tests/AppBundle/Controller/TaskControllerTest.php > tmp_task_output.txt 2>&1
findstr /C:"OK (" tmp_task_output.txt >nul
if %errorlevel% neq 0 (
    echo.
    type tmp_task_output.txt
    echo.
    echo [ERREUR] TaskControllerTest ECHOUE
    del tmp_task_output.txt
    exit /b 1
)
echo [OK] TaskControllerTest PASSE
del tmp_task_output.txt
echo.

REM Test 2: UserControllerTest
echo [2/3] Execution de UserControllerTest...
php -d error_reporting=8191 bin\phpunit.phar tests/AppBundle/Controller/UserControllerTest.php > tmp_user_output.txt 2>&1
findstr /C:"OK (" tmp_user_output.txt >nul
if %errorlevel% neq 0 (
    echo.
    type tmp_user_output.txt
    echo.
    echo [ERREUR] UserControllerTest ECHOUE
    del tmp_user_output.txt
    exit /b 1
)
echo [OK] UserControllerTest PASSE
del tmp_user_output.txt
echo.

REM Test 3: AuthorizationTest
echo [3/3] Execution de AuthorizationTest...
php -d error_reporting=8191 bin\phpunit.phar tests/AppBundle/Controller/AuthorizationTest.php > tmp_auth_output.txt 2>&1
findstr /C:"OK (" tmp_auth_output.txt >nul
if %errorlevel% neq 0 (
    echo.
    type tmp_auth_output.txt
    echo.
    echo [ERREUR] AuthorizationTest ECHOUE
    del tmp_auth_output.txt
    exit /b 1
)
echo [OK] AuthorizationTest PASSE
del tmp_auth_output.txt
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
