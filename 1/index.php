<?php

//echo __FILE__.' '.__LINE__;



/*
$tt = \Nyos\mod\items::itemsPage( $db, $vv['folder'], $_GET['level'] );
\f\pa($tt);
$tt = \Nyos\mod\items::itemsPage( $db, $vv['folder'], $_GET['level'], 10, 2 );
\f\pa($tt);
*/

// \f\pa($vv['now_mod']);


if( isset($_GET['delete_item']) && is_numeric($_GET['delete_item']) && isset($_GET['s']) && $_GET['s'] == md5('s'.$_GET['delete_item']) ){

    //$_SESSION['status1'] = true;
    //$status = '';
    $db->sql_query('UPDATE mitems SET `status` = \'delete\' WHERE id = '.$_GET['delete_item'].' LIMIT 1;');
    //echo $status;
    //die();
    \Nyos\mod\items::clearCash($vv['folder']);

    $g = $_GET;

    unset($g['delete_item']);
    unset($g['s']);

    $g['rand'] = rand();
    $g['delete_ok'] = 1;

    \f\redirect( '/', 'index.php', $g);

    die();
}


if( isset( $_GET['delete_ok'])){
    $vv['warn'] = 'Успешно удалено';
}



if( isset($vv['now_mod']['datain_name_file']) ){

    $datain_file = $_SERVER['DOCUMENT_ROOT'].'/9.site/'.$vv['folder'].'/download/datain/'.$vv['now_mod']['datain_name_file'].'.arr';
    
    if(file_exists($datain_file)){

        $vv['datain'] = unserialize(file_get_contents($datain_file));
        // \f\pa($vv['datain']);
        
    }
    
}

$vv['tpl_body'] = DR.dir_site_module_nowlev_tpl.'body.htm';

// $vv['tpl_body'] = \f\like_tpl('body', null, dir_site_module_nowlev_tpl, DR );

// $vv['tpl_0body'] = \f\like_tpl('body', $vv['dir_module_tpl'], $vv['dir_site_tpl']);

/*
        {* медленная загрузка фото в новостях 
        <script src="/js/lazyload/jquery.lazyload.min.js" type="text/javascript"></script>

        {literal}
        <script>
        $(function(){
            $("img.news_lazyImg").lazyload({
                effect: "fadeIn"
            });
        });
        </script>
        {/literal}
        *}
*/      