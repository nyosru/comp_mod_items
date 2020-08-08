<?php

namespace Nyos\mod;

use f\db as db;
use f as f;

//echo __FILE__.'<br/>';
// строки безопасности
//if (!defined('IN_NYOS_PROJECT'))
//    die('<center><h1><br><br><br><br>Cтудия Сергея</h1><p>Сработала защита <b>TPL</b> от злостных розовых хакеров.</p>
//    <a href="http://www.uralweb.info" target="_blank">Создание, дизайн, вёрстка и программирование сайтов.</a><br />
//    <a href="http://www.nyos.ru" target="_blank">Только отдельные услуги: Дизайн, вёрстка и программирование сайтов.</a>');
//    \Nyos\mod\items::$join_where = ' INNER JOIN `mitems-dops` mid '
//            . ' ON mid.id_item = mi.id '
//            . ' AND mid.name = \'date\' '
//            . ' AND mid.value_date >= :ds '
//            . ' AND mid.value_date <= :df '
//            . ' INNER JOIN `mitems-dops` mid2 '
//            . ' ON mid2.id_item = mi.id '
//            . ' AND mid2.name = \'sale_point\' '
//            . ' AND mid2.value = :sp '
//
//    ;
//    \Nyos\mod\items::$var_ar_for_1sql[':sp'] = $sp;
//    \Nyos\mod\items::$var_ar_for_1sql[':ds'] = $date_start;
//    \Nyos\mod\items::$var_ar_for_1sql[':df'] = $date_finish;
//    $oborots = \Nyos\mod\items::get($db, 'sale_point_oborot');
//  ограничение по названию доп столбцов в выборке
//       \Nyos\mod\items::$where2dop = ' AND ( midop.name = \'date\' OR midop.name = \'sale_point\' ) ';
//    
//echo __FILE__;
//
// выборка item simple
// 
//\Nyos\mod\items::$where2 = ' AND ( midop.name = \'date\' AND 
//        . ' midop.value_date '
//        . ' BETWEEN \''.date('Y-m-d',strtotime($d_start)).'\' '
//        . ' AND \''.date('Y-m-d',strtotime($d_finish)).'\' ) ';
//        
//$oborots = \Nyos\mod\items::getItemsSimple2($db, $module_oborot);
//
//        \Nyos\mod\items::$join_where = ' INNER JOIN `mitems-dops` mid '
//                . ' ON mid.id_item = mi.id '
//                . ' AND mid.name = \'start\' '
//                . ' AND mid.value_datetime >= \'' . $date . ' 08:00:00\' '
//                . ' AND mid.value_datetime <= \'' . date('Y-m-d 03:00:00', strtotime($date . ' +1day')) . '\' ';






class items {

    /**
     * тип модуля (для перезода от 1 к 3 )
     * @var type 
     */
    public static $type_module = 1;

    /**
     * если true то добавляем секрет
     */
    public static $add_s_to_res = false;
    public static $nocash = false;
    public static $dir_img_server = false;
    public static $dir_img_uri = false;
    public static $dir_img_uri_download = false;
    // если true то возвращаем из get simple только dop
    // public static $get_data_simple = true;
    public static $sort_head = null;
    public static $sql_select_vars = null;

    /**
     * переменная с переменными для get2
     * @var type 
     */
    public static $sql_vars = [];
    public static $now_mod = null;
    public static $get_data_simple = null;
    public static $cash = [];
    // эти поля должны быть на выходе в допах
    public static $need_polya_vars = [];
    public static $where = [];

    /**
     * отмена кеширования в запросе get2
     * @var type 
     */
    public static $cancel_cash = false;

    /**
     * get отрабатываем только 2 запрос по доп данным
     * @var type
     */
    public static $only2sql = false;

    /**
     * название переменной кеша текущего запроса (если пустая или нет, то используем автоназвание)
     * @var type 
     */
    public static $cash_var_name = '';

    /**
     * вернуть результаты первого запроса
     * @var type 
     */
    public static $return_items_header = false;

    /**
     * часть запроса в выборке главных items where ***
     * ( AND mi.id = \'123\' )
     * / первый запрос /
     * SELECT mi.head, self::$select_var1 
     * FROM `mitems` mi '. self::$join_where 
     * WHERE mi.`module` = :module 
     * + self::$where2
     * + self::$sql_order
     * @var type 
     */
    public static $where2 = '';
    public static $where2dop = '';

    /**
     * что добавляем в селект в выборке итемов ( itemgetsimple3 )
     * @var строка
     */
    public static $select_var1 = '';

    /**
     * массив переменных для вставки в первый sql 
     * @var type 
     */
    public static $var_ar_for_1sql = [];

    /**
     * переменная для добавления inner join в первой выборке из списка итемов
     * ( detitemsimple3 )
     * @var строка
     */
    public static $join_where = '';

    /**
     * использовать старый стиль
     * @var type
     */
    public static $style_old = null;

    /**
     * добавляем в выборку из главной таблицы " WHERE + $sql_add_where " (getItemsSimple)
     * `mitems` mi 
     * @var string
     */
    public static $sql_items_add_where = '';

    /**
     * добавляем в выборку из дополнительной таблицы " WHERE + $sql_add_where " (getItems, getItemsSimple)
      midop.`name`,
      midop.`value`,
      midop.`value_text`
      FROM
      `mitems-dops` midop
     * @var string
     */
    public static $sql_itemsdop_add_where = null;
    public static $sql_itemsdop2_add_where = null;

    /**
     * показать запрос что выполняем (гет симпл)
     * @var type 
     */
    public static $show_sql = false;

    /**
     * ищем переменные в выборке
     * первое использование в get2
     * @var type 
     */
    public static $search = [];

    /**
     * массив переменных для запроса
     * @var type 
     */
    public static $sql_itemsdop_add_where_array = [];
    public static $sql_order = '';
    public static $sql_limit = '';

    /**
     * возвращаем 1 запись (первая из всех если их несколько)
     * @var type 
     */
    public static $limit1 = false;
    public static $between = [];
    public static $between_date = [];
    public static $between_datetime = [];
    public static $timer_show = false;
    public static $join_where2 = '';

    /**
     * массив с переменными во второй запрос
     * @var type 
     */
    public static $vars_to_sql2 = [];

    /**
     * название переменной для кеша get()
     */
    public static $cash_var = '';

    /**
     * время хранения кеша get()
     * если 0 то бесконечно, остальное в секундах
     */
    public static $cash_time = 0;

    public static function setSort($a1, $a2) {
        if ($a1 == 'head' && ($a2 == 'asc' || $a2 == 'desc')) {
            self::$sort_head = $a2;
        }
    }

    /**
     * определяем папку для фоток
     * @param type $folder
     * @return type
     */
    public static function creatFolderImage($folder) {

        self::$dir_img_uri_download = 'module_items_image' . DS;
        self::$dir_img_uri = dir_site_sd . self::$dir_img_uri_download;
        self::$dir_img_server = DR . self::$dir_img_uri;

        // echo self::$dir_img_server;
        if (!is_dir(self::$dir_img_server))
            mkdir(self::$dir_img_server, 0755);

        if (is_dir(self::$dir_img_server)) {
            return self::$dir_img_server;
        } else {
            return false;
        }
    }

    public static function creatFolderImage2(string $folder) {

        self::$dir_img_uri_download = 'module_items_image' . DS;
        self::$dir_img_uri = DS . '9.site' . DS . $folder . DS . 'download' . DS . self::$dir_img_uri_download;
        self::$dir_img_server = $_SERVER['DOCUMENT_ROOT'] . self::$dir_img_uri;

        // echo self::$dir_img_server;
        if (!is_dir(self::$dir_img_server))
            mkdir(self::$dir_img_server, 0755);

        if (is_dir(self::$dir_img_server)) {
            return self::$dir_img_server;
        } else {
            return false;
        }
    }

