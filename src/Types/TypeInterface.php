<?php

namespace Zhukmax\SimpleRouter\Types;

/**
 * Interface TypeInterface
 * @package Zhukmax\SimpleRouter\Types
 */
interface TypeInterface
{
    public function header(): void ;

    /**
     * @param string|array $output
     */
    public function body($output): void ;
}
