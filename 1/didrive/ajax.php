<?php

ini_set('display_errors', 'On'); // сообщения с ошибками будут показываться
error_reporting(E_ALL); // E_ALL - отображаем ВСЕ ошибки

date_default_timezone_set("Asia/Yekaterinburg");
define('IN_NYOS_PROJECT', true);

require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
require( $_SERVER['DOCUMENT_ROOT'] . '/all/ajax.start.php' );

//require_once( DR.'/vendor/didrive/base/class/Nyos.php' );
//require_once( dirname(__FILE__).'/../class.php' );
//if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'scan_new_datafile') {
//
//    scanNewData($db);
//    //cron_scan_new_datafile();
//}


$secret_dop_item = '';
for ($i = 0; $i <= 10; $i++) {
    if (isset($_REQUEST['dop_item' . $i . '_name']{0}) && isset($_REQUEST['dop_item' . $i . '_value']{0}))
        $secret_dop_item .= '-' . $_REQUEST['dop_item' . $i . '_name'] . '-' . $_REQUEST['dop_item' . $i . '_value'];
}



// проверяем секрет
if (
        (
        isset($_REQUEST['id']{0}) && isset($_REQUEST['s']{5}) &&
        \Nyos\nyos::checkSecret($_REQUEST['s'], $_REQUEST['id']) === true
        ) ||
        ( isset($_REQUEST['id2']{0}) && isset($_REQUEST['s2']{5}) && \Nyos\nyos::checkSecret($_REQUEST['s2'], $_REQUEST['id2']) === true ) ||
        (
        isset($_REQUEST['itemsmod']{0}) &&
        isset($_REQUEST['item_id']{0}) &&
        isset($_REQUEST['dop_name']{0}) &&
        isset($_REQUEST['dop_new_value']{0}) &&
        isset($_REQUEST['s3']{5}) &&
        \Nyos\nyos::checkSecret($_REQUEST['s3'], $_REQUEST['itemsmod'] . '-' . $_REQUEST['item_id'] . '-' . $_REQUEST['dop_name'] . '-' . $_REQUEST['dop_new_value']) === true
        ) ||
//                           {# модуль итемов #}
//                           itemsmod="072.vzuscaniya"
//                           {# id итема #}
//                           item_id="{{ minus.id }}"
//
//                           {# название доп параметра #}
//                           {# dop_name="pay_check" #}
//                           {# новое значение параметра #}
//                           {# dop_new_value="no" #}
//                           {# секрет #}
//                           {# s3="{{ creatSecret( '050.chekin_checkout-'~minus.id~'-pay_check-no' ) }}"  #}
//                           
//                           {# новое значение статуса записи #}
//                           new_status="hide"
//                           {# секрет #}
//                           s3="{{ creatSecret( '072.vzuscaniya-'~minus.id~'-hide' ) }}" 

        (
        isset($_REQUEST['itemsmod']{0}) &&
        isset($_REQUEST['item_id']{0}) &&
        isset($_REQUEST['new_status']{0}) &&
        isset($_REQUEST['s3']{5}) &&
        \Nyos\nyos::checkSecret($_REQUEST['s3'], $_REQUEST['itemsmod'] . '-' . $_REQUEST['item_id'] . '-' . $_REQUEST['new_status']) === true
        ) ||
        (
        isset($_REQUEST['module']{0}) &&
        isset($_REQUEST['dop_name']{0}) &&
        isset($_REQUEST['item_id']{0}) &&
        isset($_REQUEST['s']{5}) &&
        \Nyos\nyos::checkSecret($_REQUEST['s'], $_REQUEST['module'] . $_REQUEST['dop_name'] . $_REQUEST['item_id']) === true
        ) ||
        // 
        (
        !empty($_REQUEST['action']) && !empty($_REQUEST['item_module']) && !empty($_REQUEST['s']) && \Nyos\nyos::checkSecret($_REQUEST['s'], $_REQUEST['action'] . '-' . $_REQUEST['item_module'] . $secret_dop_item) === true
        )
) {
    
}
//
else {

    $e = '';

//    foreach ($_REQUEST as $k => $v) {
//        $e .= '<Br/>' . $k . ' - ' . $v;
//    }

    f\end2('Произошла неописуемая ситуация #' . __LINE__ . ' обратитесь к администратору ' . $e // . $_REQUEST['id'] . ' && ' . $_REQUEST['secret']
            , 'error');
}

