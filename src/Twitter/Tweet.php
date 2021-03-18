<?php

namespace CViniciusSDias\AnaliseSentimento\Twitter;

use Stringable;

class Tweet implements Stringable
{
    public function __construct(private string $text)
    {
    }

    public function __toString(): string
    {
        return $this->text;
    }
}
