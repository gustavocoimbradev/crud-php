<?php

function Autoload($className) {
    $extension =  spl_autoload_extensions();
    require_once (__DIR__ . '/' . $className . $extension);
}
spl_autoload_extensions('.php');
spl_autoload_register('Autoload');