//require_once( $_SERVER['DOCUMENT_ROOT'] . '/0.all/sql.start.php');
//require( $_SERVER['DOCUMENT_ROOT'] . '/0.site/0.cfg.start.php');
//require( $_SERVER['DOCUMENT_ROOT'] . DS . '0.all' . DS . 'class' . DS . 'mysql.php' );
//require( $_SERVER['DOCUMENT_ROOT'] . DS . '0.all' . DS . 'db.connector.php' );
// добавляем смену сотруднику
// \f\pa($_REQUEST);

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit_dop_item') {

    require_once DR . '/all/ajax.start.php';

    //\f\pa($_REQUEST);
//    $ff = $db->prepare('UPDATE `mitems` SET `status` = \'hide\' WHERE `id` = :id ');
//    $ff->execute(array(':id' => (int) $_POST['id2']));
//item_id	78
//new_status	hide    

    if (isset($_REQUEST['itemsmod']{0}) && isset($_REQUEST['item_id']{0}) && isset($_REQUEST['new_status']{0})) {

        \f\Cash::deleteKeyPoFilter([ $_REQUEST['itemsmod'] ]);
        
        $ff = $db->prepare('UPDATE mitems SET status = :status WHERE id = :id ');
        $ff->execute(
                array(
                    ':id' => (int) $_POST['item_id']
                    ,
                    ':status' => $_REQUEST['new_status']
                )
        );
    }

    //
    if (isset($_REQUEST['itemsmod']{0}) && isset($_REQUEST['item_id']{0}) && isset($_REQUEST['dop_name']{0}) && isset($_REQUEST['dop_new_value']{0})) {

        $ff = $db->prepare('DELETE FROM `mitems-dops` WHERE `id_item` = :id AND `name` = :pole  ;');
        $ff->execute(
                array(
                    ':id' => (int) $_POST['item_id']
                    ,
                    ':pole' => $_REQUEST['dop_name']
                )
        );

        $ff = $db->prepare('INSERT INTO `mitems-dops` ( `id_item`, `name`, `value` ) values ( :id , :name , :val ) ');
        $ff->execute(array(
            ':id' => (int) $_REQUEST['item_id']
            ,
            ':name' => $_REQUEST['dop_name']
            ,
            ':val' => $_REQUEST['dop_new_value']
        ));
    }

    \f\end2('окей');
}

// edit dop поле
//elseif (isset($_POST['action']) && $_POST['action'] == 'edit_dop_pole') {
//
////    require_once( $_SERVER['DOCUMENT_ROOT'] . DS . '0.all' . DS . 'f' . DS . 'db.2.php' );
////    require_once( $_SERVER['DOCUMENT_ROOT'] . DS . '0.all' . DS . 'f' . DS . 'txt.2.php' );
//// $_SESSION['status1'] = true;
//// $status = '';
//
//    $e = array('id' => (int) $_POST['item_id']);
//
//    $ff = $db->prepare('DELETE FROM `mitems-dops` WHERE `id_item` = :id_item AND `name` = :name ');
//    $ff->execute(
//            array(
//                ':id_item' => $_POST['item_id'],
//                ':name' => $_POST['dop_name']
//            )
//    );
//
//
//    if (isset($_POST['new_val']{0})) {
//        $ff = $db->prepare('INSERT INTO `mitems-dops` ( `id_item`, `name`, `value` ) values ( :id, :name , :val ) ');
//        $ff->execute(array(
//            ':id' => $_POST['item_id'],
//            ':name' => $_POST['dop_name'],
//            ':val' => $_POST['new_val'],
//        ));
//    }
//
//    $dir_for_cash = DR . dir_site;
//
//    $list_cash = scandir($dir_for_cash);
//    foreach ($list_cash as $k => $v) {
//        if (strpos($v, 'cash.items.') !== false) {
//            unlink($dir_for_cash . $v);
//        }
//    }
//
//// f\end2( 'новый статус ' . $status);
//    f\end2('ок');
//}

