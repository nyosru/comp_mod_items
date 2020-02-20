$(document).ready(function () { // вся мaгия пoслe зaгрузки стрaницы

// перебор div
//function hidePosts(){ 
//  var hideText = "текст";
//  var posts = document.querySelectorAll("._post.post");
//  for (var i = 0; i<posts.length; i++) {
//    var post = posts[i].querySelector(".wall_post_text");
//    if (post.innerText.indexOf(hideText) != -1 )
//    {
//      posts[i].style.display = "none";
//    }
//  }
//}

//    function ocenka_clear2($sp, $date, $tr = '') {
//        ocenka_clear($sp, $date, $tr);
//    }



    function month(date0, nn) {

        var date = new Date(date0);
//        var mm = date.getUTCMonth() + 1; //months from 1-12
//        var dd = date.getUTCDate();
//        var YY = date.getUTCFullYear();
//        
//        var D = new Date(YY, mm, dd);
        // D.setMonth(D.getMonth() + nn);
        date.setDate(date.getDate() + nn);
        // alert(D);

        var mm = date.getUTCMonth() + 1; //months from 1-12
        
        if( mm < 10 )
        mm = '0'+mm;
    
        var dd = date.getUTCDate();

        if( dd < 10 )
        dd = '0'+dd;
        
        var YY = date.getUTCFullYear();
        
        var str = ''+YY + '-' + mm + '-' + dd;

        return str;

//var now = new Date(date);
//var formated_date = now.format("yyyy-mm-dd");

//        return date.format("yyyy-mm-dd");
    }


    function jobdesc__ocenka_clear($sp, $date, $clear_to_now = '') {

// если не пусто то трём все даты начиная с указанной
        if ($clear_to_now != '') {

            for (let i = 0; i < 32; i++) { // выведет 0, затем 1, затем 2
                // alert(i);
                now_date = month($date, i);
                // console.log(now_date);
                $('#a_price_' + $sp + '_' + now_date ).html('<div class=\'bg-warning\' style=\'padding:5px;\' >Значение изменено</div>');
            }

            // alert($date);
// month( YY,mm,dd, nn )

            // $('#a_price_' + $sp + '_' + $date).html('<div class=\'bg-warning\' style=\'padding:5px;\' >Значение изменено</div>');
            console.log('стираем все даты, начиная с указанной', $sp, $date);

        }
// если пусто то трём дату указанную
        else {

            $('#a_price_' + $sp + '_' + $date).html('<div class=\'bg-warning\' style=\'padding:5px;\' >Значение изменено</div>');
            console.log('стираем 1 дату', $sp, $date);

        }


        $.ajax({

            url: "/vendor/didrive_mod/jobdesc/1/ajax.php",
            data: "action=ocenka_clear&sp=" + $sp + "&date=" + $date + "&clear_to_now=" + $clear_to_now,

            cache: false,
            dataType: "json",

            type: "post",
            async: false,

//            beforeSend: function () {
//
//                $('span#' + $textblock_id).css('border-bottom', '2px solid orange');
//                $('span#' + $textblock_id).css('font-weight', 'bold');
//                //if (typeof $div_hide !== 'undefined') {
//                //$('#' + $div_hide).hide();
//                //}
//
//                // $("#ok_but_stat").html('<img src="/img/load.gif" alt="" border=0 />');
//                //                $("#ok_but_stat").show('slow');
//                //                $("#ok_but").hide();
//
//                ocenka_clear($in_sp, $in_date);
//
//            }
//            ,
            success: function ($j) {

                console.log('стираем оценку дня', $j);
//
//                // alert($j.status);
//
//                if ($j.status == 'error') {
//
//                    // $('span#' + $textblock_id).css('border-bottom', '2px solid red');
//                    // $('span#' + $textblock_id).css('color', 'darkred');
//
//                } else {
//
//                    $('span#' + $textblock_id).css('border-bottom', '2px solid green');
//                    // $('span#' + $textblock_id).css('color', 'darkgreen');
//
//                    // console.log($new_val);
//                    // console.log( 1, $('span#' + $textblock_id).closest('.www').find('.now_price_hour').attr('kolvo_hour'));
//                    // $('span#' + $textblock_id).closest('.smena1').find('.hours_kolvo').val($new_val);
//                    // console.log( 2, $('span#' + $textblock_id).closest('.www').find('.now_price_hour').attr('kolvo_hour'));
//
//
//                    // $.debounce( 1000, calcSummMoneySmena2 );
//                    // calcSummMoneySmena2($textblock_id);
//
////                    setTimeout( function () {
////                        //calculateSummAllGraph();
////
////                        console.log('$textblock_id', $textblock_id);
////                        // alert($textblock_id);
////
////                        calcSummMoneySmena($textblock_id);
////
////                    }, 100);
////                    //$(document).one( calculateSummAllGraph );
//
//                }


            }

        });

    }


    function jobdesc__colvo_hours__clear($sp, $date, $clear_to_now = '') {

// если не пусто то трём все даты начиная с указанной
        if ($clear_to_now != '') {

            for (let i = 0; i < 32; i++) { // выведет 0, затем 1, затем 2
                // alert(i);
                now_date = month($date, i);
                // console.log(now_date);
                $('#a_price_' + $sp + '_' + now_date ).html('<div class=\'bg-warning\' style=\'padding:5px;\' >Значение изменено</div>');
            }

            // alert($date);
// month( YY,mm,dd, nn )

            // $('#a_price_' + $sp + '_' + $date).html('<div class=\'bg-warning\' style=\'padding:5px;\' >Значение изменено</div>');
            console.log('стираем все даты, начиная с указанной', $sp, $date);

        }
// если пусто то трём дату указанную
        else {

            $('#a_price_' + $sp + '_' + $date).html('<div class=\'bg-warning\' style=\'padding:5px;\' >Значение изменено</div>');
            console.log('стираем 1 дату', $sp, $date);

        }


        $.ajax({

            url: "/vendor/didrive_mod/jobdesc/1/ajax.php",
            data: "action=ocenka_clear&sp=" + $sp + "&date=" + $date + "&clear_to_now=" + $clear_to_now,

            cache: false,
            dataType: "json",

            type: "post",
            async: false,

//            beforeSend: function () {
//
//                $('span#' + $textblock_id).css('border-bottom', '2px solid orange');
//                $('span#' + $textblock_id).css('font-weight', 'bold');
//                //if (typeof $div_hide !== 'undefined') {
//                //$('#' + $div_hide).hide();
//                //}
//
//                // $("#ok_but_stat").html('<img src="/img/load.gif" alt="" border=0 />');
//                //                $("#ok_but_stat").show('slow');
//                //                $("#ok_but").hide();
//
//                ocenka_clear($in_sp, $in_date);
//
//            }
//            ,
            success: function ($j) {

                console.log('стираем оценку дня', $j);
//
//                // alert($j.status);
//
//                if ($j.status == 'error') {
//
//                    // $('span#' + $textblock_id).css('border-bottom', '2px solid red');
//                    // $('span#' + $textblock_id).css('color', 'darkred');
//
//                } else {
//
//                    $('span#' + $textblock_id).css('border-bottom', '2px solid green');
//                    // $('span#' + $textblock_id).css('color', 'darkgreen');
//
//                    // console.log($new_val);
//                    // console.log( 1, $('span#' + $textblock_id).closest('.www').find('.now_price_hour').attr('kolvo_hour'));
//                    // $('span#' + $textblock_id).closest('.smena1').find('.hours_kolvo').val($new_val);
//                    // console.log( 2, $('span#' + $textblock_id).closest('.www').find('.now_price_hour').attr('kolvo_hour'));
//
//
//                    // $.debounce( 1000, calcSummMoneySmena2 );
//                    // calcSummMoneySmena2($textblock_id);
//
////                    setTimeout( function () {
////                        //calculateSummAllGraph();
////
////                        console.log('$textblock_id', $textblock_id);
////                        // alert($textblock_id);
////
////                        calcSummMoneySmena($textblock_id);
////
////                    }, 100);
////                    //$(document).one( calculateSummAllGraph );
//
//                }


            }

        });

    }








    $(document).on('click', '.edit_item', function () {

        console.log('click актион смена итема');

        /* <input class="edit_item" type="button" rel="{$k1}" alt="status" rev="delete" value="Удалить" /> */
        var $pole = $(this).attr('alt');
        var $val = $(this).attr('rev');
        var $id = $(this).attr('rel');
        var $s = $(this).attr('s');

        var $sp = $(this).attr('sp');
        var $date = $(this).attr('date');


        var $before_success_show_id = 0;
        var $get_query = [];

        // var $div_res = $('#' + $(this).attr('for_res'));

        $.each(this.attributes, function () {
            if (this.specified) {

                console.log('a1', this.name, this.value);

                // $get_query[this.name] = this.value;
                console.log(this.name, this.value);

                if (this.name == 'run_ocenka_clear') {

                    if (this.value == "day") {
                        jobdesc__ocenka_clear($sp, $date);
                    } else if (this.value == "days") {
                        jobdesc__ocenka_clear($sp, $date, 'da');
                    }

                } else if (this.name == 'before_success_show_id') {
                    $before_success_show_id = $('#' + this.value);
                }

            }
        });



        if (typeof $(this).attr('for_res') !== 'undefined') {

            var $div_res = $('#' + $(this).attr('for_res'));
            // console.log($div_res, 1);

        } else if (typeof $(this).attr('for_res2') !== 'undefined' && typeof $(this).attr('for_res2_text') !== 'undefined') {

            var $div_res2 = $('#' + $(this).attr('for_res2'));
            var $div_res2_text = $('#' + $(this).attr('for_res2'));
            // console.log($div_res2, 2);

        }



//alert( $div_res );

        $.ajax({

            type: 'POST',
            url: '/vendor/didrive_mod/items/1/ajax.php',
            dataType: 'json',
            data: "action=edit_pole&pole=" + $pole + "&id=" + $id + "&val=" + $val + "&s=" + $s,
            // сoбытиe дo oтпрaвки
            beforeSend: function ($data) {
                if (typeof $div_res !== 'undefined') {
                    $div_res.html('<img src="/img/load.gif" alt="" border="" />');
                } else if (typeof $div_res2 !== 'undefined') {
                    $div_res2.html('..');
                }
            },
            // сoбытиe пoслe удaчнoгo oбрaщeния к сeрвeру и пoлучeния oтвeтa
            success: function ($data) {

                if (typeof $div_res !== 'undefined') {
                    // eсли oбрaбoтчик вeрнул oшибку
                    if ($data['error'])
                    {
                        // alert($data['error']); // пoкaжeм eё тeкст
                        $div_res.html('<div class="warn warn">' + $data['html'] + '</div>');
                    }
                    // eсли всe прoшлo oк
                    else
                    {
                        $div_res.html('<div class="warn good">' + $data['html'] + '</div>');
                    }
                } else if (typeof $div_res2 !== 'undefined') {
                    $div_res2.html($div_res2_text);
                }

                if ($before_success_show_id != 0) {
                    $before_success_show_id.show('slow');
                }

            },
            // в случae нeудaчнoгo зaвeршeния зaпрoсa к сeрвeру
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status); // пoкaжeм oтвeт сeрвeрa
                alert(thrownError); // и тeкст oшибки
            }
            /*
             // сoбытиe пoслe любoгo исхoдa
             ,complete: function ($data) {
             // в любoм случae включим кнoпку oбрaтнo
             // $form.find('input[type="submit"]').prop('disabled', false);
             }
             */

        }); // ajax-

        // return false;
    });





    $('body .select_edit_item_dop').change(function (event) {
        // alert('Элемент foo был изменен.');

        $th = $(this);
        $val = $th.val();

        //alert($val);

        $uri_query = 'aa=ajax_edit_select&new_val=' + $val;

        $.each(this.attributes, function () {

            if (this.specified) {

                console.log(this.name, this.value);
                $uri_query = $uri_query + '&' + this.name + '=' + this.value;

//                if (this.name == 'hidethis' && this.value == 'da') {
//                    hidethis = 1;
//                }
//                if (this.name == 'show_id') {
//                    showid = '#' + this.value;
//                }
//                if (this.name == 'go_answer') {
//                    answer = this.value;
//                }
//                if (this.name == 'resto') {
//                    resto = '#' + this.value;
                //console.log($vars['resto']);
                // alert($res_to);
//                }
//                if (this.name == 'show_on_click') {
//                    $('#' + this.value).show('slow');
//                }

            }

        });


        $.ajax({

            url: "/vendor/didrive_mod/items/1/didrive/ajax.php",
            data: $uri_query,
            cache: false,
            dataType: "json",
            type: "post",

            beforeSend: function () {
                /*
                 if (typeof $div_hide !== 'undefined') {
                 $('#' + $div_hide).hide();
                 }
                 */
                // $("#ok_but_stat").html('<img src="/img/load.gif" alt="" border=0 />');
//                $("#ok_but_stat").show('slow');
//                $("#ok_but").hide();
                $th.css('border', '2px solid orange');
            }
            ,

            success: function ($j) {

                if ($j.status == 'error') {
                    $th.css('border', '2px solid red');
                } else {
                    $th.css('border', '2px solid green');
                }

                //alert(resto);

                // $($res_to).html($j.data);
                // $($vars['resto']).html($j.data);
                //$(resto).html($j.html);
//
//                if (showid != 0) {
//                    $(showid).show('slow');
//                }
//
//                if (hidethis == 1) {
//                    $th.hide();
//                }

                // $th("#main").prepend("<div id='box1'>1 блок</div>");                    
                // $th("#main").prepend("<div id='box1'>1 блок</div>");                    
                // $th.html( $j.html + '<br/><A href="">Сделать ещё заявку</a>');
                // $($res_to_id).html( $j.html + '<br/><A href="">Сделать ещё заявку</a>');

                // return true;

                /*
                 // alert($j.html);
                 if (typeof $div_show !== 'undefined') {
                 $('#' + $div_show).show();
                 }
                 */
//                $('#form_ok').hide();
//                $('#form_ok').html($j.html + '<br/><A href="">Сделать ещё заявку</a>');
//                $('#form_ok').show('slow');
//                $('#form_new').hide();
//
//                $('.list_mag').hide();
//                $('.list_mag_ok').show('slow');



            },
            // в случae нeудaчнoгo зaвeршeния зaпрoсa к сeрвeру
            error: function (xhr, ajaxOptions, thrownError) {
                $th.css('border', '2px solid red');
                // alert(xhr.status); // пoкaжeм oтвeт сeрвeрa
                // alert(thrownError); // и тeкст oшибки
            }
            /*
             // сoбытиe пoслe любoгo исхoдa
             ,complete: function ($data) {
             // в любoм случae включим кнoпку oбрaтнo
             // $form.find('input[type="submit"]').prop('disabled', false);
             }
             */


        });


        //return false;




    });


    $('body').on('click', '.act_smena2', function (event) {

        // alert('2323');
//        $(this).removeClass("show_job_tab");
//        $(this).addClass("show_job_tab2");
//        var $uri_query = '';
//        var $vars = [];
        // var $vars = serialize(this.attributes);
        // var $vars =  JSON.stringify(this.attributes);
        var resto = '';
        var $vars = new Array();
        var $uri_query = '';
        var hidethis = 0;
        var showid = 0;
        var answer = 0;

        $.each(this.attributes, function () {

            if (this.specified) {

                // console.log(this.name, this.value);
                // $uri_query = $uri_query + '&' + this.name + '=' + this.value.replace(' ', '..')
                $uri_query = $uri_query + '&' + this.name + '=' + this.value;
//
                if (this.name == 'hidethis' && this.value == 'da') {
                    hidethis = 1;
                }
                if (this.name == 'show_id') {
                    showid = '#' + this.value;
                }
                if (this.name == 'go_answer') {
                    answer = this.value;
                }
                if (this.name == 'resto') {
                    resto = '#' + this.value;
                    //console.log($vars['resto']);
                    // alert($res_to);
                }
//
//                if (this.name == 'show_on_click') {
//                    $('#' + this.value).show('slow');
//                }

            }

        });

        if (answer != 0) {

            if (!confirm(answer)) {
                return false;
            }

        }

//        alert($uri_query);
//        return false;

        // console.log($vars['resto']);

        // console.log($uri_query);
        //$(this).html("тут список");
        var $th = $(this);

        $.ajax({

            xurl: "/sites/yadom_admin/module/000.index/ajax.php",
            url: "/vendor/didrive_mod/jobdesc/1/didrive/ajax.php",
            data: "t=1" + $uri_query,
            cache: false,
            dataType: "json",
            type: "post",

            beforeSend: function () {
                /*
                 if (typeof $div_hide !== 'undefined') {
                 $('#' + $div_hide).hide();
                 }
                 */
                // $("#ok_but_stat").html('<img src="/img/load.gif" alt="" border=0 />');
//                $("#ok_but_stat").show('slow');
//                $("#ok_but").hide();
            }
            ,

            success: function ($j) {

                //alert(resto);

                // $($res_to).html($j.data);
                // $($vars['resto']).html($j.data);
                $(resto).html($j.html);

                if (showid != 0) {
                    $(showid).show('slow');
                }

                if (hidethis == 1) {
                    $th.hide();
                }

                // $th("#main").prepend("<div id='box1'>1 блок</div>");                    
                // $th("#main").prepend("<div id='box1'>1 блок</div>");                    
                // $th.html( $j.html + '<br/><A href="">Сделать ещё заявку</a>');
                // $($res_to_id).html( $j.html + '<br/><A href="">Сделать ещё заявку</a>');

                // return true;

                /*
                 // alert($j.html);
                 if (typeof $div_show !== 'undefined') {
                 $('#' + $div_show).show();
                 }
                 */
//                $('#form_ok').hide();
//                $('#form_ok').html($j.html + '<br/><A href="">Сделать ещё заявку</a>');
//                $('#form_ok').show('slow');
//                $('#form_new').hide();
//
//                $('.list_mag').hide();
//                $('.list_mag_ok').show('slow');

            }

        });


        return false;

    });
    // else {
    // alert(i + ': ' + $(elem).text());
    // }







    $('body').on('click', '.edit_items_dop_values', function (event) {

        //alert('2323');
//        $(this).removeClass("show_job_tab");
//        $(this).addClass("show_job_tab2");
//        var $uri_query = '';
//        var $vars = [];
        // var $vars = serialize(this.attributes);
        // var $vars =  JSON.stringify(this.attributes);
        var res_to_id = '';
        var $vars = new Array();
        var $uri_query = '';
        var hidethis = 0;
        var showid = 0;
        var answer = 0;
        var msg_to_success = 0;

        $.each(this.attributes, function () {

            if (this.specified) {

                // console.log(this.name, this.value);
                // $uri_query = $uri_query + '&' + this.name + '=' + this.value.replace(' ', '..')
                $uri_query = $uri_query + '&' + this.name + '=' + this.value;
//
                if (this.name == 'hidethis' && this.value == 'da') {
                    hidethis = 1;
                } else if (this.name == 'show_id') {
                    showid = '#' + this.value;
                } else if (this.name == 'comit_answer2') {
                    answer = this.value;
                } else if (this.name == 'msg_to_success') {
                    msg_to_success = this.value;
                } else if (this.name == 'res_to_id') {
                    res_to_id = $('#' + this.value);
                    //console.log($vars['resto']);
                    // alert($res_to);
                }
//
//                if (this.name == 'show_on_click') {
//                    $('#' + this.value).show('slow');
//                }

            }

        });





        if (answer != 0) {
            if (!confirm(answer)) {
                return false;
            }
        }

//        alert($uri_query);
//        return false;

        // console.log($vars['resto']);

        // console.log($uri_query);
        //$(this).html("тут список");
        var $th = $(this);

        $.ajax({

            url: "/vendor/didrive_mod/items/1/didrive/ajax.php",
            data: "t=1" + $uri_query,
            cache: false,
            dataType: "json",
            type: "post",

            beforeSend: function () {
                /*
                 if (typeof $div_hide !== 'undefined') {
                 $('#' + $div_hide).hide();
                 }
                 */
                // $("#ok_but_stat").html('<img src="/img/load.gif" alt="" border=0 />');
//                $("#ok_but_stat").show('slow');
//                $("#ok_but").hide();
            }
            ,

            success: function ($j) {

                //alert(resto);

                // $($res_to).html($j.data);
                // $($vars['resto']).html($j.data);
                // $(resto).html($j.html);

                if ($j.status == 'ok') {

                    if (showid != 0) {
                        $(showid).show('slow');
                    }

                    if (hidethis == 1) {
                        $th.hide();
                    }

                    if (msg_to_success != 0) {
                        res_to_id.html('<b class="warn" >' + msg_to_success + '</b>');
                    } else {
                        res_to_id.html('<b class="warn" >' + $j.html + '</b>');
                    }

                    // $th("#main").prepend("<div id='box1'>1 блок</div>");                    
                    // $th("#main").prepend("<div id='box1'>1 блок</div>");                    
                    // $th("#main").prepend("<div id='box1'>1 блок</div>");                    
                    // $th.html( $j.html + '<br/><A href="">Сделать ещё заявку</a>');
                    // $($res_to_id).html( $j.html + '<br/><A href="">Сделать ещё заявку</a>');

                    // return true;

                    /*
                     // alert($j.html);
                     if (typeof $div_show !== 'undefined') {
                     $('#' + $div_show).show();
                     }
                     */
//                $('#form_ok').hide();
//                $('#form_ok').html($j.html + '<br/><A href="">Сделать ещё заявку</a>');
//                $('#form_ok').show('slow');
//                $('#form_new').hide();
//
//                $('.list_mag').hide();
//                $('.list_mag_ok').show('slow');
                }
// если ошибка
                else {

                    if (showid != 0) {
                        $(showid).show('slow');
                    }
                    res_to_id.html('<b class="warn" >' + $j.html + '</b>');

                }
            }

        });

        return false;

    });
    // else {
    // alert(i + ': ' + $(elem).text());
    // }

});