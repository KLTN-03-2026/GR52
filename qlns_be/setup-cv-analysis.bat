@echo off
REM ===========================================================================
REM  CV ANALYSIS SYSTEM - WINDOWS SETUP SCRIPT
REM  Run: setup-cv-analysis.bat from qlns_be folder
REM ===========================================================================

echo.
echo ╔═══════════════════════════════════════════════════════════════╗
echo ║     🚀 CV ANALYSIS SYSTEM - WINDOWS SETUP                     ║
echo ╚═══════════════════════════════════════════════════════════════╝
echo.

REM Check if we're in the right directory
if not exist "artisan" (
    echo ERROR: Please run this script from qlns_be folder!
    echo cd c:\xampp\htdocs\test_case\qlns\qlns_be
    pause
    exit /b 1
)

echo 📋 STEP 1: Installing PHP Dependencies...
echo.
call composer require smalot/pdfparser guzzlehttp/guzzle
if errorlevel 1 (
    echo ERROR: Composer install failed!
    pause
    exit /b 1
)
echo ✅ Dependencies installed!
echo.

echo 📋 STEP 2: Running Migrations...
echo.
call php artisan migrate
if errorlevel 1 (
    echo ERROR: Migration failed!
    pause
    exit /b 1
)
echo ✅ Migrations completed!
echo.

echo 📋 STEP 3: Clearing Cache...
echo.
call php artisan optimize:clear
echo ✅ Cache cleared!
echo.

echo 📋 STEP 4: Running Setup Verification...
echo.
call php setup-cv-analysis.php
echo.

echo ╔═══════════════════════════════════════════════════════════════╗
echo ║                   ✅ SETUP COMPLETE!                          ║
echo ╚═══════════════════════════════════════════════════════════════╝
echo.
echo 📝 Next Steps:
echo    1. Add CVAnalysis component to HoSoUngTuyen page
echo    2. Test with one CV
echo    3. Test batch with multiple CVs
echo    4. Deploy when ready!
echo.
pause
