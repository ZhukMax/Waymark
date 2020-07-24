<?php

namespace Zhukmax\Waymark\Interfaces;

/**
 * Interface TplEngineInterface
 * @package Zhukmax\Waymark\Interfaces
 */
interface TplEngineInterface
{
    /**
     * @param string $name
     * @param array $context
     * @return string
     */
    public function render(string $name, array $context);
}
