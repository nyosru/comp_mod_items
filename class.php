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

    /**
     * добавляем в выборку из главной таблицы " WHERE + $sql_add_where "
     * `mitems` mi 
     * @var string
     */
    // public static $sql_items_add_where = null;
    /**
     * добавляем в выборку из дополнительной таблицы " WHERE + $sql_add_where "
      midop.`name`,
      midop.`value`,
      midop.`value_text`
      FROM
      `mitems-dops` midop
     * @var string
     */
    public static $sql_itemsdop_add_where = null;

    /**
     * массив переменных для запроса
     * @var type 
     */
    public static $sql_itemsdop_add_where_array = [];

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

            $f = 'SELECT * FROM `mitems` mi WHERE mi.folder = \'' . $folder . '\' '
                    . ( isset($module{1}) ? ' AND mi.`module` = \'' . addslashes($module) . '\' ' : '' )
                    . ( isset($stat{1}) ? ' AND mi.`status` = \'' . addslashes($stat) . '\' ' : '' )
                    . 'AND mi.`status` != \'delete2\' '
                    . 'ORDER BY '
                    . ( self::$sort_head == 'desc' ? ' mi.head DESC, ' : '' )
                    . ( self::$sort_head == 'asc' ? ' mi.head ASC, ' : '' )
                    . ' mi.`sort` DESC, mi.`add_d` DESC, mi.`add_t` DESC '
                    . ( isset($limit{1}) && is_numeric($limit) ? 'LIMIT ' . $limit . ' ' : '' )
                    . ';';

//            if( $_SERVER['HTTP_HOST'] == 'yapdomik.uralweb.info' )
//                echo '<br/>'.'<br/>'.$f;

            $ff = $db->prepare($f);
            $ff->execute();

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
                midop.`value_text` 
            FROM 
                `mitems-dops` midop 
                
            INNER JOIN `mitems` mi ON mi.folder = :folder '
                . ( isset($module{1}) ? ' AND mi.`module` = \'' . addslashes($module) . '\' ' : '' )
                . ( isset($stat{1}) ? ' AND mi.`status` = \'' . addslashes($stat) . '\' ' : '' )
                . 'AND mi.`status` != \'delete2\' 
                AND mi.id = midop.id_item
            WHERE 
                midop.status IS NULL 
                ' . ( isset(self::$sql_itemsdop_add_where{3}) ? ' AND ' . self::$sql_itemsdop_add_where : '' ) . '                
            ;';
        self::$sql_itemsdop_add_where = null;

