Weather API [Symfony]
==================================

Requirements
------------

  * PHP 7.1.3 or higher;
  * and the [Symfony application requirements][1].

Installation
------------

Execute these commands to install the project:

```bash
$ cd weather/
$ composer install
$ php bin/console migrate
```

Usage
-----

Run the built-in web server and access the application in your browser at <http://localhost:8000>:

```bash
$ cd weather/
$ php bin/console server:run
```

To fill the database with the weather data run the console command:

```bash
$ php bin/console app:request-report
```

You may use optional parameters:

```bash
$ php bin/console app:request-report Germany
$ php bin/console app:request-report Germany Berlin
```

API endpoints, paging, possible parameters:

```bash
$ curl "http://127.0.0.1:8000/"
$ curl "http://127.0.0.1:8000/countries"
$ curl "http://127.0.0.1:8000/weather"
$ curl "http://127.0.0.1:8000/weather?page=1&start_date=2018-06-25&end_date=2018-06-26&temperature=17&direction=higher&city_id=2"
$ curl "http://127.0.0.1:8000/weather?page=2&start_date=2018-06-25&end_date=2018-06-26"
```

Tests
-----

Execute these commands to run tests:

```bash
$ cd weather/
$ php ./bin/phpunit
```

[1]: https://symfony.com/doc/current/reference/requirements.html