<?php

namespace App;

trait Shortcodes
{

    /**
     * Boot function from laravel.
     */
    public function generateShortcode($length = 5)
    {
        $alphabet = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '0', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W'];

        $shortcode = [];
        for($i = 0; $i < $length; $i++) {
            $shortcode[] = $alphabet[random_int(0, count($alphabet)-1)];
        }

        $this->shortcode = implode('', $shortcode);
    }
}
