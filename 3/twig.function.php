<?php


$__twig['items__get'] = 3;

$function = new Twig_SimpleFunction('items3__get', function ( $db, $module, $stat = 'show', $sort = '' ) {

    try {
        // $e = '';
        \Nyos\mod\items::$type_module = 3;
        $e = \Nyos\mod\items::get($db, $module, $stat, $sort);
        return $e;
    } catch (Exception $exc) {
        \f\pa( $exc );
        return false;
    }

    // return \Nyos\mod\items::get($db, $module, $stat, $sort);
    //return \Nyos\mod\items::get($db, $module, $stat, $sort);
});
$twig->addFunction($function);

