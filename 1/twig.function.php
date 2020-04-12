<?php

/**
  определение функций для TWIG
 */


$function = new Twig_SimpleFunction('items__get', function ( $db, $module, $stat = 'show', $sort = '' ) {

    return \Nyos\mod\items::get2($db, $module, $stat, $sort);
    //return \Nyos\mod\items::get($db, $module, $stat, $sort);
});
$twig->addFunction($function);






$function = new Twig_SimpleFunction('items__getItemsSimple2', function ( $db, $module, $filtr = [], $stat = 'show', $sort = '' ) {

//    if( $_SERVER['HTTP_HOST'] == 'razv2.uralweb.info' )
//    \Nyos\mod\items::$show_sql = true;
//    if ($sort == 'date_asc') {
//        \Nyos\mod\items::$sql_order = ' ORDER BY midop.id ASC ';
//    } elseif ($sort == 'date_desc') {
//        \Nyos\mod\items::$sql_order = ' ORDER BY midop.id DESC ';
//    } else
    //if ($sort == 'sort_asc') {
    // \Nyos\mod\items::$sql_order = ' ORDER BY mi.sort ASC ';
    //} 
//    elseif ($sort == 'sort_desc') {
//        \Nyos\mod\items::$sql_order = ' ORDER BY mi.sort DESC ';
//    }
//    \f\timer::start(987);
//    \f\CalcMemory::start(66);
    // $e = \Nyos\mod\items::getItemsSimple2($db, $module, $stat, $sort );
    $e = \Nyos\mod\items::getItemsSimple3($db, $module, $stat, $sort);

//    echo \f\timer::stop('str', 987);
//    echo '<br/>m '.\f\CalcMemory::stop ('str', 66);
//    if ($sort == 'sort_asc') {
//        
//    }
//    if( $_SERVER['HTTP_HOST'] == 'yapdomik.uralweb.info'){
//        echo '<br/>'.$module;
//        \f\pa($e);    
//        //die();
//    }

    return $e;
    //return \Nyos\Nyos::creatSecret($text);
});
$twig->addFunction($function);


$function = new Twig_SimpleFunction('items__get_old', function ( $db, $module, $filtr = [], $stat = 'show', $sort = '' ) {

    // $e = \Nyos\mod\items::getItemsSimple3($db, $module, $stat, $sort );
    $e = \Nyos\mod\items::getItemsSimple3_old($db, $module, $stat, $sort);

    return $e;
});
$twig->addFunction($function);

$function = new Twig_SimpleFunction('items__readItems', function ( $db, $module, $stat = 'show', $sort = '' ) {

    $var_cash = 'items_' . $module . $stat . $sort;

    // \f\Cash::start();
    $ee = \f\Cash::getVar($var_cash);
    if (!empty($ee))
        return $ee;

//    if( $_SERVER['HTTP_HOST'] == 'razv2.uralweb.info' )
//    \Nyos\mod\items::$show_sql = true;

    \Nyos\mod\items::$style_old = true;
    $e = \Nyos\mod\items::getItemsSimple3($db, $module, $stat, $sort);
//    $e = \Nyos\mod\items::getItemsSimple($db, $module, $stat, $sort);
//    if( $_SERVER['HTTP_HOST'] == 'bbb72.ru' )
//    \f\pa($e);

    \f\Cash::setVar($var_cash,$e);
    \f\Cash::close();

    return $e;
    //return \Nyos\Nyos::creatSecret($text);
});
$twig->addFunction($function);



















$function = new Twig_SimpleFunction('readItems', function ( $module, $stat = 'show' ) {

    global $db;

//    \f\timer_start(1);
    $e = \Nyos\mod\items::getItems($db, \Nyos\nyos::$folder_now, $module, $stat, null);
//    echo '1: '.\f\timer_stop(1);
//    
//    \f\timer_start(2);
//    $e = \Nyos\mod\items::get( $db, $module, $stat, null);
//    echo '2: '.\f\timer_stop(2);
    
    return $e;
    //return \Nyos\Nyos::creatSecret($text);
});
$twig->addFunction($function);












$function = new Twig_SimpleFunction('readItems2', function ( $db, $module, $stat = null ) {

    $e = \Nyos\mod\items::getItems($db, \Nyos\nyos::$folder_now, $module, $stat, null);

//    if( $_SERVER['HTTP_HOST'] == 'yapdomik.uralweb.info')
//    \f\pa($e);    

    return $e;
    //return \Nyos\Nyos::creatSecret($text);
});
$twig->addFunction($function);



$function = new Twig_SimpleFunction('getItemsSimple', function ( $db, $module, $stat = null ) {

    $e = \Nyos\mod\items::getItemsSimple($db, $module, $stat);

//    if( $_SERVER['HTTP_HOST'] == 'yapdomik.uralweb.info')
//    \f\pa($e);    

    return $e;
    //return \Nyos\Nyos::creatSecret($text);
});
$twig->addFunction($function);
