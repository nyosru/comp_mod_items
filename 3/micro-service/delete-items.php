<?php

try {

//    $date = $in['date'] ?? $_REQUEST['date'] ?? null;
//    if (empty($date))
//        throw new \Exception('нет даты');

    if (!isset($skip_start) || $skip_start !== true)
        require_once '0start.php';

//    if (!empty($_REQUEST['id']) || !empty($_REQUEST['aj_id']))
//        $res = \Nyos\mod\items::deleteId($db, ( $_REQUEST['id'] ?? $_REQUEST['aj_id']));

    $return = [];

    if (!empty($_REQUEST['r_module']) && !empty($_REQUEST['remove']))
        $res2 = \Nyos\mod\items::deleteItemForDops($db, $_REQUEST['r_module'], $_REQUEST['remove']);

    if (strpos($_SERVER['HTTP_HOST'], 'dev') !== false) {
        $return['r2'] = ( $res2 ?? [] );
        $return['deleted'] = ( $deleted ?? [] );
    }

    \f\end2('удалено', true, $return );

    //\f\end2($res['html'], true);
} catch (Exception $exc) {

    echo '<pre>';
    print_r($exc);
    echo '</pre>';
    // echo $exc->getTraceAsString();

    f\end2($exc->getMessage(), false, $exc);
}