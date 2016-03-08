# DBAL universal module

This package integrates Doctrine DBAL in any [container-interop](https://github.com/container-interop/service-provider) compatible framework/container.

## Installation

```
composer require thecodingmachine/dbal-universal-module
```

Once installed, you need to register the [`TheCodingMachine\DbalServiceProvider`](src/DbalServiceProvider.php) into your container.

If your container supports Puli integration, you have nothing to do. Otherwise, refer to your framework or container's documentation to learn how to register *service providers*.

## Introduction

This service provider is meant to provide one connection to your database.
If you need more than one connection to your database, please configure your container directly.

## Expected values / services

This *service provider* expects the following configuration / services to be available:

| Name            | Compulsory | Description                            |
|-----------------|------------|----------------------------------------|
| `dbal.host`       | *no*       | The database host. Defaults to *localhost*  |
| `dbal.user`       | *no*       | The database user. Defaults to *root*  |
| `dbal.password`   | *no*       | The database password. Defaults to *empty*  |
| `dbal.port`       | *no*       | The database port. Defaults to *3306*  |
| `dbal.dbname`     | **yes**    | The database name.  |
| `dbal.charset`    | *no*    | The database character set.  |
| `dbal.driverOptions`    | *no*    | An array of driver options. Defaults to `[1002 =>"SET NAMES utf8"]`  |
| `dbal.params`       | *no*       | An array of parameters directly passed to the `Connection` object. If this parameter is set, all parameters above are ignored.  |
| `Doctrine\DBAL\Driver`       | *no*       | The DBAL driver to use to create the connection. Defaults to DBAL's PDO_MySQL Driver service  |


## Provided services

This *service provider* provides the following services:

| Service name                | Description                          |
|-----------------------------|--------------------------------------|
| `Doctrine\DBAL\Connection`  | A DBAL connection to your database   |

## Extended services

This *service provider* does not extend any service.