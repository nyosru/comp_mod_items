<?php

ini_set('display_errors', 'On'); // сообщения с ошибками будут показываться
error_reporting(E_ALL); // E_ALL - отображаем ВСЕ ошибки

date_default_timezone_set("Asia/Yekaterinburg");
define('IN_NYOS_PROJECT', true);

// header("Access-Control-Allow-Methods: GET");

header("Access-Control-Allow-Origin: *");

require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
require( $_SERVER['DOCUMENT_ROOT'] . '/all/ajax.start.php' );

//require_once( DR.'/vendor/didrive/base/class/Nyos.php' );
//require_once( dirname(__FILE__).'/../class.php' );
// if( !empty($_REQUEST['sys']) && $_REQUEST['sys'] == 'vue' ){
//    \f\end2('ой', false, [
//    'ses' => $_SESSION,
//     'req' => $_REQUEST,
//     'post' => $_POST,
//     'get' => $_GET,
//     'php' => file_get_contents('php://input'),
//     'php2' => json_decode(file_get_contents('php://input'),true)
//    ] );
// }

$input = json_decode(file_get_contents('php://input'), true);

// if( !empty($in) ){}
//
//if( !empty( $e['sys'] ) && $e['sys'] == 'vue' ){
//    $_REQUEST = $e;
//}

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'scan_new_datafile') {

    scanNewData($db);
//cron_scan_new_datafile();
}

if (empty($_REQUEST) && !empty($input))
    $_REQUEST = $input;

//\f\pa($input,'','','in');
//\f\pa($_REQUEST,'','','req');
//\f\pa($_POST,'','','pos');
// проверяем секрет
if (
        (
        isset($input['sys']) &&
        $input['sys'] == 'vue'
        ) ||
        (
        isset($_REQUEST['sys']) &&
        $_REQUEST['sys'] == 'vue'
        ) ||
        (
        isset($_REQUEST['action']) &&
        $_REQUEST['action'] == 'get'
        ) ||
        (
        isset($_REQUEST['aj_id']{0}) && isset($_REQUEST['aj_s']{5}) &&
        \Nyos\nyos::checkSecret($_REQUEST['aj_s'], $_REQUEST['aj_id']) === true
        ) ||
        (
        isset($_REQUEST['id']{0}) && isset($_REQUEST['s']{5}) &&
        \Nyos\nyos::checkSecret($_REQUEST['s'], $_REQUEST['id']) === true
        ) ||
        (
        isset($_REQUEST['module']{0}) &&
        isset($_REQUEST['dop_name']{0}) &&
        isset($_REQUEST['item_id']{0}) &&
        isset($_REQUEST['s']{5}) &&
        \Nyos\nyos::checkSecret($_REQUEST['s'], $_REQUEST['module'] . $_REQUEST['dop_name'] . $_REQUEST['item_id']) === true
        )
) {
    
}
//
else {
    f\end2('Произошла неописуемая ситуация #' . __LINE__ . ' обратитесь к администратору ' // . $_REQUEST['id'] . ' && ' . $_REQUEST['secret']
            , 'error');
}

//require_once( $_SERVER['DOCUMENT_ROOT'] . '/0.all/sql.start.php');
//require( $_SERVER['DOCUMENT_ROOT'] . '/0.site/0.cfg.start.php');
//require( $_SERVER['DOCUMENT_ROOT'] . DS . '0.all' . DS . 'class' . DS . 'mysql.php' );
//require( $_SERVER['DOCUMENT_ROOT'] . DS . '0.all' . DS . 'db.connector.php' );
//
//
//
//
//
//
//
//
//
//
// трём метки
if (!empty($_REQUEST['remove_cash']))
    \f\Cash::deleteKeyPoFilter($_REQUEST['remove_cash']);







if (isset($_POST['action']) && $_POST['action'] == 'show_info_strings') {

    require_once '../../../../all/ajax.start.php';
    require_once dirname(__FILE__) . '/../class.php';

// \Nyos\mod\items::getItems( $db, $folder )

    \f\end2('окей', true, array('data' => 'новый статус ' . 'val'));
}

