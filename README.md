symfony-casbin
=============

[![Latest Stable Version](http://poser.pugx.org/videni/symfony-casbin/v)](https://packagist.org/packages/videni/symfony-casbin) [![Total Downloads](http://poser.pugx.org/videni/symfony-casbin/downloads)](https://packagist.org/packages/videni/symfony-casbin) [![Latest Unstable Version](http://poser.pugx.org/videni/symfony-casbin/v/unstable)](https://packagist.org/packages/videni/symfony-casbin) [![License](http://poser.pugx.org/videni/symfony-casbin/license)](https://packagist.org/packages/videni/symfony-casbin) [![PHP Version Require](http://poser.pugx.org/videni/symfony-casbin/require/php)](https://packagist.org/packages/videni/symfony-casbin)

Use Casbin in Symfony Framework, Casbin is a powerful and efficient open-source access control library.


## Usage

### 1. Configure your enforcers

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

### 2. Install database table if you use DatabaseAdapter

```
bin/console videni-casbin:install mysql
```