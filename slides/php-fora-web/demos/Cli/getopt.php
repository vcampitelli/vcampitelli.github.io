<?php
$options = getopt('ab:c::', ['verbose', 'user:', 'password::']);
var_dump($options);