elseif (isset($_POST['action']) && $_POST['action'] == 'edit_dop_pole') {
    $skip_start = true;
    require '../micro-service/edit-dop-pole.php';
}

// записываем новую запись
elseif (isset($_POST['action']) && $_POST['action'] == 'items__creat') {

    if (empty($_REQUEST['item_module']))
        \f\end2('неописуемая ситуация №' . __LINE__, false);

    // \f\pa($_REQUEST);
    // dops для удаленя записей
    $dops_del = [];
    // все поля для добавления новой записи
    $dops_new = [];

    for ($i = 0; $i <= 10; $i++) {
        if (isset($_REQUEST['dop_item' . $i . '_name']{0}) && isset($_REQUEST['dop_item' . $i . '_value']{0})) {

            if (!empty($_REQUEST['dop_item' . $i . '_check_delete']) && $_REQUEST['dop_item' . $i . '_check_delete'] == 'da') {
                $dops_del[$_REQUEST['dop_item' . $i . '_name']] = $_REQUEST['dop_item' . $i . '_value'];
            }

            $dops_new[$_REQUEST['dop_item' . $i . '_name']] = $_REQUEST['dop_item' . $i . '_value'];
        }
    }

    \Nyos\mod\items::deleteFromDops($db, $_REQUEST['item_module'], $dops_del);
    \Nyos\mod\items::add($db, $_REQUEST['item_module'], $dops_new);

    // \f\end2('не', false);
    \f\end2('ок');


//    require_once( $_SERVER['DOCUMENT_ROOT'] . DS . '0.all' . DS . 'f' . DS . 'db.2.php' );
//    require_once( $_SERVER['DOCUMENT_ROOT'] . DS . '0.all' . DS . 'f' . DS . 'txt.2.php' );
// $_SESSION['status1'] = true;
// $status = '';

    $e = array('id' => (int) $_POST['item_id']);

    $ff = $db->prepare('DELETE FROM `mitems-dops` WHERE `id_item` = :id_item AND `name` = :name ');
    $ff->execute(
            array(
                ':id_item' => $_POST['item_id'],
                ':name' => $_POST['dop_name']
            )
    );


    if (isset($_POST['new_val']{0})) {
        $ff = $db->prepare('INSERT INTO `mitems-dops` ( `id_item`, `name`, `value` ) values ( :id, :name , :val ) ');
        $ff->execute(array(
            ':id' => $_POST['item_id'],
            ':name' => $_POST['dop_name'],
            ':val' => $_POST['new_val'],
        ));
    }

    $dir_for_cash = DR . dir_site;

    $list_cash = scandir($dir_for_cash);
    foreach ($list_cash as $k => $v) {
        if (strpos($v, 'cash.items.') !== false) {
            unlink($dir_for_cash . $v);
        }
    }

// f\end2( 'новый статус ' . $status);
    f\end2('ок');
}



\f\end2('Произошла неописуемая ситуация #' . __LINE__ . ' обратитесь к администратору', 'error');


