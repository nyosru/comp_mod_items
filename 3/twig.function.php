<?php

$function = new Twig_SimpleFunction('items__get', function ( $db, $module, $stat = 'show', $sort = '' ) {

    try {
        
        \Nyos\mod\items::$var_ar_for_1sql = [];        
        return \Nyos\mod\items::get($db, $module, $stat, $sort);

    } catch (Exception $exc) {

        \f\pa($exc);
        return false;

    }

});
$twig->addFunction($function);
