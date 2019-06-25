<?php



/**
определение функций для TWIG
 */


$function = new Twig_SimpleFunction('readItems', function ( $module, $stat = 'show' ) {
     
    global $db;
    
    $e = \Nyos\mod\items::getItems( $db, \Nyos\nyos::$folder_now, $module, $stat, null );
    
    return $e;
    //return \Nyos\Nyos::creatSecret($text);
 });
 $twig->addFunction($function);
 
$function = new Twig_SimpleFunction('readItems2', function ( $db, $module, $stat = null ) {
     
    $e = \Nyos\mod\items::getItems( $db, \Nyos\nyos::$folder_now, $module, $stat, null );
    
    return $e;
    //return \Nyos\Nyos::creatSecret($text);
 });
 $twig->addFunction($function);
