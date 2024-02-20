# Symfony Training

## Installation

Install third party PHP dependencies

```bash
$ symfony composer install
```

Run Web server and database.

```bash
$ symfony server:start -d
$ docker compose up --build
```

Build the database.

```bash
$ symfony composer rebuild
```

Open the application.

```
https://localhost:[port]/
```

Party Hard! 🎉🎉🎉

## Useful Commands

### Print Environment Variables

```base
$ symfony var:export --multiline
export DATABASE_DATABASE=akeneo
export DATABASE_DRIVER=mysql
export DATABASE_HOST=127.0.0.1
export DATABASE_NAME=akeneo
export DATABASE_PASSWORD=password
export DATABASE_PORT=33006
export DATABASE_SERVER=mysql://127.0.0.1:33006
export DATABASE_URL=mysql://root:password@127.0.0.1:33006/main?sslmode=disable&charset=utf8mb4
export DATABASE_USER=root
export DATABASE_USERNAME=root
...
```

### Show Local Available PHP Runtimes

```bash
$ symfony local:php:list

+---------+-------------------------------------+---------+--------------+-------------+---------+---------+
| Version |              Directory              |   PHP   |     PHP      |     PHP     | Server  | System? |
|         |                                     |   CLI   |     FPM      |     CGI     |         |         |
+---------+-------------------------------------+---------+--------------+-------------+---------+---------+
| 7.4.33  | /opt/homebrew/Cellar/php@7.4/7.4.33 | bin/php | sbin/php-fpm | bin/php-cgi | PHP FPM |         |
| 8.0.30  | /opt/homebrew/Cellar/php@8.0/8.0.30 | bin/php | sbin/php-fpm | bin/php-cgi | PHP FPM |         |
| 8.1.22  | /opt/homebrew/Cellar/php@8.1/8.1.22 | bin/php | sbin/php-fpm | bin/php-cgi | PHP FPM |         |
| 8.2.9   | /opt/homebrew/Cellar/php/8.2.9      | bin/php | sbin/php-fpm | bin/php-cgi | PHP FPM | *       |
+---------+-------------------------------------+---------+--------------+-------------+---------+---------+

The current PHP version is selected from .php-version from current dir: /Users/hhamon/Code/formation-akeneo/.php-version

To control the version used in a directory, create a .php-version file that contains the version number (e.g. 7.2 or 7.2.15),
or define config.platform.php inside composer.json.
If you're using Platform.sh, the version can also be specified in the .platform.app.yaml file.
```

### Query the Database with SQL

```bash
$ symfony console doctrine:query:sql 'SELECT * FROM locker_facility'

 ----- ----------------- -----------
  id    commissioned_at   name
 ----- ----------------- -----------
  151   2023-08-02        Edward
  152   2023-07-17        Juliet
  153                     Gaspard
  154   2022-07-23        Flo
  155   2022-08-16        Priscilla
  156   2023-05-21        Raymond
  ...
```

### Dump Configuration Reference

```bash
$ symfony console config:dump-reference zenstruck_foundry

# Default configuration for extension with alias: "zenstruck_foundry"
zenstruck_foundry:

    # Whether to auto-refresh proxies by default (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#auto-refresh)
    auto_refresh_proxies: null

    # Configure faker to be used by your factories.
    faker:

        # Change the default faker locale.
        locale:               null # Example: fr_FR

    ....
```

### Make Foundry Factory

```bash
$ symfony console make:factory
```

## Third Party Dependencies

### Foundry

* Documentation: https://github.com/zenstruck/foundry

```bash
$ symfony composer req foundry
$ symfony composer recipe:install --force --reset zenstruck/foundry
```