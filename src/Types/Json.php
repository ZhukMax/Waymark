<?php

namespace Zhukmax\SimpleRouter\Types;

/**
 * Class Json
 * @package Zhukmax\SimpleRouter\Types
 */
class Json implements TypeInterface
{
    public function header(): void
    {
        header('Content-Type: application/json');
    }

    /**
     * @param string|array $output
     */
    public function body($output): void
    {
        print_r(json_encode($output, JSON_UNESCAPED_UNICODE));
    }
}
