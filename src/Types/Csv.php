<?php

namespace Zhukmax\SimpleRouter\Types;

/**
 * Class Csv
 * @package Zhukmax\SimpleRouter\Types
 */
class Csv implements TypeInterface
{
    public function header(): void
    {
        $date = date("Ymd_Hms");
        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=$date.csv");
        header("Pragma: no-cache");
        header("Expires: 0");
    }

    /**
     * @param string|array $output
     */
    public function body($output): void
    {
        foreach ($output as $item) {
            echo $item . "\n";
        }
    }
}