    /**
     * Получение инфы
     * @param type $db
     * PDO class
     * @param string $folder
     * @param type $module
     * @param type $stat
     * @param type $limit
     * @return type
     */
    public static function getItems($db, string $folder, $module = null, $stat = 'show', $limit = 50) {


        $r = self::getItemsSimple3($db, $module, $stat);

        $r2 = [
            // 'img_dir' => '/sites/'.\Nyos\Nyos::$folder_now.'/download/module_items_image/',
            'img_dir' => '/sites/' . $folder . '/download/module_items_image/',
            'img_dir_dl' => 'module_items_image/'
        ];

        foreach ($r as $k => $v) {
            $v['dop'] = $v;
            $r2['data'][$k] = $v;
        }

        // return $r2;
        return \f\end2('Достали список', 'ok', $r2, 'array');







        //        if( $_SERVER['HTTP_HOST'] == 'yapdomik.uralweb.info' ){
        //            echo $folder. '<Br/> 2 '.$module.'<br/> 3 '.$stat.'<Br/> 4 '.$limit;
        //        }

        if (self::$dir_img_server === false) {
            self::creatFolderImage($folder);
        }

        //        $dir_for_cash = DR . dir_site;
        //        if (isset($module{1}) && file_exists($dir_for_cash . 'cash.items.' . $module . '.arr')) {
        //
        //            $out = unserialize(file_get_contents($dir_for_cash . 'cash.items.' . $module . '.arr'));
        //            return f\end2('Достали список', 'ok', $out, 'array');
        //        } elseif (file_exists($dir_for_cash . 'cash.items.arr')) {
        //
        //            $out = unserialize(file_get_contents($dir_for_cash . 'cash.items.arr'));
        //            return f\end2('Достали список', 'ok', $out, 'array');
        //        }


        try {

            $f = 'SELECT * FROM `mitems` mi WHERE '
                    . ' mi.`status` != \'delete2\' '
                    // . ' AND mi.folder = :folder '
                    . (isset($module{
                            1}) ? ' AND mi.`module` = \'' . addslashes($module) . '\' ' : '')
                    . (isset($stat{
                            1}) ? ' AND mi.`status` = \'' . addslashes($stat) . '\' ' : '')
                    . 'ORDER BY '
                    . (self::$sort_head == 'desc' ? ' mi.head DESC, ' : '')
                    . (self::$sort_head == 'asc' ? ' mi.head ASC, ' : '')
                    . ' mi.`sort` DESC, mi.`add_d` DESC, mi.`add_t` DESC '
                    . (isset($limit{
                            1}) && is_numeric($limit) ? 'LIMIT ' . $limit . ' ' : '')
                    . ';';

            //            if( $_SERVER['HTTP_HOST'] == 'yapdomik.uralweb.info' )
            //                echo '<br/>'.'<br/>'.$f;

            $ff = $db->prepare($f);
            $ff->execute(
                    array(
                    // ':folder' => $folder
                    )
            );

            //$re1 = $ff->fetchAll();
            $re = [];

            while ($v = $ff->fetch()) {
                // foreach ($re1 as $k => $v) {
                $re[$v['id']] = $v;
            }
        } catch (\PDOException $ex) {

            //            echo '<pre>--- ' . __FILE__ . ' ' . __LINE__ . '-------'
            //            . PHP_EOL . $ex->getMessage() . ' #' . $ex->getCode()
            //            . PHP_EOL . $ex->getFile() . ' #' . $ex->getLine()
            //            . PHP_EOL . $ex->getTraceAsString()
            //            . '</pre>';
            // не найдена таблица, создаём значит её
            if (strpos($ex->getMessage(), 'no such table') !== false) {

                self::creatTable($db);
                // \f\redirect( '/' );
            }
        }


        //            if( $_SERVER['HTTP_HOST'] == 'yapdomik.uralweb.info' )
        //                \f\pa($re);

        $ff1 = 'SELECT 
                midop.`id_item`, 
                midop.`name`, 
                
                midop.`value`,
                midop.`value_date`,
                midop.`value_datetime`,
                midop.`value_text` 
            FROM 
                `mitems-dops` midop 
                
            INNER JOIN `mitems` mi ON '
                // .'mi.folder = :folder '
                . ' mi.id = midop.id_item '
                . (isset($module{
                        1}) ? ' AND mi.`module` = \'' . addslashes($module) . '\' ' : '')
                . (isset($stat{
                        1}) ? ' AND mi.`status` = \'' . addslashes($stat) . '\' ' : '')
                . 'AND mi.`status` != \'delete2\' 
                
            WHERE 
                midop.status IS NULL 
                ' . (isset(self::$sql_itemsdop_add_where{
                        3}) ? ' AND ' . self::$sql_itemsdop_add_where : '') . '                
            ;';
        self::$sql_itemsdop_add_where = null;

        //        if( isset(self::$sql_itemsdop_add_where{3}) ){
        //            echo '<pre>';
        //        echo $ff1;
        //        echo '</pre>';
        //        echo '<br/><br/>';
        //        }

        $ff = $db->prepare($ff1);

        $for_sql = self::$sql_itemsdop_add_where_array;
        // $for_sql[':folder'] = $folder;
        self::$sql_itemsdop_add_where_array = [];

        $ff->execute($for_sql);

        // \f\pa($ff->fetchAll());

        while ($r = $ff->fetch()) {

            //            if( $_SERVER['HTTP_HOST'] == 'yapdomik.uralweb.info' )
            //                \f\pa($r);

            if (!isset($re[$r['id_item']]['dop']))
                $re[$r['id_item']]['dop'] = array();

            $re[$r['id_item']]['dop'][$r['name']] = (!empty($r['value_text']) ? $r['value_text'] : (!empty($r['value_int']) ? $r['value_int'] : (!empty($r['value_date']) ? $r['value_date'] : (!empty($r['value_datetime']) ? $r['value_datetime'] : $r['value']))));
        }

        $out = array(
            'data' => $re, 'img_dir' => self::$dir_img_uri, 'img_dir_dl' => self::$dir_img_uri_download
        );

        //        if (is_dir($dir_for_cash)) {
        //            if (isset($module{1})) {
        //                file_put_contents($dir_for_cash . 'cash.items.' . \f\translit($module, 'uri2') . '.arr', serialize($out));
        //            } else {
        //                file_put_contents($dir_for_cash . 'cash' . \f\translit($module, 'uri2') . 'items.arr', serialize($out));
        //            }
        //        }

        return f\end2('Достали список', 'ok', $out, 'array');
    }

    /**
     * получем данные по одному итему
     * @param type $db
     * @param int $id
     * @return type
     */
    public static function getItemSimple($db, int $id) {

        try {

            $ff1 = 'SELECT 
                mi.*,
                midop.id dops_id, 
                midop.`name`, 
                midop.`value`,
                midop.`value_date`,
                midop.`value_datetime`,
                midop.`value_text`,
                midop.`status` 
            FROM 
                `mitems-dops` midop 
                
            INNER JOIN `mitems` mi ON '
                    // .' mi.folder = :folder '
                    // . ' mi.id = midop.id_item '
                    . ' mi.id = :id '
                    . ' AND '
                    . ' mi.id = midop.id '

                    //            ' WHERE 
                    //                midop.status IS NULL '
                    . ' LIMIT 10 '
                    . ' ;';

            //            $ff1 = 'SELECT 
            //                mi.*
            //            FROM 
            //                `mitems` mi WHERE
            //                    mi.id = :id '
            //            . ' ;';

            if (self::$show_sql === true) {
                \f\pa($ff1);
            }

            $ff = $db->prepare($ff1);

            $for_sql = [];
            $for_sql[':id'] = $id;

            if (self::$show_sql === true) {
                \f\pa($for_sql);
            }

            $ff->execute($for_sql);

            // \f\pa($ff->fetchAll(),'','','all');

            $nn = 0;
            while ($r = $ff->fetch()) {

                if (self::$show_sql === true) {
                    \f\pa($r);
                }

                if (empty($re))
                    $re = $r;

                if (!isset($re['dop']))
                    $re['dop'] = [];

                $re['dop'][$r['name']] = $r['value'] ?? $r['value_date'] ?? $r['value_text'] ?? $r['value_int'] ?? $r['value_datetime'] ?? null;
                $nn++;
            }

            if ($nn == 0) {

                $ff1 = 'SELECT 
                mi.*
            FROM 
                `mitems` mi '
                        . ' WHERE '
                        . ' mi.id = :id '
                        . ' LIMIT 1 '
                        . ' ;';

                if (self::$show_sql === true) {
                    \f\pa($ff1);
                }

                $ff = $db->prepare($ff1);

                $for_sql = [];
                $for_sql[':id'] = $id;

                if (self::$show_sql === true) {
                    \f\pa($for_sql);
                }

                $ff->execute($for_sql);

                $re = $ff->fetch();
            }

            if (self::$show_sql === true) {
                echo 'записей ' . $nn;
            }

            return \f\end3('Достали данные', true, $re, 'array');
        }
        //
        catch (\PDOException $ex) {

            echo '<pre>--- ' . __FILE__ . ' ' . __LINE__ . '-------'
            . PHP_EOL . $ex->getMessage() . ' #' . $ex->getCode()
            . PHP_EOL . $ex->getFile() . ' #' . $ex->getLine()
            . PHP_EOL . $ex->getTraceAsString()
            . '</pre>';
            // не найдена таблица, создаём значит её
            if (strpos($ex->getMessage(), 'no such table') !== false) {

                self::creatTable($db);
                // \f\redirect( '/' );
            }

            return \f\end3('ошибка', false);
        }
    }

    /**
     * получаем данные из итемс хранилища
     * старая версия (новая опубликована 1911270941
     * @param type $db
     * @param type $module
     * @param type $stat
     * @return type
     */
    public static function getItemsSimple($db, $module = null, $stat = 'show', $sort = null) {

        $show_memory = false;
        //        $show_memory = true;
        if ($show_memory === true) {
            $sm = 0;
            $sm = memory_get_usage();
            //            echo '<br/>s1s ' . round(( $sm2 - $sm ) / 1024, 3);
        }

        $save_cash = false;

        if (empty(self::$sql_select_vars) && empty(self::$sql_itemsdop_add_where) && empty(self::$sql_itemsdop2_add_where) && empty(self::$sql_items_add_where) && empty(self::$sql_limit) && empty(self::$sql_order)) {

            if (!empty(self::$cash[$module][$stat])) {

                if (self::$get_data_simple === true) {

                    $ret = [];
                    foreach (self::$cash[$module][$stat]['data'] as $k => $v) {
                        $v['dop']['_id'] = $v['id'];
                        $v['dop']['_head'] = $v['head'];
                        $ret[] = $v['dop'];
                    }
                    self::$get_data_simple = null;
                    return \f\end2('Достали список, простой', 'ok', $ret, 'array');
                } else {
                    return self::$cash[$module][$stat];
                }
            } else {
                $save_cash = true;
            }
        }

        $folder = \Nyos\nyos::$folder_now;

        //        if( $_SERVER['HTTP_HOST'] == 'yapdomik.uralweb.info' ){
        //                                                                                                                                                                                                                            echo $folder. '<Br/> 2 '.$module.'<br/> 3 '.$stat.'<Br/> 4 '.$limit;
        //        }


        if (empty(self::$sql_order) && $sort == 'sort') {
            self::$sql_order = ' ORDER BY mi.sort ASC ';
        } elseif (empty(self::$sql_order) && $sort == 'asc_date') {
            self::$sql_order = ' ORDER BY mi.add_d ASC, mi.add_t ASC ';
        } elseif (empty(self::$sql_order) && $sort == 'desc_id') {
            self::$sql_order = ' ORDER BY mi.id DESC ';
        }

        if (self::$dir_img_server === false) {
            self::creatFolderImage($folder);
        }

        //        $dir_for_cash = DR . dir_site;
        //        if (isset($module{1}) && file_exists($dir_for_cash . 'cash.items.' . $module . '.arr')) {
        //
        //            $out = unserialize(file_get_contents($dir_for_cash . 'cash.items.' . $module . '.arr'));
        //            return f\end2('Достали список', 'ok', $out, 'array');
        //        } elseif (file_exists($dir_for_cash . 'cash.items.arr')) {
        //
        //            $out = unserialize(file_get_contents($dir_for_cash . 'cash.items.arr'));
        //            return f\end2('Достали список', 'ok', $out, 'array');
        //        }














        try {

            $ff1 = 'SELECT 
                mi.*,
                midop.id dops_id, 
                midop.`name`, 
                midop.`value`,
                midop.`value_date`,
                midop.`value_datetime`,
                midop.`value_text` '
                    . (self::$sql_select_vars ?? '')
                    . '
            FROM 
                `mitems-dops` midop 
                
            INNER JOIN `mitems` mi ON '
                    // .' mi.folder = :folder '
                    . ' mi.id = midop.id_item ' . PHP_EOL
                    // . ( isset($module{1}) ? ' AND mi.`module` = \'' . addslashes($module) . '\' ' : '' ). PHP_EOL
                    . ' AND mi.`module` = :module '
                    . (!empty($stat) ? PHP_EOL . ' AND mi.`status` = \'' . addslashes($stat) . '\' ' : '')
                    . PHP_EOL . ' AND mi.`status` != \'delete2\' '
                    . (!empty(self::$sql_items_add_where) ? ' AND ' . self::$sql_items_add_where : '')
                    . PHP_EOL
                    . (self::$sql_itemsdop2_add_where ?? '') . '
            WHERE 
                midop.status IS NULL 
                ' . (!empty(self::$sql_itemsdop_add_where) ? ' AND ' . self::$sql_itemsdop_add_where : '')
                    . ' ' . (self::$sql_order ?? '')
                    . ' ' . (self::$sql_limit ?? '')
                    . ';';

            //            if ($_SERVER['HTTP_HOST'] == 'yapdomik.uralweb.info') {
            //                \f\pa($ff1);
            //            }
            if (self::$show_sql === true) {
                \f\pa($ff1);
            }

            self::$sql_select_vars = null;
            self::$sql_itemsdop_add_where = null;
            self::$sql_itemsdop2_add_where = null;
            self::$sql_items_add_where = null;
            self::$sql_limit = '';
            self::$sql_order = '';

            //        if( isset(self::$sql_itemsdop_add_where{3}) ){
            //            echo '<pre>';
            //        echo $ff1;
            //        echo '</pre>';
            //        echo '<br/><br/>';
            //        }

            $ff = $db->prepare($ff1);

            $for_sql = self::$sql_itemsdop_add_where_array;
            // $for_sql[':folder'] = $folder;
            $for_sql[':module'] = (isset($module{
                            1})) ? $module : '';
            self::$sql_itemsdop_add_where_array = [];

            if (self::$show_sql === true) {
                \f\pa($for_sql);
            }

            $ff->execute($for_sql);

            //            if ($_SERVER['HTTP_HOST'] == 'yapdomik.uralweb.info') {
            //                \f\pa($for_sql);
            //            }
            // \f\pa($ff->fetchAll());
            $nn = 0;
            while ($r = $ff->fetch()) {

                //            if( $_SERVER['HTTP_HOST'] == 'yapdomik.uralweb.info' )
                //                \f\pa($r);

                if (self::$show_sql === true) {
                    \f\pa($r);
                }

                if (!isset($re[$r['id']])) {
                    $re[$r['id']] = $r;
                    //                    $re[$r['id']] = array(
                    //                        'id' => $r['id'],
                    //                        //'folder' => $r['folder'],
                    //                        'module' => $r['module'],
                    //                        'head' => $r['head'],
                    //                        'sort' => $r['sort'],
                    //                        'status' => $r['status'],
                    //                        'add_d' => $r['add_d'],
                    //                        'add_t' => $r['add_t']
                    //                    );
                }

                if (!isset($re[$r['id']]['dop']))
                    $re[$r['id']]['dop'] = [];

                $re[$r['id']]['dop'][$r['name']] = $r['value'] ?? $r['value_date'] ?? $r['value_text'] ?? $r['value_int'] ?? $r['value_datetime'] ?? null;
                $nn++;
            }

            if (1 == 1) {


                $sql_dop = '';

                if (!empty($re)) {
                    foreach ($re as $k => $v) {
                        $sql_dop .= ' AND mi.id != \'' . $k . '\' ';
                    }
                }

                $f = 'SELECT mi.* FROM `mitems` mi '
                        //. ' INNER JOIN '
                        // . ' LEFT JOIN `mitems-dops` mi2 ON mi.id = mi2.id_item '
                        . 'WHERE '
                        // . ' mi.folder = :folder '
                        . 'mi.`status` != \'delete2\' '
                        . $sql_dop
                        . (isset($module{
                                1}) ? ' AND mi.`module` = \'' . addslashes($module) . '\' ' : '')
                        . (isset($stat{
                                1}) ? ' AND mi.`status` = \'' . addslashes($stat) . '\' ' : '')
                        . 'ORDER BY '
                        . (self::$sort_head == 'desc' ? ' mi.head DESC, ' : '')
                        . (self::$sort_head == 'asc' ? ' mi.head ASC, ' : '')
                        . ' mi.`sort` DESC, mi.`add_d` DESC, mi.`add_t` DESC '
                        // . ( isset($limit{1}) && is_numeric($limit) ? 'LIMIT ' . $limit . ' ' : '' )
                        . ';';

                //            if( $_SERVER['HTTP_HOST'] == 'yapdomik.uralweb.info' )
                //                echo '<br/>'.'<br/>'.$f;

                $ff = $db->prepare($f);
                $ff->execute(
                //array( ':folder' => $folder )
                );

                while ($v = $ff->fetch()) {

                    $re[$v['id']] = $v;
                }
            }

            if (self::$show_sql === true) {
                echo 'записей ' . $nn;
            }








            if (1 == 2 || $nn == 0) {

                $sql_dop = '';

                if (!empty($re)) {
                    foreach ($re as $k => $v) {
                        $sql_dop .= (!empty($sql_dop) ? ' AND ' : '') . ' mi.id != \'' . $k . '\' ';
                    }
                }

                $ff1 = 'SELECT 
                        mi.* '
                        . ' ' . (self::$sql_select_vars ?? '')
                        . '
                    FROM 
                        `mitems` mi
                    WHERE '
                        . ' mi.`status` != \'delete2\' '
                        . $sql_dop
                        // .' mi.folder = :folder '
                        . (isset($module{
                                1}) ? ' AND mi.`module` = \'' . addslashes($module) . '\' ' : '')
                        . (isset($stat{
                                1}) ? ' AND mi.`status` = \'' . addslashes($stat) . '\' ' : '')
                        . (!empty(self::$sql_items_add_where) ? ' AND ' . self::$sql_items_add_where : '') . '
                        ' . (self::$sql_itemsdop2_add_where ?? '') . '
                        ' . (!empty(self::$sql_itemsdop_add_where) ? ' AND ' . self::$sql_itemsdop_add_where : '')
                        . ' ' . (self::$sql_order ?? '')
                        . ' ' . (self::$sql_limit ?? '')
                        . ';';

                //                if ($_SERVER['HTTP_HOST'] == 'yapdomik.uralweb.info') {
                //                    \f\pa($ff1);
                //                }


                self::$sql_select_vars = null;
                self::$sql_itemsdop_add_where = null;
                self::$sql_itemsdop2_add_where = null;
                self::$sql_items_add_where = null;
                self::$sql_limit = '';
                self::$sql_order = '';

                //        if( isset(self::$sql_itemsdop_add_where{3}) ){
                //            echo '<pre>';
                //        echo $ff1;
                //        echo '</pre>';
                //        echo '<br/><br/>';
                //        }

                $ff = $db->prepare($ff1);

                $for_sql = self::$sql_itemsdop_add_where_array;
                //$for_sql[':folder'] = $folder;
                self::$sql_itemsdop_add_where_array = [];

                if (self::$show_sql === true) {
                    \f\pa($for_sql);
                }

                $ff->execute($for_sql);

                //                if ($_SERVER['HTTP_HOST'] == 'yapdomik.uralweb.info') {
                if (self::$show_sql === true) {
                    \f\pa($for_sql);
                }

                while ($r = $ff->fetch()) {
                    $re[$r['id']] = $r;
                }
            }
        }
        //
        catch (\PDOException $ex) {

            //            echo '<pre>--- ' . __FILE__ . ' ' . __LINE__ . '-------'
            //            . PHP_EOL . $ex->getMessage() . ' #' . $ex->getCode()
            //            . PHP_EOL . $ex->getFile() . ' #' . $ex->getLine()
            //            . PHP_EOL . $ex->getTraceAsString()
            //            . '</pre>';
            // не найдена таблица, создаём значит её
            if (strpos($ex->getMessage(), 'no such table') !== false) {

                self::creatTable($db);
                // \f\redirect( '/' );
            }
        }

        $out = array(
            'data' => $re ?? [], 'img_dir' => self::$dir_img_uri, 'img_dir_dl' => self::$dir_img_uri_download
        );

        if ($save_cash === true)
            self::$cash[$module][$stat] = $out;

        //        if (is_dir($dir_for_cash)) {
        //            if (isset($module{1})) {
        //                file_put_contents($dir_for_cash . 'cash.items.' . \f\translit($module, 'uri2') . '.arr', serialize($out));
        //            } else {
        //                file_put_contents($dir_for_cash . 'cash' . \f\translit($module, 'uri2') . 'items.arr', serialize($out));
        //            }
        //        }

        self::$show_sql = false;

        if (self::$get_data_simple === true) {

            $ret = [];
            foreach ($out['data'] as $k => $v) {
                $v['dop']['_head'] = $v['head'];
                $v['dop']['_id'] = $v['id'];
                $ret[$v['id']] = $v['dop'];
            }
            self::$get_data_simple = null;




            if ($show_memory === true) {
                $sm2 = 0;
                $sm2 = memory_get_usage();
                //            echo '<br/>s1s ' . round(( $sm2 - $sm ) / 1024, 3);
            }



            return \f\end2('Достали список, простой', 'ok', $ret, 'array');
        } else {
            return \f\end2('Достали список', 'ok', $out, 'array');
        }
    }

    /**
     * новая версия от 191127 0941
     * @param type $db
     * @param type $module
     * @param type $stat
     * @param type $sort
     * @return type
     */
    public static function getItemsSimple2($db, $module = null, $stat = 'show', $sort = null) {

        // echo ' память ';

        if ($module == '070.jobman' || $module == '061.dolgnost')
            $file_cash = DR . dir_site_sd . 'getItemsSimple2_' . $module . '_' . $stat . (!empty($sort) ? md5($sort) : '') . '.cash.json';

        //        echo '<Br/>'.$file_cash;

        /**
         * читаем кеш контент
         */
        if (1 == 2 && isset($file_cash) && file_exists($file_cash)) {
            return json_decode(file_get_contents($file_cash), true);
        }

        $cash = $module . $stat . (!empty($sort) ? md5($sort) : '');

        if (!empty(self::$cash['itemsimple'][$cash]))
            return self::$cash['itemsimple'][$cash];

        $show_memory = false;
        $show_memory = true;

        if ($show_memory === true) {
            $sm = 0;
            $sm = memory_get_usage();
            //            echo '<br/>s1s ' . round(( $sm2 - $sm ) / 1024, 3);
        }

        $folder = \Nyos\nyos::$folder_now;

        if (self::$dir_img_server === false) {
            self::creatFolderImage($folder);
        }

        \f\timer::start(47);

        // $ff1 = ' ( SELECT 
        $ff1 = ' SELECT 
                mi.id,
                mi.head,
                mi.sort,
                mi.status,

                midop.id dops_id, 
                midop.`name`, 
                midop.`value`,
                midop.`value_date`,
                midop.`value_datetime`,
                midop.`value_text` 

            FROM 
                `mitems` mi

            LEFT JOIN `mitems-dops` midop ON '
                . ' mi.id = midop.id_item '
                . ' AND midop.status IS NULL '
                . ' WHERE '
                . ' mi.`module` = :module '
                . (!empty($stat) ? ' AND mi.status = \'' . addslashes($stat) . '\' ' : '')
                . (self::$where2 ?? '')
                . self::$sql_order ?? '';


        //            \f\pa($ff1);

        self::$where2 = '';

        $ff = $db->prepare($ff1);

        $for_sql = [];
        $for_sql[':module'] = $module ?? '';

        $ff->execute($for_sql);

        // \f\pa( $ff->fetchAll(), '', '', 'все');
        // die;
        // while( \f\pa($ff->fetchAll(), '', '', 'все');

        $re = [];
        $sql = '';

        while ($r = $ff->fetch()) {


            if (empty($re[$r['id']])) {

                $re[$r['id']] = [
                    'id' => $r['id'],
                    'head' => $r['head'],
                    'sort' => $r['sort'],
                    'status' => $r['status']
                ];

                // \f\pa($r);
                // $re[] = [ 'id' => $r['id'], 'head' => $r['head'], 'sort' => $r['sort'] ];
                // $sql .= (!empty($sql) ? ',' : '' ) . $r['id'];
            }

            $re[$r['id']][$r['name']] = $r['value'] ?? $r['value_date'] ?? $r['value_text'] ?? $r['value_int'] ?? $r['value_datetime'] ?? null;
        }

        // echo '<br/>timer: ' . \f\timer::stop('str', 47);

        if ($sort == 'sort_asc') {
            usort($re, "\\f\\sort_ar_sort");
        }

        // \f\pa($re);

        if (!empty(self::$sql_order))
            self::$sql_order = '';

        /**
         * пишем кеш контент
         */
        if (!empty($file_cash)) {
            file_put_contents($file_cash, json_encode($re));
        }

        return $re;
        return self::$cash['itemsimple'][$cash] = $re;


        /*
          $re2 = [];
          foreach ($re as $k => $v) {
          if (isset($v['id']))
          $re2[$v['id']] = $k;
          }

          // \f\pa($re);
          // \f\pa($sql);
          // die();
          // $ff1 = ' ( SELECT
          $ff1 = ' SELECT

          midop.id_item id,
          midop.id dops_id,
          midop.`name`,
          midop.`value`,
          midop.`value_date`,
          midop.`value_datetime`,
          midop.`value_text` '
          . ( self::$sql_select_vars ?? '' )
          . '

          FROM `mitems-dops` midop '
          . ' WHERE '
          . ' midop.id_item IN (' . $sql . ') '
          . ' AND midop.status IS NULL '

          ;

          //            \f\pa($ff1);

          $ff = $db->prepare($ff1);

          $for_sql = [];
          $ff->execute($for_sql);

          // while( \f\pa($ff->fetchAll(), '', '', 'все');
          // $re = [];

          while ($r = $ff->fetch()) {

          if (empty($re[( $re2[$r['id']] ?? $r['id'] )]))
          $re[( $re2[$r['id']] ?? $r['id'] )] = ['id' => $r['id'], 'head' => $re1[$r['id']], 'start2' => 'ok'];

          if (!empty($r['name']))
          $re[( $re2[$r['id']] ?? $r['id'] )][$r['name']] = $r['value'] ?? $r['value_date'] ?? $r['value_text'] ?? $r['value_int'] ?? $r['value_datetime'] ?? null;
          }



          if ($sort == 'sort_asc') {
          usort($re, "\\f\\sort_ar_sort");
          }


          if ($show_memory === true) {
          $sm2 = 0;
          $sm2 = memory_get_usage();
          echo '<br/>s' . __LINE__ . 's > ' . round(( $sm2 - $sm ) / 1024, 2) . ' Kb = ';
          }

          // \f\pa($re);

          return self::$cash['itemsimple'][$cash] = $re;

          //return \f\end2('Достали список, простой', 'ok', $re, 'array');
          // return \f\end3('Достали список, простой', true, $re);
         * 
         */
    }

    /**
     * новая версия от 191128 0823
     * @param type $db
     * @param type $module
     * @param type $stat
     * @param type $sort
     * date_asc sort_asc
     * @return type
     */
    public static function getItemsSimple3($db, $module = null, $stat = 'show', $sort = null) {

        return self::get($db, $module, $stat, $sort);

        // echo '<br/>'.__FUNCTION__.' '.$module;

        try {

            if (empty(self::$where2dop) && empty(self::$need_polya_vars)) {
                $save_cash = true;
            } else {
                $save_cash = false;
            }

            // echo '<br/>-- ' . $cash_var;
            //        if( $_SERVER['HTTP_HOST'] == 'yapdomik.uralweb.info' )
            //            echo '<br/>a - '.$module;
            //        
            //        if ( 1 == 1 || $module == '050.chekin_checkout') {

            /**
             * запускаем мемкеш и тащим если есть кеш
             */
            $memcache = false;
            if (1 == 2) {
                $memcache = true;

                if (isset($memcache) && $memcache === true && $save_cash === true) {
                    if (1 == 1 && class_exists('memcache')) {

                        $cash_var = $module . '__' . $stat . '__' . $sort;
                        // echo '<br/>'.$cash_var;

                        $memcache_obj = new \Memcache;
                        //Соединяемся с нашим сервером
                        $memcache_obj->connect('127.0.0.1', 11211) or die("Could not connect");

                        //Попытаемся получить объект с ключом our_var
                        $var_key = @$memcache_obj->get($cash_var);

                        if (!empty($var_key)) {

                            //                if ($_SERVER['HTTP_HOST'] == 'yapdomik.uralweb.info') {
                            //                    echo '<Br/>есть рез';
                            //                }

                            $memcache_obj->close();
                            //Если объект закэширован, выводим его значение

                            if (self::$style_old === true) {
                                return ['data' => $var_key];
                            } else {
                                return $var_key;
                            }

                            // return json_decode($var_key);
                        }
                        //    else
                        //    {
                        //        //Если в кэше нет объекта с ключом our_var, создадим его
                        //        //Объект our_var будет храниться 5 секунд и не будет сжат
                        //        $memcache_obj->set('our_var', date('G:i:s'), false, 5);
                        //         //Выведем закэшированные данные
                        //        echo $memcache_obj->get('our_var');
                        //    }
                        //    //Закрываем соединение с сервером Memcached
                        //    $memcache_obj->close();        
                    }
                }
            }

            if (1 == 1) {

                //        echo '<br/><br/>' . __FUNCTION__ . '<Br/>';
                //        echo '<br/>' . $module;
                // echo ' память ';
                //        if ($module == '070.jobman' || $module == '061.dolgnost')
                //            $file_cash = DR . dir_site_sd . 'getItemsSimple2_' . $module . '_' . $stat . (!empty($sort) ? md5($sort) : '' ) . '.cash.json';
                //        echo '<Br/>'.$file_cash;

                /**
                 * читаем кеш контент
                 */
                //        if (1 == 2 && isset($file_cash) && file_exists($file_cash)) {
                //            return json_decode(file_get_contents($file_cash), true);
                //        }
                //        $cash = $module . $stat . (!empty($sort) ? md5($sort) : '' );
                //        if (!empty(self::$cash['itemsimple'][$cash]))
                //            return self::$cash['itemsimple'][$cash];
                //        $show_memory = false;
                // $show_memory = true;
                //
                if (isset($show_memory) && $show_memory === true) {

                    echo '<hr>mod - ' . $module . '<br/>';

                    $sm = 0;
                    $sm = memory_get_usage();
                    //            echo '<br/>s1s ' . round(( $sm2 - $sm ) / 1024, 3);

                    \f\timer::start(123);
                }

                $folder = \Nyos\nyos::$folder_now;

                if (self::$dir_img_server === false) {
                    self::creatFolderImage($folder);
                }

                //        \f\timer::start(47);
                // $ff1 = ' ( SELECT 
                //            if (!empty(self::$need_polya_vars)) {
                //                $sql_dop1 = '';
                //                
                //                    foreach( self::$need_polya_vars as $kk ){
                //                        if( !isset($v[$kk]) ){
                //                            
                //                            $sql_dop1 = ' INNER JOIN ';
                //                            
                //                            // $skip = true;
                //                            // break;
                //                        }
                //                        
                //                    }
                //
                //            }else{
                //                $sql_dop1 = '';
                //            }
                //            if ($sort == 'date_asc') {
                //                self::$sql_order = ' ORDER BY midop.id ASC ';
                //            }
                //            //
                //            else
                if ($sort == 'date__desc') {
                    self::$sql_order = ' ORDER BY mi.add_d DESC, mi.add_t DESC ';
                }
                //
                elseif ($sort == 'add__asc') {
                    self::$sql_order = ' ORDER BY mi.add_d ASC, mi.add_t ASC ';
                }
                //
                elseif ($sort == 'add__desc') {
                    self::$sql_order = ' ORDER BY mi.add_d DESC, mi.add_t DESC ';
                }
                //
                elseif ($sort == 'add_date__desc') {
                    self::$sql_order = ' ORDER BY mi.add_d DESC ';
                }
                //
                elseif ($sort == 'sort_asc') {
                    self::$sql_order = ' ORDER BY mi.sort ASC ';
                }
                //
                elseif ($sort == 'sort_desc') {
                    self::$sql_order = ' ORDER BY mi.sort DESC ';
                }


                $ff1 = ' SELECT 
                mi.id,
                mi.head,
                mi.sort,
                mi.status
                ' . self::$select_var1 . '
            FROM 
                `mitems` mi '
                        . (self::$join_where ?? '')
                        . ' WHERE '
                        . ' mi.`module` = :module '
                        . (!empty($stat) ? ' AND mi.status = \'' . addslashes($stat) . '\' ' : '')
                        . (self::$where2 ?? '')
                        . self::$sql_order ?? '';

                if (self::$show_sql === true)
                    \f\pa($ff1);

                self::$join_where = self::$where2 = '';

                $ff = $db->prepare($ff1);


                self::$var_ar_for_1sql[':module'] = ($module ?? '');
                $ff->execute(self::$var_ar_for_1sql);

                if (self::$show_sql === true)
                    \f\pa(self::$var_ar_for_1sql);


                self::$var_ar_for_1sql = [];
                self::$select_var1 = '';

                // \f\pa( $ff->fetchAll(), '', '', 'все');
                // die;
                // while( \f\pa($ff->fetchAll(), '', '', 'все');

                $re = [];
                $sql = '';

                while ($r = $ff->fetch()) {

                    if (empty($re[$r['id']])) {

                        // \f\pa($r);
                        //                        $re[$r['id']] = [
                        //                            'id' => $r['id'],
                        //                            'head' => $r['head'],
                        //                            'sort' => $r['sort'],
                        //                            'status' => $r['status']
                        //                        ];

                        $re[$r['id']] = $r;

                        // \f\pa($r);
                        // $re[] = [ 'id' => $r['id'], 'head' => $r['head'], 'sort' => $r['sort'] ];
                        $sql .= (!empty($sql) ? ',' : '') . $r['id'];
                    }

                    //            $re[$r['id']][$r['name']] = $r['value'] ?? $r['value_date'] ?? $r['value_text'] ?? $r['value_int'] ?? $r['value_datetime'] ?? null;
                }

                //        echo '<br/>timer: ' . \f\timer::stop('str', 47);
                //        if ($sort == 'sort_asc') {
                //            usort($re, "\\f\\sort_ar_sort");
                //        }
                // \f\pa($re);

                if (!empty(self::$sql_order))
                    self::$sql_order = '';

                if (!empty($sql)) {

                    /**
                     * пишем кеш контент
                     */
                    //        if (!empty($file_cash)) {
                    //            file_put_contents($file_cash, json_encode($re));
                    //        }
                    //        return $re;
                    //        return self::$cash['itemsimple'][$cash] = $re;


                    /*
                      $re2 = [];
                      foreach ($re as $k => $v) {
                      if (isset($v['id']))
                      $re2[$v['id']] = $k;
                      }

                      // \f\pa($re);
                      // \f\pa($sql);
                      // die();
                      // $ff1 = ' ( SELECT
                     * */
                    $ff1 = ' SELECT

                midop.id_item id, '
                            // .' midop.id dops_id, '
                            . ' midop.`name`,
                midop.`value`,
                midop.`value_date`,
                midop.`value_datetime`,
                midop.`value_text` '
                            . (self::$sql_select_vars ?? '')
                            . '
            FROM `mitems-dops` midop '
                            . ' WHERE '
                            . ' midop.id_item IN (' . $sql . ') '
                            . ' AND midop.status IS NULL '
                            . (self::$where2dop ?? '');

                    //            \f\pa($ff1);

                    $ff = $db->prepare($ff1);

                    //        $for_sql = [];
                    //        $ff->execute($for_sql);
                    $ff->execute();

                    // while( \f\pa($ff->fetchAll(), '', '', 'все');
                    // $re = [];

                    while ($r = $ff->fetch()) {

                        if (empty($re[($re2[$r['id']] ?? $r['id'])])) {
                            //$re[( $re2[$r['id']] ?? $r['id'] )] = ['id' => $r['id'], 'head' => $re1[$r['id']], 'start2' => 'ok'];
                            $re[($re2[$r['id']] ?? $r['id'])] = ['id' => $r['id'], 'start2' => 'ok'];
                        }

                        if (!empty($r['name'])) {
                            if (self::$style_old === true) {
                                $re[($re2[$r['id']] ?? $r['id'])]['dop'][$r['name']] = $r['value'] ?? $r['value_date'] ?? $r['value_text'] ?? $r['value_int'] ?? $r['value_datetime'] ?? null;
                            } else {
                                $re[($re2[$r['id']] ?? $r['id'])][$r['name']] = $r['value'] ?? $r['value_date'] ?? $r['value_text'] ?? $r['value_int'] ?? $r['value_datetime'] ?? null;
                            }
                        }
                    }

                    if (!empty(self::$need_polya_vars)) {

                        $re2 = [];

                        foreach ($re as $k => $v) {

                            $skip = false;

                            foreach (self::$need_polya_vars as $kk) {

                                if (!isset($v[$kk])) {
                                    $skip = true;
                                    break;
                                }
                            }

                            if ($skip == true) {
                                //$re2[$k] = $v;
                                unset($re[$k]);
                            }
                        }

                        //                $re = $re2;
                        //                unset($re2);
                    }

                    if ($sort == 'sort_asc') {
                        usort($re, "\\f\\sort_ar_sort");
                    } elseif ($sort == 'date_asc') {
                        usort($re, "\\f\\sort_ar_date");
                    }

                    if (isset($show_memory) && $show_memory === true) {
                        $sm2 = 0;
                        $sm2 = memory_get_usage();
                        echo '<br/>'
                        . 's' . __LINE__ . 's > ' . round(($sm2 - $sm) / 1024, 2) . ' Kb = '
                        . '<br/>'
                        . 'timer:' . \f\timer::stop('str', 123) . ' - ';
                    }
                }
            }

            // \f\pa($re);






            /**
             * если в начале запустили мемкеш то сохраняем результат
             */
            //        if (isset($memcache) && $memcache === true) {
            //            if (isset($memcache_obj) && !empty($cash_var)) {
            //
            //                //Выведем закэшированные данные
            //                $memcache_obj->set($cash_var, $re, false, 3600 * 24);
            //                //Закрываем соединение с сервером Memcached
            //                $memcache_obj->close();
            //            }
            //        }



            /**
             * трем переменные
             */
            self::$where2dop = '';
            self::$need_polya_vars = [];


            if (self::$style_old === true) {
                self::$style_old = false;
                return ['data' => $re];
            } else {
                return $re;
            }

            /*
              return self::$cash['itemsimple'][$cash] = $re;

              //return \f\end2('Достали список, простой', 'ok', $re, 'array');
              // return \f\end3('Достали список, простой', true, $re);
             *
             */
        }
        //
        catch (\PDOException $ex) {

            echo '<pre>--- ' . __FILE__ . ' ' . __LINE__ . '-------'
            . PHP_EOL . $ex->getMessage() . ' #' . $ex->getCode()
            . PHP_EOL . $ex->getFile() . ' #' . $ex->getLine()
            . PHP_EOL . $ex->getTraceAsString()
            . '</pre>';
            // не найдена таблица, создаём значит её
            if (strpos($ex->getMessage(), 'no such table') !== false) {

                self::creatTable($db);
                // \f\redirect( '/' );
            }

            return \f\end3('ошибка', false);
        }
    }

    /**
     * получаем данные, 
     * @param type $db
     * @param строка $module
     * @param строка $stat
     * show hide ''
     * @param строка $sort
     * @return массив
     */
    public static function get($db, $module = null, $stat = 'show', $sort = null) {

        // запись строки в файл лога
        if (1 == 2) {
            $log_file = $_SERVER['DOCUMENT_ROOT'] . '/log_mov.log';
            // `\f\pa($log_file);
            $e = debug_backtrace();
            $debug_line = $e[0]['file'] . ' #' . $e[0]['line'];
            file_put_contents($log_file
                    , PHP_EOL . date('Y-m-d H:i:s') . ' items get module ' . $module . ' ' . $stat . ' ' . $sort
                    . PHP_EOL . '           ' . $debug_line
                    . PHP_EOL . '           ' . __FILE__ . ' #' . __LINE__
                    , FILE_APPEND);
        }

        if ($stat == 'all')
            $stat = '';

        // echo '<br/>'.__FUNCTION__.' '.$module;

        try {

            if (empty(self::$cash_var)) {

                $dop_cash_var = (!empty(self::$sql_get_dops) ? serialize(self::$sql_get_dops) : '')
                        . '..'
                        . (!empty(self::$where2) ? serialize(self::$where2) : '')
                        . '..'
                        . (!empty(self::$search) ? serialize(self::$search) : '')
                        . '..'
                        . (!empty(self::$join_where) ? serialize(self::$join_where) : '')
                        . '..'
                        . (!empty(self::$sql_vars) ? serialize(self::$sql_vars) : '')
                        . '..'
                        . (!empty(self::$sql_get_dops) ? serialize(self::$sql_get_dops) : '')
                        . '..'
                        . (!empty(self::$return_items_header) ? serialize(self::$return_items_header) : '')
                        . '.s.' . ($sort ?? '');

                self::$cash_var = 'item_get_' . $module . '_' . $stat . '_' . $sort . '_' . (!empty($dop_cash_var) ? md5($dop_cash_var) : '');
            }

            // self::$cash_var = '';

            if (!empty(self::$cash_var)) {

                $re = false;
                // $re = \f\Cash::getVar(self::$cash_var);

                if ($re !== false)
                    return $re;
            }



            if (
                    empty(self::$where2dop) && empty(self::$where2) && empty(self::$need_polya_vars) && empty(self::$nocash) && empty(self::$join_where) && empty(self::$var_ar_for_1sql)
            ) {

                $save_cash = true;
                // echo '<br/>#'.__LINE__;
            } else {

                $save_cash = false;
                // echo '<br/>#'.__LINE__;
            }

            // echo '<br/>-- ' . $cash_var;
            //        if( $_SERVER['HTTP_HOST'] == 'yapdomik.uralweb.info' )
            //            echo '<br/>a - '.$module;
            //        
            //        if ( 1 == 1 || $module == '050.chekin_checkout') {

            $save_cash = false;

            if (
                    $save_cash === true &&
                    ($module == \Nyos\mod\JobDesc::$mod_jobman || $module == \Nyos\mod\JobDesc::$mod_man_job_on_sp || $module == \Nyos\mod\JobDesc::$mod_dolgn
                    // || $module == \Nyos\mod\JobDesc::$mod_salary
                    || $module == \Nyos\mod\JobDesc::$mod_sp_link_timeo)
            ) {

                $cash_var = 'items__mod' . $module . '_' . $stat . '_' . $sort;
                $e = \f\Cash::getVar($cash_var);
                // \f\pa($e, '', '', '$e');
                // echo '<br/>#'.__LINE__;

                if (!empty($e))
                    return $e;
            } else {
                $save_cash = false;
            }

            /**
             * запускаем мемкеш и тащим если есть кеш
             */
            $memcache = false;
            if (1 == 2) {
                $memcache = true;

                if (isset($memcache) && $memcache === true && $save_cash === true) {
                    if (1 == 1 && class_exists('memcache')) {

                        $cash_var = $module . '__' . $stat . '__' . $sort;
                        // echo '<br/>'.$cash_var;

                        $memcache_obj = new \Memcache;
                        //Соединяемся с нашим сервером
                        $memcache_obj->connect('127.0.0.1', 11211) or die("Could not connect");

                        //Попытаемся получить объект с ключом our_var
                        $var_key = @$memcache_obj->get($cash_var);

                        if (!empty($var_key)) {

                            //                if ($_SERVER['HTTP_HOST'] == 'yapdomik.uralweb.info') {
                            //                    echo '<Br/>есть рез';
                            //                }

                            $memcache_obj->close();
                            //Если объект закэширован, выводим его значение

                            if (self::$style_old === true) {
                                return ['data' => $var_key];
                            } else {
                                return $var_key;
                            }

                            // return json_decode($var_key);
                        }
                        //    else
                        //    {
                        //        //Если в кэше нет объекта с ключом our_var, создадим его
                        //        //Объект our_var будет храниться 5 секунд и не будет сжат
                        //        $memcache_obj->set('our_var', date('G:i:s'), false, 5);
                        //         //Выведем закэшированные данные
                        //        echo $memcache_obj->get('our_var');
                        //    }
                        //    //Закрываем соединение с сервером Memcached
                        //    $memcache_obj->close();        
                    }
                }
            }

            if (1 == 1) {

                if (isset($show_memory) && $show_memory === true) {

                    echo '<hr>mod - ' . $module . '<br/>';

                    $sm = 0;
                    $sm = memory_get_usage();
                    //            echo '<br/>s1s ' . round(( $sm2 - $sm ) / 1024, 3);

                    \f\timer::start(123);
                }

                $folder = \Nyos\nyos::$folder_now;

                if (self::$dir_img_server === false) {
                    self::creatFolderImage($folder);
                }

                if ($sort == 'date__desc') {
                    self::$sql_order = ' ORDER BY mi.add_d DESC, mi.add_t DESC ';
                }
                //
                elseif ($sort == 'add__asc') {
                    self::$sql_order = ' ORDER BY mi.add_d ASC, mi.add_t ASC ';
                }
                //
                elseif ($sort == 'add__desc') {
                    self::$sql_order = ' ORDER BY mi.add_d DESC, mi.add_t DESC ';
                }
                //
                elseif ($sort == 'add_date__desc') {
                    self::$sql_order = ' ORDER BY mi.add_d DESC ';
                }
                //
                elseif (
                        $sort == 'sort_asc' || $sort == 'sort'
                ) {
                    self::$sql_order = ' ORDER BY mi.sort ASC ';
                }
                //
                elseif ($sort == 'sort_desc') {
                    self::$sql_order = ' ORDER BY mi.sort DESC ';
                }

                $search_id = '';
                $nnt = 1;
                if (!empty(self::$search['id'])) {
                    foreach (self::$search['id'] as $v) {
                        self::$var_ar_for_1sql[':search_id' . $nnt] = $v;
                        $search_id .= (!empty($search_id) ? ' OR ' : '' ) . ' mi.id = :search_id' . $nnt . ' ';
                        $nnt++;
                    }
                    $search_id = ' AND ( ' . $search_id . ' ) ';
                    unset(self::$search['id']);
                }

                $nn = 99;
                $nn2 = 99;

                if (1 == 1 && !empty(self::$between)) {

                    if (self::$show_sql === true)
                        \f\pa(self::$between, '', '', 'self::$between');

                    // $rebase = true;

                    foreach (self::$between as $k1 => $v1) {

                        if (is_array($v1) && isset($v1[0]) && isset($v1[1])) {

                            self::$join_where .= PHP_EOL . ' INNER JOIN `mitems-dops` md' . $nn . ' ON '
                                    . ' md' . $nn . '.id_item = mi.id '
                                    . PHP_EOL . ' AND md' . $nn . '.name = :i_name' . $nn . ' '
                                    . ' AND md' . $nn . '.value between :i_val' . $nn . '_0 and :i_val' . $nn . '_1 ';

                            self::$var_ar_for_1sql[':i_name' . $nn] = $k1;
                            self::$var_ar_for_1sql[':i_val' . $nn . '_0'] = $v1[0];
                            self::$var_ar_for_1sql[':i_val' . $nn . '_1'] = $v1[1];

                            $nn++;
                            $nn2++;
                        }
                    }

                    self::$between = [];
                }

                if (1 == 1 && !empty(self::$between_date)) {

                    // echo '<br/>#'.__LINE__;

                    if (self::$show_sql === true)
                        \f\pa(self::$between_date, '', '', 'self::$between_date');

                    // $rebase = true;

                    foreach (self::$between_date as $k1 => $v1) {

                        if (is_array($v1) && isset($v1[0]) && isset($v1[1])) {

                            // echo '<br/>#'.__LINE__;

                            self::$join_where .= PHP_EOL . ' INNER JOIN `mitems-dops` md' . $nn . ' ON '
                                    . ' md' . $nn . '.id_item = mi.id '
                                    . PHP_EOL . ' AND md' . $nn . '.name = :i_name' . $nn . ' '
                                    . ' AND md' . $nn . '.value_date between :i_val' . $nn . '_0 and :i_val' . $nn . '_1 ';

                            self::$var_ar_for_1sql[':i_name' . $nn] = $k1;
                            self::$var_ar_for_1sql[':i_val' . $nn . '_0'] = $v1[0];
                            self::$var_ar_for_1sql[':i_val' . $nn . '_1'] = $v1[1];

                            $nn++;
                            $nn2++;
                        }
                    }
                    self::$between_date = [];
                }

                // self::$join_where .= ' /* */ ';

                if (1 == 1 && !empty(self::$between_datetime)) {

                    if (self::$show_sql === true)
                        \f\pa(self::$between_datetime, '', '', 'self::$between_datetime');

                    // $rebase = true;

                    foreach (self::$between_datetime as $k1 => $v1) {

                        if (sizeof($v1) > 0 && isset($v1[0]) && isset($v1[1])) {

                            self::$join_where .= PHP_EOL
                                    . ' INNER JOIN `mitems-dops` md' . $nn
                                    . PHP_EOL
                                    . ' ON '
                                    . ' md' . $nn . '.id_item = mi.id '
                                    . PHP_EOL
                                    . ' AND md' . $nn . '.name = :i_name' . $nn . ' '
                                    . PHP_EOL
                                    . ' AND md' . $nn . '.value_datetime between :i_val' . $nn . '_0 and :i_val' . $nn . '_1 ';

                            self::$var_ar_for_1sql[':i_name' . $nn] = $k1;
                            self::$var_ar_for_1sql[':i_val' . $nn . '_0'] = date('Y-m-d H:i:s', strtotime($v1[0]));
                            self::$var_ar_for_1sql[':i_val' . $nn . '_1'] = date('Y-m-d H:i:s', strtotime($v1[1]));

                            $nn++;
                            $nn2++;
                        }
                    }
                    self::$between_datetime = [];
                }

                //self::$sql_order = ' LIMIT 0,100 ';

                $ff1 = ' SELECT 
                mi.id,
                mi.head,
                mi.sort,
                mi.status
                ' . self::$select_var1 . '
            FROM 
                `mitems` mi '
                        . (self::$join_where ?? '')
                        . ' WHERE '
                        . (!empty($module) ? ' mi.`module` = :module ' : '')
                        . $search_id
                        . (!empty($stat) ? (!empty($module) ? ' AND ' : '') . ' mi.status = \'' . addslashes($stat) . '\' ' : '')
                        . (self::$where2 ?? '')
                        . self::$sql_order ?? '';

                if (self::$show_sql === true)
                    \f\pa($ff1, '', '', '$ff1 sql1');

                self::$join_where = self::$where2 = '';

                $ff = $db->prepare($ff1);

                if (!empty($module))
                    self::$var_ar_for_1sql[':module'] = ($module ?? '');

                $ff->execute(self::$var_ar_for_1sql);

                if (self::$show_sql === true)
                    \f\pa(self::$var_ar_for_1sql, '', '', 'self::$var_ar_for_1sql');

                self::$var_ar_for_1sql = [];
                self::$join_where = self::$select_var1 = self::$sql_order = '';

                /**
                 * возвращаем много первых запросов
                 */
                if (self::$return_items_header === true) {

                    self::$return_items_header = false;
                    // return $ff->fetchAll();
                    $return = [];
                    while ($w = $ff->fetch()) {
                        $return[$w['id']] = $w;
                    }
                    return $return;
                }


                // \f\pa( $ff->fetchAll(), '', '', 'все');
                // die;
                // while( \f\pa($ff->fetchAll(), '', '', 'все');

                $re = [];
                $sql_1id = $sql = '';



                while ($r = $ff->fetch()) {

                    if (empty($re[$r['id']])) {

                        // \f\pa($r);
                        //                        $re[$r['id']] = [
                        //                            'id' => $r['id'],
                        //                            'head' => $r['head'],
                        //                            'sort' => $r['sort'],
                        //                            'status' => $r['status']
                        //                        ];

                        /**
                         * добавляем секрет к id  ( $s )
                         */
                        if (self::$add_s_to_res === true) {
                            $r['s'] = \Nyos\Nyos::creatSecret($r['id']);
                        }

                        $re[$r['id']] = $r;

                        // \f\pa($r);
                        // $re[] = [ 'id' => $r['id'], 'head' => $r['head'], 'sort' => $r['sort'] ];
                        $sql .= (!empty($sql) ? ',' : '') . $r['id'];

                        $sql_1id = $r['id'];
                    }

                    //            $re[$r['id']][$r['name']] = $r['value'] ?? $r['value_date'] ?? $r['value_text'] ?? $r['value_int'] ?? $r['value_datetime'] ?? null;
                }

                //        echo '<br/>timer: ' . \f\timer::stop('str', 47);
                //        if ($sort == 'sort_asc') {
                //            usort($re, "\\f\\sort_ar_sort");
                //        }
                // \f\pa($re);

                if (!empty(self::$sql_order))
                    self::$sql_order = '';

                if (!empty($sql)) {

                    /**
                     * пишем кеш контент
                     */
                    //        if (!empty($file_cash)) {
                    //            file_put_contents($file_cash, json_encode($re));
                    //        }
                    //        return $re;
                    //        return self::$cash['itemsimple'][$cash] = $re;

                    /*
                      $re2 = [];
                      foreach ($re as $k => $v) {
                      if (isset($v['id']))
                      $re2[$v['id']] = $k;
                      }

                      // \f\pa($re);
                      // \f\pa($sql);
                      // die();
                      // $ff1 = ' ( SELECT
                     * */

                    $rebase = false;

                    //                    if (self::$show_sql === true)
                    //                        \f\pa(self::$join_where2, '', '', 'self::$join_where2 перед обработкой допов');

                    if (1 == 1) {

                        /**
                         * счётчик для переменных что будут в поиске и фильтрах
                         */
                        $nn = 1;
                        $nn2 = 1;

                        if (!empty(self::$search)) {

                            if (self::$show_sql === true)
                                \f\pa(self::$search, '', '', 'self::$search');

                            $rebase = true;

                            foreach (self::$search as $k1 => $v1) {
                                // if (isset($v1[0]) && isset($v1[1])) {

                                self::$join_where2 .= PHP_EOL . ' INNER JOIN `mitems-dops` midop' . $nn . ' ON '
                                        . ' midop' . $nn . '.id_item = midop.id_item '
                                        . ' AND midop' . $nn . '.name = :i_name' . $nn
                                        . ' AND ';

                                self::$vars_to_sql2[':i_name' . $nn] = $k1;

                                $s2 = '';

                                if (is_array($v1) && sizeof($v1) > 0) {

                                    foreach ($v1 as $k2 => $v2) {

                                        $s2 .= (!empty($s2) ? ' OR ' : '') . PHP_EOL . ' midop' . $nn . '.value = :i_val' . $nn2;
                                        //                                        . ' AND midop' . $nn . '.value = :i_val' . $nn
                                        //                                ;
                                        self::$vars_to_sql2[':i_val' . $nn2] = $v2;
                                        //                                    self::$vars_to_sql2[':i_val' . $nn . '_0'] = $v1[0];
                                        //                                    self::$vars_to_sql2[':i_val' . $nn . '_1'] = $v1[1];
                                        $nn2++;
                                    }
                                    // }
                                    self::$join_where2 .= ' ( ' . $s2 . ' ) ';
                                } else {

                                    self::$join_where2 .= PHP_EOL . ' midop' . $nn . '.value = :i_val' . $nn2;
                                    self::$vars_to_sql2[':i_val' . $nn2] = $v1;
                                }

                                $nn++;
                                $nn2++;
                            }
                            self::$search = [];
                        }

                        if (1 == 1 && !empty(self::$between)) {

                            if (self::$show_sql === true)
                                \f\pa(self::$between, '', '', 'self::$between');

                            $rebase = true;

                            foreach (self::$between as $k1 => $v1) {

                                if (is_array($v1) && isset($v1[0]) && isset($v1[1])) {

                                    self::$join_where2 .= PHP_EOL . ' INNER JOIN `mitems-dops` md' . $nn . ' ON '
                                            . ' md' . $nn . '.id_item = midop.id_item '
                                            . PHP_EOL . ' AND md' . $nn . '.name = :i_name' . $nn . ' '
                                            . ' AND md' . $nn . '.value between :i_val' . $nn . '_0 and :i_val' . $nn . '_1 ';

                                    //                            self::$join_where .= ' INNER JOIN `mitems-dops` midop' . $nn . ' ON '
                                    //                                    . ' midop' . $nn . '.id_item = midop.id_item '
                                    //                                    . ' AND midop' . $nn . '.name = :i_name' . $nn . ' '
                                    //                                    . ' AND midop' . $nn . '.value_date >= :i_val' . $nn . '_0 '
                                    //                                    . ' AND midop' . $nn . '.value_date <= :i_val' . $nn . '_1 '
                                    //                            ;

                                    self::$vars_to_sql2[':i_name' . $nn] = $k1;
                                    self::$vars_to_sql2[':i_val' . $nn . '_0'] = $v1[0];
                                    self::$vars_to_sql2[':i_val' . $nn . '_1'] = $v1[1];

                                    $nn++;
                                    $nn2++;
                                }
                            }
                            self::$between = [];
                        }

                        if (1 == 1 && !empty(self::$between_date)) {

                            if (self::$show_sql === true)
                                \f\pa(self::$between_date, '', '', 'self::$between_date');

                            $rebase = true;

                            foreach (self::$between_date as $k1 => $v1) {

                                if (is_array($v1) && isset($v1[0]) && isset($v1[1])) {

                                    self::$join_where2 .= PHP_EOL . ' INNER JOIN `mitems-dops` md' . $nn . ' ON '
                                            . ' md' . $nn . '.id_item = midop.id_item '
                                            . PHP_EOL . ' AND md' . $nn . '.name = :i_name' . $nn . ' '
                                            . ' AND md' . $nn . '.value_date between :i_val' . $nn . '_0 and :i_val' . $nn . '_1 ';

                                    //                            self::$join_where .= ' INNER JOIN `mitems-dops` midop' . $nn . ' ON '
                                    //                                    . ' midop' . $nn . '.id_item = midop.id_item '
                                    //                                    . ' AND midop' . $nn . '.name = :i_name' . $nn . ' '
                                    //                                    . ' AND midop' . $nn . '.value_date >= :i_val' . $nn . '_0 '
                                    //                                    . ' AND midop' . $nn . '.value_date <= :i_val' . $nn . '_1 '
                                    //                            ;

                                    self::$vars_to_sql2[':i_name' . $nn] = $k1;
                                    self::$vars_to_sql2[':i_val' . $nn . '_0'] = $v1[0];
                                    self::$vars_to_sql2[':i_val' . $nn . '_1'] = $v1[1];

                                    $nn++;
                                    $nn2++;
                                }
                            }
                            self::$between_date = [];
                        }

                        if (1 == 1 && !empty(self::$between_datetime)) {

                            if (self::$show_sql === true)
                                \f\pa(self::$between_datetime, '', '', 'self::$between_datetime');

                            $rebase = true;

                            foreach (self::$between_datetime as $k1 => $v1) {

                                if (isset($v1[0]) && isset($v1[1])) {

                                    self::$join_where2 .= PHP_EOL . ' INNER JOIN `mitems-dops` md' . $nn . ' ON '
                                            . ' md' . $nn . '.id_item = midop.id_item '
                                            . PHP_EOL . ' AND md' . $nn . '.name = :i_name' . $nn2 . ' '
                                            . ' AND md' . $nn . '.value_datetime between :i_val' . $nn2 . '_0 and :i_val' . $nn2 . '_1 ';

                                    //                            self::$join_where .= ' INNER JOIN `mitems-dops` midop' . $nn . ' ON '
                                    //                                    . ' midop' . $nn . '.id_item = midop.id_item '
                                    //                                    . ' AND midop' . $nn . '.name = :i_name' . $nn . ' '
                                    //                                    . ' AND midop' . $nn . '.value_date >= :i_val' . $nn . '_0 '
                                    //                                    . ' AND midop' . $nn . '.value_date <= :i_val' . $nn . '_1 '
                                    //                            ;

                                    self::$vars_to_sql2[':i_name' . $nn2] = $k1;
                                    self::$vars_to_sql2[':i_val' . $nn2 . '_0'] = $v1[0];
                                    self::$vars_to_sql2[':i_val' . $nn2 . '_1'] = $v1[1];
                                    $nn++;
                                    $nn2++;
                                }
                            }
                            self::$between_datetime = [];
                        }
                    }


                    $rebase_ar = [];

                    if ($rebase === true) {
                        // массив данных с 1 запроса
                        $rebase_ar = $re;
                        $re = [];
                    }


                    // \f\pa($re);


                    if (self::$show_sql === true)
                        \f\pa(self::$join_where2, '', '', 'self::$join_where2 перед запросом');

                    $ff1 = ' SELECT

                midop.id_item id, '
                            // .' midop.id dops_id, '
                            . ' midop.`name`,
                midop.`value`,
                midop.`value_date`,
                midop.`value_datetime`,
                midop.`value_text` '
                            . (self::$sql_select_vars ?? '')
                            . PHP_EOL
                            . ' FROM `mitems-dops` midop '
                            . PHP_EOL
                            . self::$join_where2
                            . PHP_EOL
                            . ' WHERE '
                            . PHP_EOL
                            //                            . ' midop.id_item IN (' . $sql . ') '
                            . (
                            ($sql_1id != $sql) ? ' midop.id_item IN (' . $sql . ') ' : ' midop.id_item = \'' . $sql_1id . '\' ')
                            . PHP_EOL
                            . ' AND midop.status IS NULL '
                            . PHP_EOL
                            . (self::$where2dop ?? '');

                    self::$join_where2 = self::$where2dop = '';

                    if (self::$show_sql === true)
                        \f\pa($ff1, '', '', '$ff1 sql2');

                    //            \f\pa($ff1);

                    $ff = $db->prepare($ff1);

                    //        $for_sql = [];
                    //        $ff->execute($for_sql);

                    if (self::$show_sql === true)
                        \f\pa(self::$vars_to_sql2, '', '', 'self::$vars_to_sql2');

                    $ff->execute(self::$vars_to_sql2);

                    self::$vars_to_sql2 = [];

                    // while( \f\pa($ff->fetchAll(), '', '', 'все');
                    // $re = [];

                    while ($r = $ff->fetch()) {

                        if ($rebase === true) {

                            // массив данных с 1 запроса
                            // $rebase_ar = $re;

                            if (!isset($re[$r['id']])) {
                                if (isset($rebase_ar[$r['id']])) {
                                    $re[$r['id']] = $rebase_ar[$r['id']];
                                } else {
                                    $re[$r['id']] = ['skip_1sql_ar' => 'da'];
                                }
                            }

                            if (!empty($r['name'])) {
                                $re[$r['id']][$r['name']] = $r['value'] ?? $r['value_date'] ?? $r['value_text'] ?? $r['value_int'] ?? $r['value_datetime'] ?? null;
                                $re[$r['id']]['rebase'] = 'da';
                            }
                        } else {

                            if (empty($re[($re2[$r['id']] ?? $r['id'])])) {
                                //$re[( $re2[$r['id']] ?? $r['id'] )] = ['id' => $r['id'], 'head' => $re1[$r['id']], 'start2' => 'ok'];
                                $re[($re2[$r['id']] ?? $r['id'])] = ['id' => $r['id'], 'start2' => 'ok'];
                            }

                            if (!empty($r['name'])) {
                                if (self::$style_old === true) {
                                    $re[($re2[$r['id']] ?? $r['id'])]['dop'][$r['name']] = $r['value'] ?? $r['value_date'] ?? $r['value_text'] ?? $r['value_int'] ?? $r['value_datetime'] ?? null;
                                } else {
                                    $re[($re2[$r['id']] ?? $r['id'])][$r['name']] = $r['value'] ?? $r['value_date'] ?? $r['value_text'] ?? $r['value_int'] ?? $r['value_datetime'] ?? null;
                                }
                            }
                        }
                    }


                    if (!empty(self::$need_polya_vars)) {

                        $re2 = [];

                        if (!empty($re))
                            foreach ($re as $k => $v) {

                                $skip = false;

                                foreach (self::$need_polya_vars as $kk) {

                                    if (!isset($v[$kk])) {
                                        $skip = true;
                                        break;
                                    }
                                }

                                if ($skip == true) {
                                    //$re2[$k] = $v;
                                    unset($re[$k]);
                                }
                            }

                        //                $re = $re2;
                        //                unset($re2);
                    }


                    //\f\pa($sort);

                    if ($sort == 'sort_asc') {
                        usort($re, "\\f\\sort_ar_sort");
                    } elseif ($sort == 'date_asc') {
                        usort($re, "\\f\\sort_ar_date");
                    } elseif ($sort == 'date_desc') {
                        usort($re, "\\f\\sort_ar_date_desc");
                        // usort($re, "\\f\\sort_ar_date");
                    }

                    if (isset($show_memory) && $show_memory === true) {
                        $sm2 = 0;
                        $sm2 = memory_get_usage();
                        echo '<br/>'
                        . 's' . __LINE__ . 's > ' . round(($sm2 - $sm) / 1024, 2) . ' Kb = '
                        . '<br/>'
                        . 'timer:' . \f\timer::stop('str', 123) . ' - ';
                    }
                }
            }

            // \f\pa($re);

            /**
             * если в начале запустили мемкеш то сохраняем результат
             */
            //        if (isset($memcache) && $memcache === true) {
            //            if (isset($memcache_obj) && !empty($cash_var)) {
            //
            //                //Выведем закэшированные данные
            //                $memcache_obj->set($cash_var, $re, false, 3600 * 24);
            //                //Закрываем соединение с сервером Memcached
            //                $memcache_obj->close();
            //            }
            //        }



            /**
             * трем переменные
             */
            self::$where2dop = '';
            self::$need_polya_vars = [];

            self::$show_sql = false;


            if (self::$style_old === true) {

                self::$style_old = false;
                return ['data' => $re];
            } elseif (self::$limit1 === true) {

                self::$limit1 = false;
                foreach ($re as $k => $v) {
                    return $v;
                }
            } else {

                //                if ($save_cash === true) {
                ////                    // $cash_var = 'items__'.$module.'_'.$stat.'_'.$sort;
                //                    // \f\Cash::setVar($cash_var, $re, 60 * 60 * 2);
                //                    \f\Cash::setVar($cash_var, $re);
                ////                    // \f\pa($e,'','','$e');
                //              1  }
                //                if (!empty(self::$cash_var)) {
                //                    \f\Cash::setVar(self::$cash_var, $re, ( self::$cash_time ?? 0));
                //                }

                return $re;
            }

            /*
              return self::$cash['itemsimple'][$cash] = $re;

              //return \f\end2('Достали список, простой', 'ok', $re, 'array');
              // return \f\end3('Достали список, простой', true, $re);
             *
             */
        }


        //
        catch (\Exception $ex) {
            \f\pa($ex);
        }
        //
        catch (\Throwable $ex) {
            \f\pa($ex);
        }
        //
        catch (\PDOException $ex) {

            echo '<pre>--- ' . __FILE__ . ' ' . __LINE__ . '-------'
            . PHP_EOL . $ex->getMessage() . ' #' . $ex->getCode()
            . PHP_EOL . $ex->getFile() . ' #' . $ex->getLine()
            . PHP_EOL . $ex->getTraceAsString()
            . '</pre>';

            // не найдена таблица, создаём значит её
            if (strpos($ex->getMessage(), 'no such table') !== false) {

                self::creatTable($db);
                // \f\redirect( '/' );
            }

            return \f\end3('ошибка', false);
        }
    }

    public static function get2007($db, string $module, $stat = 'show', $sort = null) {

        // echo '<br/>'.__FUNCTION__.' '.$module;
        // try {

        if (1 == 1) {

            $folder = \Nyos\nyos::$folder_now;

            if (self::$dir_img_server === false) {
                self::creatFolderImage($folder);
            }

            if (
                    $sort == 'sort_asc' || $sort == 'sort'
            ) {
                self::$sql_order = ' ORDER BY sort ASC ';
            }
            //
            elseif ($sort == 'sort_desc') {
                self::$sql_order = ' ORDER BY sort DESC ';
            }

            $search_id = '';
            $nnt = 1;
            if (!empty(self::$search['id'])) {
                foreach (self::$search['id'] as $v) {
                    self::$var_ar_for_1sql[':search_id' . $nnt] = $v;
                    $search_id .= (!empty($search_id) ? ' OR ' : '' ) . ' id = :search_id' . $nnt;
                    $nnt++;
                }
                $search_id = ' AND ( ' . $search_id . ' ) ';
                unset(self::$search['id']);
            }

            $nn = 99;
            $nn2 = 99;

            if (1 == 1 && !empty(self::$between)) {

                if (self::$show_sql === true)
                    \f\pa(self::$between, '', '', 'self::$between');

                foreach (self::$between as $k1 => $v1) {
                    if (is_array($v1) && isset($v1[0]) && isset($v1[1])) {

                        self::$where2 .= PHP_EOL . ' AND `' . \f\translit($k1, 'uri2') . '` BETWEEN :i_val' . $nn . '_0 AND :i_val' . $nn . '_1 ';
                        self::$var_ar_for_1sql[':i_val' . $nn . '_0'] = $v1[0];
                        self::$var_ar_for_1sql[':i_val' . $nn . '_1'] = $v1[1];

                        $nn++;
                        $nn2++;
                    }
                }

                self::$between = [];
            }

            if (1 == 1 && !empty(self::$between_date)) {

                if (self::$show_sql === true)
                    \f\pa(self::$between_date, '', '', 'self::$between_date');

                foreach (self::$between_date as $k1 => $v1) {
                    if (is_array($v1) && isset($v1[0]) && isset($v1[1])) {

                        self::$where2 .= PHP_EOL . ' AND `' . \f\translit($k1, 'uri2') . '` BETWEEN :i_val' . $nn . '_0 AND :i_val' . $nn . '_1 ';
                        self::$var_ar_for_1sql[':i_val' . $nn . '_0'] = $v1[0];
                        self::$var_ar_for_1sql[':i_val' . $nn . '_1'] = $v1[1];

                        $nn++;
                        $nn2++;
                    }
                }

                self::$between_date = [];
            }

            if (1 == 1 && !empty(self::$between_datetime)) {

                if (self::$show_sql === true)
                    \f\pa(self::$between_datetime, '', '', 'self::$between_datetime');

                foreach (self::$between_datetime as $k1 => $v1) {
                    if (is_array($v1) && isset($v1[0]) && isset($v1[1])) {

                        self::$where2 .= PHP_EOL . ' AND `' . \f\translit($k1, 'uri2') . '` BETWEEN :i_val' . $nn . '_0 AND :i_val' . $nn . '_1 ';
                        self::$var_ar_for_1sql[':i_val' . $nn . '_0'] = $v1[0];
                        self::$var_ar_for_1sql[':i_val' . $nn . '_1'] = $v1[1];

                        $nn++;
                        $nn2++;
                    }
                }

                self::$between_datetime = [];
            }

            //self::$sql_order = ' LIMIT 0,100 ';

            $ff1 = ' SELECT *
                    FROM 
                        `mod_' . \f\translit($module, 'uri2') . '` mi '
                    . ' WHERE '
                    . $search_id
                    . (!empty($stat) ? ' mi.status = \'' . addslashes($stat) . '\' ' : '' )
                    . (self::$where2 ?? '')
                    . ( self::$sql_order ?? '' );

            if (self::$show_sql === true)
                \f\pa($ff1, '', '', '$ff1 sql1');

            self::$join_where = self::$where2 = '';

            $ff = $db->prepare($ff1);

//                if (!empty($module))
//                    self::$var_ar_for_1sql[':module'] = ($module ?? '');

            $ff->execute(self::$var_ar_for_1sql);

            if (self::$show_sql === true)
                \f\pa(self::$var_ar_for_1sql, '', '', 'self::$var_ar_for_1sql');

            self::$var_ar_for_1sql = [];
            self::$join_where = self::$select_var1 = self::$sql_order = '';

            if (!empty(self::$sql_order))
                return $ff->fetchAll();

            $return = [];
            while ($w = $ff->fetch()) {

//                        if (!empty(self::$need_polya_vars)) {
//                        }

                if (self::$add_s_to_res === true) {
                    $w['s'] = \Nyos\Nyos::creatSecret($w['id']);
                }

                $return[$w['id']] = $w;
            }
            return $return;
        }

        // } elseif (self::$limit1 === true) {
        // self::$limit1 = false;
//        }
//
//        //
//        catch (\Exception $ex) {
//            \f\pa($ex);
//        }
//        //
//        catch (\Throwable $ex) {
//            \f\pa($ex);
//        }
//        //
//        catch (\PDOException $ex) {
//
//            echo '<pre>--- ' . __FILE__ . ' ' . __LINE__ . '-------'
//            . PHP_EOL . $ex->getMessage() . ' #' . $ex->getCode()
//            . PHP_EOL . $ex->getFile() . ' #' . $ex->getLine()
//            . PHP_EOL . $ex->getTraceAsString()
//            . '</pre>';
//
//            // не найдена таблица, создаём значит её
//            if (strpos($ex->getMessage(), 'no such table') !== false) {
//
//                // self::creatTable($db);
//                // \f\redirect( '/' );
//            }
//
//            return \f\end3('ошибка', false);
//        }
    }

    /**
     * получаем данные, 
     * новая версия от 2004112344
     * @param type $db
     * @param строка $module
     * @param строка $stat
     * show hide ''
     * @param строка $sort
     * @return массив
     */
    public static function get2($db, string $module, $stat = 'show', $sort = null) {

        // запись строки в файл лога
        if (1 == 1) {
            $log_file = $_SERVER['DOCUMENT_ROOT'] . '/log_mov.log';
            // `\f\pa($log_file);
            $e = debug_backtrace();
            $debug_line = $e[0]['file'] . ' #' . $e[0]['line'];
            file_put_contents($log_file
                    , PHP_EOL . date('Y-m-d H:i:s') . ' items get2 module ' . $module . ' ' . $stat . ' ' . $sort
                    . PHP_EOL . '           ' . $debug_line
                    . PHP_EOL . '           ' . __FILE__ . ' #' . __LINE__
                    , FILE_APPEND);
        }


        // echo '<br/>'.__FUNCTION__.' '.$module;

        try {

            //                    $cash_var = '123123';
            //                    \f\Cash::setVar($cash_var   , ['qwqw'], ( $cash_time ?? 0));
            //                    $return = \f\Cash::getVar($cash_var);
            //                    var_dump($return);
            // кеширование вкл/выкл
            if (1 == 1) {
                if (!empty(self::$cash_var_name)) {
                    $cash_var = self::$cash_var_name;
                } else {
                    $dop_in_cash_var1 = '';
                    if (!empty(self::$search))
                        $dop_in_cash_var1 .= serialize(self::$search);
                    if (!empty(self::$between))
                        $dop_in_cash_var1 .= serialize(self::$between);
                    if (!empty(self::$between_date))
                        $dop_in_cash_var1 .= serialize(self::$between_date);
                    if (!empty(self::$between_datetime))
                        $dop_in_cash_var1 .= serialize(self::$between_datetime);
                    if (!empty(self::$join_where))
                        $dop_in_cash_var1 .= serialize(self::$join_where);
                    if (!empty(self::$sql_vars))
                        $dop_in_cash_var1 .= serialize(self::$sql_vars);

                    $cash_var = 'items__' . $module . '_'
                            . md5('stat' . ($stat ?? '')
                                    . '_sort' . ($sort ?? '') . '_'
                                    . ( $dop_in_cash_var1 ?? '' ));
                }

                // $cash_time = 0;
                // $cash_var = 'items__mod' . $module . '_' . $stat . '_' . $sort;
                if (!empty($cash_var) && self::$cancel_cash !== true) {
                    $e = \f\Cash::getVar($cash_var);
                    // \f\pa($cash_var);
                    // \f\pa($e, '', '', '$e');
                    // echo '<br/>#'.__LINE__;

                    if (!empty($e))
                        return $e;
                }
            }
            //
            //            $return = false;
            //            // $cash_var = 'items__mod' . $module . '_' . $stat . '_' . $sort;
            //            $return = \f\Cash::getVar($cash_var);
            //            // \f\pa($e, '', '', '$e');
            //            // echo '<br/>#'.__LINE__;
            //
            //
            //            if (!empty($return)) {
            //                return $return;
            //            // \f\pa($cash_var);
            //
            //            $return = false;
            //
            //            if (!empty($cash_var)) {
            //                $return = \f\Cash::getVar($cash_var);
            //
            //                // \f\pa($return);
            //                var_dump($return);
            ////                if ($return === false) {
            ////                    echo '<br/>#' . __LINE__;
            ////                } else {
            ////                    echo '<br/>#' . __LINE__;
            ////                }
            //            }
            //            if ($return !== false) {
            //
            //                return \f\end3('выборка готова (кеш)', true, $return);

            if (1 == 2) {
                
            } else {

                // если есть то что ищем ( ключ - значение )
                if (!empty(self::$search)) {

                    $nn = 1;

                    foreach (self::$search as $k1 => $v1) {

                        self::$join_where .= ' INNER JOIN `mitems-dops` midop1 ON '
                                . ' midop1.id_item = mi.id '
                                . ' AND midop1.name = :i_name' . $nn . ' '
                                . ' AND midop1.value = :i_val' . $nn . ' ';
                        self::$sql_vars[':i_name' . $nn] = $k1;
                        self::$sql_vars[':i_val' . $nn] = $v1;
                        $nn++;
                    }
                    self::$search = [];
                }

                $ff1 = ' SELECT '
                        . ' mi.id item_id, '
                        . ' mi.head, '
                        . ' mi.sort, '
                        . ' mi.status, '
                        . ' midop.name, '
                        . ' midop.value, '
                        . ' midop.value_date, '
                        . ' midop.value_datetime, '
                        . ' midop.value_int, '
                        . ' midop.value_text '
                        . ' FROM '
                        . ' `mitems-dops` midop '
                        //
                        . ' INNER JOIN `mitems` mi ON '
                        . ' mi.`module` = :module '
                        . ' AND mi.id = midop.id_item '
                        . (!empty($stat) ? ' AND mi.status = :status ' : '')
                        //
                        . (self::$join_where ?? '')
                //.' LIMIT 10 '
                ;

                // \f\pa($ff1);

                $ff = $db->prepare($ff1);

                // переменные в запрос
                if (1 == 1) {

                    $ar_for_sql = [':module' => $module];

                    if (!empty($stat))
                        $ar_for_sql[':status'] = $stat;

                    if (!empty(self::$sql_vars)) {
                        $ar_for_sql = array_merge($ar_for_sql, self::$sql_vars);
                    }

                    // \f\pa($ar_for_sql);
                }

                // трём все входящие параметры
                self::$sql_vars = [];
                self::$join_where = '';

                $ff->execute($ar_for_sql);

                //                echo __LINE__;
                //                $ee = $ff->fetchAll();
                // return $ee;
                // \f\pa($ee);

                $return = [];

                //foreach ($ee as $k => $v) {
                while ($v = $ff->fetch()) {

                    // \f\pa($v);
                    // echo '<pre>'; var_dump($v); echo '</pre>';

                    if (!isset($return[$v['item_id']]))
                        $return[$v['item_id']] = [
                            'id' => $v['item_id'], 'head' => $v['head'], 'sort' => $v['sort'], 'status' => $v['status']
                        ];

                    $dd = (!empty($v['value']) ? $v['value'] : (!empty($v['value_date']) ? $v['value_date'] : (!empty($v['value_datetime']) ? $v['value_datetime'] : (!empty($v['value_int']) ? $v['value_int'] : (!empty($v['value_text']) ? $v['value_text'] : null)))));

                    if (!empty($dd))
                        $return[$v['item_id']][$v['name']] = $dd;
                }

                // usort($res_items, "\\f\\sort_ar_date_desc");
                //usort($res_items, "\\f\\sort__oborot_sp_last_monht_bolee__desc");

                if (!empty($sort)) {

                    if ($sort == 'sort_asc') {
                        usort($return, "\\f\\sort_ar_sort");
                    } elseif ($sort == 'date_asc') {
                        usort($return, "\\f\\sort_ar_date");
                    } elseif ($sort == 'date_desc') {
                        usort($return, "\\f\\sort_ar_date_desc");
                        // usort($re, "\\f\\sort_ar_date");
                    }
                }

                if (!empty($cash_var))
                    \f\Cash::setVar($cash_var, $return, ($cash_time ?? 0));


                //                if (!empty($cash_var)) {
                //                    \f\pa($cash_var);
                //                    $e = \f\Cash::setVar($cash_var, $return, ( $cash_time ?? 0));
                //                    \f\pa($e);
                //                }
                // return \f\end3('выборка готова', true, $return);
                return $return;
            }
        }
        //
        catch (\PDOException $ex) {

            echo '<pre>--- ' . __FILE__ . ' ' . __LINE__ . '-------'
            . PHP_EOL . $ex->getMessage() . ' #' . $ex->getCode()
            . PHP_EOL . $ex->getFile() . ' #' . $ex->getLine()
            . PHP_EOL . $ex->getTraceAsString()
            . '</pre>';

            // не найдена таблица, создаём значит её
            if (strpos($ex->getMessage(), 'no such table') !== false) {

                self::creatTable($db);
                // \f\redirect( '/' );
            }

            return \f\end3('ошибка', false);
        }
    }

    public static function getItemsSimple3_old($db, $module = null, $stat = 'show', $sort = null) {

        //        $show_memory = true;

        if (isset($show_memory) && $show_memory === true) {
            $sm = 0;
            $sm = memory_get_usage();
            //            echo '<br/>s1s ' . round(( $sm2 - $sm ) / 1024, 3);
            \f\timer::start(123);
        }

        $folder = \Nyos\nyos::$folder_now;

        if (self::$dir_img_server === false) {
            self::creatFolderImage($folder);
        }

        $ff1 = ' SELECT 
                mi.id,
                mi.head,
                mi.sort,
                mi.status

            FROM 
            
                `mitems` mi

            WHERE '
                . ' mi.`module` = :module '
                . (!empty($stat) ? ' AND mi.status = \'' . addslashes($stat) . '\' ' : '')
                . (self::$where2 ?? '')
                . self::$sql_order ?? '';

        //            \f\pa($ff1);

        self::$where2 = '';

        $ff = $db->prepare($ff1);

        $for_sql = [':module' => ($module ?? '')];
        $ff->execute($for_sql);

        // \f\pa( $ff->fetchAll(), '', '', 'все');

        $re = [];
        $sql = '';

        while ($r = $ff->fetch()) {

            //            if (empty($re[$r['id']])) {
            //\f\pa($r);

            $re[$r['id']] = [
                'id' => $r['id'],
                'head' => $r['head'],
                'sort' => $r['sort'],
                'status' => $r['status']
            ];

            // \f\pa($r);
            // $re[] = [ 'id' => $r['id'], 'head' => $r['head'], 'sort' => $r['sort'] ];
            $sql .= (!empty($sql) ? ',' : '') . $r['id'];
            //          }
            //            $re[$r['id']][$r['name']] = $r['value'] ?? $r['value_date'] ?? $r['value_text'] ?? $r['value_int'] ?? $r['value_datetime'] ?? null;
        }

        //        echo '<br/>timer: ' . \f\timer::stop('str', 47);
        //        if ($sort == 'sort_asc') {
        //            usort($re, "\\f\\sort_ar_sort");
        //        }
        // \f\pa($re);

        if (!empty(self::$sql_order))
            self::$sql_order = '';

        /**
         * пишем кеш контент
         */
        //        if (!empty($file_cash)) {
        //            file_put_contents($file_cash, json_encode($re));
        //        }
        //        return $re;
        //        return self::$cash['itemsimple'][$cash] = $re;

        /*
          $re2 = [];
          foreach ($re as $k => $v) {
          if (isset($v['id']))
          $re2[$v['id']] = $k;
          }

          // \f\pa($re);
          // \f\pa($sql);
          // die();
          // $ff1 = ' ( SELECT
         * */
        $ff1 = ' SELECT

          midop.id_item id, '
                // .' midop.id dops_id, '
                . ' midop.`name`,
          midop.`value`,
          midop.`value_date`,
          midop.`value_datetime`,
          midop.`value_text` '
                . (self::$sql_select_vars ?? '')
                . '

          FROM `mitems-dops` midop '
                . ' WHERE '
                . ' midop.id_item IN (' . $sql . ') '
                . ' AND midop.status IS NULL ';

        //            \f\pa($ff1);

        $ff = $db->prepare($ff1);

        //        $for_sql = [];
        //        $ff->execute($for_sql);
        $ff->execute();

        // while( \f\pa($ff->fetchAll(), '', '', 'все');
        // $re = [];

        while ($r = $ff->fetch()) {

            if (empty($re[($re2[$r['id']] ?? $r['id'])])) {
                //$re[( $re2[$r['id']] ?? $r['id'] )] = ['id' => $r['id'], 'head' => $re1[$r['id']], 'start2' => 'ok'];
                $re[($re2[$r['id']] ?? $r['id'])] = ['id' => $r['id'], 'start2' => 'ok'];
            }

            if (!empty($r['name']))
                $re[($re2[$r['id']] ?? $r['id'])]['dop'][$r['name']] = $r['value'] ?? $r['value_date'] ?? $r['value_text'] ?? $r['value_int'] ?? $r['value_datetime'] ?? null;
        }

        if ($sort == 'sort_asc') {
            usort($re, "\\f\\sort_ar_sort");
        }

        if (isset($show_memory) && $show_memory === true) {
            $sm2 = 0;
            $sm2 = memory_get_usage();
            echo '<br/>'
            . 's' . __LINE__ . 's > ' . round(($sm2 - $sm) / 1024, 2) . ' Kb = '
            . '<br/>'
            . 'timer:' . \f\timer::stop('str', 123) . ' - ';
        }

        // \f\pa($re);

        return $re;
        /*
          return self::$cash['itemsimple'][$cash] = $re;

          //return \f\end2('Достали список, простой', 'ok', $re, 'array');
          // return \f\end3('Достали список, простой', true, $re);
         *
         */
    }

    public static function getItems_old190605($db, string $folder, $module = null, $stat = 'show', $limit = 50) {


        //        if( $_SERVER['HTTP_HOST'] == 'yapdomik.uralweb.info' ){
        //            echo $folder. '<Br/> 2 '.$module.'<br/> 3 '.$stat.'<Br/> 4 '.$limit;
        //        }

        if (self::$dir_img_server === false) {
            self::creatFolderImage($folder);
        }

        //        $dir_for_cash = DR . dir_site;
        //        if (isset($module{1}) && file_exists($dir_for_cash . 'cash.items.' . $module . '.arr')) {
        //
        //            $out = unserialize(file_get_contents($dir_for_cash . 'cash.items.' . $module . '.arr'));
        //            return f\end2('Достали список', 'ok', $out, 'array');
        //        } elseif (file_exists($dir_for_cash . 'cash.items.arr')) {
        //
        //            $out = unserialize(file_get_contents($dir_for_cash . 'cash.items.arr'));
        //            return f\end2('Достали список', 'ok', $out, 'array');
        //        }


        try {

            $f = 'SELECT * FROM `mitems` WHERE `folder` = \'' . $folder . '\' '
                    . (isset($module{
                            1}) ? ' AND `module` = \'' . addslashes($module) . '\' ' : '')
                    . (isset($stat{
                            1}) ? ' AND `status` = \'' . addslashes($stat) . '\' ' : '')
                    . 'AND `status` != \'delete2\' '
                    . 'ORDER BY `sort` DESC, `add_d` DESC, `add_t` DESC '
                    . (isset($limit{
                            1}) && is_numeric($limit) ? 'LIMIT ' . $limit . ' ' : '')
                    . ';';

            if ($_SERVER['HTTP_HOST'] == 'yapdomik.uralweb.info')
                echo '<br/>' . '<br/>' . $f;

            $ff = $db->prepare($f);
            $ff->execute();
        } catch (\PDOException $ex) {

            //            echo '<pre>--- ' . __FILE__ . ' ' . __LINE__ . '-------'
            //            . PHP_EOL . $ex->getMessage() . ' #' . $ex->getCode()
            //            . PHP_EOL . $ex->getFile() . ' #' . $ex->getLine()
            //            . PHP_EOL . $ex->getTraceAsString()
            //            . '</pre>';
            // не найдена таблица, создаём значит её
            if (strpos($ex->getMessage(), 'no such table') !== false) {

                self::creatTable($db);
                // \f\redirect( '/' );
            }
        }

        $in_sql2 = '';
        $return = array();

        //while ($r = $db->sql_fr($sql)) {
        while ($r = $ff->fetch()) {


            if ($_SERVER['HTTP_HOST'] == 'yapdomik.uralweb.info') {
                //echo '<br/>'.'<br/>'.$f;
                \f\pa($r);
            }


            $return[$r['id']] = $r;
            $in_sql2 .= (isset($in_sql2{
                            3}) ? ' OR ' : '') . ' `id_item` = \'' . $r['id'] . '\' ';
        }

        // $sql2 = $db->sql_query('SELECT `id_item`, `name`, `value`,`value_text` FROM `mitems-dops` WHERE (' . $in_sql2 . ') AND `status` IS NULL ;');

        $ff1 = 'SELECT `id_item`, `name`, `value`,`value_text` FROM `mitems-dops` WHERE '
                . (isset($in_sql2{
                        5}) ? ' (' . $in_sql2 . ') AND ' : '')
                . ' `status` IS NULL ;';

        if ($_SERVER['HTTP_HOST'] == 'yapdomik.uralweb.info') {
            echo '<br/>' . '<br/>' . $ff1;
            //\f\pa($r);
        }


        $ff = $db->prepare($ff1);
        $ff->execute();

        //while ($r = $db->sql_fr($sql2)) {
        while ($r = $ff->fetch()) {


            if ($_SERVER['HTTP_HOST'] == 'yapdomik.uralweb.info') {
                //echo '<br/>'.'<br/>'.$ff1;
                \f\pa($r);
            }


            if (!isset($return[$r['id_item']]['dop']))
                $return[$r['id_item']]['dop'] = array();

            if (isset($r['value_text']{
                            0})) {
                $return[$r['id_item']]['dop'][$r['name']] = $r['value_text'];
            } elseif (isset($r['value']{
                            0})) {

                $return[$r['id_item']]['dop'][$r['name']] = $r['value'];
            }
        }

        $out = array(
            'data' => $return, 'img_dir' => self::$dir_img_uri, 'img_dir_dl' => self::$dir_img_uri_download
        );

        //        if (is_dir($dir_for_cash)) {
        //            if (isset($module{1})) {
        //                file_put_contents($dir_for_cash . 'cash.items.' . \f\translit($module, 'uri2') . '.arr', serialize($out));
        //            } else {
        //                file_put_contents($dir_for_cash . 'cash' . \f\translit($module, 'uri2') . 'items.arr', serialize($out));
        //            }
        //        }

        return f\end2('Достали список', 'ok', $out, 'array');
    }

    public static function creatTable($db) {


        $ff = $db->prepare('CREATE TABLE mitems (
                    `id`   INTEGER       UNIQUE
                         PRIMARY KEY AUTOINCREMENT
                         NOT NULL,
                    `folder` VARCHAR (50)  NOT NULL,
                    `module` VARCHAR (50)  NOT NULL,
                    `head`   VARCHAR (255) DEFAULT NULL,
                    `sort`   INTEGER (2)       NOT NULL
                                         DEFAULT \'50\',
                    `status` VARCHAR       NOT NULL
                                         DEFAULT \'show\',
                    `add_d`  DATE           NOT NULL,
                    `add_t`  TIME           NOT NULL
                );');
        $ff->execute();
        $ff = $db->prepare('CREATE TABLE [mitems-dops] (
                    `id`        INTEGER       UNIQUE
                         PRIMARY KEY AUTOINCREMENT
                         NOT NULL,
                    `id_item`     INTEGER       NOT NULL    REFERENCES mitems (id),
                    `name`        VARCHAR (255) NOT NULL,
                    `value`       VARCHAR (255),
                    `value_date`    DATE,
                    `value_datetime`    DATETIME,
                    `value_int`     NUMERIC ,
                    `value_text`  TEXT,
                    `status`      VARCHAR,
                    `date_status` INTEGER
                );');
        $ff->execute();

        //die('Созданы таблицы, перезагрузите страницу');
        \nyos\Msg::sendTelegramm('Создали таблицы для итемов', null, 1);
    }

    /**
     * получаем все итемы
     * @global \Nyos\mod\type $status
     * @param type $db
     * @param string $folder
     * @param string $module
     * @param string $stat
     * @param int $limit
     * @return type
     */
    public static function getItems2($db, string $folder, string $module = null, string $stat = 'show', int $limit = null) {

        //        if( $_SERVER['HTTP_HOST'] == 'p-shop.uralweb.info' ){
        //            global $status;
        //            $status = '';
        //        }
        // папка для кеша данных
        $cash_dir = DR . dir_site;

        $cash_file = 'items.'
                . (isset($folder{
                        1}) ? (string) $folder . '.' : '')
                . (isset($module{
                        1}) ? (string) $module . '.' : '')
                . (isset($stat{
                        1}) ? (string) $stat . '.' : '')
                . (isset($limit{
                        1}) ? (string) $limit . '.' : '')
                . '.cash';

        if (file_exists($cash_dir . $cash_file))
            return unserialize(file_get_contents($cash_dir . $cash_file));

        if (self::$dir_img_server === false)
            self::creatFolderImage2($folder);

        $out = array('data' => \f\db\getSql($db, 'SELECT * FROM `mitems` WHERE `folder` = \'' . addslashes($folder) . '\' '
                    . (isset($module{
                            1}) ? ' AND `module` = \'' . addslashes($module) . '\' ' : '')
                    . (isset($stat{
                            1}) ? ' AND `status` = \'' . addslashes($stat) . '\' ' : '')
                    . 'AND `status` != \'delete2\' '
                    . 'ORDER BY `sort` DESC, `add_d` DESC, `add_t` DESC '
                    . (isset($limit{
                            1}) && is_numeric($limit) ? 'LIMIT ' . $limit . ' ' : '')
                    . ';'));

        $sql2 = $db->sql_query('SELECT 

                d.id_item, 
                d.name, 
                d.value, 
                d.value_text
                
            FROM 
                `mitems-dops` d
                
            INNER JOIN 
                mitems i 
                
            ON
                i.id = d.id_item 
                AND i.folder = \'' . addslashes($folder) . '\' 
                AND i.module = \'' . addslashes($module) . '\' ' .
                (isset($stat{
                        1}) ? ' AND i.status = \'' . addslashes($stat) . '\' ' : '')
                . ' WHERE 
                d.`status` IS NULL 

            ;');

        while ($r = $db->sql_fr($sql2)) {

            //echo \f\pa($r);

            if (!isset($out['data'][$r['id_item']]))
                continue;

            if (!isset($out['data'][$r['id_item']]['dop']))
                $out['data'][$r['id_item']]['dop'] = array();

            $out['data'][$r['id_item']]['dop'][$r['name']] = trim($r['value'] . $r['value_text']);
        }

        $out['img_dir'] = self::$dir_img_uri;
        $out['img_dir_dl'] = self::$dir_img_uri_download;

        file_put_contents($cash_dir . $cash_file, serialize($out));

        return f\end2('Достали список', 'ok', $out, 'array');
    }

    /**
     * считаем страницы списка записей в папке и модуле
     * @global \Nyos\mod\type $status
     * @param type $db
     * @param type $folder
     * @param type $module
     * @param type $on1page
     * @param type $now_page
     * @return type
     */
    public static function itemsPage($db, $folder, $module, $on1page = 10, $now_page = 1) {

        //$_SESSION['status1'] = true;
        //$status = '';

        if (isset($_SESSION['status1']) && $_SESSION['status1'] === true) {
            global $status;
            $status .= '<fieldset><legend>' . __CLASS__ . ' #' . __LINE__ . ' + ' . __FUNCTION__ . '</legend>';
        }

        //if ($module == '005.news') {
        //    $shows = true;
        //    $_SESSION['status1'] = true;
        //    $status = '';
        //}
        $sql = $db->sql_query('SELECT COUNT(id) kolvo FROM `mitems` '
                . 'WHERE '
                . ' `folder` = \'' . $folder . '\' '
                . ' AND `module` = \'' . addslashes($module) . '\' '
                . ' AND `status` = \'show\' '
                . ' GROUP BY `folder` '
                . ';');
        //if ($shows === true) {
        //    $_SESSION['status1'] = false;
        //    echo $status;
        //}

        $r = $db->sql_fr($sql);

        $r['kolvo_page'] = ceil($r['kolvo'] / $on1page);

        if ($now_page > 0 && $now_page <= $r['kolvo_page']) {
            $r['now_page'] = $now_page;
        } else {
            $r['now_page'] = $now_page = 1;
        }

        if ($now_page == 1) {
            $r['limit_start'] = 0;
            $r['limit_fin'] = $on1page - 1;
        } else {
            $r['limit_start'] = ($now_page - 1) * $on1page;
            $r['limit_fin'] = $now_page * $on1page - 1;
        }
        $r['folder'] = $folder;
        $r['level'] = $module;
        // \f\pa($r);

        if (isset($_SESSION['status1']) && $_SESSION['status1'] === true)
            $status .= '<span class="bot_line">#' . __LINE__ . '</span></fieldset>';

        return f\end3('страниицы, ограничения', 'ok', $r);
    }

    /**
     * добавление новой записи
     * @global type $status
     * @param type $db
     * @param type $folder
     * @param type $cfg_mod
     * @param type $data
     * @return type
     */
    public static function addNew_old($db, $folder, $cfg_mod, $data) {

        // \f\pa($cfg_mod);
        // \f\pa($data);

        if (isset($_SESSION['status1']) && $_SESSION['status1'] === true) {
            global $status;
            $status .= '<fieldset><legen>' . __CLASS__ . ' #' . __LINE__ . ' + ' . __FUNCTION__ . '</legend>';
        }

        if (self::$dir_img_server === false) {
            self::creatFolderImage($folder);
        }

        if (self::$dir_img_server === false) {
            return f\end2('Ошибка, папка для файлов не может быть создана', 'error', array('file' => __FILE__, 'line' => __LINE__), 'array');
        }

        //echo '<br/>#'.__LINE__;

        if (isset($data['head'])) {

            //$_SESSION['status1'] = true;
            //$status = '';
            $new_id = db\db2_insert($db, 'mitems', array(
                'folder' => $folder, 'module' => $cfg_mod['cfg.level'], 'head' => $data['head'], 'add_d' => 'NOW', 'add_t' => 'NOW'
                    ), 'da', 'last_id');
            //echo $status;
            //
            //echo '<br/>#'.__LINE__;
            //echo '<br/>#-'.$new_id;

            $in_db = array();

            foreach ($cfg_mod as $k => $v) {

                if (isset($data[$k]{
                                0}) && isset($v['name_rus']{
                                0})) {

                    if (isset($v['type']) && ($v['type'] == 'textarea' || $v['type'] == 'textarea_html')) {

                        $in_db[] = array(
                            'name' => $k,
                            'value_text' => $data[$k]
                        );
                    } elseif (isset($v['type']) && $v['type'] == 'translit' && isset($v['var_in']{
                                    0}) && isset($data[$v['var_in']])) {

                        $in_db[] = array(
                            'name' => $k,
                            'value_text' => \f\translit($data[$v['var_in']], 'uri2')
                        );
                    } else {

                        $in_db[] = array(
                            'name' => $k,
                            'value' => $data[$k]
                        );
                    }
                }
            }

            if (isset($data['files']) && sizeof($data['files']) > 0) {

                require_once $_SERVER['DOCUMENT_ROOT'] . '/0.all/class/nyos_image.php';



                //echo '<br/>#'.__LINE__;
                $nn = 1;

                foreach ($data['files'] as $k => $v) {

                    //f\pa($v);

                    if (isset($cfg_mod[$k]) && is_array($v) && isset($v['size']) && $v['size'] > 100 && isset($v['error']) && $v['error'] == 0) {

                        $new_name = $new_id . '_' . $nn . '_' . rand(10, 99) . '.' . f\get_file_ext($v['name']);

                        $e = \Nyos\nyos_image::autoJpgRotate($v['tmp_name'], self::$dir_img_server . $new_name);

                        if (!file_exists(self::$dir_img_server . $new_name)) {
                            copy($v['tmp_name'], self::$dir_img_server . $new_name);
                        }

                        $in_db[] = array(
                            'name' => $k,
                            'value' => $new_name
                        );
                    }
                }
            }

            // echo '<Br/>db\sql_insert_mnogo - ' .$new_id ;
            //$status = '';
            \f\db\sql_insert_mnogo2($db, 'mitems-dops', $in_db, array('id_item' => $new_id));
            // db\sql_insert_mnogo($db, 'mitems-dops', $in_db, array('id_item' => $new_id));
            //echo $status;
        }

        if (isset($_SESSION['status1']) && $_SESSION['status1'] === true) {
            $status .= '</fieldset>';
        }

        self::clearCash($folder);

        return f\end2('Окей, запись добавлена', 'ok', array('file' => __FILE__, 'line' => __LINE__), 'array');
    }

    /**
     * удалить массу записей
     * @param type $db
     * @param string $module_name
     * @param type $data_dops
     */
    public static function deleteItems2($db, string $module_name, array $datas) {
        foreach ($datas as $k => $v) {
            self::deleteItems($db, \Nyos\Nyos::$folder_now, $module_name, $v);
        }
    }

    /**
     * какаято старая версия
     * @param type $db
     * @param string $folder
     * @param string $module_name
     * @param type $data_dops
     * @param type $id
     * @return type
     */
    public static function deleteItems($db, string $folder, string $module_name, $data_dops = [], $id = null) {

        if (!empty($id)) {

            $ff = $db->prepare('UPDATE FROM mitems SET `status` = \'delete\' WHERE id = :id ');
            $ff->execute(array(':id' => $id));
            // $ff = $db->prepare('UPDATE FROM mitems-dops SET `status` = \'delete\' WHERE id_item = :id ');
            // $ff->execute(array(':id' => $id));
            return \f\end3('удалёно');
        } else {

            $vars = array(
                ':mod' => $module_name,
            );

            $dopsql = '';
            $nn = 1;
            foreach ($data_dops as $k => $v) {
                $dopsql .= PHP_EOL . ' AND id IN ( SELECT id_item FROM `mitems-dops` WHERE name = :k' . $nn . ' AND value = :v' . $nn . ' ) ';
                $vars[':k' . $nn] = $k;
                $vars[':v' . $nn] = $v;
                $nn++;
            }

            $sql = 'UPDATE mitems 
                    SET status = \'delete\'
                    WHERE module = :mod '
                    . PHP_EOL . ' AND status != \'delete2\' '
                    // .' AND `id` IN ( SELECT mid.id_item FROM `mitems-dops` mid WHERE mid.name = \'jobman\' AND mid.value = :id_user ) '
                    . $dopsql
                    . ';';
            //\f\pa($sql);
            $ff = $db->prepare($sql);
            $ff->execute($vars);



            /*

              $dopsql = '';
              $nn = 1;
              foreach ($data_dops as $k => $v) {
              $dopsql .= ' INNER JOIN `mitems-dops` mid5' . $nn . ' ON mid5' . $nn . '.id_item = mi.id AND mid5' . $nn . '.name = \'' . addslashes($k) . '\' AND mid5' . $nn . '.value = \'' . addslashes($v) . '\' ';
              $nn++;
              }

              $ff = $db->prepare('SELECT
              mi.id
              FROM
              mitems mi

              ' . $dopsql . '

              WHERE
              mi.module = :mod1 AND
              mi.status != \'delete2\'
              GROUP BY
              mi.id
              ');

              $ff->execute(array(
              // ':id_user' => 'f34d6d84-5ecb-4a40-9b03-71d03cb730cb',
              ':mod1' => $module_name,
              // ':date' => ' date(\'' . date('Y-m-d', $_SERVER['REQUEST_TIME'] - 3600*24*3 ) .'\') ',
              // ':dates' => $start_date //date( 'Y-m-d', ($_SERVER['REQUEST_TIME'] - 3600 * 24 * 14 ) )
              ));
              //$e3 = $ff->fetchAll();

              $sql2 = '';
              while ($e = $ff->fetch()) {

              $sql2 .= (!empty($sql2) ? ' OR ' : '' ) . ' `id` = \'' . $e['id'] . '\' ';
              }

              //echo '<br/>';
              $er = 'UPDATE mitems SET `status` = \'delete\' WHERE ' . $sql2;
              //echo '<br/>';
              $f = $db->prepare($er);
              $f->execute();

              //self::clearCash();
              //\f\pa($e);
             * 
             */
        }

        return \f\end3('Окей');
    }

    /**
     * удаление параметра по id или по списку параметров
     * 
     * @param type $db
     * @param строка $module
     * модуль
     * @param массив $dops
     * если массив пустой то трём все значения
     * [ 'dop1' => $val, 'dop2' => $val ]
     * @return массив
     */
    public static function deleteFromDops($db, $module, $dops = []) {

        // echo '<br/>' . __FILE__ . ' - ' . __LINE__ . ' - ' . $module;
        // \f\pa($dops);

        \f\Cash::deleteKeyPoFilter([$module]);

        // echo '<br/>#' . __LINE__;
        // удалчем все значения в таблице
        if (empty($dops)) {

            $ff = $db->prepare('UPDATE `mitems` SET `status` = \'delete\' WHERE `module` = :module ;');
            $ff->execute([':module' => $module]);

            return \f\end3('удалёно (всё)', true, ['line' => __LINE__]);
        }
        // удаляем с фильтром
        elseif (1 == 1) {

            if (!empty($dops)) {
                
                $delete_ids = [];
                
                $res = self::get($db, $module);
                foreach ($res as $k => $v) {
                    $delete = true;
                    foreach ($dops as $k1 => $v1) {
                        if( $v[$k1] != $v1 ){
                            $delete = false;
                            break;
                        }
                    }
                    
                    if( $delete === true ){
                        $delete_ids[] = $v['id'];
                    }
                    
                }
                
                if( !empty($delete_ids ) ){
                
                    $var_in_sql = [ ':module' => $module ];
                    
                    $sql = 'UPDATE `mitems` mi '
                            . ' SET `mi`.`status` = \'delete\' '
                            . ' WHERE mi.`module` = :module AND mi.`id` IN (' . implode( ' , ', $delete_ids) . ') '
                            . ' ;';
                
                    $ff = $db->prepare($sql);
                    // \f\pa($var_in_sql);
                    $ff->execute($var_in_sql);
                    
                }
                
            }




            // \Nyos\mod\items::$search[':module'] = $module;
        }
        // удаляем с фильтром
        else {

            $var_in_sql = [
                ':module' => $module
            ];

            $dop_sql = '';
            $nn = 1;

            if (!empty($dops))
                foreach ($dops as $k => $v) {
                    $dop_sql .= ' INNER JOIN `mitems-dops` md' . $nn . ' '
                            . ' ON '
                            . ' md' . $nn . '.id_item = mi.id '
                            . ' AND md' . $nn . '.name = :name' . $nn . ' '
                            . ' AND '
                            . ' ( '
                            . ' md' . $nn . '.value = :var' . $nn . ' '
                            . ' OR md' . $nn . '.value_date = :var' . $nn . ' '
                            . ' OR md' . $nn . '.value_datetime = :var' . $nn . ' '
                            . ' ) ';
                    $var_in_sql[':name' . $nn] = $k;
                    $var_in_sql[':var' . $nn] = $v;
                    $nn++;
                }

            $sql = 'SELECT mi.id FROM `mitems` mi ' . $dop_sql . ' WHERE mi.`module` = :module ;';
            // \f\pa($sql);
            $ff = $db->prepare($sql);
            // \f\pa($var_in_sql);
            $ff->execute($var_in_sql);

            // \f\pa($ff->fetchAll());

            if (!empty($dops)) {

                $ids = '';

                while ($res = $ff->fetch()) {
                    // \f\pa($res, '', '', '$res');
                    $ids .= (!empty($ids) ? ',' : '') . $res['id'];
                }

                if (!empty($ids)) {
                    $var_in_sql = [':module' => $module];

                    $sql = 'UPDATE `mitems` mi '
                            . ' SET `mi`.`status` = \'delete\' '
                            . ' WHERE mi.`module` = :module AND mi.`id` IN (' . $ids . ') '
                            . ' ;';
                    // \f\pa($sql);
                    $ff = $db->prepare($sql);
                    // \f\pa($var_in_sql);
                    $ff->execute($var_in_sql);
                }
            }
        }

        return \f\end3('удалёно', true, ['line' => __LINE__]);
    }

    /**
     * удаление 1 id из таблицы items
     * @param type $db
     * @param array $ids
     * @return type
     */
    public static function deleteId($db, int $id) {

        $ff = $db->prepare('UPDATE `mitems` SET `status` = \'delete\' WHERE id = :id ;');
        $ff->execute(array(':id' => $id));

        return \f\end3('удалёно', true, ['id' => $id]);
    }

    /**
     * версия 2007
     * удаление итемов по доп полям
     * @param type $db
     * @param type $module
     * @param type $dops
     * @return type
     */
    public static function deleteItemForDops($db, $module, $dops) {

        $var_in = [];
        $nn = 1;
        $sql_in = '';

        foreach ($dops as $k => $v) {
            $var_in[':v' . $nn] = $v;
            $sql_in .= (!empty($sql_in) ? ' AND ' : '' ) . ' `' . \f\translit($k, 'uri2') . '` = :v' . $nn;
            $nn++;
        }

        $sql = 'UPDATE `mod_' . \f\translit($module, 'uri2') . '` SET `status` = \'delete\' WHERE ' . $sql_in . ' ;';
        // \f\pa($sql);
        $ff = $db->prepare($sql);
        $ff->execute($var_in);

        return \f\end3('удалёно', true);
    }

    /**
     * удаление одномерного массива id из таблицы items
     * @param type $db
     * @param array $ids
     * @return type
     */
    public static function deleteIds($db, array $ids) {

        if (empty($ids))
            return \f\end3('нечего удалять', false);

        $sql = 'UPDATE `mitems` SET `status` = \'delete\' WHERE ( `id` = ' . implode(' OR `id` = ', $ids) . ' ) ;';
        // \f\pa($sql);
        $ff = $db->prepare($sql);
        //$in = [':ids' => implode(' OR `id` = ', $ids)];
        //$ff->execute($in);
        //\f\pa($in);
        $ff->execute();

        return \f\end3('удалёно', true);
    }

    /**
     * добавление записи (версия от 200214 )
     * @global \Nyos\mod\type $status
     * @param type $db
     * @param string $folder
     * @param array $cfg_mod
     * @param array $data
     * @return type
     */
    public static function add($db, string $mod_name, array $data, $files = array(), $add_all_dops = false) {

        // новая модель пишем только в новую бд
        if (self::$type_module == 3) {
            
            if( !isset(\Nyos\Nyos::$menu[$mod_name]) )
            \Nyos\Nyos::getMenu();

            $data_in = [];
            
            foreach( $data as $k => $v ){
                if( isset( \Nyos\Nyos::$menu[$mod_name][$k]['name_rus'] ) ){
                    $data_in[$k] = $v;
                }
            }
            
            // \f\pa($data_in);
            
            $in = \f\db\db2_insert($db, 'mod_' . \f\translit($mod_name, 'uri2') , $data_in, '', 'last_id');

            // \f\pa($in);
            // $ee = self::addNewSimple($db, $mod_name, $data, $files, $add_all_dops);

            return $in;
        }elseif (self::$type_module == 2) {

            // \f\pa( [ $mod_name, $data, $files , $add_all_dops ] );

            $in = \f\db\db2_insert($db, 'mod_' . \f\translit($mod_name, 'uri2'), $data, '', 'last_id');
            // \f\pa($in);

            $ee = self::addNewSimple($db, $mod_name, $data, $files, $add_all_dops);

            return $ee;
        } else {

            \f\Cash::deleteKeyPoFilter([$mod_name]);
            return self::addNewSimple($db, $mod_name, $data, $files, $add_all_dops);
        }
    }

    public static function edits($db, string $mod_name, array $items_id, $new_dop = []) {

        \f\Cash::deleteKeyPoFilter([$mod_name]);

        $for_sql = [
            ':items' => implode(',', $items_id),
            ':name_dops' => implode(',', array_keys($new_dop))
        ];

        $sql = 'UPDATE `mitems-dops` SET `status` = \'delete\', `date_status` = NOW() WHERE id_item in ( :items ) AND `name` in ( :name_dops );';

        if (self::$show_sql === true)
            \f\pa($sql);

        $ff = $db->prepare($sql);

        if (self::$show_sql === true)
            \f\pa($for_sql);

        $ff->execute($for_sql);

        $rows = [];

        foreach ($items_id as $id) {
            $t = $new_dop;
            $t['id_item'] = $id;
            foreach ($new_dop as $k => $v) {
                $t2 = $t;
                $t2['name'] = $k;
                $t2['value'] = $v;
                $rows[] = $t2;
            }
        }

        \f\db\sql_insert_mnogo($db, 'mitems-dops', $rows);

        self::$show_sql = false;

        // return self::addNewSimple($db, $mod_name, $data, $files, $add_all_dops);
    }

    /**
     * добавление записи боллее лёгкая версия
     * @global \Nyos\mod\type $status
     * @param type $db
     * @param string $folder
     * @param array $cfg_mod
     * @param array $data
     * @return type
     */
    public static function addNewSimple($db, string $mod_name, array $data, $files = array(), $add_all_dops = false) {

        \f\Cash::deleteKeyPoFilter([$mod_name]);

        $folder = \Nyos\Nyos::$folder_now;
        $cfg_mod = \Nyos\Nyos::$menu[$mod_name] ?? $mod_name;
        // $cfg_mod = 123;

        if (isset($add_all_dops['id']))
            unset($add_all_dops['id']);

        try {

            return $e = self::addNew($db, $folder, $cfg_mod, $data, $files, $add_all_dops);
        } catch (\PDOException $ex) {

            return false;
        }
    }

    //    public static function selectGoodVarsInItems( string $module, array $data ) {
    //
    //            //\f\pa( $data );
    //
    //            // \f\pa(\Nyos\nyos::$menu['073.comments']);
    //            $indb2 = [];
    //
    //            foreach ($data as $k => $v) {
    //                if (isset(\Nyos\nyos::$menu['073.comments'][$k]['name_rus'])) {
    //                    $indb2[$k] = $v;
    //                }
    //            }
    //
    //            \f\pa( $indb2 );
    //        
    //    }

    public static function addNewSimples($db, string $mod_name, array $data, $files = array(), $add_all_dops = false) {

        if (empty($data))
            return false;

        \f\Cash::deleteKeyPoFilter([$mod_name]);

        $folder = \Nyos\Nyos::$folder_now;
        $cfg_mod = \Nyos\Nyos::$menu[$mod_name];

        // если свободный тип, то добавляем все ключи во всех элементах что хотим добавить
        if (!empty($cfg_mod['items_type']) && $cfg_mod['items_type'] === 'free') {
            foreach ($data as $k => $v) {
                foreach ($v as $k12 => $v12) {
                    if (!isset($cfg_mod[$k12]))
                        $cfg_mod[$k12] = 1;
                }
            }
        }

        $nn = 0;

        foreach ($data as $k => $v) {
            self::addNew($db, $folder, $cfg_mod, $v);
            $nn++;
        }

        return $nn;
    }

    /**
     * новая версия с исключениями
     * @global \Nyos\mod\type $status
     * @param type $db
     * @param string $folder
     * @param array $cfg_mod
     * @param array $data
     * @return type
     */
    public static function addNew($db, string $folder, $cfg_mod, array $data, $files = array(), $add_all_dops = false) {

        \f\Cash::deleteKeyPoFilter([($cfg_mod['cfg.level'] ?? $cfg_mod)]);

        if (empty(self::$dir_img_server)) {
            self::creatFolderImage($folder);
        }

        if (self::$dir_img_server === false)
            throw new \Exception('Ошибка, папка для файлов не создана');

        if (empty($data['head']))
            $data['head'] = 1;

        $arin = array(
            'module' => ($cfg_mod['cfg.level'] ?? $cfg_mod),
            'head' => $data['head'],
            'add_d' => date('Y-m-d', $_SERVER['REQUEST_TIME']),
            'add_t' => date('H:i:s', $_SERVER['REQUEST_TIME'])
        );

        if (!empty($folder))
            $arin['folder'] = $folder;

        if (!empty($data['status']))
            $arin['status'] = $data['status'];

        $new_id = \f\db\db2_insert($db, 'mitems', $arin, 'da', 'last_id');
        $in_db = array();

        foreach ($cfg_mod as $k => $v) {

            if (!empty($cfg_mod['items_type']) && $cfg_mod['items_type'] === 'free') {
                
            } elseif ($add_all_dops === false && empty($v['name_rus']))
                continue;

            if (isset($data[$k]) || isset($v['default'])) {

                if (isset($v['type']) && ($v['type'] == 'textarea' || $v['type'] == 'textarea_html')) {

                    $in_db[] = array(
                        'name' => $k,
                        'value_text' => $data[$k] ?? $v['default']
                    );
                }
                //
                elseif (isset($v['type']) && $v['type'] == 'datetime') {

                    $in_db[] = array(
                        'name' => $k,
                        'value_datetime' => date('Y-m-d H:i:s',
                                !empty($data[$k]) ? strtotime($data[$k] . ' ' . (!empty($data[$k . '_time']) ? $data[$k . '_time'] : '')) :
                                ( (!empty($v['default']) && $v['default'] == 'now') ? $_SERVER['REQUEST_TIME'] : $v['default'] )
                        )
                    );
                }
                //
                elseif (isset($v['type']) && $v['type'] == 'date') {

                    $in_db[] = array(
                        'name' => $k,
                        'value_date' => date('Y-m-d',
                                !empty($data[$k]) ? strtotime($data[$k]) :
                                ((!empty($v['default']) && $v['default'] == 'now') ? $_SERVER['REQUEST_TIME'] : null))
                    );
                }
                //
                elseif (isset($v['type']) && $v['type'] == 'number') {

                    $in_db[] = array(
                        'name' => $k,
                        'value' => $data[$k] ?? $v['default']
                    );
                }
                //
                else {

                    $in_db[] = array(
                        'name' => $k,
                        'value' => $data[$k] ?? $v['default']
                    );
                }
            }
            //
            elseif (!empty($v['type']) && $v['type'] == 'translit' && !empty($v['var_in']{0}) && !empty($data[$v['var_in']]{0})) {
                $in_db[] = array(
                    'name' => $v['var_in'] . '_translit',
                    'value_text' => \f\translit($data[$v['var_in']], 'uri2')
                );
            }
        }

        if (isset($files['name']) && sizeof($files['name']) > 0) {

            $nn = 0;
            foreach ($files['name'] as $k0 => $v0) {

                if (!empty($v0) && isset($files['error'][$k0]) && $files['error'][$k0] == 0 && isset($cfg_mod[$k0])) {

                    $nn++;
                    $new_name = $k0 . '_' . $nn . '_' . substr(\f\translit($v0, 'uri2'), 0, 30) . '_' . rand(10, 99) . '.' . \f\get_file_ext($v0);

                    $save_file = self::$dir_img_server . $new_name;

                    if (!file_exists($save_file)) {

                        $e = \Nyos\nyos_image::autoJpgRotate($files['tmp_name'][$k0], $save_file);

                        if (!file_exists($save_file))
                            copy($files['tmp_name'][$k0], $save_file);

                        if (file_exists($save_file))
                            $in_db[] = array(
                                'name' => $k0,
                                'value' => $new_name
                            );
                    }
                }
            }
        }
        // если один файл
        else {
            $nn = 1;

            foreach ($files as $k => $v) {

                if (isset($cfg_mod[$k]) && is_array($v) && isset($v['size']) && $v['size'] > 100 && isset($v['error']) && $v['error'] == 0) {

                    $new_name = $new_id . '_' . $nn . '_' . substr(\f\translit($v['name'], 'uri2'), 0, 50) . '_' . rand(10, 99) . '.' . f\get_file_ext($v['name']);

                    if (!function_exists('\Nyos\nyos_image::autoJpgRotate') && file_exists(DR . '/vendor/didrive/base/Nyos_image.php'))
                        require_once DR . '/vendor/didrive/base/Nyos_image.php';

                    $e = \Nyos\nyos_image::autoJpgRotate($v['tmp_name'], self::$dir_img_server . $new_name);

                    if (!file_exists(self::$dir_img_server . $new_name)) {
                        copy($v['tmp_name'], self::$dir_img_server . $new_name);
                    }

                    $in_db[] = array(
                        'name' => $k,
                        'value' => $new_name
                    );
                }
            }
        }

        \f\db\sql_insert_mnogo($db, 'mitems-dops', $in_db, array('id_item' => $new_id));


// добавление в новую базу
        if (1 == 2) {

            // \f\pa($arin);
            \f\pa($in_db);

            $var_array = [];
            foreach ($in_db as $v) {

                $var = $v['value'] ?? $v['value_date'] ?? $v['value_datetime'] ?? null;

                if ($var !== null)
                    $var_array[$v['name']] = $var;
            }

            \f\db\db2_insert($db, 'mod_' . \f\translit($arin['module'], 'uri2'), $var_array, true);
            \f\pa('mod_' . \f\translit($arin['module'], 'uri2'));
        }



        return \f\end3('Окей, запись добавлена', 'ok', array('file' => __FILE__, 'line' => __LINE__), 'array');
    }

    public static function clearCash($folder = null) {

        if ($folder === null)
            $folder = \Nyos\Nyos::$folder_now;

        $s = scandir(DR . DS . 'sites' . DS . $folder . DS);
        foreach ($s as $k => $v) {
            if (strpos($v, 'items') !== false && strpos($v, 'cash') !== false) {
                unlink(DR . DS . 'sites' . DS . $folder . DS . $v);
            }
        }
    }

    /**
     * сохраняем доп параметры к имеющимся итемам
     * массив ( id итем > [ поле = значение ] )
     * @param type $db
     * @param type $array
     * @return type
     */
    public static function saveNewDop($db, $array) {

        if (empty($array))
            return \f\end3('нет данных для сохранения', false);

        $sql = '';
        $nn = 1;

        $indb = $dop_ar = [];

        foreach ($array as $id => $v1) {

            if (empty($v1))
                continue;

            $nn++;

            $sql .= (!empty($sql) ? ' OR ' : '') . ' ( `mitems-dops`.`id_item` = :id' . $nn . ' AND ';
            $dop_ar[':id' . $nn] = $id;

            $sql2 = '';
            foreach ($v1 as $key => $value) {

                $sql2 .= (!empty($sql2) ? ' OR ' : '') . ' `mitems-dops`.`name` = :name' . $nn . ' ';
                $dop_ar[':name' . $nn] = $key;
                $nn++;

                $indb[] = [
                    'id_item' => $id,
                    'name' => $key,
                    'value' => $value
                ];
            }

            $sql .= ' ( ' . $sql2 . ' ) ) ';
        }

        //echo $sql;
        // $sql1 = 'UPDATE `mitems-dops` SET `status` = \'delete\', `date_status` = NOW() WHERE `status` != \'delete\' AND ( ' . $sql . ' ) ';

        $sql1 = 'UPDATE '
                . ' `mitems-dops` '
                . ' SET '
                . ' `status` = \'delete\''
                . ' ,`date_status` = NOW() '
                . ' WHERE '
                . ' `date_status` IS NULL '
                . ' AND ( ' . $sql . ' ) ';

        //        $sql1 = 'DELETE FROM `mitems-dops` '
        //                // . 'SET `status` = \'delete\''
        //                // . ', `date_status` = NOW() '
        //                . ' WHERE ' . $sql . ' ;';
        //$sql1 = 'DELETE FROM  `mitems-dops` WHERE ( ' . $sql . ' ) ';
        //        echo '<br/>';
        //        \f\pa($dop_ar);
        //        return;
        // \f\pa($sql1);
        $ff = $db->prepare($sql1);

        // \f\pa($dop_ar);
        $e = $ff->execute($dop_ar);
        // \f\pa($e, '', '', 'sql delete');
        // \f\pa($indb);

        $er = \f\db\sql_insert_mnogo($db, 'mitems-dops', $indb);
        // \f\pa($er);
        // echo '<br/>изменено доп параметров имеющихся записей: ' . sizeof($indb);

        return \f\end3('окей записали');
    }

    /**
     * редактируем 1 итем
     * @param type $db
     * @param type $id_item
     * @param type $folder
     * @param type $cfg_mod
     * @param type $data
     * @return type
     */
    public static function saveEdit($db, $id_item, $folder, $cfg_mod, $data) {


        if (self::$dir_img_server === false)
            self::creatFolderImage($folder);

        if (self::$dir_img_server === false)
            return f\end2('Ошибка, папка для файлов не может быть создана', 'error', array('file' => __FILE__, 'line' => __LINE__), 'array');

        // \f\pa($data,2);
        // \f\pa($cfg_mod,2);
        // \f\pa($cfg_mod, '', '', '$cfg_mod');
        // \f\pa($data, '', '', '$data');
        //$data_old = self::getItemSimple($db, $id_item);
        // echo $cfg_mod['cfg.level'];
        //        \f\CalcMemory::start(778);
        //        \f\timer::start(778);

        self::$where2 = ' AND mi.id = \'' . (int) $id_item . '\' ';
        $data_old = self::getItemsSimple3($db, $cfg_mod['cfg.level']);
        // \f\pa($data_old, '', '', '$data_old');
        //        echo 'tt'.\f\timer::stop( 'str', 778 );
        //        echo 'tt'. \f\CalcMemory::stop( 778 );

        if (isset($data['head']{
                        0}) && $data_old[$id_item]['head'] != $data['head']) {

            // echo '<br/>' . __FILE__ . ' ' . __LINE__;
            \f\db\db_edit2($db, 'mitems', array('id' => $id_item), array('head' => $data['head']), false, 1, 'da');
        }

        // die();

        if (!empty($data['head'])) {

            /*
              $new_id = db\db2_insert($db, 'mitems', array(
              'folder' => $folder
              , 'module' => $cfg_mod['cfg.level']
              , 'head' => $data['head']
              , 'add_d' => 'NOW'
              , 'add_t' => 'NOW'
              ), 'da', 'last_id');
             */

            //$dop_sql = '';

            $in_db = [];

            if (isset($cfg_mod['head_translit'])) {
                $in_db[] = [
                    'name' => 'head_translit',
                    'value' => \f\translit($data['head'], 'uri2')
                ];
            }

            //\f\pa($cfg_mod,2,null,'$cfg_mod');
            //\f\pa($data,2,null,'$data');

            foreach ($cfg_mod as $k => $v) {

                if (isset($data[$k . '_del']) && $data[$k . '_del'] == 'yes')
                    continue;

                //echo '<br/>'.__LINE__;
                //if (isset($data[$k]{0}) && is_array($v) && !empty($v['name_rus']) ) {
                if (isset($data[$k]{
                                0}) && !empty($v['name_rus'])) {

                    //echo '<br/>'.__LINE__;
                    // $dop_sql .= ( isset($dop_sql{1}) ? ' OR ' : '' ) . ' `name` = \'' . addslashes($k) . '\' ';
                    //                    if (isset($v['type']) && ( $v['type'] == 'textarea' || $v['type'] == 'textarea_html' )) {
                    //                        $in_db_text[$k] = $data[$k];
                    //                    } else {
                    //                        $in_db[$k] = $data[$k];
                    //                    }
                    //\f\pa($v);

                    if ($v['type'] == 'textarea' || $v['type'] == 'textarea_html') {

                        $in_db[] = array(
                            'name' => $k,
                            'value_text' => $data[$k]
                        );
                    } elseif ($v['type'] == 'datetime') {

                        $in_db[] = array(
                            'name' => $k,
                            'value_datetime' => date('Y-m-d H:i:s', strtotime($data[$k] . ' ' . (isset($data[$k . '_time']) ? $data[$k . '_time'] : '')))
                        );
                    } elseif ($v['type'] == 'date') {

                        $in_db[] = array(
                            'name' => $k,
                            'value_date' => date('Y-m-d', strtotime($data[$k]))
                        );
                    } elseif ($v['type'] == 'number') {

                        $in_db[] = array(
                            'name' => $k,
                            'value' => $data[$k]
                        );
                    } else {

                        $in_db[] = array(
                            'name' => $k,
                            'value' => $data[$k]
                        );
                    }
                }
            }

            //            \f\pa($cfg_mod);
            //            \f\pa($data);

            if (isset($data['files']) && sizeof($data['files']) > 0) {

                //require_once $_SERVER['DOCUMENT_ROOT'] . '/0.all/class/nyos_image.php';

                $nn = 1;

                foreach ($data['files'] as $k => $v) {

                    //f\pa($v);

                    if (isset($cfg_mod[$k]) && is_array($v) && isset($v['size']) && $v['size'] > 100 && isset($v['error']) && $v['error'] == 0) {

                        $new_name = $id_item . '_' . $nn . '_' . substr(\f\translit($v['name'], 'uri2'), 0, 50) . '.' . f\get_file_ext($v['name']);

                        $e = \Nyos\nyos_image::autoJpgRotate($v['tmp_name'], self::$dir_img_server . $new_name);
                        // \f\pa($e);

                        if (!file_exists(self::$dir_img_server . $new_name)) {
                            copy($v['tmp_name'], self::$dir_img_server . $new_name);
                        }

                        //$in_db[$k] = $new_name;
                        $in_db[] = array(
                            'name' => $k,
                            'value' => $new_name
                        );
                    }
                }
            }

            // $db->sql_query('DELETE FROM `mitems-dops` WHERE `id_item` = \'' . $id_item . '\' AND (' . $dop_sql . ') ;');
            // $db->sql_query('DELETE FROM `mitems-dops` WHERE `id_item` = \'' . $id_item . '\' ;');

            $ff = $db->prepare('DELETE FROM `mitems-dops` WHERE `id_item` = :item_id ;');
            $ff->execute(array(':item_id' => $id_item));

            //            $in = [];
            //
            //            if (isset($in_db_text))
            //                foreach ($in_db_text as $k => $v) {
            //                    $in[] = array('name' => $k, 'value_text' => $v);
            //                }
            //
            //            if (isset($in_db))
            //                foreach ($in_db as $k => $v) {
            //                
            //                    $in[] = array('name' => $k, 'value' => $v);
            //                    
            //                }
            // \f\pa($in_db, 2, null, '$in_db');
            // \f\db\sql_insert_mnogo($db, 'mitems-dops', $in_db, array('id_item' => $id_item));

            \f\db\sql_insert_mnogo($db, 'mitems-dops', $in_db, ['id_item' => $id_item]);

            self::clearCash($folder);
        }
        // die();

        return \f\end2('Изменения сохранены', 'ok', array('file' => __FILE__, 'line' => __LINE__), 'array');
    }

}
