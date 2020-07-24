<?php

namespace Zhukmax\Waymark\Types;

/**
 * Interface TypeInterface
 * @package Zhukmax\Waymark\Types
 */
interface TypeInterface
{
    public function header(): void ;

    /**
     * @param string|array $output
     */
    public function body($output): void ;
}
