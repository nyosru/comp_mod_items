Russian

----- Установка ----- 

composer require didrive_mod/items




------------------
использование др пример / 200203
------------------- 

// ограничение в выборке

\Nyos\mod\items::$join_where = ' INNER JOIN `mitems-dops` mid '
        . ' ON mid.id_item = mi.id '
        . ' AND mid.name = \'date\' '
        . ' AND mid.value_date >= :ds '
        . ' AND mid.value_date <= :df '
        . ' INNER JOIN `mitems-dops` mid2 '
        . ' ON mid2.id_item = mi.id '
        . ' AND mid2.name = \'sale_point\' '
        . ' AND mid2.value = :sp '
;
\Nyos\mod\items::$var_ar_for_1sql[':sp'] = $sp;
\Nyos\mod\items::$var_ar_for_1sql[':ds'] = $date_start;
\Nyos\mod\items::$var_ar_for_1sql[':df'] = $date_finish;
$array = \Nyos\mod\items::get($db, 'sale_point_oborot');

--------------

//  ограничение по названию доп столбцов в выборке

\Nyos\mod\items::$where2dop = ' AND ( midop.name = \'date\' OR midop.name = \'sale_point\' ) ';






------------------
использование др пример
------------------- 

\Nyos\mod\items::$join_where = ' INNER JOIN `mitems-dops` md1 ON md1.id_item = mi.id AND md1.name = \'date\' AND md1.value_date >= :ds AND md1.value_date <= :df 
        INNER JOIN `mitems-dops` md2 ON md2.id_item = mi.id AND md2.name = \'sale_point\' AND md2.value = :sp ';
\Nyos\mod\items::$var_ar_for_1sql = [
    ':sp' => $sp_id,
    ':ds' => date('Y-m-d', strtotime($date_start)),
    ':df' => date('Y-m-d', strtotime($date_fin))
];
$ret = \Nyos\mod\items::getItemsSimple3($db, '074.time_expectations_list');


------ Пример ----------
загрузки с ограничением запроса и доп параметров

// запрос в главный запрос
\Nyos\mod\items::$where2 = ' AND `id` = \'' . (int) $sp . '\' ';
// если тащим одно значение
\Nyos\mod\items::$limit1 = true;
// ограничение выборки доп параметров
\Nyos\mod\items::$where2dop = ' AND `name` = \'id_tech_for_oborot\' ';
// старт выборки
$sp1 = \Nyos\mod\items::get($db, $mod_sp);

------ удаление всех итемов указывая допы и модуль --------

\Nyos\mod\items::deleteFromDops($db, $module , [
    'sale_point' => $ocenka['data']['sp'],
    'date' => $ocenka['data']['date'],
]);
