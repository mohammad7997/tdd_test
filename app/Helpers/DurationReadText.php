<?php

namespace App\Helpers;

class DurationReadText
{
    protected $duration_read_for_one_word = 1;
    protected $duration_read;

    public function __construct(string $text)
    {
        $count_text = count(explode(' ',$text));
        $this->duration_read = $this->duration_read_for_one_word * $count_text;
    }

    public function get_seconde()
    {
        return $this->duration_read;
    }
    public function get_minutes()
    {
        return $this->duration_read/60;
    }
}
