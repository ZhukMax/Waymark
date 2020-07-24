<?php

namespace Zhukmax\Waymark\Types;

/**
 * Class Text
 * @package Zhukmax\Waymark\Types
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
