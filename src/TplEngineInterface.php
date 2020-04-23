<?php

namespace Zhukmax\SimpleRouter;

/**
 * Interface TplEngineInterface
 * @package Zhukmax\SimpleRouter
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
