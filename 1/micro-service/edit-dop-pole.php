<?php

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


    if (isset($_POST['new_val'])) {
        $sql = 'INSERT INTO `mitems-dops` ( `id_item`, `name`, `value` ) values ( :id, :name , :val ) ';
        // \f\pa($sql);
        $ff = $db->prepare($sql);
        $in_sql = [
            ':id' => $_POST['item_id'],
            ':name' => $_POST['dop_name'],
            ':val' => $_POST['new_val'] ?? 0,
        ];
        // \f\pa($in_sql);
        $ff->execute($in_sql);
    }

//    $dir_for_cash = DR . dir_site;
//    $list_cash = scandir($dir_for_cash);
//    foreach ($list_cash as $k => $v) {
//        if (strpos($v, 'cash.items.') !== false) {
//            unlink($dir_for_cash . $v);
//        }
//    }
// f\end2( 'новый статус ' . $status);
    f\end2('ок');
} catch (Exception $exc) {

    echo '<pre>';
    print_r($exc);
    echo '</pre>';
    // echo $exc->getTraceAsString();
}