// добавить итем или заменить (удалить, добавить)
// аякс через didrive base
elseif (isset($_POST['action']) && $_POST['action'] == 'didrive__items__new_edit') {

    if (empty($_REQUEST['items_module']))
        \f\end2('неописуемая ситуация #' . __LINE__, false);

    $dops = [];
    for ($i = 1; $i <= 10; $i++) {
        if (!empty($_REQUEST['addpole' . $i]) && isset($_REQUEST['addpole' . $i . 'val'])) {
            $dops[$_REQUEST['addpole' . $i]] = $_REQUEST['addpole' . $i . 'val'];
        }
    }

    if (empty($dops))
        \f\end2('неописуемая ситуация #' . __LINE__, false);

    $del = \Nyos\mod\items::deleteFromDops($db, $_REQUEST['items_module'], $dops);
    // \f\pa($we);

    if (!empty($_REQUEST['value'])) {
        $dops[$_REQUEST['edit_dop_name']] = $_REQUEST['value'];

        $res = \Nyos\mod\items::add($db, $_REQUEST['items_module'], $dops);
        // \f\pa($res);
    }

    /*
      if (empty($_REQUEST['add_module']) || empty($_REQUEST['add']))
      \f\end2('Что то пошло не так #' . __LINE__, false);

      //ob_start('ob_gzhandler');
      // \f\pa($_REQUEST);

      $res = \Nyos\mod\items::add($db, $_REQUEST['add_module'], $_REQUEST['add']);
      // \f\pa($res);
      // \f\end2($res['html'].'<Br/>'.$r, true);

     */

//    $r = ob_get_contents();
//    ob_end_clean();

    \f\end2('окей, удалили и добавили');
    // \f\end2('asd', true, [ 'add' => ( $res ?? 'x' ), 'del' => ( $del ?? 'x' ) ] );
}

// копируем запись в разные записи с доп параметром
// pays из jobdesc
elseif (
        (!empty($_REQUEST['action']) && $_REQUEST['action'] == 'pays_copy_to_sps') ||
        (!empty($input['action']) && $input['action'] == 'pays_copy_to_sps')
) {

    $in = $input ?? $_REQUEST;



//    echo '<div style="padding-left:200px;padding-top:200px;" >';
//    \f\pa($_POST, '', '', 'post');
    \Nyos\mod\items::$where2 = ' AND mi.id = :i ';
    \Nyos\mod\items::$var_ar_for_1sql[':i'] = $in['item_id'];
    \Nyos\mod\items::$limit1 = true;
    $copy = \Nyos\mod\items::get($db, \Nyos\mod\JobDesc::$mod_salary);
//    \f\pa($copy);

    $new_date_blank = [];

    foreach ($copy as $k => $v) {

        if ($k == 'id')
            continue;

        if (!empty($v))
            $new_date_blank[$k] = $v;
    }

    foreach ($in['to_sps'] as $v1) {

        $v = $new_date_blank;
        $v['sale_point'] = $v1;
        $new_ids[] = $v;
    }

// \f\pa($new_ids);
    $e = \Nyos\mod\items::addNewSimples($db, \Nyos\mod\JobDesc::$mod_salary, $new_ids);
    //\f\pa($e, '', '', 'res');
    // echo '</div>';
    // $vv['warn'] .= '<div class="alert-warning" style="padding: 10px;" >Скопировано</div>';







    \f\end2('скопировано', true, ['as' => $e]);
}

// добавить новый итем
elseif (
        (!empty($_REQUEST['action']) && $_REQUEST['action'] == 'add_new') ||
        (!empty($input['action']) && $input['action'] == 'add_new')
) {

    $dd = $input ?? $_REQUEST;

    if (empty($dd['add_module']) || empty($dd['add']))
        \f\end2('Что то пошло не так #' . __LINE__, false);

    //ob_start('ob_gzhandler');
    // \f\pa($_REQUEST);

    \Nyos\mod\items::$type_module = 2;
    $res = \Nyos\mod\items::add($db, $dd['add_module'], $dd['add']);

    // \f\pa($res);
//    $r = ob_get_contents();
//    ob_end_clean();
    // \f\end2($res['html'].'<Br/>'.$r, true);

    \f\end2($res['html'], true);
}

