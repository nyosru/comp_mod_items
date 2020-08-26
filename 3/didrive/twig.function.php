<?php

//
///**
//  определение функций для TWIG
// */
//
$function = new Twig_SimpleFunction('items__get_file_didrive_tpls', function () {

    $e = scandir( dirname(__FILE__).'/tpl/' );
    
    $re = [];
    foreach( $e as $k => $v ){
        $re[$v] = 1;
    }
    
    return $re;
});
$twig->addFunction($function);
//
//
//
//
//$function = new Twig_SimpleFunction('add_items_on_modal', function ( $module, $data = [], $default = [], $skip = [] ) {
//
//    global $db;
//
//    if (isset(\Nyos\nyos::$menu[$module])) {
//        \f\pa(\Nyos\nyos::$menu[$module], 2);
//    }
//
//    \f\pa(\Nyos\nyos::$menu, 2);
//
//    // $e = \Nyos\mod\items::getItems( $db, \Nyos\nyos::$folder_now, $module, $stat, null );
//    // return $e;
//    //return \Nyos\Nyos::creatSecret($text);
//});
//$twig->addFunction($function);
//
//$function = new Twig_SimpleFunction('get_site_user', function ( $db, $folder = '' ) {
//
//    if (empty($folder)) {
//        $folder = \Nyos\Nyos::$folder_now;
//    }
//
//    $s = $db->prepare('SELECT * FROM `gm_user` WHERE `folder` = :folder ');
//    $s->execute(array(':folder' => $folder));
//    $r = $s->fetchAll();
//    // \f\pa($r);
//
//    return $r;
//
//    //\f\pa(\Nyos\nyos::$menu,2);
//    // $e = \Nyos\mod\items::getItems( $db, \Nyos\nyos::$folder_now, $module, $stat, null );
//    // return $e;
//    //return \Nyos\Nyos::creatSecret($text);
//});
//$twig->addFunction($function);
//
$function = new Twig_SimpleFunction('items__getUsersDi', function ( $db, $and_access = false ) {

    // echo '123123';
    // $return = \Nyos\mod\lk::getUsers($db, $folder );
    // $return = \Nyos\Mod\Lk::getUsers($db, null, 'moder' );
    $return = \Nyos\Mod\Lk::getUsers($db);

    if ($and_access === true) {
        $access = \Nyos\mod\AdminAccess::getModerAccess($db);
        //\f\pa($access);

        foreach ($access as $k => $v) {
            if (!empty($return[$k])) {
                $return[$k]['access'] = $v;
            }
        }
    }

    return $return;
});
$twig->addFunction($function);
