<?php



if (!empty($_REQUEST['items__refresh_db'])) {

    \f\pa('грузим старые базы в новые');
    
    // die('23');
    
    \f\timer_start(233);
    // die('123');
    $list = [];
    
    \Nyos\nyos::getMenu();
    
    // \f\pa(\Nyos\nyos::$menu);
    // die();
    
    foreach (\Nyos\nyos::$menu as $k => $v) {
        if (isset($v['type']) && $v['type'] == 'items') {

            \f\pa($k);
            
            if (isset($_REQUEST['only'])) {
                if ($_REQUEST['only'] == $k) {
                    $list[] = $k;
                    $e = \f\db\db_creat_local_table($db, $k, null, true);
                    \f\pa($e);
                    break;
                }
            } else {
                $list[] = $k;
                // echo '<br/>' . $k;
                // \f\pa($v);
                $e = \f\db\db_creat_local_table($db, $k, null, true);
                \f\pa($e);
            }
        }
    }

    // $timer = \f\timer_stop(233);

//    $skip_start = true;
//    require_once DR . '/vendor/didrive_mod/jobdesc/1/didrive/micro-service/creat-db-summ-table.php';

    $timer = \f\timer_stop(233);

    if (isset($_REQUEST['show_res']) && $_REQUEST['show_res'] == 'no') {
        die('end');
    } else {
        \f\end2('ok', true, [$list, $timer]);
    }
    
    die( 'обновление баз данных закончено' );
    
}






//f\pa($vv['now_inf_cfg']);

if (isset($vv['now_inf_cfg']['load_inf']) && $vv['now_inf_cfg']['load_inf'] == 'da') {
    //$vv['inf']['items'][$vv['now_inf_cfg']['cfg.level']] = \Nyos\mod\items::getItems($db, $vv['folder'], $vv['now_inf_cfg']['cfg.level']);
    try{
        $vv['inf']['items'][$vv['now_inf_cfg']['cfg.level']] = \Nyos\mod\items::getItems2($db, $vv['folder'], $vv['now_inf_cfg']['cfg.level']);
    } catch ( \Exception $e ){
        // ошибку писать в лог
    }
}


if (1 == 2) {
// echo '11<pre>'; print_r($q); echo '</pre>';
// echo '22<pre>'; print_r($w); echo '</pre>';
// $w['folder'] => jobs

    if (isset($w['folder']) && is_dir($_SERVER['DOCUMENT_ROOT'] . DS . '9.site' . DS . folder . DS . 'download' . DS . $w['folder'])) {

        if (file_exists($_SERVER['DOCUMENT_ROOT'] . DS . '9.site' . DS . folder . DS . '_smartydata.' . $w['cfg.level'] . '.json')) {
            //echo __FILE__.'<br/>';

            $vv['informer'][$w['cfg.level']] = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . DS . '9.site' . DS . folder . DS . '_smartydata.' . $w['cfg.level'] . '.json'), true);
            // echo '<pre>'; print_r($vv['informer'][$w['cfg.level']]); echo '</pre>';
            //  = date
        }
    }
}