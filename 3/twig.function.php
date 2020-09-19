<?php

$function = new Twig_SimpleFunction('items__get', function ( $db, $module, $stat = 'show', $sort = '' ) {

    \Nyos\mod\items::$var_ar_for_1sql = [];

    // echo __FILE__;
    
    try {

        if ($stat == 'show_id') {

            $e = \Nyos\mod\items::get($db, $module, $stat, $sort);
            // \f\pa($e);

            $e1 = [];
            foreach ($e as $k => $v) {
//                \f\pa($v);
                $e1[$v['id']] = $v;
            }
            return $e1;
            
        } else {

            // \Nyos\mod\items::$show_sql = true;
            return \Nyos\mod\items::get($db, $module, $stat, $sort);
            
        }
    } catch (Exception $exc) {

        \f\pa($exc);
        return false;
    }
});
$twig->addFunction($function);
