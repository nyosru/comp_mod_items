<?php

namespace Didrive\Mod;

if (!defined('IN_NYOS_PROJECT'))
    throw new Exception('Что то пошло не так, обратитесь к администратору', 666);

echo '<br/>Привет буфет '.__FILE__.' ['.__LINE__.']';

class Items {

    public static $dir_img_server = false;
    public static $dir_img_uri = false;
    public static $dir_img_uri_download = false;
    public static $my_dir = null;

    /**
     * возвращает путь теущего файла класса (путь всего модуля)
     * @return type
     */
    public static function getDir() {
        return self::$my_dir = dirname(__FILE__).DIRECTORY_SEPARATOR;
    }
    
    public static function creatTable($db, string $table = 'mitems') {
        if ($table == 'mitems') {

            try {

                $ff2 = $db->prepare('CREATE TABLE IF NOT EXISTS `mitems` ( '
// наверное в MySQL .' `id` int NOT NULL AUTO_INCREMENT, '
// в SQLlite
                        . ' `id` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL , '
                        . ' `folder` VARCHAR(150) DEFAULT NULL, '
                        . ' `module` VARCHAR(50) NOT NULL, '
                        . ' `head` VARCHAR DEFAULT NULL, '
                        . ' `sort` INTEGER(2) DEFAULT \'50\', '
                        . ' `status` VARCHAR(50) NOT NULL DEFAULT \'show\', '
                        . ' `add_d` INTEGER NOT NULL , '
                        . ' `add_t` INTEGER NOT NULL  '
                        . ' );');
//$ff->execute([$domain]);
                $ff2->execute();

                $ff2 = null;

                $ff2 = $db->prepare('CREATE TABLE IF NOT EXISTS `mitems-dops` ( '
// наверное в MySQL .' `id` int NOT NULL AUTO_INCREMENT, '
// в SQLlite
                        . ' `id` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL , '
                        . ' `item_id` INTEGER NOT NULL , '
                        . ' `name` VARCHAR DEFAULT NULL, '
                        . ' `value` VARCHAR DEFAULT NULL, '
                        . ' `value_text` TEXT DEFAULT NULL, '
                        . ' `status` VARCHAR DEFAULT NULL, '
                        . ' `date_status` INTEGER '
                        . ' );');
//$ff->execute([$domain]);
                $ff2->execute();
            } catch (\PDOException $ex) {

                echo '<pre>--- ' . __FILE__ . ' ' . __LINE__ . '-------'
                . PHP_EOL . $ex->getMessage() . ' #' . $ex->getCode()
                . PHP_EOL . $ex->getFile() . ' #' . $ex->getLine()
                . PHP_EOL . $ex->getTraceAsString()
                . '</pre>';
                die();
            }
        }
    }

