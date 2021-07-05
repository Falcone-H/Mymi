<?php
namespace app\controller;

class Error extends Base
{
    public function __call($method, $args)
    {
        return $this->create([], 'Page not found', 404);
    }
}