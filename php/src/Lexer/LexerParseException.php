<?php
declare(strict_types=1);

namespace app\Lexer;

class LexerParseException extends \Exception
{
    public function __construct(string $message, int $position, ?\Throwable $previous = null)
    {
        parent::__construct("lexical error: " . $message . " (at: {$position})", 0, $previous);
    }
}