    public static function addNew($db, $folder = null, array $cfg_mod, array $data, array $files = array()) {

        if (empty(self::$dir_img_server))
            self::creatFolderImage($folder);

        if (isset($data['head'])) {

            $new_id = \f\db\db2_insert($db, 'mitems', array(
                'folder' => $folder
                , 'module' => $cfg_mod['cfg.level']
                , 'head' => $data['head']
                , 'add_d' => date('Y-m-d', $_SERVER['REQUEST_TIME'])
                , 'add_t' => date('H:i:s', $_SERVER['REQUEST_TIME'])
                    ), 'da', 'last_id');

            $in_db = [];

            foreach ($cfg_mod as $k => $v) {

                if (empty($v['type']))
                    continue;

                if (isset($data[$k]{0})) {

                    if ($v['type'] == 'textarea' && $v['type'] == 'textarea_html') {
                        $in_db[] = array(
                            'name' => $k,
                            'value_text' => $data[$k]
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

                require_once $_SERVER['DOCUMENT_ROOT'] . '/include/nyos/nyos_image.php';

//echo '<br/>#'.__LINE__;
                $nn = 1;

//\f\pa($files,2);

                foreach ($files as $k => $v) {

//f\pa($v);

                    if (isset($cfg_mod[$k]) && is_array($v) && isset($v['size']) && $v['size'] > 100 && isset($v['error']) && $v['error'] == 0) {

                        $new_name = $new_id . '_' . $nn . '_' . rand(10, 99) . '.' . \f\get_file_ext($v['name']);

                        $e = \Nyos\Nyos_image::autoJpgRotate($v['tmp_name'], self::$dir_img_server . $new_name);

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



            \f\pa($in_db, 2);

// echo '<Br/>db\sql_insert_mnogo - ' .$new_id ;
//$status = '';
            \f\db\sql_insert_mnogo($db, 'mitems-dops', $in_db, array('item_id' => $new_id));
// db\sql_insert_mnogo($db, 'mitems-dops', $in_db, array('id_item' => $new_id));
//echo $status;
        }

//self::clearCash($folder);

        return true;
    }

    public static function getItems($db, string $folder = null, string $module = null, string $stat = 'show', int $limit = null) {

        try {

            $ff = $db->prepare('SELECT
                
                mi.id,
                mi.head, 
                mi.sort, 
                mi.status, 
                mi.add_d, 
                mi.add_t,
                
                d.id dop_id,
                d.name dop_name,
                d.value dop_value, 
                d.value_text dop_value_text
                
            FROM 
                `mitems-dops` `d`
                
            INNER JOIN mitems mi ON mi.`id` = `d`.`item_id` '
                    . ( isset($folder{1}) ? ' AND mi.`folder` = :folder ' : '' )
// .' AND i.folder = \'' . addslashes($folder) . '\' '
// .' AND i.module = \'' . addslashes($module) . '\' ' .
                    . ' AND mi.`module` = :module '
                    . ' AND mi.`status` ' . ( isset($stat{1}) ? ' = :status ' : ' != \'delete2\' ' )
                    . ' 
            WHERE 
                `d`.`status` IS NULL
            ORDER BY  
                mi.`sort` DESC
            ;');

            $a = array(':module' => $module);

            if (isset($stat{1}))
                $a[':status'] = $stat;

            if (isset($folder{1}))
                $a[':folder'] = $folder;

            // \f\pa($a);

            $ff->execute($a);

            $r = $ff->fetchAll();

            // echo '<pre>'; var_dump($r); echo '</pre>';
            // \f\pa($r);
            // \f\pa($out,2);

            $return['data'] = [];

            foreach ($r as $k => $v) {

                if (!isset($return['data'][$v['id']]))
                    $return['data'][$v['id']] = array(
                        'id' => $v['id'],
                        'head' => $v['head'],
                        'status' => $v['status'],
                        'sort' => $v['sort'],
                        'dop' => []
                    );

                $return['data'][$v['id']]['dop'][$v['dop_name']] = $v['dop_value'] . $v['dop_value_text'];
            }

            // \f\pa($return,2);
        } catch (\PDOException $ex) {

            echo '<Br/>' . __FILE__ . ' ' . __LINE__ . ' <pre>'
            . PHP_EOL . 'error in ' . $ex->getFile() . ' #' . $ex->getLine()
            . PHP_EOL . '#' . $ex->getCode() . ' ' . $ex->getMessage()
            . PHP_EOL . $ex->getTraceAsString()
            . '</pre>';

            if (strpos($ex->getMessage(), 'no such table: mitems') !== false) {

                self::creatTable($db);
                throw new \NyosEx('При получении данных items не было таблицы, создали.');
                
            } else {

                throw new \NyosEx('При получении данных PDO ошибка <pre>'
                . PHP_EOL . 'error in ' . $ex->getFile() . ' #' . $ex->getLine()
                . PHP_EOL . '#' . $ex->getCode() . ' ' . $ex->getMessage()
                . PHP_EOL . $ex->getTraceAsString() . '</pre>');
            }
        }

        $return['img_dir'] = self::$dir_img_uri;
        $return['img_dir_dl'] = self::$dir_img_uri_download;

        // \f\pa($return, 2);

        return $return;
    }

    public static function creatFolderImage(string $site_dir = null) {

        if ($site_dir === null)
            $site_dir = DirSite0;

        self::$dir_img_uri_download = 'module_items_image' . DS;
        self::$dir_img_uri = DirSite0 . self::$dir_img_uri_download;
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

}



class items_old190407 {

    public static $dir_img_server = false;
    public static $dir_img_uri = false;
    public static $dir_img_uri_download = false;

    /**
     * определяем папку для фоток
     * @param type $folder
     * @return type
     */
    public static function creatFolderImage($folder) {

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

    public static function getItems_old190407($db, $folder, $module = null, $stat = 'show', $limit = 50) {

//$_SESSION['status1'] = true;
//$status = '';

        if (isset($_SESSION['status1']) && $_SESSION['status1'] === true) {
            global $status;
            $status .= '<fieldset><legen>' . __CLASS__ . ' #' . __LINE__ . ' + ' . __FUNCTION__ . '</legend>';
        }

        if (self::$dir_img_server === false) {
            self::creatFolderImage($folder);
        }

// папка для кеша данных
        $dir_for_cash = $_SERVER['DOCUMENT_ROOT'] . '/9.site/' . $folder . '/';

        if (is_dir($dir_for_cash)) {
            if (isset($module{1}) && file_exists($dir_for_cash . 'cash.items.' . $module . '.arr')) {
                $out = unserialize(file_get_contents($dir_for_cash . 'cash.items.' . $module . '.arr'));
                return f\end2('Достали список', 'ok', $out, 'array');
            } elseif (file_exists($dir_for_cash . 'cash.items.arr')) {
                $out = unserialize(file_get_contents($dir_for_cash . 'cash.items.arr'));
                return f\end2('Достали список', 'ok', $out, 'array');
            }
        }

//if ($module == '005.news') {
//    $shows = true;
//    $_SESSION['status1'] = true;
//    $status = '';
//}
        $sql = $db->sql_query('SELECT * FROM `mitems` WHERE `folder` = \'' . $folder . '\' '
                . ( isset($module{1}) ? ' AND `module` = \'' . addslashes($module) . '\' ' : '' )
                . ( isset($stat{1}) ? ' AND `status` = \'' . addslashes($stat) . '\' ' : '' )
                . 'AND `status` != \'delete2\' '
                . 'ORDER BY `sort` DESC, `add_d` DESC, `add_t` DESC '
                . ( isset($limit{1}) && is_numeric($limit) ? 'LIMIT ' . $limit . ' ' : '' )
                . ';');
//if ($shows === true) {
//    $_SESSION['status1'] = false;
//    echo $status;
//}

        $in_sql2 = '';
        $return = array();

        while ($r = $db->sql_fr($sql)) {
            $return[$r['id']] = $r;
            $in_sql2 .= ( isset($in_sql2{3}) ? ' OR ' : '' ) . ' `id_item` = \'' . $r['id'] . '\' ';
        }

        $sql2 = $db->sql_query('SELECT `id_item`, `name`, `value`,`value_text` FROM `mitems-dops` WHERE (' . $in_sql2 . ') AND `status` IS NULL ;');

        while ($r = $db->sql_fr($sql2)) {

            if (!isset($return[$r['id_item']]['dop']))
                $return[$r['id_item']]['dop'] = array();

            if (isset($r['value_text']{0})) {
                $return[$r['id_item']]['dop'][$r['name']] = $r['value_text'];
            } elseif (isset($r['value']{0})) {
                $return[$r['id_item']]['dop'][$r['name']] = $r['value'];
            }
        }

        if (isset($_SESSION['status1']) && $_SESSION['status1'] === true)
            $status .= '<span class="bot_line">#' . __LINE__ . '</span></fieldset>';

//echo $status;
// if( $module == '005.news' ){
// \f\pa($return);
// }

        $out = array('data' => $return
            , 'img_dir' => self::$dir_img_uri
            , 'img_dir_dl' => self::$dir_img_uri_download
        );

        if (is_dir($dir_for_cash)) {
            if (isset($module{1})) {
                file_put_contents($dir_for_cash . 'cash.items.' . $module . '.arr', serialize($out));
            } else {
                file_put_contents($dir_for_cash . 'cash.items.arr', serialize($out));
            }
        }

        return f\end2('Достали список', 'ok', $out, 'array');
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
        $cash_dir = $_SERVER['DOCUMENT_ROOT'] . '/9.site/' . $folder . '/';

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
    public static function addNew($db, $folder, $cfg_mod, $data) {

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
    public static function addNew2_old190410($db, string $folder, array $cfg_mod, array $data, array $files = array()) {

        if (empty(self::$dir_img_server)) {
            self::creatFolderImage($folder);
        }

        if (self::$dir_img_server === false)
            throw new \Exception('Ошибка, папка для файлов не создана');

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

//            \f\pa($cfg_mod);
//            \f\pa($data);

            foreach ($cfg_mod as $k => $v) {

                if (empty($v['type']))
                    continue;

//                echo $k;
//                \f\pa($v);
//if (isset($data[$k]{0}) && isset($v['name_rus']{0})) {
                if (isset($data[$k]{0})) {

                    if ($v['type'] == 'textarea' || $v['type'] == 'textarea_html') {

                        $in_db[] = array(
                            'name' => $k,
                            'value_text' => $data[$k]
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

                require_once $_SERVER['DOCUMENT_ROOT'] . '/0.all/class/nyos_image.php';

//echo '<br/>#'.__LINE__;
                $nn = 1;

                foreach ($files as $k => $v) {

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

//\f\pa($in_db);
// echo '<Br/>db\sql_insert_mnogo - ' .$new_id ;
//$status = '';
            \f\db\sql_insert_mnogo2($db, 'mitems-dops', $in_db, array('id_item' => $new_id));
// db\sql_insert_mnogo($db, 'mitems-dops', $in_db, array('id_item' => $new_id));
//echo $status;
        }

        self::clearCash($folder);

        return f\end2('Окей, запись добавлена', 'ok', array('file' => __FILE__, 'line' => __LINE__), 'array');
    }

    public static function clearCash_old190410(string $folder) {

        $dir_cash = $_SERVER['DOCUMENT_ROOT'] . '/9.site/' . $folder . '/';

        $s = scandir($dir_cash);
        foreach ($s as $k => $v) {
            if (strpos($v, 'items') !== false && strpos($v, 'cash') !== false) {
                unlink($dir_cash . $v);
            }
        }
    }

    public static function saveEdit($db, $id_item, $folder, $cfg_mod, $data) {

//$show_status = true;

        if (isset($show_status) && $show_status === true) {
            $status = '';
            $_SESSION['status1'] = true;
        }

        if (isset($_SESSION['status1']) && $_SESSION['status1'] === true) {
            global $status;

            $status .= '<fieldset class="status" ><legend>' . __CLASS__ . ' #' . __LINE__ . ' + ' . __FUNCTION__ . '</legend>';
        }

        if (self::$dir_img_server === false) {
            self::creatFolderImage($folder);
        }
        if (self::$dir_img_server === false) {
            return f\end2('Ошибка, папка для файлов не может быть создана', 'error', array('file' => __FILE__, 'line' => __LINE__), 'array');
        }

        if (isset($data['head']{0})) {


            f\db\db_edit2($db, 'mitems', array('id' => $id_item), array('head' => $data['head']), false, 1, 'da');

            /*
              $new_id = db\db2_insert($db, 'mitems', array(
              'folder' => $folder
              , 'module' => $cfg_mod['cfg.level']
              , 'head' => $data['head']
              , 'add_d' => 'NOW'
              , 'add_t' => 'NOW'
              ), 'da', 'last_id');
             */

            $in_db = array();

            $dop_sql = '';

            foreach ($cfg_mod as $k => $v) {

                if (isset($data[$k]{0}) && is_array($v) && isset($v['name_rus']{0})) {

                    $dop_sql .= ( isset($dop_sql{1}) ? ' OR ' : '' ) . ' `name` = \'' . addslashes($k) . '\' ';

                    if (isset($v['type']) && ( $v['type'] == 'textarea' || $v['type'] == 'textarea_html' )) {
                        $in_db[] = array(
                            'name' => $k,
                            'value_text' => $data[$k]
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

                $nn = 1;

                foreach ($data['files'] as $k => $v) {

//f\pa($v);

                    if (isset($cfg_mod[$k]) && is_array($v) && isset($v['size']) && $v['size'] > 100 && isset($v['error']) && $v['error'] == 0
                    ) {

                        $new_name = $id_item . '_' . $nn . '_' . rand(10, 99) . '.' . f\get_file_ext($v['name']);

                        $e = \Nyos\nyos_image::autoJpgRotate($v['tmp_name'], self::$dir_img_server . $new_name);
// \f\pa($e);

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

            $db->sql_query('DELETE FROM `mitems-dops` WHERE `id_item` = \'' . $id_item . '\' AND (' . $dop_sql . ') ;');

// echo '<Br/>db\sql_insert_mnogo - ' .$new_id ;
            db\sql_insert_mnogo($db, 'mitems-dops', $in_db, array('id_item' => $id_item));
        }

        self::clearCash($folder);

        if (isset($_SESSION['status1']) && $_SESSION['status1'] === true) {
            $status .= '<span class="bot_line">#' . __LINE__ . '</span></fieldset>';

            if (isset($show_status) && $show_status === true)
                echo $status;
        }

        return f\end2('Изменения сохранены', 'ok', array('file' => __FILE__, 'line' => __LINE__), 'array');
    }

}