//        echo $ff1;
//        echo '<br/><br/>';

        $ff = $db->prepare($ff1);

        $for_sql = self::$sql_itemsdop_add_where_array;
        $for_sql[':folder'] = $folder;
        self::$sql_itemsdop_add_where_array = [];

        $ff->execute($for_sql);

        // \f\pa($ff->fetchAll());

        while ($r = $ff->fetch()) {

//            if( $_SERVER['HTTP_HOST'] == 'yapdomik.uralweb.info' )
//                \f\pa($re);

            if (!isset($re[$r['id_item']]['dop']))
                $re[$r['id_item']]['dop'] = array();

            $re[$r['id_item']]['dop'][$r['name']] = (isset($r['value_text']{1})) ? $r['value_text'] : $r['value'];
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
                    `add_d`  INTEGER           NOT NULL,
                    `add_t`  INTEGER           NOT NULL
                );');
        $ff->execute();
        $ff = $db->prepare('CREATE TABLE [mitems-dops] (
                    `id`        INTEGER       UNIQUE
                         PRIMARY KEY AUTOINCREMENT
                         NOT NULL,
                    `id_item`     INTEGER       NOT NULL    REFERENCES mitems (id),
                    `name`        VARCHAR (255) NOT NULL,
                    `value`       VARCHAR (255),
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

    /**
     * новая версия с исключениями
     * @global \Nyos\mod\type $status
     * @param type $db
     * @param string $folder
     * @param array $cfg_mod
     * @param array $data
     * @return type
     */
    public static function addNew($db, string $folder, array $cfg_mod, array $data, $files = array(), $add_all_dops = false) {

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

            $new_id = \f\db\db2_insert($db, 'mitems', array(
                'folder' => $folder
                , 'module' => $cfg_mod['cfg.level']
                , 'head' => $data['head']
                , 'add_d' => date('Y-m-d', $_SERVER['REQUEST_TIME'])
                , 'add_t' => date('H:i:s', $_SERVER['REQUEST_TIME'])
                    ), 'da', 'last_id');

            // echo 'новый id '.$new_id;

            $in_db = array();

            foreach ($cfg_mod as $k => $v) {

                if ($add_all_dops === false && empty($v['type']))
                    continue;

//                echo '<br/>';
//                echo $k;
//                echo '<br/>';
//                \f\pa($v);
//                echo '<br/>';
//                if( isset($data[$k]) )
//                \f\pa($data[$k]);
//                echo '<br/>';
                //if (isset($data[$k]{0}) && isset($v['name_rus']{0})) {
                if (isset($data[$k]{0})) {

                    if ($v['type'] == 'textarea' || $v['type'] == 'textarea_html') {

                        $in_db[] = array(
                            'name' => $k,
                            'value_text' => $data[$k]
                        );
                    } elseif ($v['type'] == 'datetime') {

                        $in_db[] = array(
                            'name' => $k,
                            'value_text' => date('Y-m-d H:i:s', strtotime($data[$k] . ' ' . ( isset($data[$k . '_time']) ? $data[$k . '_time'] : '' )))
                        );
                    } else {

                        $in_db[] = array(
                            'name' => $k,
                            'value' => $data[$k]
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
            // db\sql_insert_mnogo($db, 'mitems-dops', $in_db, array('id_item' => $new_id));
            //echo $status;
        }

        self::clearCash($folder);

        return f\end2('Окей, запись добавлена', 'ok', array('file' => __FILE__, 'line' => __LINE__), 'array');
    }

    public static function clearCash(string $folder) {

        $s = scandir(DR . dir_site);
        foreach ($s as $k => $v) {
            if (strpos($v, 'items') !== false && strpos($v, 'cash') !== false) {
                unlink(DR . dir_site . $v);
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

            if (isset($cfg_mod['head_translit'])) {
                $in_db['head_translit'] = \f\translit($data['head'], 'uri2');
            }

            foreach ($cfg_mod as $k => $v) {

                if (isset($data[$k . '_del']) && $data[$k . '_del'] == 'yes')
                    continue;

                if (isset($data[$k]{0}) && is_array($v) && isset($v['name_rus']{0})) {

                    // $dop_sql .= ( isset($dop_sql{1}) ? ' OR ' : '' ) . ' `name` = \'' . addslashes($k) . '\' ';

                    if (isset($v['type']) && ( $v['type'] == 'textarea' || $v['type'] == 'textarea_html' )) {
                        $in_db_text[$k] = $data[$k];
                    } else {
                        $in_db[$k] = $data[$k];
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

                        $in_db[$k] = $new_name;
                    }
                }
            }

            // $db->sql_query('DELETE FROM `mitems-dops` WHERE `id_item` = \'' . $id_item . '\' AND (' . $dop_sql . ') ;');
            // $db->sql_query('DELETE FROM `mitems-dops` WHERE `id_item` = \'' . $id_item . '\' ;');

            $ff = $db->prepare('DELETE FROM `mitems-dops` WHERE `id_item` = \'' . $id_item . '\' ;');
            $ff->execute();

            $in = [];

            if (isset($in_db_text))
                foreach ($in_db_text as $k => $v) {
                    $in[] = array('name' => $k, 'value_text' => $v);
                }

            if (isset($in_db))
                foreach ($in_db as $k => $v) {
                    $in[] = array('name' => $k, 'value' => $v);
                }

            \f\db\sql_insert_mnogo($db, 'mitems-dops', $in, array('id_item' => $id_item));

            self::clearCash($folder);
        }

        return f\end2('Изменения сохранены', 'ok', array('file' => __FILE__, 'line' => __LINE__), 'array');
    }

}
