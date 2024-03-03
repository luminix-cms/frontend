<?php

namespace Luminix\Frontend\Services;

class JsService {

    private $data = [];

    private $catchables = [];

    public function set($key, $value)
    {
        $this->data[$key] = $value;
    }

    public function get($key)
    {
        if (!isset($this->data[$key])) {
            return null;
        }

        return $this->data[$key];
    }

    public function catches($key)
    {
        $keys = is_array($key) ? $key : [$key];

        foreach ($keys as $key) {
            if (!in_array($key, $this->catchables)) {
                $this->catchables[] = $key;
            }
        }
    }

    public function all()
    {
        return $this->data;
    }

    public function catchables()
    {
        return $this->catchables;
    }

}

