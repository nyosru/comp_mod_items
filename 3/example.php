сылка с параметрами для добавления записи

https://yapdomik.uralweb.info/i.didrive.php?level=sale_point_oborot&showonly=1&edit__sale_point=1&edit__date=2019-05-01


выбор с доп параметрами


    \Nyos\mod\items::$sql_itemsdop_add_where_array = array(
        ':dt1' => date('Y-m-d 05:00:01', strtotime($_REQUEST['date']) )
        ,
        ':dt2' => date('Y-m-d 23:50:01', strtotime($_REQUEST['date']) )
    );
    \Nyos\mod\items::$sql_itemsdop2_add_where = '
        INNER JOIN `mitems-dops` md1 
            ON 
                md1.id_item = mi.id 
                AND md1.name = \'start\'
                AND md1.value_datetime >= :dt1
                AND md1.value_datetime <= :dt2
        ';
    $checki = \Nyos\mod\items::getItemsSimple($db, '050.chekin_checkout', 'show' );
    \f\pa($checki);






сортировка

\Nyos\mod\items::setSort( 'head', 'asc' );
$vv['list'] = \Nyos\mod\items::getItems( $db, $vv['folder'], $vv['now_level']['cfg.level'], null);




    \Nyos\mod\items::$sql_itemsdop_add_where = ' ( 
            ( 
                midop.name != \'stagirovka_start\' 
                OR
                ( midop.name = \'stagirovka_start\' AND midop.value <= date(\'' . $date_finish . '\') )
            )
            OR
            ( 
                midop.name != \'fulljob_start\' 
                OR
                ( midop.name = \'fulljob_start\' AND midop.value <= date(\'' . $date_finish . '\') )
            )
            OR
            ( 
                midop.name != \'job_end\' 
                OR
                ( midop.name = \'job_end\' AND midop.value >= date(\'' . $date_start . '\') )
            )
        )
        ';
    $jobmans = \Nyos\mod\items::getItems($db, \Nyos\nyos::$folder_now, '070.jobman', 'show', null);





ajax изменение видимости записи

<a href="#" class="btn3 edit_items_dop_values drop2_{{ k1 }}" 
   xstyle='display:none;'
   {# действие после вопроса #}
   comit_answer="Отменить взыскание ?"

   {# замена доп параметра #}
   action="edit_dop_item"

   {# модуль итемов #}
   itemsmod="072.vzuscaniya"
   {# id итема #}
   item_id="{{ minus.id }}"

   {# название доп параметра #}
   {# dop_name="pay_check" #}
   {# новое значение параметра #}
   {# dop_new_value="no" #}
   {# секрет #}
   {# s3="{{ creatSecret( '050.chekin_checkout-'~minus.id~'-pay_check-no' ) }}"  #}

   {# новое значение статуса записи #}
   new_status="hide"
   {# секрет #}
   s3="{{ creatSecret( '072.vzuscaniya-'~minus.id~'-hide' ) }}" 

   {# скрыть ссылку после клика #}
   hidethis="da" 
   {# сделать видимым блок по id #}
   show_id="ares{{ minus.id }}" 
   {# id куда печатаем результат #}
   res_to_id="ares{{ minus.id }}" 
   {# сообщение печатаем если всё ок #}
   msg_to_success="Отменено"

   {# print_res_to_id="ares{{ k1 }}" #}

   >Отменить взыскание</a>

<div id="ares{{ minus.id }}" style="display:none;"></div>




изменить доп параметры в итемс


<a href="#" class="btn3 edit_items_dop_values drop2_{{ k1 }}" 
   style='display:none;'
   {# действие после вопроса #}
   comit_answer="Отменить разрешение на оплату смены ?"

   {# замена доп параметра #}
   action="edit_dop_item"

   {# модуль итемов #}
   itemsmod="050.chekin_checkout"
   {# id итема #}
   item_id="{{ k1 }}"
   {# название доп параметра #}
   dop_name="pay_check"
   {# новое значение параметра #}
   dop_new_value="no"

   {# секрет #}
   s3="{{ creatSecret( '050.chekin_checkout-'~k1~'-pay_check-no' ) }}" 

   {# скрыть ссылку после клика #}
   hidethis="da" 
   {# сделать видимым блок по id #}
   show_id="ares{{ k1 }}" 
   {# id куда печатаем результат #}
   res_to_id="ares{{ k1 }}" 
   {# сообщение печатаем если всё ок #}
   msg_to_success="Отменено"

   {# print_res_to_id="ares{{ k1 }}" #}

   >Отозвать разрешение на оплату</a>
