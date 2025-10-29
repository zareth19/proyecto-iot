@echo off
echo Iniciando procesador de colas de Laravel...
cd /d "c:\xampp\htdocs\laravel\proyecto-iot"
php artisan queue:work --timeout=60
pause