<?php


if( !isset($di_mod) )
$di_mod = [];

$di_mod['items'][1] = array(
    'dir' => dirname(__FILE__).DIRECTORY_SEPARATOR
);

require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'class.php';