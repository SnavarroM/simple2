chgrp -R www-data storage bootstrap/cache && \
    chown -R www-data storage bootstrap/cache && \
    chmod -R ug+rwx storage bootstrap/cache && \

touch storage/logs/laravel.log && \
chmod 775 storage/logs/laravel.log && \
chown www-data storage/logs/laravel.log && \
chown -R www-data:www-data public/uploads/

composer install && \

php artisan config:clear && \
php artisan cache:clear

php artisan migrate:fresh --seed && \
php artisan simple:backend admin@simple.cl 123456 && \
php artisan simple:manager admin@simple.cl 123456 && \

#NPM
npm install && \
npm run dev

php artisan elasticsearch:admin create && \
echo "Done..."

