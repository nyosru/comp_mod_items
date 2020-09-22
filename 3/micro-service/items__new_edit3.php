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

    if (!empty($_REQUEST['aj_id']) && !empty($_REQUEST['aj_s']) && !empty($_REQUEST['aj_table']) && \Nyos\Nyos::checkSecret($_REQUEST['aj_s'], $_REQUEST['aj_table'] . $_REQUEST['aj_id']) !== false) {
        
    } else {
        \f\end3('что то пошло не так', false, [__FILE__, __LINE__]);
    }


//$_REQUEST['aj_table']	"mod_sale_point_oborot"
//var1	"sale_point"
//var1v	"1"
//var2	"date"
//var2v	"2020-09-15"
//var_edit	"oborot_server_hand"
//value	"12345"    

    $ar_in = [
        $_REQUEST['var_edit'] => $_REQUEST['value']
    ];

    $v1 = [':new_val' => $_REQUEST['value']];
    $dop_sql = '';

    for ($e = 1; $e <= 10; $e++) {
        if (isset($_REQUEST['var' . $e]) && isset($_REQUEST['var' . $e . 'v'])) {
            $dop_sql .= (!empty($dop_sql) ? ' AND ' : '' ) . ' `' . addslashes($_REQUEST['var' . $e]) . '` = :v' . $e . ' ';
            $v1[':v' . $e] = $_REQUEST['var' . $e . 'v'];

            $ar_in[$_REQUEST['var' . $e]] = $_REQUEST['var' . $e . 'v'];
        }
    }

    $ff = $db->prepare('UPDATE `' . addslashes($_REQUEST['aj_table']) . '` SET `' . $_REQUEST['var_edit'] . '` = :new_val WHERE ' . $dop_sql);
    $ff->execute($v1);

    $ss = $ff->rowCount();
    // \f\pa($ss);
    // если нет задетых строк редактированием
    if ($ss == 0) {

        \f\db\db2_insert($db, $_REQUEST['aj_table'], $ar_in);
        f\end2('ок добавлено', true);
    }
    // если есть задетые строки запросом
    else {

        f\end2('ок изменено', true);
    }
} catch (\Exception $exc) {

//    echo '<pre>';
//    print_r($exc);
//    echo '</pre>';
// echo $exc->getTraceAsString();

    f\end2('ошибка ' . $exc->getMessage(), false, $exc);
}