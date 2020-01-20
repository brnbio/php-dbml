<?php

declare(strict_types=1);

use Symfony\Component\VarDumper\VarDumper;

if (!function_exists('dd')) {
    function dd(...$vars): void
    {
        foreach ($vars as $var) {
            VarDumper::dump($var);
        }
        die();
    }
}