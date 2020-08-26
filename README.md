Russian

----- Установка ----- 

composer require didrive_mod/items




отправка инфы аякс + результат в #res_to_id
<input class="base__send_to_ajax" type="button" 
       res_to_id="res{{ v1.id }}"
       href_to_ajax="/vendor/didrive_mod/items/3/micro-service/edit-dop-pole.php"
       id="{{v1.id}}"
       s="{{ creatSecret(v1.id) }}"
       ajax_module="{{ get.level }}"
       dop_name="status"
       new_val="delete"
       value="Удалить" />






------------- ниже этой строки под вопросом инфа 2020,08,26 -------------

--- сделать выборку с кешированием ----

// дополнение к запросу
            \Nyos\mod\items::$join_where .= ' INNER JOIN `mitems-dops` midop01 ON '
                    . ' midop01.id_item = mi.id '
                    . ' AND midop01.name = :name71 '
                    . ' AND midop01.value_datetime >= :ds '
                    . ' AND midop01.value_datetime <= :df '
            ;
// переменные
    \Nyos\mod\items::$sql_vars[':name71'] = 'start';
    \Nyos\mod\items::$sql_vars[':ds'] = date('Y-m-d 08:00:00', strtotime($date_start));
    \Nyos\mod\items::$sql_vars[':df'] = date('Y-m-d 03:00:00', strtotime($date_finish . ' +1 day'));

// поиск переменных с точным указанием
    \Nyos\mod\items::$search = [
        'date' => '01-01-2020',
        'sp' => '1'
        ];

// выключатель кеша
    // \Nyos\mod\items::$cancel_cash = true;

// переменная для кеша
    \Nyos\mod\items::$cash_var_name = 'asdasd';

    $return['checks'] = \Nyos\mod\items::get2($db, self::$mod_checks);




----- запись новой записи с доп параметром, и удаление страой если была -----------

    {% set action = 'didrive__items__new_edit' %}
    {% set aj_module = '003_money_buh_pm' %}
    {% set aj_value = ( pm_user[k]['summa'] ?? '' ) %}
    {% set aj_edit_pole = 'summa' %}

    {% set dops = { 'jobman' : man_id ,
           "date" : get.d_start ,
           "type_plus" :  k } %}

    <input type="number" 
           class="didrive__items__new_edit"
           style="width: 80px;"

           step="0.01"
           min="1"
           max="99999"
           placeholder=""

           action="{{ action }}"
           items_module="{{ aj_module }}"

           edit_dop_name="{{ aj_edit_pole }}"
           value="{{ aj_value }}"

           {% set string_dop = '' %}
           {% set nn = 1 %}

           {% for k,v in dops %}

               addpole{{ nn }} = "{{ k }}"
               addpole{{ nn }}val = "{{ v }}"

               {% set string_dop = string_dop~'_'~k~'_'~v %}

               {% set nn = nn+1 %}

           {% endfor %}

           aj_id="{{ action }}_{{ aj_module }}{{ string_dop }}"
           aj_s="{{ creatSecret( action ~'_'~ aj_module ~ string_dop ) }}"

           >




------------ изменение доп параметра после клика по ссылке с вопросом и секретом ------------

        <a href="#" class="btn3 edit_items_dop_values drop2_{{ pay.id }} btn btn-xs btn-light" 

           xstyle='display:none;'

           {# действие после вопроса #}
           comit_answer="Отменить премию ?"

           {# замена доп параметра #}
           action="edit_dop_item"

           {# модуль итемов #}
           itemsmod="075.buh_oplats"

           {# id итема #}
           item_id="{{ pay.id }}"

           {# название доп параметра #}
           {# dop_name="pay_check" #}
           {# новое значение параметра #}
           {# dop_new_value="no" #}
           {# секрет #}
           {# s3="{{ creatSecret( '050.chekin_checkout-'~item.id~'-pay_check-no' ) }}"  #}

           {# новое значение статуса записи #}
           new_status="hide"

           {# секрет #}
           s3="{{ creatSecret( '075.buh_oplats-'~pay.id~'-hide' ) }}" 

           {# скрыть ссылку после клика #}
           hidethis="da" 

           {# сделать видимым блок по id #}
           show_id="del_pay_{{ pay.id }}" 

           {# id куда печатаем результат #}
           res_to_id="del_pay_{{ pay.id }}" 

           {# сообщение печатаем если всё ок #}
           msg_to_success="Отменено"

           >Х</a>

           <div style="display:none;" id="del_pay_{{ pay.id }}" ></div>
                   







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
