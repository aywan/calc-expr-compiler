<?php
declare(strict_types=1);

namespace app\Lexer;

class LexemeList
{
    /**
     * @var Lexeme[]
     */
    public array $lexemes = [];

    public function push(?Lexeme $lexeme): void
    {
        if (null === $lexeme) {
            return;
        }
        $this->lexemes[] = $lexeme;
    }
}