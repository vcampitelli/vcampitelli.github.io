<?php
define('BASH_COLOR_RESET', "\e[m");
define('ASK_FOR_EMAIL_TEXT', BASH_COLOR_RESET . "Qual o email? (tecle ENTER para terminar) \e[0;33m");

// Identifica se é um shell interativo ou se estamos fornecendo a stream toda (por exemplo, por pipe)
$interactive = ftell(STDIN) === false;

$interactive && print ASK_FOR_EMAIL_TEXT;

while (($line = trim(fgets(STDIN, 256))) !== false) {
    // Enter
    if ($line === '') {
        break;
    }

    print (filter_var($line, FILTER_VALIDATE_EMAIL) === false)
        ? "\e[0;31m[ ✕ ] Email inválido"
        : "\e[0;32m[ ✔ ] Email válido";

    print BASH_COLOR_RESET;
    !$interactive && print " ({$line})";
    print PHP_EOL;

    $interactive && print PHP_EOL . ASK_FOR_EMAIL_TEXT;
}

// Limpa o terminal
echo BASH_COLOR_RESET;
