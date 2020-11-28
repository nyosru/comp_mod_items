<?php
// проверяем что есть в модулях (какие переменные) и добавляем в базу данных те которых нет

try {

//    $date = $in['date'] ?? $_REQUEST['date'] ?? null;
//
//    if (empty($date))
//        throw new \Exception('нет даты');

    if (isset($skip_start) && $skip_start === true) {
        
    } else {
        require_once '0start.php';
    }

// \f\pa($_REQUEST);
//    if (!empty($_REQUEST['id']) && !empty($_REQUEST['s']) && \Nyos\Nyos::checkSecret($_REQUEST['s'], $_REQUEST['id']) !== false) {
//        
//    } else {
//        \f\end3('что то пошло не так', false, [__FILE__, __LINE__]);
//    }
//
//    $error2 = '';
//    $sql2 = '';

    if (empty(\Nyos\Nyos::$menu))
        \Nyos\Nyos::getMenu();

    // \f\pa(\Nyos\Nyos::$menu, 2);

    foreach (\Nyos\Nyos::$menu as $k0 => $v0) {

        echo '<br/>'
        . '<br/>++ ' . $k0;

        try {

            $table = 'mod_' . \f\translit($k0, 'uri2');
            $polya = \f\db\pole_list($db, $table);
            // \f\pa($polya, 2);

            foreach ($v0 as $k => $v) {
                if (!empty($v['name_rus'])) {

                    echo '<br/>' . $k;

                    if (!isset($polya[$k])) {

                        echo '<br/><b>++ ' . __LINE__ . ' нет поля, добавляем</b>';

                        try {

                            if (!empty($v['type']) && $v['type'] == 'select') {
                                $ff = $db->prepare('ALTER TABLE `' . $table . '` ADD `' . addslashes($k) . '` INT(9) NULL DEFAULT NULL ;');
                            } 
                            //
                            elseif ((!empty($v['type']) && $v['type'] == 'date') || (!empty($v['db_type']) && $v['db_type'] == 'date')) {
                                $ff = $db->prepare('ALTER TABLE `' . $table . '` ADD `' . addslashes($k) . '` DATE NULL DEFAULT NULL ;');
                            } 
                            //
                            else {
                                // $ff = $db->prepare('ALTER TABLE `'.$table.'` ADD `'.addslashes($k).'` VARCHAR(250) NULL DEFAULT NULL FIRST, ADD INDEX (`'.addslashes($k).'`);');
                                $ff = $db->prepare('ALTER TABLE `' . $table . '` ADD `' . addslashes($k) . '` VARCHAR(250) NULL DEFAULT NULL FIRST ;');
                            }
                            $ff->execute();
                        } catch (\Exception $ex) {

                            \f\pa($ex);
                        }
                    }
//                    else{
//                        echo '<br/>'.__LINE__.' есть поле';
//                    }
                }
            }
        } catch (\Exception $ex) {

            // \f\pa($ex, 2);
        }
    }




//    if (!empty($_REQUEST['ajax_delete1mod']) && !empty($_REQUEST['ajax_delete1s'])) {
//
//        if (!empty(\Nyos\Nyos::$menu[$_REQUEST['ajax_delete1mod']])) {
//
//            $mod = \Nyos\Nyos::$menu[$_REQUEST['ajax_delete1mod']];
//
//            if (!empty($mod['type']) && $mod['type'] == 'items' && !empty($mod['version']) && $mod['version'] == 3) {
//
//                $s_text = '';
//
//                $sql0 = '';
//                $sql0_v = [];
//
//                for ($w = 1; $w <= 10; $w++) {
//                    if (!empty($_REQUEST['ajax_delete1v' . $w . 'pole']) && !empty($_REQUEST['ajax_delete1v' . $w . 'val'])) {
//                        $s_text .= $_REQUEST['ajax_delete1v' . $w . 'val'];
//
//                        $sql0 .= (!empty($sql0) ? ' AND ' : '' ) . ' ' . $_REQUEST['ajax_delete1v' . $w . 'pole'] . ' = :vv' . $w . ' ';
//                        $sql0_v[':vv' . $w] = $_REQUEST['ajax_delete1v' . $w . 'val'];
//                    }
//                }
//
//                if (!empty($s_text) && \Nyos\nyos::checkSecret($_REQUEST['ajax_delete1s'], $s_text) !== false) {
//
//                    if (\Nyos\Nyos::$db_type == 'pg') {
//                        $ff = $db->prepare('UPDATE "mod_' . \f\translit($_REQUEST['ajax_module'], 'uri2') . '" SET status = delete WHERE ' . $sql0);
//                    } else {
//                        $ff = $db->prepare('UPDATE `mod_' . \f\translit($_REQUEST['ajax_module'], 'uri2') . '` SET status = delete WHERE ' . $sql0);
//                    }
//
//                    $ff->execute($sql0_v);
//
//                    $sql0 .= ' ok ';
//                }
//            }
//        }
//    }
//
//    if ( isset(\Nyos\Nyos::$menu[$_REQUEST['ajax_module']]['type']) && \Nyos\Nyos::$menu[$_REQUEST['ajax_module']]['type'] == 'items' && \Nyos\Nyos::$menu[$_REQUEST['ajax_module']]['version'] == 3 ) {
//
//        try {
//
//            if (\Nyos\Nyos::$db_type == 'pg') {
//                $ff = $db->prepare('UPDATE "mod_' . \f\translit($_REQUEST['ajax_module'], 'uri2') . '" SET "' . \f\translit($_REQUEST['dop_name'], 'uri2') . '" = :val WHERE "id" = :id ');
//            } else {
//                $ff = $db->prepare('UPDATE `mod_' . \f\translit($_REQUEST['ajax_module'], 'uri2') . '` SET `' . \f\translit($_REQUEST['dop_name'], 'uri2') . '` = :val WHERE `id` = :id ');
//            }
//
//            $ff->execute(
//                    array(
//                        ':id' => $_REQUEST['id'],
//                        ':val' => $_REQUEST['new_val']
//                    )
//            );
//
//            $sql2 = 'ок2';
//        } catch (\PDOException $exc) {
//            $error2 = $exc->getMessage();
//            \f\end2('ошибка: ' . $exc->getMessage(), false);
//        }
//
//        \f\end2('сохранено');
//    }
//
//
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
//    if (isset($_POST['new_val'])) {
//        $sql = 'INSERT INTO `mitems-dops` ( `id_item`, `name`, `value` ) values ( :id, :name , :val ) ';
//// \f\pa($sql);
//        $ff = $db->prepare($sql);
//        $in_sql = [
//            ':id' => $_POST['item_id'],
//            ':name' => $_POST['dop_name'],
//            ':val' => $_POST['new_val'] ?? 0,
//        ];
//// \f\pa($in_sql);
//        $ff->execute($in_sql);
//    }
//
////    $dir_for_cash = DR . dir_site;
////    $list_cash = scandir($dir_for_cash);
////    foreach ($list_cash as $k => $v) {
////        if (strpos($v, 'cash.items.') !== false) {
////            unlink($dir_for_cash . $v);
////        }
////    }
//// f\end2( 'новый статус ' . $status);
//
//
//
//
//
//
//
//    f\end2('ок', true, ['sql2' => $sql2, '$error2' => $error2]);
} catch (Exception $exc) {

    echo '<pre>';
    print_r($exc);
    echo '</pre>';
// echo $exc->getTraceAsString();
}