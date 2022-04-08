videni/casbin
=============

[![Latest Stable Version](http://poser.pugx.org/videni/casbin/v)](https://packagist.org/packages/videni/casbin) [![Total Downloads](http://poser.pugx.org/videni/casbin/downloads)](https://packagist.org/packages/videni/casbin) [![Latest Unstable Version](http://poser.pugx.org/videni/casbin/v/unstable)](https://packagist.org/packages/videni/casbin) [![License](http://poser.pugx.org/videni/casbin/license)](https://packagist.org/packages/videni/casbin) [![PHP Version Require](http://poser.pugx.org/videni/casbin/require/php)](https://packagist.org/packages/videni/casbin)

Use Casbin in Symfony Framework, Casbin is a powerful and efficient open-source access control library. Built on top of [php-casbin/php-casbin](https://github.com/php-casbin/php-casbin) and [php-casbin/database-adapter](https://github.com/php-casbin/database-adapter)

## Installation

### 1. Add `videni/casbin` as your dependency


```
composer require videni/casbin:"^1.0@dev"
```

### 2. Configure your enforcers

```
videni_casbin:
    default_enforcer: mysql
    enforcers:
        mysql:
            path: "%kernel.project_dir%/config/packages/videni_casbin/rbac_with_domains_model.conf"
            adapter: mysql
    adapters:
        mysql:
            class: Videni\Casbin\Adapter\DatabaseAdapter
            options:
                type: "mysql"
                hostname: "db"
                database: "zaizai"
                username: "zaizai"
                password: "zaizai"
                hostport: "3306"
```

### 3. Install database tables if you use DatabaseAdapter

```
bin/console videni-casbin:install mysql
```

the `mysql` is your adapter name

## Usage

### 1. Casbin enforcer

you can get the default enforcer by `videni_casbin.default_enforcer`, others are managed by EnforcerManager.

```
/** @var \Videni\Casbin\EnforcerManager $enforcerManager **/
$enforcerManager->getEnforer('mysql'); //  return the default enforcer if no argument provided.
```

### 2. Symfony security voter - CasbinVoter

A Symfony security voter which uses the default casbin enforcer. 

