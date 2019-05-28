<?php

// namespace Nyos\mod;

// use Nyos\mod\lk as lk;
// use Nyos as Nyos;
// use f as f;

// ini_set('display_errors', 'On'); // сообщения с ошибками будут показываться
// error_reporting(E_ALL); // E_ALL - отображаем ВСЕ ошибки

date_default_timezone_set("Asia/Yekaterinburg");
define('IN_NYOS_PROJECT', true);

// sleep(1);

// require( $_SERVER['DOCUMENT_ROOT'] . '/index.session_start.php' );
// require( $_SERVER['DOCUMENT_ROOT'] . '/0.all/class/nyos.2.php' );
// require( $_SERVER['DOCUMENT_ROOT'] . '/0.all/f/ajax.php' );

require( $_SERVER['DOCUMENT_ROOT'] . '/0.site/0.cfg.start.php');
require( $_SERVER['DOCUMENT_ROOT'] . DS . '0.all' . DS . 'class' . DS . 'mysql.php' );
require( $_SERVER['DOCUMENT_ROOT'] . DS . '0.all' . DS . 'db.connector.php' );

//$_SESSION['status1'] = true;
// $status = '';
$db->sql_query('UPDATE `mitems` SET `status` = \'delete2\' WHERE `status` = \'delete\' ;');
// echo $status;

die('ок, удалили '.$db->sql_affectedrows() );
