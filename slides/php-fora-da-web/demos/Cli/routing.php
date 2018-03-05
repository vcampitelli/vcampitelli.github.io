<?php
if ($argc != 3) {
    echo "Uso: {$argv[0]} <module> <command>" . PHP_EOL;
    exit(2);
}

array_shift($argv);
$module = array_shift($argv); // ou $options['module']
$class = "MyCli\Controllers\\{$module}";
if (!class_exists($class)) {
    throw new DomainException("Módulo {$module} não encontrado");
}

$command = array_shift($argv); // ou $options['command']
if (!method_exists($class, $command)) {
    throw new DomainException("Comando {$command} não encontrado");
}

(new $class())->{$command}($argv); // ou $options
