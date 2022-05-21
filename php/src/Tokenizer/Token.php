<?php
declare(strict_types=1);

namespace app\Tokenizer;

class Token
{
    public function __construct(
        public readonly TokenType $type,
        public readonly string    $value,
        public readonly int       $position,
    )
    {
    }

    public function __toString(): string
    {
        return "{$this->type->string()} '$this->value' pos=$this->position";
    }
}