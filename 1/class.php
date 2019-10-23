<?php

namespace Nyos\mod\items_v1;

//echo __FILE__.'<br/>';
// строки безопасности
if (!defined('IN_NYOS_PROJECT'))
    die('<center><h1><br><br><br><br>Cтудия Сергея</h1><p>Сработала защита <b>TPL</b> от злостных розовых хакеров.</p>
    <a href="http://www.uralweb.info" target="_blank">Создание, дизайн, вёрстка и программирование сайтов.</a><br />
    <a href="http://www.nyos.ru" target="_blank">Только отдельные услуги: Дизайн, вёрстка и программирование сайтов.</a>');

//echo __FILE__;

/**
 * сканируем новые дата файлы
 * @return type
 */
function scanNewData($db, $return = 'die') {

    //echo '<br/>#'.__LINE__;
    //ini_set('display_errors', 'On'); // сообщения с ошибками будут показываться
    //error_reporting(E_ALL); // E_ALL - отображаем ВСЕ ошибки

    require_once( $_SERVER['DOCUMENT_ROOT'] . '/0.all/f/txt.2.php');
    require_once( $_SERVER['DOCUMENT_ROOT'] . '/0.all/f/file.2.php');

    $folder = \Nyos\nyos::getFolder($db);
    $amnu = \Nyos\nyos::get_menu($folder);

    // echo '<br/>#'.__LINE__;
    //\f\pa($amnu,2);

    if (isset($amnu) && sizeof($amnu) > 0) {

        $res_dir = $_SERVER['DOCUMENT_ROOT'] . DS . '9.site' . DS . $folder . DS . 'download' . DS . 'datain' . DS;

        if (!is_dir($res_dir))
            mkdir($res_dir, 0755);

        foreach ($amnu as $k1 => $v1) {

            if (
                    isset($v1['type']) && $v1['type'] == 'items' &&
                    isset($v1['datain_name_file']) && file_exists($res_dir . $v1['datain_name_file'])
                    && !isset($v1['sceep_auto_compile'])
            ) {

                // echo '<br/>#' . __LINE__ . ' // ' . $res_dir . $v1['datain_name_file'];

                // if (file_exists($res_dir . $v1['datain_name_file'] . '.arr'))
                // rename($res_dir . $v1['datain_name_file'] . '.arr', $res_dir . $v1['datain_name_file'] . '.arr.delete');

                $e = \f\convertFileToSerialArray(
                        $res_dir . $v1['datain_name_file']
                        , $res_dir . $v1['datain_name_file'] . '.arr'
                        , '1c-win1251');
                // \f\pa($e,2,null,'convertFileToSerialArray');
                // die();

                  if (file_exists($res_dir . $v1['datain_name_file'] . '.arr'))
                  rename($res_dir . $v1['datain_name_file'], $res_dir . $v1['datain_name_file'] . '.delete');

                //\f\pa($e,2);
            }
        }

        if ($return == 'array') {
            return \f\end3('файлы обработаны #' . __LINE__, true);
        } else {
            return \f\end2('файлы обработаны #' . __LINE__, true);
        }
    }

    if ($return == 'array') {
        return \f\end3('нет менюшек #' . __LINE__, false);
    } else {
        return \f\end2('нет менюшек #' . __LINE__, false);
    }
}
