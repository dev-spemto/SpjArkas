@echo off
title SPJ ARKAS Server Launcher
cd /d "%~dp0"

:: Set Path PHP Portable (langsung di folder php)
set PHP_PATH=%~dp0php\php.exe

if not exist "%PHP_PATH%" (
    echo [ERROR] PHP Portable tidak ditemukan di %PHP_PATH%!
    pause
    exit /b
)

:: Jalankan Server Laravel di Port 8000
"%PHP_PATH%" artisan serve --host=127.0.0.1 --port=8000