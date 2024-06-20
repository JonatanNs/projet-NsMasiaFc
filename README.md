# Projet Ns Masia Fc

My project is to create the website of a fictional football club named NS MASIA Football Club.

This project will aim to increase the visibility of the club and its revenues. This site will allow the club to publish all the information and news about the club, to sell its equipment and match tickets.

The NS MASIA FC website will allow users to discover the club and its history. In addition, it will offer them the opportunity to follow the news of the club and attend sporting events.

Languages used:
* HTML/SAAS(CSS)
* JavaScript 
* PHP/Twig - PHP MySQL

# Start the full PHP project

## Install libraries with Composer

- Dotenv
- Twig
- Dumper

## Twig Extension

- To set dates in French on Twig
- For sending emails
- For internationalization

## Useful links

- For flags 
- Icon library 
- Sort by category
- Php Mailler

## Start the project

---

# Install libraries with Composer

## Dotenv

```sh
composer require vlucas/phpdotenv
```

## Twig

```sh
composer require "twig/twig:^3.0"
```

## Var Dumper

```sh
composer require --dev symfony/var-dumper
```

<p>Then, each time you add a class to one of the folders :</p>

```sh
composer dump-autoload
```
# Twig Extension

## To set dates in French on Twig

```
composer require twig/intl-extra
```

## For sending emails

```
composer require phpmailer/phpmailer
```

## For internationalization

This extension adds internationalization (i18n) features to Twig, making it easier to manage the localization of web applications in different languages.

```
composer require phpmyadmin/twig-i18n-extension:^4.1
```

# Useful Links

- Icon : https://css.gg/ ou https://fontawesome.com
- Stripe : https://stripe.com
- Bootstrap : https://getbootstrap.com
- Php Mailler : https://github.com/PHPMailer/PHPMailer