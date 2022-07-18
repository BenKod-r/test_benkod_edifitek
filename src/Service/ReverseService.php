<?php

namespace App\Service;

class ReverseService
{
    /**
     * @param string $text
     * @return string
     */
    public function reverse(string $text): string
    {
        return ucfirst(implode(' ', array_reverse(explode(' ', $text))));
    }
}
