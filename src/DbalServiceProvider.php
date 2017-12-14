<?php

namespace TheCodingMachine;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Driver;
use Doctrine\DBAL\Tools\Console\Command\ImportCommand;
use Doctrine\DBAL\Tools\Console\Command\ReservedWordsCommand;
use Doctrine\DBAL\Tools\Console\Command\RunSqlCommand;
use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
use Interop\Container\ContainerInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Helper\HelperSet;
use TheCodingMachine\Funky\Annotations\Extension;
use TheCodingMachine\Funky\Annotations\Factory;
use TheCodingMachine\Funky\ServiceProvider;

class DbalServiceProvider extends ServiceProvider
{
    /**
     * @Factory()
     * @param ContainerInterface $container
     * @return Connection
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public static function createConnection(ContainerInterface $container) : Connection
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

        return new Connection($params, $driver);
    }

    /**
     * @Factory(name="dbal.dbname")
     * @return string
     * @throws DBALException
     */
    public static function getDbname():string
    {
        throw new DBALException('The "dbname" must be set in the container entry "dbal.dbname"');
    }

    /**
     * @Factory()
     * @return Driver
     */
    public static function getDriver():Driver
    {
        return new Driver\PDOMySql\Driver();
    }

    /**
     * @Factory(name="dbal.host")
     * @return string
     */
    public static function getHost():string
    {
        return 'localhost';
    }

    /**
     * @Factory(name="dbal.user")
     * @return string
     */
    public static function getUser():string
    {
        return 'root';
    }

    /**
     * @Factory(name="dbal.password")
     * @return string
     */
    public static function getPassword():string
    {
        return '';
    }

    /**
     * @Factory(name="dbal.port")
     * @return int
     */
    public static function getPort():int
    {
        return 3306;
    }

    /**
     * @Factory(name="dbal.charset")
     * @return string
     */
    public static function getCharset():string
    {
        return 'utf8';
    }

    /**
     * @Factory(name="dbal.driverOptions")
     * @return array
     */
    public static function getDriverOptions():array
    {
        return [1002 => 'SET NAMES utf8'];
    }

    /**
     * @Extension()
     */
    public static function extendConsole(Application $console): Application
    {
        $console->addCommands([
            new RunSqlCommand(),
            new ImportCommand(),
            new ReservedWordsCommand(),
        ]);
        return $console;
    }

    /**
     * Registers the DB in the Symfony console helper set (used by commands)
     *
     * @Extension()
     */
    public static function extendHelperSet(HelperSet $helperSet, Connection $connection): HelperSet
    {
        $helperSet->set(new ConnectionHelper($connection), 'db');
    }
}
