<?php
declare(strict_types=1);

namespace app\Lexer;

enum LexemeType
{
    case UNKNOWN;
    case OPERATOR;
    case NUMBER;
    case BRACKET_OPEN;
    case BRACKET_CLOSE;
    case PERIOD;
    case IDENTIFIER;
    case CONST;
    case FUNCTION;

    public function string(): string
    {
        return $this->name;
    }
}