<?php

namespace Niyama\FileManager\Structures;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

class FileData implements Jsonable, Arrayable, \Stringable
{
    public array $frames = [];

    public static function fromArray($data)
    {

        $instance = new FileData();
        $instance->frames = array_key_exists('frames', $data) ? $data['frames'] : [];

        return $instance;
    }

    public function toJson($options = 0)
    {
        return json_encode($this);
    }

    public function toArray()
    {
        return [
            'frames' => $this->frames,
        ];
    }



    public function __toString(): string
    {
        return $this->toJson();

    }
}