// удалить итем
elseif (isset($_POST['action']) && $_POST['action'] == 'remove_item') {

//    if (empty($_REQUEST['add_module']) || empty($_REQUEST['add']))
//        \f\end2('Что то пошло не так #' . __LINE__, false);
    //ob_start('ob_gzhandler');
    // \f\pa($_REQUEST);

    if (!empty($_REQUEST['id']) || !empty($_REQUEST['aj_id']))
        $res = \Nyos\mod\items::deleteId($db, ( $_REQUEST['id'] ?? $_REQUEST['aj_id']));

    // удаляем из старой версии
    if (!empty($_REQUEST['r_module']) && !empty($_REQUEST['remove'])) {
        $list = \Nyos\mod\items::get($db, $_REQUEST['r_module']);

        $for_sql = '';

        foreach ($list as $k => $v) {
            $delete = true;

            foreach ($_REQUEST['remove'] as $k1 => $v1) {
                if (isset($v[$k1])) {
                    if ($v[$k1] != $v1) {
                        $delete = false;
                        break;
                    }
                }
            }

            if ($delete === true) {
                \f\pa($v);
                $for_sql .= (!empty($for_sql) ? ' OR ' : '' ) . ' `id` = \'' . $v['id'] . '\' ';
            }
        }

        if (!empty($for_sql)) {
            $sql = 'UPDATE `mitems` SET `status` = \'delete\' WHERE ' . $for_sql . ' ;';
            // \f\pa($sql);
            $ff = $db->prepare($sql);
            $ff->execute($var_in);
        }
    }

    // новая версия 2007
    if (!empty($_REQUEST['r_module']) && !empty($_REQUEST['remove']))
        $res2 = \Nyos\mod\items::deleteItemForDops($db, $_REQUEST['r_module'], $_REQUEST['remove']);

    \f\end2('удалено', true, ['r1' => $res, 'r2' => $res2]);
    //\f\end2($res['html'], true);
}
/**
 * изменение инфы в главном итемс
 */
//
elseif (isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit_pole') {

    try {

        \Nyos\Nyos::getMenu();

        if (isset(\Nyos\Nyos::$menu[$_REQUEST['module']])) {

            if (isset(\Nyos\Nyos::$menu[$_REQUEST['module']]['type']) && \Nyos\Nyos::$menu[$_REQUEST['module']]['type'] == 'items' && isset(\Nyos\Nyos::$menu[$_REQUEST['module']]['version']) && \Nyos\Nyos::$menu[$_REQUEST['module']]['version'] == 3) {

                $new_dop = [];

                for ($ww = 1; $ww <= 10; $ww++) {
                    if (isset($_REQUEST['new_dop' . $ww . 'var']) && isset($_REQUEST['new_dop' . $ww . 'val'])) {
                        $new_dop[$_REQUEST['new_dop' . $ww . 'var']] = $_REQUEST['new_dop' . $ww . 'val'];
                    }
                }

                \Nyos\mod\items::edit($db, $_REQUEST['module'], ['id' => $_REQUEST['aj_id']], $new_dop);

                \f\end2('sdfsdf', true, ['sdf' => \Nyos\Nyos::$menu[$_REQUEST['module']]]);
            }
        }

        \f\end2('sdf', false, ['menu' => \Nyos\Nyos::$menu]);

        if (!empty($_REQUEST['clear_cash']))
            \f\Cash::deleteKeyPoFilter($_REQUEST['clear_cash']);

        $e = array('id' => (int) $_REQUEST['id']);
        $e1 = array($_REQUEST['pole'] => $_REQUEST['val']);

        \f\db\db_edit2($db, 'mitems', $e, $e1);

//    $dir_for_cash = DR . dir_site;
//
//    $list_cash = scandir($dir_for_cash);
//    foreach ($list_cash as $k => $v) {
//        if (strpos($v, 'cash.items.') !== false) {
//            unlink($dir_for_cash . $v);
//        }
//    }

        \f\end2('новый статус ' . $_REQUEST['val']);
    } catch (Exception $ex) {
        \f\end2('неописуемая Ex ситуация #' . __LINE__, false);
    }
}
/**
 * изменение инфы в дополнительных итемсах
 */
