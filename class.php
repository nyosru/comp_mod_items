<?php

namespace Nyos\mod;

use f\db as db;
use f as f;

//echo __FILE__.'<br/>';
// строки безопасности
if (!defined('IN_NYOS_PROJECT'))
    die('<center><h1><br><br><br><br>Cтудия Сергея</h1><p>Сработала защита <b>TPL</b> от злостных розовых хакеров.</p>
    <a href="http://www.uralweb.info" target="_blank">Создание, дизайн, вёрстка и программирование сайтов.</a><br />
    <a href="http://www.nyos.ru" target="_blank">Только отдельные услуги: Дизайн, вёрстка и программирование сайтов.</a>');

//echo __FILE__;

class items {

    public static $dir_img_server = false;
    public static $dir_img_uri = false;
    public static $dir_img_uri_download = false;
    public static $sort_head = null;
    public static $sql_select_vars = null;
    public static $cash = [];
    public static $now_mod = null;

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
     * массив переменных для запроса
     * @var type 
     */
    public static $sql_itemsdop_add_where_array = [];
    public static $sql_order = '';
    public static $sql_limit = '';

    public static function setSort($a1, $a2) {
        if ($a1 == 'head' && ( $a2 == 'asc' || $a2 == 'desc' )) {
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
                    . ( isset($module{1}) ? ' AND mi.`module` = \'' . addslashes($module) . '\' ' : '' )
                    . ( isset($stat{1}) ? ' AND mi.`status` = \'' . addslashes($stat) . '\' ' : '' )
                    . 'ORDER BY '
                    . ( self::$sort_head == 'desc' ? ' mi.head DESC, ' : '' )
                    . ( self::$sort_head == 'asc' ? ' mi.head ASC, ' : '' )
                    . ' mi.`sort` DESC, mi.`add_d` DESC, mi.`add_t` DESC '
                    . ( isset($limit{1}) && is_numeric($limit) ? 'LIMIT ' . $limit . ' ' : '' )
                    . ';';

//            if( $_SERVER['HTTP_HOST'] == 'yapdomik.uralweb.info' )
//                echo '<br/>'.'<br/>'.$f;

            $ff = $db->prepare($f);
            $ff->execute(
                    array(
                    // ':folder' => $folder
            ));

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
                . ( isset($module{1}) ? ' AND mi.`module` = \'' . addslashes($module) . '\' ' : '' )
                . ( isset($stat{1}) ? ' AND mi.`status` = \'' . addslashes($stat) . '\' ' : '' )
                . 'AND mi.`status` != \'delete2\' 
                
            WHERE 
                midop.status IS NULL 
                ' . ( isset(self::$sql_itemsdop_add_where{3}) ? ' AND ' . self::$sql_itemsdop_add_where : '' ) . '                
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

            $re[$r['id_item']]['dop'][$r['name']] = (!empty($r['value_text']) ? $r['value_text'] :
                    (!empty($r['value_int']) ? $r['value_int'] :
                    (!empty($r['value_date']) ? $r['value_date'] :
                    (!empty($r['value_datetime']) ? $r['value_datetime'] : $r['value'] )
                    )
                    )
                    );
        }

        $out = array('data' => $re
            , 'img_dir' => self::$dir_img_uri
            , 'img_dir_dl' => self::$dir_img_uri_download
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
     * получаем данные из итемс хранилища
     * @param type $db
     * @param type $module
     * @param type $stat
     * @return type
     */
    public static function getItemsSimple($db, $module = null, $stat = 'show') {

        $save_cash = false;

        if (empty(self::$sql_select_vars) && empty(self::$sql_itemsdop_add_where) && empty(self::$sql_itemsdop2_add_where) && empty(self::$sql_items_add_where) && empty(self::$sql_limit) && empty(self::$sql_order)) {

            if (!empty(self::$cash[$module][$stat])) {
                return self::$cash[$module][$stat];
            } else {
                $save_cash = true;
            }
        }

        $folder = \Nyos\nyos::$folder_now;

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

            $ff1 = 'SELECT 
                mi.*,
                midop.id dops_id, 
                midop.`name`, 
                midop.`value`,
                midop.`value_date`,
                midop.`value_datetime`,
                midop.`value_text` '
                    . ( self::$sql_select_vars ?? '' )
                    . '
            FROM 
                `mitems-dops` midop 
                
            INNER JOIN `mitems` mi ON '
                    // .' mi.folder = :folder '
                    . ' mi.id = midop.id_item ' . PHP_EOL
                    // . ( isset($module{1}) ? ' AND mi.`module` = \'' . addslashes($module) . '\' ' : '' ). PHP_EOL
                    . ' AND mi.`module` = :module '
                    . (!empty($stat) ? PHP_EOL . ' AND mi.`status` = \'' . addslashes($stat) . '\' ' : '' )
                    . PHP_EOL . ' AND mi.`status` != \'delete2\' '
                    . (!empty(self::$sql_items_add_where) ? ' AND ' . self::$sql_items_add_where : '' )
                    . PHP_EOL
                    . ( self::$sql_itemsdop2_add_where ?? '' ) . '
            WHERE 
                midop.status IS NULL 
                ' . (!empty(self::$sql_itemsdop_add_where) ? ' AND ' . self::$sql_itemsdop_add_where : '' )
                    . ' ' . ( self::$sql_order ?? '' )
                    . ' ' . ( self::$sql_limit ?? '' )
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
            $for_sql[':module'] = ( isset($module{1}) ) ? $module : '';
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
                        . ( isset($module{1}) ? ' AND mi.`module` = \'' . addslashes($module) . '\' ' : '' )
                        . ( isset($stat{1}) ? ' AND mi.`status` = \'' . addslashes($stat) . '\' ' : '' )
                        . 'ORDER BY '
                        . ( self::$sort_head == 'desc' ? ' mi.head DESC, ' : '' )
                        . ( self::$sort_head == 'asc' ? ' mi.head ASC, ' : '' )
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
                        $sql_dop .= (!empty($sql_dop) ? ' AND ' : '' ) . ' mi.id != \'' . $k . '\' ';
                    }
                }

                $ff1 = 'SELECT 
                        mi.* '
                        . ' ' . ( self::$sql_select_vars ?? '' )
                        . '
                    FROM 
                        `mitems` mi
                    WHERE '
                        . ' mi.`status` != \'delete2\' '
                        . $sql_dop
                        // .' mi.folder = :folder '
                        . ( isset($module{1}) ? ' AND mi.`module` = \'' . addslashes($module) . '\' ' : '' )
                        . ( isset($stat{1}) ? ' AND mi.`status` = \'' . addslashes($stat) . '\' ' : '' )
                        . (!empty(self::$sql_items_add_where) ? ' AND ' . self::$sql_items_add_where : '' ) . '
                        ' . ( self::$sql_itemsdop2_add_where ?? '' ) . '
                        ' . (!empty(self::$sql_itemsdop_add_where) ? ' AND ' . self::$sql_itemsdop_add_where : '' )
                        . ' ' . ( self::$sql_order ?? '' )
                        . ' ' . ( self::$sql_limit ?? '' )
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

        $out = array('data' => $re ?? []
            , 'img_dir' => self::$dir_img_uri
            , 'img_dir_dl' => self::$dir_img_uri_download
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

        return f\end2('Достали список', 'ok', $out, 'array');
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
                    . ( isset($module{1}) ? ' AND `module` = \'' . addslashes($module) . '\' ' : '' )
                    . ( isset($stat{1}) ? ' AND `status` = \'' . addslashes($stat) . '\' ' : '' )
                    . 'AND `status` != \'delete2\' '
                    . 'ORDER BY `sort` DESC, `add_d` DESC, `add_t` DESC '
                    . ( isset($limit{1}) && is_numeric($limit) ? 'LIMIT ' . $limit . ' ' : '' )
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
            $in_sql2 .= ( isset($in_sql2{3}) ? ' OR ' : '' ) . ' `id_item` = \'' . $r['id'] . '\' ';
        }

        // $sql2 = $db->sql_query('SELECT `id_item`, `name`, `value`,`value_text` FROM `mitems-dops` WHERE (' . $in_sql2 . ') AND `status` IS NULL ;');

        $ff1 = 'SELECT `id_item`, `name`, `value`,`value_text` FROM `mitems-dops` WHERE '
                . ( isset($in_sql2{5}) ? ' (' . $in_sql2 . ') AND ' : '' )
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

            if (isset($r['value_text']{0})) {
                $return[$r['id_item']]['dop'][$r['name']] = $r['value_text'];
            } elseif (isset($r['value']{0})) {

                $return[$r['id_item']]['dop'][$r['name']] = $r['value'];
            }
        }

        $out = array('data' => $return
            , 'img_dir' => self::$dir_img_uri
            , 'img_dir_dl' => self::$dir_img_uri_download
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
                . ( isset($folder{1}) ? (string) $folder . '.' : '' )
                . ( isset($module{1}) ? (string) $module . '.' : '' )
                . ( isset($stat{1}) ? (string) $stat . '.' : '' )
                . ( isset($limit{1}) ? (string) $limit . '.' : '' )
                . '.cash';

        if (file_exists($cash_dir . $cash_file))
            return unserialize(file_get_contents($cash_dir . $cash_file));

        if (self::$dir_img_server === false)
            self::creatFolderImage2($folder);

        $out = array('data' => \f\db\getSql($db, 'SELECT * FROM `mitems` WHERE `folder` = \'' . addslashes($folder) . '\' '
                    . ( isset($module{1}) ? ' AND `module` = \'' . addslashes($module) . '\' ' : '' )
                    . ( isset($stat{1}) ? ' AND `status` = \'' . addslashes($stat) . '\' ' : '' )
                    . 'AND `status` != \'delete2\' '
                    . 'ORDER BY `sort` DESC, `add_d` DESC, `add_t` DESC '
                    . ( isset($limit{1}) && is_numeric($limit) ? 'LIMIT ' . $limit . ' ' : '' )
                    . ';')
        );

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
                ( isset($stat{1}) ? ' AND i.status = \'' . addslashes($stat) . '\' ' : '' )
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
                'folder' => $folder
                , 'module' => $cfg_mod['cfg.level']
                , 'head' => $data['head']
                , 'add_d' => 'NOW'
                , 'add_t' => 'NOW'
                    ), 'da', 'last_id');
            //echo $status;
            //
            //echo '<br/>#'.__LINE__;
            //echo '<br/>#-'.$new_id;

            $in_db = array();

            foreach ($cfg_mod as $k => $v) {

                if (isset($data[$k]{0}) && isset($v['name_rus']{0})) {

                    if (isset($v['type']) && ( $v['type'] == 'textarea' || $v['type'] == 'textarea_html' )) {

                        $in_db[] = array(
                            'name' => $k,
                            'value_text' => $data[$k]
                        );
                    } elseif (isset($v['type']) && $v['type'] == 'translit' && isset($v['var_in']{0}) && isset($data[$v['var_in']])) {

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

                    if (isset($cfg_mod[$k]) && is_array($v) && isset($v['size']) && $v['size'] > 100 && isset($v['error']) && $v['error'] == 0
                    ) {

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
     * добавление записи боллее лёгкая версия
     * @global \Nyos\mod\type $status
     * @param type $db
     * @param string $folder
     * @param array $cfg_mod
     * @param array $data
     * @return type
     */
    public static function addNewSimple($db, string $mod_name, array $data, $files = array(), $add_all_dops = false) {

        $folder = \Nyos\Nyos::$folder_now;
        $cfg_mod = \Nyos\Nyos::$menu[$mod_name] ?? $mod_name;

        try {

            return $e = self::addNew($db, $folder, $cfg_mod, $data, $files, $add_all_dops);
        } catch (\PDOException $ex) {
            return false;
        }
    }

    public static function addNewSimples($db, string $mod_name, array $data, $files = array(), $add_all_dops = false) {

        $folder = \Nyos\Nyos::$folder_now;
        $cfg_mod = \Nyos\Nyos::$menu[$mod_name];

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

        if (empty(self::$dir_img_server)) {
            self::creatFolderImage($folder);
        }

        if (self::$dir_img_server === false)
            throw new \Exception('Ошибка, папка для файлов не создана');

        if (!isset($data['head']{0}))
            $data['head'] = 1;

        //echo '<Br/>'.__FILE__.' '.__LINE__;
        // \f\pa($data);

        if (isset($data['head'])) {

            // echo '<Br/>'.__FILE__.' '.__LINE__;

            $arin = array(
                'module' => ( $cfg_mod['cfg.level'] ?? $cfg_mod )
                , 'head' => $data['head']
                , 'add_d' => date('Y-m-d', $_SERVER['REQUEST_TIME'])
                , 'add_t' => date('H:i:s', $_SERVER['REQUEST_TIME'])
            );

            if (!empty($folder))
                $arin['folder'] = $folder;

            if (!empty($data['status']))
                $arin['status'] = $data['status'];

            $new_id = \f\db\db2_insert($db, 'mitems', $arin, 'da', 'last_id');

            // echo 'новый id '.$new_id;

            $in_db = array();

            // \f\pa($cfg_mod,2,null,'$cfg_mod');

            if( isset($cfg_mod) && is_array($cfg_mod) && sizeof($cfg_mod) > 0 )
            foreach ($cfg_mod as $k => $v) {

                // \f\pa($v,2,null,'$cfg_mod $v');

                if ($add_all_dops === false && empty($v['type']))
                    continue;

//                echo '<hr><hr>';
//                \f\pa($k);
//                \f\pa($v);
//                \f\pa($data[$k]);
                //if (isset($data[$k]{0}) && isset($v['name_rus']{0})) {
                // \f\pa($k);
                // \f\pa($data[$k]);
                //if ( isset($data[$k]{0})) {
                if (!empty($data[$k]) || !empty($v['default'])) {

                    // echo '<br>' . __LINE__;

                    if ($v['type'] == 'textarea' || $v['type'] == 'textarea_html') {

                        $in_db[] = array(
                            'name' => $k,
                            'value_text' => $data[$k] ?? $v['default']
                        );
                    } elseif ($v['type'] == 'datetime') {

                        $in_db[] = array(
                            'name' => $k,
                            'value_datetime' => date('Y-m-d H:i:s', isset($data[$k]{1}) ?
                                    strtotime($data[$k] . ' ' . ( isset($data[$k . '_time']) ? $data[$k . '_time'] : '' )) :
                                    ( (!empty($v['default']) && $v['default'] == 'now') ? $_SERVER['REQUEST_TIME'] : '' )
                            )
                        );
                    } elseif ($v['type'] == 'date') {

                        $in_db[] = array(
                            'name' => $k,
                            'value_date' => date('Y-m-d', isset($data[$k]{2}) ? strtotime($data[$k]) : ( (!empty($v['default']) && $v['default'] == 'now') ? $_SERVER['REQUEST_TIME'] : null ))
                        );
                    } elseif ($v['type'] == 'number') {

                        $in_db[] = array(
                            'name' => $k,
                            'value' => $data[$k] ?? $v['default']
                        );
                    } else {

                        $in_db[] = array(
                            'name' => $k,
                            'value' => $data[$k] ?? $v['default']
                        );
                    }
                } elseif ($v['type'] == 'translit' && isset($v['var_in']{0}) && isset($data[$v['var_in']]{0})) {

                    $in_db[] = array(
                        'name' => $v['var_in'] . '_translit',
                        'value_text' => \f\translit($data[$v['var_in']], 'uri2')
                    );
                }
            }

            if (isset($files) && sizeof($files) > 0) {


                //echo '<br/>#'.__LINE__;
                $nn = 1;

                foreach ($files as $k => $v) {

                    //f\pa($v);

                    if (isset($cfg_mod[$k]) && is_array($v) && isset($v['size']) && $v['size'] > 100 && isset($v['error']) && $v['error'] == 0
                    ) {

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

            //\f\pa($in_db);
            // echo '<Br/>db\sql_insert_mnogo - ' .$new_id ;
            //$status = '';
            \f\db\sql_insert_mnogo($db, 'mitems-dops', $in_db, array('id_item' => $new_id));
            // \f\pa($in_db,2,null,'items что пишем в базу');
            // db\sql_insert_mnogo($db, 'mitems-dops', $in_db, array('id_item' => $new_id));
            //echo $status;
        }

        self::clearCash($folder);

        return \f\end2('Окей, запись добавлена', 'ok', array('file' => __FILE__, 'line' => __LINE__), 'array');
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

    public static function saveEdit($db, $id_item, $folder, $cfg_mod, $data) {


        if (self::$dir_img_server === false) {
            self::creatFolderImage($folder);
        }
        if (self::$dir_img_server === false) {
            return f\end2('Ошибка, папка для файлов не может быть создана', 'error', array('file' => __FILE__, 'line' => __LINE__), 'array');
        }

        if (isset($data['head']{0})) {

            \f\db\db_edit2($db, 'mitems', array('id' => $id_item), array('head' => $data['head']), false, 1, 'da');

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
                $in_db['head_translit'] = \f\translit($data['head'], 'uri2');
            }

            //\f\pa($cfg_mod,2,null,'$cfg_mod');
            //\f\pa($data,2,null,'$data');

            foreach ($cfg_mod as $k => $v) {

                if (isset($data[$k . '_del']) && $data[$k . '_del'] == 'yes')
                    continue;

                //echo '<br/>'.__LINE__;
                //if (isset($data[$k]{0}) && is_array($v) && !empty($v['name_rus']) ) {
                if (!empty($data[$k]) && !empty($v['name_rus'])) {

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
                            'value_datetime' => date('Y-m-d H:i:s', strtotime($data[$k] . ' ' . ( isset($data[$k . '_time']) ? $data[$k . '_time'] : '' )))
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

                    if (isset($cfg_mod[$k]) && is_array($v) && isset($v['size']) && $v['size'] > 100 && isset($v['error']) && $v['error'] == 0
                    ) {

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
            // \f\pa($in_db,2,null,'$in_db');

            \f\db\sql_insert_mnogo($db, 'mitems-dops', $in_db, array('id_item' => $id_item));

            self::clearCash($folder);
        }

        return f\end2('Изменения сохранены', 'ok', array('file' => __FILE__, 'line' => __LINE__), 'array');
    }

}
