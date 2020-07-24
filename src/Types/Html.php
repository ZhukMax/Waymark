<?php

namespace Zhukmax\Waymark\Types;

/**
 * Class Html
 * @package Zhukmax\Waymark\Types
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
