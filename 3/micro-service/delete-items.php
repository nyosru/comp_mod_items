<?php

if (isset($skip_start) && $skip_start === true) {
    
} else {
    require_once '0start.php';
    $skip_start = false;
}


try {

//    $date = $in['date'] ?? $_REQUEST['date'] ?? null;
//    if (empty($date))
//        throw new \Exception('нет даты');
//    if (!empty($_REQUEST['id']) || !empty($_REQUEST['aj_id']))
//        $res = \Nyos\mod\items::deleteId($db, ( $_REQUEST['id'] ?? $_REQUEST['aj_id']));

    $return = [];


    if (!empty($_REQUEST['delete_id']) && !empty($_REQUEST['s']) && \Nyos\Nyos::checkSecret($_REQUEST['s'], $_REQUEST['delete_id']) !== false) {

        $return['line'] = __LINE__;

        $sql = 'UPDATE `mod_' . \f\translit($_REQUEST['r_module'],'uri2') . '` SET status = \'delete\' WHERE id = :id ;';
        $ff = $db->prepare($sql);
        
        $vars = [':id' => $_REQUEST['delete_id']];
        $ff->execute($vars);
        
    } elseif (!empty($_REQUEST['r_module']) && !empty($_REQUEST['remove'])) {

        $return['line'] = __LINE__;

        $res2 = \Nyos\mod\items::deleteItemForDops($db, $_REQUEST['r_module'], $_REQUEST['remove']);
    }

    if (strpos($_SERVER['HTTP_HOST'], 'dev') !== false) {
        $return['r2'] = ( $res2 ?? [] );
        $return['deleted'] = ( $deleted ?? [] );
    }

    \f\end2('удалено', true, $return);

    //\f\end2($res['html'], true);
} catch (Exception $exc) {

//    echo '<pre>';
//    print_r($exc);
//    echo '</pre>';
    // echo $exc->getTraceAsString();

    f\end2('Ошибка : ' . $exc->getMessage(), false, [ 'error' => $exc ] );
}