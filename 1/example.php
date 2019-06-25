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
