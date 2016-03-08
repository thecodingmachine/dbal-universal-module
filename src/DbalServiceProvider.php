<?php

namespace TheCodingMachine;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Driver;
use Interop\Container\ContainerInterface;
use Interop\Container\ServiceProvider;

class DbalServiceProvider implements ServiceProvider
{
    public static function getServices()
    {
        return [
            Connection::class => 'createConnection',
            'dbal.host' => 'getHost',
            'dbal.user' => 'getUser',
            'dbal.password' => 'getPassword',
            'dbal.port' => 'getPort',
            'dbal.dbname' => 'getDbname',
            'dbal.charset' => 'getCharset',
            'dbal.driverOptions' => 'getDriverOptions',
            Driver::class => 'getDriver',
        ];
    }

    public static function createConnection(ContainerInterface $container, callable $previous = null) : Connection
    {
        if ($container->has('dbal.params')) {
            $params = $container->get('dbal.params');
        } else {
            $params = array(
                'host' => $container->get('dbal.host'),
                'user' => $container->get('dbal.user'),
                'password' => $container->get('dbal.password'),
                'port' => $container->get('dbal.port'),
                'dbname' => $container->get('dbal.dbname'),
                'charset' => $container->get('dbal.charset'),
                'driverOptions' => $container->get('dbal.driverOptions'),
            );
        }

        $driver = $container->get(Driver::class);

        $connection = new Connection($params, $driver);

        return $connection;
    }

    public static function getHost(ContainerInterface $container, callable $previous = null) :string
    {
        if ($previous !== null) {
            return $previous();
        }

        return 'localhost';
    }

    public static function getUser(ContainerInterface $container, callable $previous = null):string
    {
        if ($previous !== null) {
            return $previous();
        }

        return 'root';
    }

    public static function getPassword(ContainerInterface $container, callable $previous = null):string
    {
        if ($previous !== null) {
            return $previous();
        }

        return '';
    }

    public static function getPort(ContainerInterface $container, callable $previous = null):int
    {
        if ($previous !== null) {
            return $previous();
        }

        return 3306;
    }

    public static function getDbname(ContainerInterface $container, callable $previous = null):string
    {
        if ($previous !== null) {
            return $previous();
        }
        throw new DBALException('The "dbname" must be set in the container entry "dbal.dbname"');
    }

    public static function getCharset(ContainerInterface $container, callable $previous = null):string
    {
        if ($previous !== null) {
            return $previous();
        }

        return 'utf8';
    }

    public static function getDriverOptions(ContainerInterface $container, callable $previous = null):array
    {
        if ($previous !== null) {
            return $previous();
        }

        return array(1002 => 'SET NAMES utf8');
    }

    public static function getDriver(ContainerInterface $container, callable $previous = null):Driver
    {
        if ($previous !== null) {
            return $previous();
        }

        return new Driver\PDOMySql\Driver();
    }
}
