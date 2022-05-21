<?php
declare(strict_types=1);

namespace app\Lexer;

class Lexeme
{
    public function __construct(
        public readonly LexemeType $type,
        public readonly string $value,
        public readonly int $position,
    )
    {
    }

    public function __toString(): string
    {
        return "{$this->type->string()} '$this->value' pos=$this->position";
    }
}