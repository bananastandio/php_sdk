<?php

namespace Banana\Models;

use Banana\Client;

abstract class Base
{
    protected $client;

    protected static $resourcePath;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setClient(Client $client)
    {
        $this->client = $client;
    }

    public static function find(Client $client, $id)
    {
        $resp = $client->get(static::$resourcePath . "/{$id}.json")->getBody();
        $data = json_decode($resp, true);
        $obj = new static($data);
        $obj->setClient($client);
        return $obj;
    }

    public static function fetch(Client $client, $page = 1)
    {
        $objs = array();
        $resp = $client->get(static::$resourcePath . ".json")->getBody();
        $rows = json_decode($resp, true);

        foreach ($rows as $eventData) {
            $obj = new static($eventData);
            $obj->setClient($client);
            $objs[] = $obj;
        }
        return $objs;
    }

    public function delete()
    {
        return $this->client->delete(static::$resourcePath . "/{$this->id}.json");
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }

        $trace = debug_backtrace();
        trigger_error(
            'Undefined property via __get(): ' . $name .
            ' in ' . $trace[0]['file'] .
            ' on line ' . $trace[0]['line'],
            E_USER_NOTICE);
        return null;
    }


}
