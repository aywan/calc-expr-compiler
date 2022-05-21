<?php
declare(strict_types=1);

namespace app\Tokenizer;

class TokenList
{
    /**
     * @var Token[]
     */
    public array $tokens = [];

    public function pushToken(TokenType $type, string $value, int $position): void
    {
        if (TokenType::NONE === $type || "" === $value) {
            return;
        }

        $this->tokens[] = new Token($type, $value, $position);
    }

    /**
     * @return \Generator<Token>
     */
    public function each(): \Generator
    {
        foreach ($this->tokens as $token) {
            yield $token;
        }
    }

    public function getIterator(): TokenIterator
    {
        return new TokenIterator($this);
    }
}