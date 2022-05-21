<?php
declare(strict_types=1);

namespace app\Lexer;

use app\Tokenizer\Token;
use app\Tokenizer\TokenIterator;
use app\Tokenizer\TokenList;
use app\Tokenizer\TokenType;

class Lexer
{
    private const NONE = 0;
    private const NEXT = 1;
    private const NUMBER = 2;
    private const AFTER_NUMBER = 3;
    private const UNARY_MINUS = 4;

    /**
     * @param TokenList $tokens
     * @return LexemeList
     * @throws LexerParseException
     */
    public function parse(TokenList $tokens): LexemeList
    {
        $lexemes = new LexemeList();

        $tokenGen = $tokens->getIterator();

        while ($tokenGen->hasNext()) {
            $cur = $tokenGen->current();
            if ($cur === null) {
                continue;
            }
            $lex = null;
            switch ($cur->type) {
                case TokenType::SPACE:
                    $tokenGen->skip(TokenType::SPACE);
                    break;

                case TokenType::NUMBER:
                    $lex = $this->getNumberLex($tokenGen);
                    break;

                case TokenType::SEP:
                    $lex = $this->geSepLex($tokenGen);
                    break;

                case TokenType::NAME:
                    $lex = new Lexeme(LexemeType::IDENTIFIER, $cur->value, $cur->position);
                    $tokenGen->move();
                    break;

                default:
                    throw new LexerParseException("unknown token type {$cur}", $cur->position);
            }

            $lexemes->push($lex);
        }

        return $lexemes;
    }

    /**
     * @param TokenIterator $tokenGen
     * @return array
     */
    private function parseNoneState(TokenIterator $tokenGen, array &$buf): array
    {
        $tokenGen->skip(TokenType::SPACE);
        $cur = $tokenGen->current();
        \assert($cur instanceof Token);

        switch ($cur->type) {
            case TokenType::NUMBER:
                $buf[] = $cur;
                $tokenGen->move();
                return [null, self::NUMBER];

            case TokenType::SEP:
                if ($cur->value === '-') {
                    $buf[] = $cur;
                    $tokenGen->move();
                    return [null, self::UNARY_MINUS];
                }

                if ($cur->value === '(') {
                    $tokenGen->move();
                    return [new Lexeme(LexemeType::BRACKET_OPEN, $cur->value, $cur->position), self::NONE];
                }

                if ($cur->value === ')') {
                    $tokenGen->move();
                    return [new Lexeme(LexemeType::BRACKET_CLOSE, $cur->value, $cur->position), self::NONE];
                }
        }

        return [new Lexeme(LexemeType::UNKNOWN, $cur->value, $cur->position), self::NONE];
    }

    /**
     * @throws LexerParseException
     */
    private function getNumberLex(TokenIterator $tokenGen): Lexeme
    {
        $cur = $tokenGen->current();
        \assert($cur !== null && $cur->type === TokenType::NUMBER);
        $position = $cur->position;
        $buf = $cur->value;

        $next = $tokenGen->getNext(1);
        if ($next !== null && $next->type === TokenType::SEP && $next->value === '.') {
            $buf .= $next->value;

            $next = $tokenGen->getNext(2);
            if (null === $next || $next->type !== TokenType::NUMBER) {
                $buf .= $next->value ?? '';
                throw new LexerParseException("number parse error: '$buf' expected number after dot", $position);
            }
            $buf .= $next->value;

            $tokenGen->move(2);
        } else {
            $tokenGen->move();
        }

        return new Lexeme(LexemeType::NUMBER, $buf, $position);
    }

    private function geSepLex(TokenIterator $tokenGen): Lexeme
    {
        $cur = $tokenGen->current();

        switch ($cur->value) {
            case '(':
                $lex = new Lexeme(LexemeType::BRACKET_OPEN, $cur->value, $cur->position);
                break;

            case ')':
                $lex = new Lexeme(LexemeType::BRACKET_CLOSE, $cur->value, $cur->position);
                break;

            case ',':
                $lex = new Lexeme(LexemeType::PERIOD, $cur->value, $cur->position);
                break;

            case '+':
            case '-':
            case '^':
            case '%':
                $lex = new Lexeme(LexemeType::OPERATOR, $cur->value, $cur->position);
                break;

            case '*':
            case '/':
                $next = $tokenGen->getNext(1);
                if ($next !== null && $next->type === TokenType::SEP && $next->value === $cur->value) {
                    $lex = new Lexeme(LexemeType::OPERATOR, $cur->value . $next->value, $cur->position);
                    $tokenGen->move();
                } else {
                    $lex = new Lexeme(LexemeType::OPERATOR, $cur->value, $cur->position);
                }
                break;

            default:
                throw new LexerParseException("unknown token {$cur->value}", $cur->position);
        }

        $tokenGen->move();

        return $lex;
    }
}