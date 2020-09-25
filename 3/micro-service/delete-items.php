<?php

//<a 
//class='base_modal_go icon_removed_item'
//data-toggle="modal" 
//data-target="#di_modal"
//modal_header='Удалить смену'
//ajax_link='/vendor/didrive_mod/items/3/micro-service/delete-items.php'
//ajax_vars='r_module=050.chekin_checkout&remove[jobman]={{ i.jobman }}&remove[start]={{ i.start }}'
//reload_page_after_ok="da"
//get_answer='Удаляем смену ?'
//><span class="fa fa-remove" ></span></a>

try {

//    $date = $in['date'] ?? $_REQUEST['date'] ?? null;
//    if (empty($date))
//        throw new \Exception('нет даты');

    if (!isset($skip_start) || $skip_start !== true)
        require_once '0start.php';



    // \f\pa($_REQUEST);
//    if (!empty($_REQUEST['id']) || !empty($_REQUEST['aj_id']))
//        $res = \Nyos\mod\items::deleteId($db, ( $_REQUEST['id'] ?? $_REQUEST['aj_id']));

    $return = [];

    // if (!empty($_REQUEST['r_module']) && !empty($_REQUEST['remove'])) {

    if ( !empty($_REQUEST['r_module']) && !empty($_REQUEST['delete_id']) && !empty($_REQUEST['s']) && \Nyos\Nyos::checkSecret($_REQUEST['s'], $_REQUEST['delete_id']) !== false) {

        // \Nyos\mod\items::$show_sql = true;
        $return['kolvo'] = \Nyos\mod\items::edit($db, $_REQUEST['r_module'], [ 'id' => $_REQUEST['delete_id'] ] , [ 'status' => 'delete' ], 'kolvo' );
        
        \f\end2('удалено', true, $return);
        
//        $sql = 'UPDATE `mod_' . \f\translit($_REQUEST['r_module'], 'uri2') . '` SET status = \'delete\' WHERE id = :id ;';
//        // \f\pa($sql);
//        $ff = $db->prepare($sql);
//        $vars = [':id' => $_REQUEST['delete_id']];
//        //\f\pa($vars);
//        $ff->execute($vars);
        
    } elseif (!empty($_REQUEST['r_module']) && !empty($_REQUEST['remove'])) {
        
        $res2 = \Nyos\mod\items::deleteItemForDops($db, $_REQUEST['r_module'], $_REQUEST['remove']);
        
    }
    // }

    \f\end2('удалено', true, $return);

    //\f\end2($res['html'], true);
} catch (\Exception $exc) {

//    echo '<pre>';
//    print_r($exc);
//    echo '</pre>';
    // echo $exc->getTraceAsString();

    \f\end2($exc->getMessage(), false, $exc);
}