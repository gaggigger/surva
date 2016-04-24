<?php

namespace Surva\Utilities;

class Log
{
    protected static $opts = [
        'enable' => true,
        'file' => 'log.txt',
        'showCaller' => false,
        'showDuration' => true,
        'dateFormat' => '[F j, Y h:iA]',
        'durationFormat' => '%04d',
        'ignoredClasses' => [
            'Surva\\Core\\Log',
            'Surva\\Core\\Result',
        ],
        'levels' => [
            'debug' => 'DEBG',
            'info' => '    ',
            'notice' => 'NOTE',
            'warning' => 'WARN',
            'error' => 'EROR',
            'critical' => 'CRIT',
            'alert' => 'ALRT',
            'emergency' => 'EMGY',
        ],
        'separator' => ' · ',
    ];

    protected static $duration, $logStr = '';

    public static function init($opts)
    {
        foreach ($opts as $key => $value) {
            static::$opts[$key] = $value;
        }
        static::start();
    }

    public static function log($level, $message = null, $context = null)
    {
        if (!static::$opts['enable']) {
            return;
        }
        $data = '';

        if (isset($level)) {
            if (!array_key_exists($level, static::$opts['levels'])) {
                $allowed = '['.implode(', ', static::$opts['levels']).']';
                throw new \InvalidArgumentException(
                    'First argument should be one of '.$allowed
                );
            }
        } else {
            throw new \InvalidArgumentException('Missing first argument');
        }

        if (isset($message)) {
            $msgType = gettype($message);
            if ($msgType != 'string' &&
                ($msgType == 'object' && method_exists($msg, '__toString'))
            ) {
                throw new \InvalidArgumentException(
                    'Second argument must be string or object with __toString() method.'
                );
            }
        }

        if (isset($context)) {
            $contextType = gettype($context);
            if ($contextType != 'array') {
                throw new \InvalidArgumentException(
                    'Third argument must be an array.'
                );
            }
        }

        static::$logStr .= static::build($level, $message, $context);
    }

    private static function build($level, $message, $context)
    {
        $sep = static::$opts['separator'];
        $data = static::interpolate($message, $context);
        $level = static::$opts['levels'][$level].$sep;
        $caller = '';
        $duration = '';

        if (static::$opts['showCaller']) {
            $caller = static::getCaller().$sep;
        }

        if (static::$opts['showDuration']) {
            if (static::$duration == null) {
                static::$duration = microtime();
            }
            $duration = microtime(true) - static::$duration;
            $duration = sprintf(
                static::$opts['durationFormat'],
                $duration * 1000.00
            );
            $duration .= $sep;
        }

        return "{$level}{$duration}{$caller}{$data}\n";
    }

    public static function interpolate($message, $context = null)
    {
        if (!isset($context)) {
            return $message;
        }
        $replace = array();
        foreach ($context as $key => $val) {
            $replace['{'.$key.'}'] = $val;
        }

        return strtr($message, $replace);
    }

    private static function getCaller()
    {
        $trace = debug_backtrace();
        $ignoredClasses = static::$opts['ignoredClasses'];

        $caller = '{main}';

        for ($i = 1; $i < count($trace); ++$i) {
            if (isset($trace[$i]['class'])
                && !in_array($trace[$i]['class'], $ignoredClasses)
            ) {
                $callerClass = '';
                $callerFunc = '';
                $callerClass = explode('\\', $trace[$i]['class']);
                $callerClass = $callerClass[count($callerClass) - 1].':';
                if (isset($trace[$i]['function'])) {
                    $callerFunc = $trace[$i]['function'];
                }
                $caller = $callerClass.$callerFunc;

                return $caller;
            }
        }

        return $caller;
    }

    public static function __callStatic($name, $args)
    {
        if (array_key_exists($name, static::$opts['levels'])) {
            if (isset($args)) {
                $args[0] = isset($args[0]) ? $args[0] : '';
                $args[1] = isset($args[1]) ? $args[1] : null;
                static::log($name, $args[0], $args[1]);
            } else {
                static::log($name);
            }
        } else {
            throw new \BadMethodCallException(
                "No implementation for {$name}"
            );
        }
    }

    public static function enable()
    {
        static::$opts['enable'] = true;
    }

    public static function disable()
    {
        static::$opts['enable'] = false;
    }

    public static function start()
    {
        $date = new \DateTime();
        $date = $date->format(static::$opts['dateFormat']);
        $sep = static::$opts['separator'];
        $method = $_SERVER['REQUEST_METHOD'];
        $path = $_SERVER['REQUEST_URI'];
        $request = "{$method} \"{$path}\"";
        static::$duration = microtime(true);
        static::$logStr .= PHP_EOL;
        static::log('notice', $request.$sep.$date);
    }

    public static function end()
    {
        $sep = static::$opts['separator'];
        $dur = (microtime(true) - static::$duration) * 1000.00;
        $dur = sprintf('%.2fms', $dur);
        $peakReal = memory_get_peak_usage(true);
        // static::enable();
        static::log(
            'notice',
            'Finished in {dur} using {real}',
            [
                'dur' => $dur,
                'real' => Helper::size($peakReal),
            ]
        );

        if (is_file(static::$opts['file'])) {
            file_put_contents(
                static::$opts['file'],
                static::$logStr,
                FILE_APPEND
            );
        }
    }
}
