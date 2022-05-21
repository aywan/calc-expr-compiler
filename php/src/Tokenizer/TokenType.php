<?php
declare(strict_types=1);

namespace app\Tokenizer;

enum TokenType
{
    case NONE;
    case SPACE;
    case NUMBER;
    case NAME;
    case SEP;

    public function string(): string
    {
        return $this->name;
    }
}