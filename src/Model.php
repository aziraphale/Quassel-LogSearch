<?php

namespace QuasselLogSearch;

abstract class Model
{
    public function __get($name)
    {
        if (!empty(static::$publicPropertiesRead) && in_array($name, static::$publicPropertiesRead, true)) {
            return $this->{$name};
        }

        trigger_error("Invalid property `$name`", E_USER_WARNING);
        return null;
    }

    public function __set($name, $value)
    {
        if (!empty(static::$publicPropertiesWrite) && in_array($name, static::$publicPropertiesWrite, true)) {
            $this->{$name} = $value;
        } else {
            trigger_error("Invalid property `$name`", E_USER_WARNING);
        }
    }

    public function __isset($name)
    {
        return (!empty(static::$publicPropertiesRead) && in_array($name, static::$publicPropertiesRead, true) && isset($this->{$name}));
    }

    public function __unset($name)
    {
        if (!empty(static::$publicPropertiesWrite) && in_array($name, static::$publicPropertiesWrite, true)) {
            unset($this->{$name});
        } else {
            trigger_error("Invalid property `$name`", E_USER_WARNING);
        }
    }
}
