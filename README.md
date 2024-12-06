# Project Set
```
composer update
```
# Project Setup .env
```
cp .env.example .env
```
# Project Setup Key
```
php artisan key:generate
```
# Project Setup migrate
```
php artisan migrate
```

# Create Permission
```
php artisan db:seed --class=PermissionTableSeeder
```

# Passport Key and client generate
```
php artisan passport:keys --force
```
```
php artisan passport:client --personal
```
