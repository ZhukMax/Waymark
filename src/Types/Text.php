<?php

namespace Zhukmax\SimpleRouter\Types;

/**
 * Class Text
 * @package Zhukmax\SimpleRouter\Types
 */
class Text implements TypeInterface
{
    public function header(): void
    {
        header("Content-Type: text/plain");
    }

    /**
     * @param string|array $output
     */
    public function body($output): void
    {
        print_r($output);
    }
}
