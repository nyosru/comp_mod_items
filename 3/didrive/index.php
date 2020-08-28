<?php

if (1 == 1) {

//        $s = $db->prepare('SELECT sql FROM `sqlite_master` WHERE `name` = :table LIMIT 1 ');
//        $s->execute( array( ':table' => $table ) );
//        $r = $s->fetchAll();
//        \f\pa($r);
//if ($_SERVER['HTTP_HOST'] == 'bbb72.ru') {
//
//    if (file_exists(DR . '/vendor/didrive/f/cash.php'))
//        require_once DR . '/vendor/didrive/f/cash.php';
//
////    phpinfo();
////    die();
//    
//// echo '<br/>'.__FILE__.' ('.__LINE__.')';
//    if (class_exists('\\f\\Cash')) {
//
//        // \f\Cash::deleteKeyPoFilter($_GET['level']);
//
//        \f\Cash::start();
//
//        if ($_SERVER['HTTP_HOST'] == 'bbb72.ru') {
//            $rr = \f\Cash::$cache->fetchAll();
//            //$rr = \f\Cash::$cache->getAll();
//            \f\pa($rr);
//            
//            
//        }
//
//        echo '<br/>есть #' . __LINE__;
//    } else {
//        echo '<br/>нет #' . __LINE__;
//    }
//}
//\f\pa($_REQUEST);


    /**
     * добавление записи
     */
    if (!empty($_POST['addnew'])) {

        try {

//            $new = [];
//            foreach ($_POST as $k => $v) {
//                if (!empty($v))
//                    $new[$k] = $v;
//            }

            $r = Nyos\mod\items::add($db, $vv['now_level']['cfg.level'], $_POST);
            $vv['warn'] .= (!empty($vv['warn']) ? '<br/>' : '' ) . 'Запись добавлена';

            if (isset($_GET['goto_start']))
                \f\redirect('/', 'i.didrive.php', array('warn' => 'Запись добавлена'));
        } catch (Exception $e) {

            $vv['warn'] .= (!empty($vv['warn']) ? '<br/>' : '' ) . 'Произошла неописуемая ситуация #' . $e->getCode() . '.' . $e->getLine() . ' (ошибка: ' . $e->getMessage() . ' )';
        }
    }
//
    elseif (!empty($_REQUEST['delete_item_head'])) {

        $db->sql_Query('UPDATE mitems SET `status` = \'delete\' 
        WHERE 
        `head` = \'' . addslashes($_REQUEST['head']) . '\' 
        AND `module` = \'' . addslashes($vv['level']) . '\' 
        AND `folder` = \'' . addslashes($vv['folder']) . '\' 
        ;');

        Nyos\mod\items::clearCash($vv['folder']);

        if (isset($r['status']) && $r['status'] == 'ok') {
            $vv['warn'] .= (!empty($vv['warn']) ? '<br/>' : '' ) . $r['html'];
        }
    }
//
    elseif (!empty($_POST['delete_item_id'])) {

        $db->sql_Query('UPDATE mitems SET `status` = \'delete2\' 
        WHERE 
        `id` = \'' . addslashes($_POST['id']) . '\' 
        AND `module` = \'' . addslashes($vv['level']) . '\' 
        AND `folder` = \'' . addslashes($vv['folder']) . '\' 
        ;');

        Nyos\mod\items::clearCash($vv['folder']);

        if (isset($r['status']) && $r['status'] == 'ok') {
            $vv['warn'] .= (!empty($vv['warn']) ? '<br/>' : '' ) . $r['html'];
        }
    }
// сохранение редактирования
    elseif (isset($_REQUEST['save_id']) && is_numeric($_REQUEST['save_id']) && isset($_REQUEST['save_edit'])) {

        $d = $_POST;
        unset($d['addnew']);
        $d['files'] = $_FILES;

        // \f\pa($d);
        // \Nyos\mod\items::$show_sql = true;
        $r = \Nyos\mod\items::edit($db,);
        $r = \Nyos\mod\items::saveEdit($db, $_REQUEST['save_id'], $vv['folder'], $vv['now_level'], $d);

        if (isset($r['status']) && $r['status'] == 'ok') {
            $vv['warn'] .= (!empty($vv['warn']) ? '<br/>' : '' ) . $r['html'];
        }
    } elseif (isset($_GET['refresh_cash']) && $_GET['refresh_cash'] == 'da') {
        \Nyos\mod\items::clearCash($vv['folder']);
    }

    $vv['krohi'] = [];
    $vv['krohi'][1] = array(
        'text' => $vv['now_level']['name'],
        'uri' => $vv['now_level']['cfg.level']
    );

//\Nyos\mod\items::setSort( 'head', 'asc' );
//$vv['list'] = \Nyos\mod\items::getItems( $db, $vv['folder'], $vv['now_level']['cfg.level'], null);
//\f\pa($vv['list']);
// \f\pa($vv['now_level']);
// \f\pa($vv['list']);
// \f\pa($_POST);

    foreach ($vv['now_level'] as $k => $v) {

        // \f\pa($v);

        if (isset($v['type']) && $v['type'] == 'textarea_html') {
            // $vv['ckeditor_in'][$k] = array( 'type' => 'mini.img' );
            $vv['ckeditor_in'][$k] = [];
        }

//    echo PHP_EOL.$k;
//    \f\pa($v);
//    echo PHP_EOL;

        if (isset($v['import_1_module']) && empty($vv['v_data'][$v['import_1_module']])) {
            // $vv['v_data'][$v['import_1_module']] = Nyos\mod\items::getItems($db, $vv['folder'], $v['import_1_module']);
            // $vv['v_data'][$v['import_1_module']] = Nyos\mod\items::getItemsSimple3($db, $v['import_1_module']);
            if (!empty($v['import_1_up']) && !empty($v['import_1_id']) && !empty($v['import_1_show'])) {

                $table = 'mod_' . \f\translit($v['import_1_module'], 'uri2');

                // \Nyos\mod\items::$show_sql = true;

                \Nyos\mod\items::$sql_select_vars = [];
                \Nyos\mod\items::$sql_select_vars[] = ' items.* ';

                \Nyos\mod\items::$sql_select_vars[] = ' t1."' . $v['import_1_id'] . '" as "t1id" ';
                \Nyos\mod\items::$sql_select_vars[] = ' t1."' . $v['import_1_show'] . '" as "t1v" ';

                \Nyos\mod\items::$joins = ' LEFT JOIN ' . $table . ' t1 ON items.' . $v['import_1_up'] . ' = t1.' . $v['import_1_id'] . '  ';

                \Nyos\mod\items::$sql_select_vars[] = ' t2."' . $v['import_1_id'] . '" as "t2id" ';
                \Nyos\mod\items::$sql_select_vars[] = ' t2."' . $v['import_1_show'] . '" as "t2v" ';

                \Nyos\mod\items::$joins .= ' LEFT JOIN ' . $table . ' t2 ON t1.' . $v['import_1_up'] . ' = t2.' . $v['import_1_id'] . '  ';

                \Nyos\mod\items::$sql_select_vars[] = ' t3."' . $v['import_1_id'] . '" as "t3id" ';
                \Nyos\mod\items::$sql_select_vars[] = ' t3."' . $v['import_1_show'] . '" as "t3v" ';

                \Nyos\mod\items::$joins .= ' LEFT JOIN ' . $table . ' t3 ON t2.' . $v['import_1_up'] . ' = t3.' . $v['import_1_id'] . '  ';

                \Nyos\mod\items::$sql_select_vars[] = ' t4."' . $v['import_1_id'] . '" as "t3id" ';
                \Nyos\mod\items::$sql_select_vars[] = ' t4."' . $v['import_1_show'] . '" as "t3v" ';

                \Nyos\mod\items::$joins .= ' LEFT JOIN ' . $table . ' t4 ON t3.' . $v['import_1_up'] . ' = t4.' . $v['import_1_id'] . '  ';

                if (!empty($v['import_1_concat'])) {

                    \Nyos\mod\items::$sql_select_vars[] = ' CONCAT( 
                    t4."' . $v['import_1_show'] . '" 
                    , CASE WHEN t4."' . $v['import_1_show'] . '" != \'\' THEN \' ' . $v['import_1_concat'] . ' \' END
                    , t3."' . $v['import_1_show'] . '" 
                    , CASE WHEN t3."' . $v['import_1_show'] . '" != \'\' THEN \' ' . $v['import_1_concat'] . ' \' END
                    , t2."' . $v['import_1_show'] . '"
                    , CASE WHEN t2."' . $v['import_1_show'] . '" != \'\' THEN \' ' . $v['import_1_concat'] . ' \' END
                    , t1."' . $v['import_1_show'] . '" 
                    , CASE WHEN t1."' . $v['import_1_show'] . '" != \'\' THEN \' ' . $v['import_1_concat'] . ' \' END
                    ) concat_' . $v['import_1_show'] . ' ';
                }
            }

            $vv['v_data'][$v['import_1_module']] = \Nyos\mod\items::get($db, $v['import_1_module'], 'show', 'id_id');

//        \f\pa($v['import_1_module']);
//        \f\pa($vv['v_data'][$v['import_1_module']],2);
        }

        if (isset($v['import_2_module']) && empty($vv['v_data'][$v['import_2_module']])) {
            // $vv['v_data'][$v['import_2_module']] = Nyos\mod\items::getItems($db, $vv['folder'], $v['import_2_module']);
            // $vv['v_data'][$v['import_2_module']] = Nyos\mod\items::getItemsSimple3($db, $v['import_2_module']);
            $vv['v_data'][$v['import_2_module']] = Nyos\mod\items::get($db, $v['import_2_module'], 'show', 'id_id');
//        \f\pa($v['import_2_module']);
//        \f\pa($vv['v_data'][$v['import_2_module']],2);
        }
    }

// \f\pa($vv['v_data']);
//echo dir_mods_mod_vers_didrive_tpl;
//echo '<br/>';
//echo dir_site_module_nowlev_tpldidr;
//echo '<br/>';

    $vv['tpl_body'] = \f\like_tpl('body', dir_mods_mod_vers_didrive_tpl, dir_site_module_nowlev_tpldidr, DR);

    $vv['in_body_end'][] = '<script src="' . DS . 'vendor' . DS . 'didrive' . DS . 'base' . DS . 'js.lib' . DS . 'jquery.ba-throttle-debounce.min.js"></script>';
}