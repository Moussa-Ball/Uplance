# Uplance official repository

## This repository contains the official source code of the freelance website, Uplance.

## Dependencies required

To use this repository, you must first ensure that
these dependencies below are already installed.
Make sure your server meets the following requirements:

- [tmux](https://doc.ubuntu-fr.org/tmux) (For multi window in terminal)
- [Node.js](https://nodejs.org/)
- [yarn](https://yarnpkg.com)
- [maildev](https://danfarrelly.nyc/MailDev/)
- [Laravel Requirements](https://laravel.com/docs/5.8#server-requirements)
- [make](http://www.gnu.org/software/make/) - (Already installed on Linux)
- [composer](https://getcomposer.org/download/)
- [redis-server](https://redis.io/download)
- [laravel-echo-server](https://github.com/tlaverdure/laravel-echo-server)
- Database (Mysql/Others)

## Options

laravel-echo-server must be installed globally with npm or yarn.
It's a NodeJs server for Laravel Echo broadcasting with Socket.io.

``` bash
$ npm install -g laravel-echo-server
```

After installing laravel-echo-server, initialize it by running the command below. 
Otherwise, use the laravel-echo-server.json.example file and rename it to laravel-echo-server.json.

``` bash
$ laravel-echo-server init
```

Then you can install redis-server with this command below for ubuntu.

``` bash
$ sudo apt-get install redis-server
```

For archlinux proceeds as follows.

``` bash
$ sudo pacman -S redis-server
```

## Usage

Clone the repository first.

``` bash
$ git clone https://Moussa-Ball@bitbucket.org/uplancecanada/uplance.git
```

Rename the .env.example file to .env and configure the environment variables 
by adding also these variables below and their values.

```
FACEBOOK_CLIENT_ID=
FACEBOOK_CLIENT_SECRET=

GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
```

AWS S3 is also used in the project. You must specify the environment variables for Amazon S3.

```
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=
AWS_BUCKET=
```

Do not forget to configure the variables for the database and the email.
After configuring the environment variables, proceed as follows in the terminal.

``` bash
$ composer install
$ yarn install
$ php artisan migrate
$ php artisan passport:keys
$ make dev
```

:warning Do not make `make dev` on windows if you dont have installed `make` and `tmux`.
Use [cmder](https://cmder.net/) and launch on each tab the commands below.
Do the above steps without make dev then do what is mentioned below.

``` bash
$ php artisan serve
$ yarn hot or npm run hot
$ maildev --ip 127.0.0.1
$ laravel-echo-server start
$ redis-server
```

### To install the CRM ([Laravel Voyager](https://laravelvoyager.com/))

``` bash
$ php artisan voyager:install
```

### Add an administrator on the crm 
``` bash
$ php artisan voyager:admin email
```

The email must be an email from a user already registering in database.

### Laravel Passport ([Laravel Passport](https://laravel.com/docs/5.8/passport))

``` bash
$ php artisan passport:keys
```

This command generates the encryption keys Passport needs in order to generate access token.
Otherwise the ajax part will not work with `Vue.js`.


To run the linter tests in php for the code format, run the command below.

``` bash
$ ./vendor/bin/phpcs
```

To fix code formatting errors, run the command below:

``` bash
$ ./vendor/bin/phpcbf
```

To launch the javascript eslint, run the command below:

``` bash
$ npm run lint
```

To launch unit tests for vue.js, run the command below:

``` bash
$ npm run test
```

If you have a 404 problem or 500 with apache. Refer to this link below to solve the problem.

### [404 Error | Laravel - Apache](https://stackoverflow.com/questions/22757749/laravel-redirects-to-a-route-but-then-apache-gives-404-error)

### [500 Error | Laravel - Apache](https://stackoverflow.com/questions/31543175/getting-a-500-internal-server-error-on-laravel-5-ubuntu-14-04)
