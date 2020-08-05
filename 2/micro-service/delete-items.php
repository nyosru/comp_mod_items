<?php

try {

//    $date = $in['date'] ?? $_REQUEST['date'] ?? null;
//
//    if (empty($date))
//        throw new \Exception('нет даты');

    if (!isset($skip_start) || $skip_start !== true)
        require_once '0start.php';


//    if (isset($_REQUEST['only_new_db']) && $_REQUEST['only_new_db'] == 'da') {
//        
//    } else {

        if (!empty($_REQUEST['id']) || !empty($_REQUEST['aj_id']))
            $res = \Nyos\mod\items::deleteId($db, ( $_REQUEST['id'] ?? $_REQUEST['aj_id']));

        // удаляем из старой версии
        if (!empty($_REQUEST['r_module']) && !empty($_REQUEST['remove'])) {
            $list = \Nyos\mod\items::get($db, $_REQUEST['r_module']);

            $for_sql = '';

            if (strpos($_SERVER['HTTP_HOST'], 'dev') !== false)
                $deleted = [];

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
                    // \f\pa($v);
                    if (strpos($_SERVER['HTTP_HOST'], 'dev') !== false)
                        $deleted[] = $v;
                    $for_sql .= (!empty($for_sql) ? ' OR ' : '' ) . ' `id` = \'' . $v['id'] . '\' ';
                }
            }

            if (!empty($for_sql)) {
                $sql = 'UPDATE `mitems` SET `status` = \'delete\' WHERE ' . $for_sql . ' ;';
                // \f\pa($sql);
                $ff = $db->prepare($sql);
                $ff->execute();
            }
        }
    // }

    // новая версия 2007
    if (!empty($_REQUEST['r_module']) && !empty($_REQUEST['remove']))
        $res2 = \Nyos\mod\items::deleteItemForDops($db, $_REQUEST['r_module'], $_REQUEST['remove']);

    if (strpos($_SERVER['HTTP_HOST'], 'dev') !== false)
        \f\end2('удалено', true, ['r1' => ( $res ?? [] ), 'r2' => ( $res2 ?? [] ), 'deleted' => ( $deleted ?? [] )]);

    \f\end2('удалено', true);

    //\f\end2($res['html'], true);
} catch (Exception $exc) {

    echo '<pre>';
    print_r($exc);
    echo '</pre>';
    // echo $exc->getTraceAsString();

    f\end2($exc->getMessage(), false, $exc);
}