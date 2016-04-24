<?php

namespace Surva\Utilities;

class File
{
    protected static $defaults = [
        'file' => '',
        'autoBackup' => false,
    ];

    public $basename, $dirname;
    protected $opts, $file, $data;

    public function __construct($opts)
    {
        $this->opts = static::$defaults;
        foreach ($opts as $key => $value) {
            $this->opts[$key] = $value;
        }
        $this->file = $this->opts['file'];
        if (!is_file($this->file)) {
            $this->data = '';
            file_put_contents($this->file, '');
            Log::info('Created "{name}"', ['name' => basename($this->file)]);
        } else {
            $this->read();
        }
    }

    public function read()
    {
        $this->data = file_get_contents($this->file);
        $this->basename = basename($this->file);
        $this->dirname = dirname($this->file);
        if (strlen($this->data) <= 0) {
            Log::critical(
                '"{name}" is empty!',
                ['name' => $this->basename]
            );
        }

        return $this->data;
    }

    /**
     * Write Data to file.
     *
     * @param string $data Data to be written to file
     *
     * @return int Number of bytes written to file, -1 if failed to write
     */
    public function write($data)
    {
        $result = -1;
        if (!isset($data) || strlen($data) == 0) {
            Log::warning('Emptying "{name}"', [
                'name' => $this->basename,
            ]);
        }
        if ($this->opts['autoBackup']
            && isset($this->data)
            && strlen($this->data) > 0
        ) {
            $this->backup();
        }
        $this->data = $data;
        $result = file_put_contents($this->file, $this->data);
        Log::info(
            'Wrote {size} to "{name}"',
            [
                'size' => Helper::size($result),
                'name' => $this->basename,
            ]
        );

        return $result;
    }

    public function backup()
    {
        $dir = $this->dirname;
        $name = $this->basename;
        $time = time();
        $backup = "{$dir}/{$time}-{$name}";
        file_put_contents($backup, $this->data);
        Log::info('Created backup "{name}"', ['name' => "{$time}-{$name}"]);

        return (string) $time;
    }

    public function restore($time)
    {
        $dir = $this->dirname;
        $name = $this->basename;
        $backup = "{$dir}/{$time}-{$name}";
        if (is_file($backup)) {
            $result = copy($backup, $this->file);
            Log::info('Restored "{backup}"', ['backup' => "{$time}-{$name}"]);
        } else {
            Log::emergency(
                'Attempting to restore missing file "{backup}"',
                ['backup' => "{$time}-{$name}"]
            );
            $result = false;
        }

        return $result;
    }

    public function getBackups()
    {
        $dir = $this->dirname;
        $name = $this->basename;
        $files = scandir($dir);
        $backups = [];
        foreach ($files as $file) {
            if (strpos($file, $name)) {
                $time = explode('-', $file)[0];
                array_push($backups, $time);
            }
        }
        $num = count($backups);

        return $backups;
    }

    public function get()
    {
        return $this->data;
    }
}
