<?php
echo "Qual seu nome? ";
$line = trim(fgets(STDIN));
echo "Bem-vindo, {$line}." . PHP_EOL;
