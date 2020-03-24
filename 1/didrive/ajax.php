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
        (
        isset($_REQUEST['items_module']{0}) &&
        isset($_REQUEST['edit_dop_name']{0}) &&
        //isset($_REQUEST['edit_item_id']) &&
        isset($_REQUEST['s']{5}) &&
        (
        (
        isset($_REQUEST['addpole1val']{0}) &&
        \Nyos\nyos::checkSecret($_REQUEST['s'], ($_REQUEST['edit_item_id'] ?? '' ) . $_REQUEST['edit_dop_name']
                . $_REQUEST['addpole1val']
        ) === true
        ) ||
        (
        isset($_REQUEST['addpole1val']{0}) &&
        isset($_REQUEST['addpole2val']{0}) &&
        \Nyos\nyos::checkSecret($_REQUEST['s'], ($_REQUEST['edit_item_id'] ?? '' ) . $_REQUEST['edit_dop_name']
                . $_REQUEST['addpole1val']
                . $_REQUEST['addpole2val']
        ) === true
        ) ||
        (
        isset($_REQUEST['addpole1val']{0}) &&
        isset($_REQUEST['addpole2val']{0}) &&
        isset($_REQUEST['addpole3val']{0}) &&
        \Nyos\nyos::checkSecret($_REQUEST['s'], ($_REQUEST['edit_item_id'] ?? '' ) . $_REQUEST['edit_dop_name']
                . $_REQUEST['addpole1val']
                . $_REQUEST['addpole2val']
                . $_REQUEST['addpole3val']
        ) === true
        ) ||
        (
        isset($_REQUEST['addpole1val']{0}) &&
        isset($_REQUEST['addpole2val']{0}) &&
        isset($_REQUEST['addpole3val']{0}) &&
        isset($_REQUEST['addpole4val']{0}) &&
        \Nyos\nyos::checkSecret($_REQUEST['s'], ($_REQUEST['edit_item_id'] ?? '' ) . $_REQUEST['edit_dop_name']
                . $_REQUEST['addpole1val']
                . $_REQUEST['addpole2val']
                . $_REQUEST['addpole3val']
                . $_REQUEST['addpole4val']
        ) === true
        ) ||
        (
        isset($_REQUEST['addpole1val']{0}) &&
        isset($_REQUEST['addpole2val']{0}) &&
        isset($_REQUEST['addpole3val']{0}) &&
        isset($_REQUEST['addpole4val']{0}) &&
        isset($_REQUEST['addpole5val']{0}) &&
        \Nyos\nyos::checkSecret($_REQUEST['s'], ($_REQUEST['edit_item_id'] ?? '' ) . $_REQUEST['edit_dop_name'] . $_REQUEST['addpole1val'] . $_REQUEST['addpole2val']
                . $_REQUEST['addpole3val']
                . $_REQUEST['addpole4val']
                . $_REQUEST['addpole5val']
        ) === true
        )
        )
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

<<<<<<< HEAD
if ( isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit_dop_item' ) {
=======
if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'newedit_items_dop') {
>>>>>>> f3b7cea634dba47d6601562561fe2dbf601ca238

    //\f\pa( $_REQUEST );

    $data = [];

    for ($e = 1; $e <= 5; $e++) {
        if (isset($_REQUEST['addpole' . $e]) && isset($_REQUEST['addpole' . $e . 'val'])) {
            $data[$_REQUEST['addpole' . $e]] = $_REQUEST['addpole' . $e . 'val'];
        }
    }

// удаляем запись с такими параметрами если есть        
    if (1 == 1) {

        \Nyos\mod\items::deleteFromDops($db, $_REQUEST['items_module'], $data);
    }

    $data[$_REQUEST['edit_dop_name']] = $_REQUEST['value'];

    \Nyos\mod\items::add($db, $_REQUEST['items_module'], $data);

    \f\end2('окей сохранили', true, ['data_in' => $data]);
} elseif (isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit_dop_item') {

//    require_once DR . '/all/ajax.start.php';
    //\f\pa($_REQUEST);
//    $ff = $db->prepare('UPDATE `mitems` SET `status` = \'hide\' WHERE `id` = :id ');
//    $ff->execute(array(':id' => (int) $_POST['id2']));
//item_id	78
//new_status	hide    

    if (isset($_REQUEST['itemsmod']{0}) && isset($_REQUEST['item_id']{0}) && isset($_REQUEST['new_status']{0})) {

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
elseif (isset($_POST['action']) && $_POST['action'] == 'edit_dop_pole') {

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



f\end2('Произошла неописуемая ситуация #' . __LINE__ . ' обратитесь к администратору', 'error');


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