<?php

declare(strict_types=1);

namespace app\Tokenizer;

class Tokenizer
{
    public function toToken(string $source): TokenList
    {
        $list = new TokenList();

        $len = mb_strlen($source);
        $buf = '';
        $position = 0;
        $state = TokenType::NONE;

        for ($i = 0; $i < $len; $i++) {

            $c = $source[$i];

            switch (true) {
                case $c === ' ':
                    if ($state === TokenType::SPACE) {
                        $buf .= $c;
                    } else {
                        $list->pushToken($state, $buf, $position);
                        $buf = $c;
                        $state = TokenType::SPACE;
                        $position = $i;
                    }
                    break;

                case ctype_digit($c):
                    if ($state === TokenType::NAME || $state=== TokenType::NUMBER) {
                        $buf .= $c;
                    } else {
                        $list->pushToken($state, $buf, $position);
                        $buf = $c;
                        $state = TokenType::NUMBER;
                        $position = $i;
                    }
                    break;

                case ctype_alpha($c) || $c === '_':
                    if ($state === TokenType::NAME) {
                        $buf .= $c;
                    } else {
                        $list->pushToken($state, $buf, $position);
                        $buf = $c;
                        $state = TokenType::NAME;
                        $position = $i;
                    }
                    break;

                case in_array($c, ['+','-','*','/','^', '.', ',', '(', ')', '%'], true):
                    $list->pushToken($state, $buf, $position);
                    $buf = $c;
                    $state = TokenType::SEP;
                    $position = $i;
                    break;

                default:
                    throw new \LogicException('unknown token type pos = ' . $i);
            }
        }
        $list->pushToken($state, $buf, $position);

        return $list;
    }
}