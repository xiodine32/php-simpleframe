<?php
/**
 * Created by PhpStorm.
 * User: xiodine
 * Date: 2018-04-17
 * Time: 21:39
 */

namespace Engine;

class Request
{
    public $type = '';
    public $path = [];
    public $gets = [];
    public $posts = [];
    public $files = [];

    /**
     * @param $variable string
     * @return bool
     */
    public function has($variable)
    {
        if (isset($this->files[$variable]))
            return true;
        if (isset($this->posts[$variable]))
            return true;
        if (isset($this->gets[$variable]))
            return true;

        return false;
    }

    /**
     * @param $variable string
     * @param mixed $default
     * @return mixed|null
     */
    public function get($variable, $default = null)
    {
        if (isset($this->files[$variable]))
            return $this->files[$variable];
        if (isset($this->posts[$variable]))
            return $this->posts[$variable];
        if (isset($this->gets[$variable]))
            return $this->gets[$variable];

        return $default;
    }
}