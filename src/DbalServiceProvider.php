<?php
namespace TheCodingMachine;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Driver;
use Interop\Container\ContainerInterface;
use Interop\Container\Factories\Parameter;
use Interop\Container\ServiceProvider;

class DbalServiceProvider implements ServiceProvider
{
    public function getServices()
    {
        return [
            Connection::class => [DbalServiceProvider::class,'createConnection'],
            Driver::class => [DbalServiceProvider::class,'getDriver'],
            // Default parameters should be overloaded by the container
            'dbal.host'=> new Parameter('localhost'),
            'dbal.user'=> new Parameter('root'),
            'dbal.password'=> new Parameter(''),
            'dbal.port'=> new Parameter('3306'),
            'dbal.dbname'=> [DbalServiceProvider::class, 'getDbname'],
            'dbal.charset'=> new Parameter('utf8'),
            'dbal.driverOptions'=> new Parameter([1002 => "SET NAMES utf8"])
        ];
    }
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

        $connection = new Connection($params, $driver);

        return $connection;
    }

    public static function getDbname():string
    {
        throw new DBALException('The "dbname" must be set in the container entry "dbal.dbname"');
    }

    public static function getDriver():Driver
    {
        return new Driver\PDOMySql\Driver();
    }
}
