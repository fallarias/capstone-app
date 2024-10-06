@echo off
cd C:\xampp\htdocs\CAPSTONE\capstone-app
echo Running Laravel scheduler at %date% %time% >> schedule.log
php artisan schedule:run >> schedule.log 2>&1
echo Finished running at %date% %time% >> schedule.log

