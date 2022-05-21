<?php

declare(strict_types=1);

foreach (glob('./src/*.php') as $file) {
    require_once $file;
}
foreach (glob('./src/*/*.php') as $file) {
    require_once $file;
}

$tokenizer = new \app\Tokenizer\Tokenizer();
$tokens = $tokenizer->toToken('-2 + (-2 // 4) + (-(-16 % 10)) (2.55+1.150)  * 10 - (1 / 5 + 0.8 - 16 ^ 0.5) ^ 2.0 - sin(pi/2) + cos(sin(0.0/210)) + atan2(0**2, 1)'); // 25
$lexer = new \app\Lexer\Lexer();
$lexemes = $lexer->parse($tokens);
foreach ($lexemes->lexemes as $item) {
    echo $item, PHP_EOL;
}
