Russian

----- Установка ----- 

composer require didrive_mod/items



------------------
использование
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
