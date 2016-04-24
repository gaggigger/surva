<?php

namespace Surva\Utilities;

class Helper
{
    public static function size($bytes)
    {
        $kb = 1024.00;
        $mb = 1024 * $kb;
        $gb = 1024 * $mb;
        $size = $bytes;
        $str = 'B';
        if ($bytes < $kb) {
            $str = '%dB';
        } elseif ($bytes < $mb) {
            $size /= $kb;
            $str = '%.2fKB';
        } elseif ($bytes < $gb) {
            $size /= $mb;
            $str = '%.2fMB';
        } else {
            $size /= $gb;
            $str = '%.2fGB';
        }

        return sprintf($str, $size);
    }

    public static function ellipsis($str)
    {
        if (strlen($str) > 10) {
            return sprintf('%.10s...', $str);
        } else {
            return $str;
        }
    }

    public static function duration($durms)
    {
        $s = 1000.00;
        $m = 60 * $s;
        $h = 60 * $m;
        $dur = $durms;
        $str = '%.0f';

        if ($durms < $s) {
            $str .= 'ms';
        } elseif ($durms < $m) {
            $dur /= $s;
            $str .= 's';
        } elseif ($durms < $h) {
            $dur /= $m;
            $str .= 'min';
        } else {
            $dur /= $h;
            $str .= 'hrs';
        }

        return sprintf($str, $dur);
    }
}
