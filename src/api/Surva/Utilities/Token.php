<?php

namespace Surva\Utilities;

class Token
{
    public static function generate(
        $length = 128,
        $characters = 'abcdefghijklmnopqrstuvwxyz1234567890'
    ) {
        $factory = new \RandomLib\Factory();
        $generator = $factory->getMediumStrengthGenerator();

        return $generator->generateString($length, $characters);
    }
}