/*
elseif (isset($_POST['action']) && $_POST['action'] == 'delete_smena') {

    require_once DR . '/all/ajax.start.php';

    $ff = $db->prepare('UPDATE `mitems` SET `status` = \'hide\' WHERE `id` = :id ');
    $ff->execute(array(':id' => (int) $_POST['id2']));

    \f\end2('смена удалена');
}
//
elseif (isset($_POST['action']) && $_POST['action'] == 'recover_smena') {

    require_once DR . '/all/ajax.start.php';

    $ff = $db->prepare('UPDATE `mitems` SET `status` = \'show\' WHERE `id` = :id ');
    $ff->execute(array(':id' => (int) $_POST['id2']));

    \f\end2('смена восстановлена');
}
//
elseif (
        isset($_POST['action']) && ( $_POST['action'] == 'add_new_smena' || $_POST['action'] == 'confirm_smena')
) {
    // action=add_new_smena

    try {

        require_once DR . '/all/ajax.start.php';

        if ($_POST['action'] == 'add_new_smena') {

            if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php'))
                require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

            if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/vendor/didrive_mod/items/class.php'))
                require ($_SERVER['DOCUMENT_ROOT'] . '/vendor/didrive_mod/items/class.php');

            // если старт часов меньше часов сдачи
            if (strtotime($_REQUEST['start_time']) > strtotime($_REQUEST['fin_time'])) {
                //$b .= '<br/>'.__LINE__;
                $start_time = strtotime($_REQUEST['date'] . ' ' . $_REQUEST['start_time']);
                $fin_time = strtotime($_REQUEST['date'] . ' ' . $_REQUEST['fin_time']) + 3600 * 24;
            }
            // если старт часов больше часов сдачи
            else {
                //$b .= '<br/>'.__LINE__;
                $start_time = strtotime($_REQUEST['date'] . ' ' . $_REQUEST['start_time']);
                $fin_time = strtotime($_REQUEST['date'] . ' ' . $_REQUEST['fin_time']);
            }

            \Nyos\mod\items::addNew($db, $vv['folder'], \Nyos\nyos::$menu['050.chekin_checkout'], array(
                'head' => rand(100, 100000),
                'jobman' => $_REQUEST['jobman'],
                'sale_point' => $_REQUEST['salepoint'],
                'start' => date('Y-m-d H:i', $start_time),
                'fin' => date('Y-m-d H:i', $fin_time)
            ));

            \f\end2('<div>'
                    . '<nobr><b class="warn" >смена добавлена</b>'
                    . '<br/>'
                    . date('d.m.y H:i', $start_time) . ' - ' . date('d.m.y H:i', $fin_time)
                    . '</nobr>'
                    . '</div>', true);
        }
        //
        elseif ($_POST['action'] == 'confirm_smena') {

//        if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php'))
//            require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

            $ff = $db->prepare('DELETE FROM `mitems-dops` WHERE `id_item` = :id AND `name` = \'pay_check\' ;');
            $ff->execute(array(':id' => (int) $_POST['id2']));

            $ff = $db->prepare('INSERT INTO `mitems-dops` ( `id_item`, `name`, `value` ) values ( :id, \'pay_check\', \'yes\' ) ');
            $ff->execute(array(':id' => (int) $_POST['id2']));

            \f\end2( '<div>'
                        . '<nobr>'
                            . '<b class="warn" >отправлено на оплату</b>'
                        . '</nobr>'
                    . '</div>', true );
        }
        //
        elseif ($_POST['action'] == 'edit_items_dop') {

//        if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php'))
//            require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

            $ff = $db->prepare('DELETE FROM `mitems-dops` WHERE `id_item` = :id AND `name` = \'pay_check\' ;');
            $ff->execute(array(':id' => (int) $_POST['id2']));

            $ff = $db->prepare('INSERT INTO `mitems-dops` ( `id_item`, `name`, `value` ) values ( :id, \'pay_check\', \'yes\' ) ');
            $ff->execute(array(':id' => (int) $_POST['id2']));

            \f\end2( '<div>'
                        . '<nobr>'
                            . '<b class="warn" >отправлено на оплату</b>'
                        . '</nobr>'
                    . '</div>', true );
        }
        
    } catch (\Exception $ex) {

        $e = '<pre>--- ' . __FILE__ . ' ' . __LINE__ . '-------'
                . PHP_EOL . $ex->getMessage() . ' #' . $ex->getCode()
                . PHP_EOL . $ex->getFile() . ' #' . $ex->getLine()
                . PHP_EOL . $ex->getTraceAsString()
                . '</pre>';

        \f\end2($e, true);
    } catch (\PDOException $ex) {

        $e = '<pre>--- ' . __FILE__ . ' ' . __LINE__ . '-------'
                . PHP_EOL . $ex->getMessage() . ' #' . $ex->getCode()
                . PHP_EOL . $ex->getFile() . ' #' . $ex->getLine()
                . PHP_EOL . $ex->getTraceAsString()
                . '</pre>';

        \f\end2($e, true);
    }
}
//
elseif (isset($_POST['action']) && $_POST['action'] == 'add_new_minus') {
    // action=add_new_smena

    try {

        require_once DR . '/all/ajax.start.php';

        if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php'))
            require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

        if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/vendor/didrive_mod/items/class.php'))
            require ($_SERVER['DOCUMENT_ROOT'] . '/vendor/didrive_mod/items/class.php');

        \Nyos\mod\items::addNew($db, $vv['folder'], \Nyos\nyos::$menu['072.vzuscaniya'], array(
            // 'head' => rand(100, 100000),
            'date' => date('Y-m-d H:i', $_SERVER['REQUEST_TIME']),
            'jobman' => $_REQUEST['jobman'],
            'sale_point' => $_REQUEST['salepoint'],
            'summa' => $_REQUEST['summa'],
            'text' => $_REQUEST['text']
        ));


//        if (date('Y-m-d', $start_time) == date('Y-m-d', $fin_time)) {
//            $dd = true;
//        } else {
//            $dd = false;
//        }
//        $r = ob_get_contents();
//        ob_end_clean();


        \f\end2('<div>'
                . '<nobr><b class="warn" >взыскание добавлено</b>'
                . '<br/>'
                . $_REQUEST['summa']
                . '<br/>'
                . '<small>' . $_REQUEST['text'] . '</small>'
//                . (
//                $dd === true ?
//                        '<br/>с ' . date('H:i', $start_time) . ' - ' . date('H:i', $fin_time) : '<br/>с ' . date('Y-m-d H:i:s', $start_time) . '<br/>по ' . date('Y-m-d H:i:s', $fin_time)
//                )
                // .'окей '.$b
//                . '</br>'
//                . $b
//                . '</br>'
//                . $r
                . '</nobr>'
                . '</div>', true);
    } catch (\Exception $ex) {

        $e = '<pre>--- ' . __FILE__ . ' ' . __LINE__ . '-------'
                . PHP_EOL . $ex->getMessage() . ' #' . $ex->getCode()
                . PHP_EOL . $ex->getFile() . ' #' . $ex->getLine()
                . PHP_EOL . $ex->getTraceAsString()
                . '</pre>';

        \f\end2($e, true);
    } catch (\PDOException $ex) {

        $e = '<pre>--- ' . __FILE__ . ' ' . __LINE__ . '-------'
                . PHP_EOL . $ex->getMessage() . ' #' . $ex->getCode()
                . PHP_EOL . $ex->getFile() . ' #' . $ex->getLine()
                . PHP_EOL . $ex->getTraceAsString()
                . '</pre>';

        \f\end2($e, true);
    }
}
///
elseif (isset($_POST['action']) && $_POST['action'] == 'show_info_strings') {

    require_once DR . '/all/ajax.start.php';

    if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php'))
        require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

    if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/all/exception.nyosex'))
        require $_SERVER['DOCUMENT_ROOT'] . '/all/exception.nyosex';

    // require_once DR.'/vendor/didrive_mod/items/class.php';
    // \Nyos\mod\items::getItems( $db, $folder )
    // echo DR ;
    $loader = new Twig_Loader_Filesystem(dirname(__FILE__) . '/tpl.ajax/');

// инициализируем Twig
    $twig = new Twig_Environment($loader, array(
        'cache' => $_SERVER['DOCUMENT_ROOT'] . '/templates_c',
        'auto_reload' => true
            //'cache' => false,
            // 'debug' => true
    ));

    if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/all/twig.function.php'))
        require ($_SERVER['DOCUMENT_ROOT'] . '/all/twig.function.php');

    if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/vendor/didrive_mod/items/class.php'))
        require ($_SERVER['DOCUMENT_ROOT'] . '/vendor/didrive_mod/items/class.php');

    if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/vendor/didrive_mod/items/1/twig.function.php'))
        require ($_SERVER['DOCUMENT_ROOT'] . '/vendor/didrive_mod/items/1/twig.function.php');

//    \Nyos\Mod\Items::getItems($db, $folder, $module, $stat, $limit);

    $vv['get'] = $_GET;

    $ttwig = $twig->loadTemplate('show_table.htm');
    echo $ttwig->render($vv);

    $r = ob_get_contents();
    ob_end_clean();

    // die($r);


    \f\end2('окей', true, array('data' => $r));
}

f\end2('Произошла неописуемая ситуация #' . __LINE__ . ' обратитесь к администратору', 'error');

exit;
*/