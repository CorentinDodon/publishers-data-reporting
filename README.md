# Publisher's revenues and data reporting

Publisher's revenues and data reporting API : A simple PHP Laravel / MySQL project

## Tech Stack

**Back:** PHP 8.2, Laravel 10.4

**Database:** MySQL

**Environment:** Docker + Sail

**Additional dev dependencies** : "kitloong/laravel-migrations-generator", "reliese/laravel"

## Prerequisites

Docker must be installed on your device.
## Installation

#### 1) Install vendor dependencies with Composer

```bash
composer install
```

#### 1) Install vendor dependencies without Composer
Either create a container with composer to install the vendor dependencies
```bash
docker run --rm --interactive --tty -v $(pwd):/app composer install
```

Either create a temporary container to prepare the project
```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/var/www/html \
    -w /var/www/html \
    laravelsail/php82-composer:latest \
    composer install --ignore-platform-reqs
```

#### 2) Build the container with Sail
```bash
./vendor/bin/sail up
```

#### 3) Execute the migrations
```bash
./vendor/bin/sail artisan migrate
```

#### 3) Populate the database with seeder
```bash
./vendor/bin/sail artisan db:seed
```

#### 4) Get latest currency rates from default provider
```bash
./vendor/bin/sail artisan app:get-daily-currency-exchange-rates
```
## API Reference

#### Get reporting data by Publisher for the specified date interval

```http
  GET /api/reporting-data/publisher/{publisherId}/interval/{startDate}/{endDate}?page={page}&perPage={perPage}&currency={currency}
```

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `publisherId` | `string` | **Required**. The id of the publisher's data |
| `startDate` | `string` | **Required**. startDate of the observed dataset |
| `endDate` | `string` | **Required**. endDate of the observed dataset |
| `page` | `string` | Page number of dataset |
| `perPage` | `string` | Number of element per page |
| `currency` | `string` | currency of the revenues (USD by default) |

#### Get total revenues of Publisher for the specified date interval

```http
  GET /api/reporting-data/revenues/publisher/{publisherId}/interval/{startDate}/{endDate}
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `publisherId` | `string` | **Required**. The id of the publisher's data |
| `startDate` | `string` | **Required**. startDate of the observed dataset |
| `endDate` | `string` | **Required**. endDate of the observed dataset |


