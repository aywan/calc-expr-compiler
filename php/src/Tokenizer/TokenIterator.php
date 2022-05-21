<?php
declare(strict_types=1);

namespace app\Tokenizer;

class TokenIterator
{
    private TokenList $list;
    private int $pos;

    public function __construct(TokenList $list)
    {
        $this->list = $list;
        $this->pos = 0;
    }

    public function hasNext(): bool
    {
        return isset($this->list->tokens[$this->pos + 1]);
    }

    public function move(int $d = 1): void
    {
        $this->pos += $d;
    }

    public function current(): Token
    {
        return $this->list->tokens[$this->pos];
    }

    public function getNext(int $pos): ?Token
    {
        $pos = $this->pos + $pos;

        return $this->list->tokens[$pos] ?? null;
    }

    public function skip(TokenType $type): void
    {
        while (isset($this->list->tokens[$this->pos]) && $this->list->tokens[$this->pos]->type === $type) {
            $this->pos++;
        }
    }
}