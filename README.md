## Requirements
- Docker

## Getting started
Executve these scripts to launch application.

1. `cp .env.example .env`
2. OPTIONAL: Update and fill in DB_DATABASE, DB_USERNAME, & DB_PASSWORD as needed.
3. `composer install`
4. `./vendor/bin/sail up -d`
5. `./vendor/bin/sail artisan migrate`
6. `./vendor/bin/sail artisan key:generate`
7. `./vendor/bin/sail artisan db:seed`
8. `./vendor/bin/sail npm install`
9. `./vendor/bin/sail npm run build`
10. Navigate to http://localhost

## Teardown
1. `./vendor/bin/sail down`
