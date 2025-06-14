<?php

$senha = 123;
$senhaa = password_hash($senha, PASSWORD_DEFAULT);

echo" $senhaa";