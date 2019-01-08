# Installation (linux, mac)

Run these commands in the terminal:

```
git clone https://github.com/AlexMordred/neurony.git
cd neurony
composer install
mv .env.example .env
```

Then edit the `.env` file, setup your database connection. Then run this command:

```
php artisan migrate --seed
```

Start the development server:

```
php artisan serve
```

Navigate to `http://localhost:8000`.

Admin credentials: admin@example.com / qwerty