// edit dop поле
elseif (isset($_POST['action']) && $_POST['action'] == 'edit_dop_pole') {

// если есть переменные удаления кеша .. то запускаем обработку удаления кеша, внутри которой пройдёт поиск и чистка по переменным что отправим
    try {

//        echo '<br/>'.__LINE__;
        foreach ($_REQUEST as $k => $v) {
            if (strpos($k, 'ajax_cash_delete') !== false) {
//                echo '<br/>'.__LINE__;
                \f\Cash::clearCasheFromVars($_REQUEST);

//                \f\Cash::$cache->delete('jobdesc__hoursonjob_calculateHoursOnJob_2020-01-02_sp1');
//                $ee = \f\Cash::$cache->get('jobdesc__hoursonjob_calculateHoursOnJob_2020-01-02_sp1');
//                \f\pa($ee,'','','2020-01-02 sp1');

                break;
            }
        }
    } catch (\Exception $exc) {
//        echo $exc->getTraceAsString();
    }



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
/**
 * получаем перменные 1 записи
 */
//
elseif (isset($_REQUEST['action']) && $_REQUEST['action'] == 'show') {


    if (isset($_REQUEST['module']{1})) {

        require_once( $_SERVER['DOCUMENT_ROOT'] . DS . '0.all' . DS . 'f' . DS . 'db.2.php' );
        require_once( $_SERVER['DOCUMENT_ROOT'] . DS . '0.all' . DS . 'f' . DS . 'txt.2.php' );

// $_SESSION['status1'] = true;
// $status = '';
// \f\db\db_edit2($db, 'mitems', array('id' => (int) $_POST['id']), array($_POST['pole'] => $_POST['val']));

        require_once( $_SERVER['DOCUMENT_ROOT'] . DS . '0.site' . DS . 'exe' . DS . 'items' . DS . 'class.php' );
        $items = Nyos\mod\items::getItems($db, $_REQUEST['folder'], '010.tovars');

        /*
          f\pa($_REQUEST['id']);
          f\pa($_REQUEST['var']);
          f\pa($items);
         * 
         */

        if (isset($_REQUEST['var']) && isset($items['data'][$_REQUEST['id']]['dop'][$_REQUEST['var']])) {

            $r1 = $r2 = array();
            $r1[] = 'ish2/rizolin-opt.ru/images/';
            $r2[] = '/9.site/' . $_REQUEST['folder'] . '/download/img/';
            $r1[] = 'FSm.png';
            $r2[] = 'fsm.png';
            $r1[] = 'AS.png';
            $r2[] = 'as.png';

            $r1[] = 'width:64px';
            $r2[] = 'max-width:64px';
            $r1[] = 'height:64px';
            $r2[] = 'max-height:64px;padding:10px;';

            die('<style>.icof img{ max-height:64px;padding:10px;max-width:64px }</style><div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>'
                    . '<h4 class="modal-title" id="myModalLabel">' . $items['data'][$_REQUEST['id']]['head'] . '</h4>'
                    . '</div>
                    <div class="modal-body">
                    <div style="padding:20px">' . str_replace($r1, $r2, $items['data'][$_REQUEST['id']]['dop'][$_REQUEST['var']]) . '</div></div>');
        } else {
// f\end2( 'новый статус ' . $status);
            f\end2('ок', 'ok', $items);
        }
    } else {
        f\end2('новый статус 1111111');
    }
}

/**
 * получаем перменные 1 записи
 */
//
elseif (isset($_REQUEST['action']) && $_REQUEST['action'] == 'get') {

    // header("Access-Control-Allow-Methods: GET");

    if (!empty($_REQUEST['module'])) {

//        if( $_REQUEST['module'] == '050.chekin_checkout' )
//        \f\pa($_REQUEST);
        // define('IN_NYOS_PROJECT', true);

        require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
        // require $_SERVER['DOCUMENT_ROOT'] . '/all/ajax.start.php';
        // require_once $_SERVER['DOCUMENT_ROOT'] .'/all/ajax.start.php';
        // require_once dirname(__FILE__) . '/../class.php';
        // require_once( $_SERVER['DOCUMENT_ROOT'] . DS . '0.all' . DS . 'f' . DS . 'db.2.php' );
        // require_once( $_SERVER['DOCUMENT_ROOT'] . DS . '0.all' . DS . 'f' . DS . 'txt.2.php' );
        // $_SESSION['status1'] = true;
        // $status = '';
        // \f\db\db_edit2($db, 'mitems', array('id' => (int) $_POST['id']), array($_POST['pole'] => $_POST['val']));
        // require_once( $_SERVER['DOCUMENT_ROOT'] . DS . '0.site' . DS . 'exe' . DS . 'items' . DS . 'class.php' );

        if (!empty($_REQUEST['add_secret']))
            \Nyos\mod\items::$add_s_to_res = true;

        if (!empty($_REQUEST['search']))
            \Nyos\mod\items::$search = $_REQUEST['search'];

        if (!empty($_REQUEST['between_date']))
            \Nyos\mod\items::$between_date = $_REQUEST['between_date'];

        if (!empty($_REQUEST['between_datetime']))
            \Nyos\mod\items::$between_datetime = $_REQUEST['between_datetime'];

        if (!empty($_REQUEST['between_month'])) {
            foreach ($_REQUEST['between_month'] as $pole => $val) {
                $ds = date('Y-m-01', strtotime($val));
                \Nyos\mod\items::$between_date[$pole] = [$ds, date('Y-m-d', strtotime($ds . ' +1 month -1 day'))];
            }
        }

        if (!empty($_REQUEST['between_dt_month'])) {
            foreach ($_REQUEST['between_dt_month'] as $pole => $val) {
                $ds = date('Y-m-01 03:00:00', strtotime($val));
                \Nyos\mod\items::$between_datetime[$pole] = [$ds, date('Y-m-d 03:00:00', strtotime($ds . ' +1 month '))];
            }
        }

        // \Nyos\mod\items::$show_sql = true;
        $items = \Nyos\mod\items::get($db, $_REQUEST['module'], ($_REQUEST['status'] ?? 'show'), ($_REQUEST['sort'] ?? null));

        /**
         * доп обработка после выборки
         */
        if (isset($_REQUEST['load_after']) && $_REQUEST['load_after'] == 'jobman_fio') {

//        $list_jm = [];
//        foreach( $items as $v ){
//            $list_jm[$v['jobman']] = 1;
//        }
//        
//        // \Nyos\mod\items::$search['jobman']
//        $items = \Nyos\mod\items::get($db, $_REQUEST['module'], ($_REQUEST['status'] ?? 'show'), ($_REQUEST['sort'] ?? null));
        }


        if (isset($_REQUEST['group']) && (
                $_REQUEST['group'] == 'jobman__date_now__ar' || $_REQUEST['group'] == 'jobman__start_smena__ar' || $_REQUEST['group'] == 'jobman__date__ar' || $_REQUEST['group'] == 'date1__sale_point'
                )) {

            $re = [];

            foreach ($items as $k => $v) {

                // группируем рабочие смены
                if ($_REQUEST['group'] == 'jobman__start_smena__ar') {

                    if (!empty($v['jobman']) && !empty($v['start']))
                        $re[$v['jobman']][date('Y-m-d', strtotime($v['start'] . ' -4 hour'))][] = $v;
                } elseif ($_REQUEST['group'] == 'date1__sale_point') {

                    if (!empty($v['date']) && !empty($v['sale_point']))
                        $re[$v['date']] = $v['sale_point'];
                } elseif ($_REQUEST['group'] == 'jobman__date_now__ar') {

                    if (!empty($v['jobman']) && !empty($v['date_now']))
                        $re[$v['jobman']][$v['date_now']][] = $v;
                } elseif ($_REQUEST['group'] == 'jobman__date__ar') {

                    if (!empty($v['jobman']) && !empty($v['date']))
                        $re[$v['jobman']][$v['date']][] = $v;
                }
            }

            \f\end2('окей #' . __LINE__, true, ['data' => $re]);
        }


        \f\end2('окей #' . __LINE__, true, ['data' => $items]);
    } else {

        \f\end2('не указан модуль #' . __LINE__, false);
    }
}


\f\end2('Произошла неописуемая ситуация #' . __LINE__ . ' обратитесь к администратору', 'error');
