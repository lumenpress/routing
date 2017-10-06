<?php

namespace LumenPress\Lumen;

trait DispatcherResolver
{
    protected $dispatcherResolver;

    public function setDispatcherResolver(\Closure $resolver)
    {
        $this->dispatcherResolver = $resolver;
    }

    protected function createDispatcher()
    {
        if (isset($this->dispatcherResolver)) {
            return call_user_func($this->dispatcherResolver);
        }

        return parent::createDispatcher();
    }
}
