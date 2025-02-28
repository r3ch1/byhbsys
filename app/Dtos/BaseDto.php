<?php

namespace App\Dtos;

abstract class BaseDto
{
    public function fromArray(array $data)
    {
        foreach ($data as $key => $value) {
            $this->{$key} = $value;
        }
        return $this;
    }

    public function toArray():array
    {
        $vars = [];
        foreach (get_class_vars(get_class($this)) as $key => $value) {
            $vars[$key] = $this->{$key};
        }
        return $vars;
    }

    public function __get($name)
    {
        return $this->{$name};
    }
}
