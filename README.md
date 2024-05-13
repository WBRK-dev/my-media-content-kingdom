# My Media Content Kingdom

## Content
- [Installatie](#installatie)

## Installatie
1. Eerst wil je de `.env` file maken.
Dit doe je door `.env.example` te kopieren en te plakken met de naam `.env`. Vul daarna het `.env` bestand in met jou eigen sleutels, wachtenwoorden, enz.
2. Daarna wil je de database tables migreren. <b>(shell command)</b>
```
php artisan migrate
```
3. Om dit project te bekijken in de browser kan je gebruik maken van artisan serve. <b>(optioneel)</b> <b>(shell command)</b>
```
php artisan serve
```