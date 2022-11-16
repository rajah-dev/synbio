<?php

//Connect to mysql database
class Connection
{
//static - method is accesible globally, w/o requiring instance
//Connection::make(configFileGoesHere);
public static function make($config)
{
    try {
        return new PDO(
            $config['connection'].';dbname='.$config['dbname'].';charset='.$config['charset'],
            $config['username'],
            $config['password'],
            $config['options']
        );
    } catch (\PDOException $exception) {
        throw new \PDOException($exception->getMessage(), (int)$exception->getCode());
        }
    }
}