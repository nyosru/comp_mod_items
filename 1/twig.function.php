<?php

/**
  определение функций для TWIG
 */
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
    $e = \Nyos\mod\items::getItemsSimple3($db, $module, $stat, $sort );

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
    $e = \Nyos\mod\items::getItemsSimple3_old($db, $module, $stat, $sort );
    
    return $e;

});
$twig->addFunction($function);





$function = new Twig_SimpleFunction('items__readItems', function ( $db, $module, $stat = 'show', $sort = '' ) {

//    if( $_SERVER['HTTP_HOST'] == 'razv2.uralweb.info' )
//    \Nyos\mod\items::$show_sql = true;

    if ($sort == 'date_asc') {
        \Nyos\mod\items::$sql_order = ' ORDER BY midop.id ASC ';
    } elseif ($sort == 'date_desc') {
        \Nyos\mod\items::$sql_order = ' ORDER BY midop.id DESC ';
    } elseif ($sort == 'sort_asc') {
        \Nyos\mod\items::$sql_order = ' ORDER BY mi.sort ASC ';
    } elseif ($sort == 'sort_desc') {
        \Nyos\mod\items::$sql_order = ' ORDER BY mi.sort DESC ';
    }

    $e = \Nyos\mod\items::getItemsSimple($db, $module, $stat);

    return $e;
    //return \Nyos\Nyos::creatSecret($text);
});
$twig->addFunction($function);















$function = new Twig_SimpleFunction('readItems', function ( $module, $stat = 'show' ) {

    global $db;

    $e = \Nyos\mod\items::getItems($db, \Nyos\nyos::$folder_now, $module, $stat, null);

//    if( $_SERVER['HTTP_HOST'] == 'yapdomik.uralweb.info')
//    \f\pa($e);    
    
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
