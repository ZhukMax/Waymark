<?php

namespace Zhukmax\SimpleRouter\Types;

/**
 * Class Html
 * @package Zhukmax\SimpleRouter\Types
 */
class Html implements TypeInterface
{
    public function header(): void
    {
        header("Content-Type: text/html");
    }

    /**
     * @param string|array $output
     */
    public function body($output): void
    {
        print_r($output);
    }
}
