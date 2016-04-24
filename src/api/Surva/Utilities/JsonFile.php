<?php

namespace Surva\Utilities;

class JsonFile extends File
{
    protected $obj, $results;

    public function __construct($opts)
    {
        parent::__construct($opts);
        $this->initJson();
    }

    public function first()
    {
        $result = null;
        if (isset($this->results) && count($this->results) > 0) {
            $result = $this->results[0];
            $this->results = null;
        }

        return $result;
    }

    protected function initJson()
    {
        if (isset($this->data)) {
            $this->obj = json_decode($this->data);
            $num = 1;
            if (gettype($this->obj) == 'array') {
                $num = count($this->obj);
            }
            if (json_last_error() == JSON_ERROR_NONE) {
                Log::info(
                    'Parsed "{name}", {num} record(s)',
                    ['name' => $this->basename, 'num' => $num]
                );
            } else {
                Log::emergency(
                    'Invalid syntax in "{name}"',
                    ['name' => $this->basename]
                );
            }
        }
    }

    public function get()
    {
        $result = null;

        if (isset($this->results)) {
            $result = $this->results;
            $this->results = null;
        }

        return $result;
    }

    public function put($obj)
    {
        if (isset($obj)) {
            $this->obj = $obj;

            return $this->write(json_encode($this->obj));
        } else {
            return;
        }
    }

    public function where($key, $value, $opr = '==')
    {
        if (!isset($this->results)) {
            $this->results = $this->obj;
        }
        if (count($this->results) == 0) {
            return;
        }
        $results = [];
        foreach ($this->results as $record) {
            if (isset($record->{$key})) {
                $append = false;
                if (gettype($opr) == 'string') {
                    $append = $this->compare($record->{$key}, $opr, $value);
                } elseif (is_callable($opr)) {
                    $append = call_user_func_array(
                        $opr,
                        [
                            $record->{$key},
                            $value,
                        ]
                    );
                }
                if ($append) {
                    array_push($results, $record);
                }
            }
        }
        $query = is_callable($opr) ?
            "fn(\"$key\", \"$value\")" :
            "\"{$key}\" {$opr} \"{$value}\"";
        Log::info(
            'Matched {num} in "{name}" {query}',
            [
                'num' => count($results),
                'name' => $this->basename,
                'query' => $query,
            ]
        );
        $this->results = $results;

        return $this;
    }

    private function compare($left, $opr, $right)
    {
        $str = '$result = ($left'.$opr.'$right);';
        eval($str);

        return $result;
    }
}

/*

    public function traverse($key, $opr, $value, $obj = null)
    {
        if ($obj == null) {
            $obj = $this->obj;
            $this->results = [];
        }
        $type = gettype($obj);
        if ($type == 'object') {
            if (isset($obj->nodes) && count($obj->nodes) > 0) {
                foreach ($obj->nodes as $record) {
                    $this->traverse($key, $opr, $value, $record);
                }
            }
            if (isset($obj->{$key})
                && $this->compare($obj->{$key}, $opr, $value)) {
                array_push($this->results, $obj);
            }
        } elseif ($type == 'array') {
            foreach ($obj as $record) {
                if (isset($record->{$key})
                  && $this->compare($record->{$key}, $opr, $value)) {
                    array_push($this->results, $record);
                }
            }
        }

        return $this;
    }

 */
