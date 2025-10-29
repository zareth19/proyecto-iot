@echo off
echo ========================================
echo    SISTEMA DE ALERTAS IoT - ACTIVADO
echo ========================================
echo.
echo Iniciando procesador de colas...
echo Las notificaciones se enviaran automaticamente
echo.
echo Presiona Ctrl+C para detener
echo ========================================
echo.

cd /d "c:\xampp\htdocs\laravel\proyecto-iot"
php artisan queue:work --timeout=60 --tries=3

pause