# form component
https://www.bbitrix.ru/pages/components/bitriks-komponent-obratnaya-svyaz-ajax/  

Создание компонента из формы.  
- имя комопнента feedback.send
- компонент можно разместить в папках `bitrix/components/namespace` или `local/components/namespace`
- создадим папку шаблона `local/components/damir/feedback.send/templates/.default`

## local/components/test/feedback.send/component.php
Основной файл компонента.

    if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
    // Генерация капча
    $arResult['CAPTCHA_CODE'] = $APPLICATION->CaptchaGetCode();
    // Инициализация подключение шаблона
    $this->IncludeComponentTemplate();

## local/components/test/feedback.send/ajax.php
Обработка данных и отправка формы:

    <?php
    require_once($_SERVER['DOCUMENT_ROOT']. "/bitrix/modules/main/include/prolog_before.php");
    if( $_REQUEST['get'] == 'captcha' ) {
      echo $code = $APPLICATION->CaptchaGetCode();
    }
    if( $_REQUEST['get'] == 'send' ) {
      $error = '';
      if( !$APPLICATION->CaptchaCheckCode($_REQUEST['captcha_word'], $_REQUEST['captcha_sid']) ) {
        $error .= "Текст с картинки введен неверно!";
      }
      if( $_REQUEST['name'] == '' ) {
        $error .= "Поле Имя не заполнено!";
      }
      if( $_REQUEST['email'] == '' || !check_email($_REQUEST['email']) ) {
        $error .= "Поле Email введено неверно!";
      }
      if( $_REQUEST['message']=='' ) {
        $error .= "Заполните текст сообщения!";
      }
      if( $error ) {
        echo "<span style='color:#ff0000;'>" . $error . "</span>";
      } else {
        $arEventFields = array(
          "NAME"    => $_REQUEST['name'],
          "EMAIL"   => $_REQUEST['email'],
          "MESSAGE" => $_REQUEST['message'],
        );
        // Если все поля введены верно, то вызывается событие FEEDBACK_SEND:
        if( CEvent::Send("FEEDBACK_SEND", array(SITE_ID), $arEventFields) ) {
          echo "<span style='color:#0fcf00;'>Сообщение успешно отправлено!</span>";
        } else {
          echo "<span style='color:#ff0000;'>Ошибка почтового сервера!</span>";
        }
      }
    }

## local/components/test/feedback.send/templates/.default/template.php

    <form action="" method="post" class="feedback_form">
      <input type="hidden" name="get" value="send" />
      <?php if( $arResult['SUCCESS'] == 'Y' ) { ?>
        <span class="success"><?php echo $arResult['MESSAGE']; ?></span>
      <?php } ?>
      <?php if( $arResult['SUCCESS'] == 'N') { ?>
        <span class="error">
          <?php foreach( $arResult['ERROR'] as $k => $v ) echo $v, "<br />"; ?>
        </span>
      <?php } ?>
      <div class="item">
        <label>Введите имя:</label>
        <input type="text" name="name" value="" required />
      </div>
      <div class="item">
        <label>Введите E-mail:</label>
        <input type="text" name="email" value="" required />
      </div>
      <div class="item">
        <label>Введите сообщение:</label>
        <textarea name="message" required></textarea>
      </div>
      <div class="item">
        <label>Введите капчу:</label>
        <img src="/bitrix/tools/captcha.php?captcha_sid=<?php echo $arResult['CAPTCHA_CODE'];?>" alt="CAPTCHA" width="120" class="captcha_img" /><br />
        <a href="#" rel="nofollow" class="update">Обновить капчу</a><br />
        <input type="hidden" name="captcha_sid" value="<?php echo $arResult['CAPTCHA_CODE'];?>" />
        <input type="text" name="captcha_word" value="" required/>
      </div><!-- captcha-holder -->
      <input type="submit" value="Отправить" />
    </form>
    <div class="schema"></div>

    <?php
      CJSCore::Init(array("jquery", "popup"));
    ?>

    <script>
    $( document ).ready( function() {
      $( '.update' ).on( 'click', function() {
        $.ajax({
          url: '<?php echo $componentPath . '/ajax.php' ;?>',
          type: 'post',
          data: 'get=captcha',
          success: function(data){
            $('.feedback_form .captcha_img').attr('src', '/bitrix/tools/captcha.php?captcha_sid=' + data);
            $('.feedback_form input[name="captcha_sid"]').val(data);
          }
        });
        return false;
      });

      $('.feedback_form').on('submit', function() {
        var name = $('input[name="name"]').val();
        var email = $('input[name="email"]').val();
        var message = $('textarea[name="message"]').val();
        var captcha_word = $('input[name="captcha_word"]').val();
        var captcha_sid = $('input[name="captcha_sid"]').val();
        $.ajax({
          url: '<?php echo $componentPath . '/ajax.php'; ?>',
          type: 'post',
          data: 'get=send&name='+name+'&email='+email+'&message='+message+'&captcha_word='+captcha_word+'&captcha_sid='+captcha_sid,
          success: function(data) {
            var popup = new BX.CDialog({
              'title': 'Отправка формы',
              'content': data,
              'draggable': true,
              'resizable': false,
              'width':250,
              'height':200,
              'buttons': [BX.CDialog.btnClose]
            });
            popup.Show();
          }
        });
        return false;
      });
    });
    </script>

## local/components/test/feedback.send/templates/.default/style.css

    .feedback_form{
      display:block;
      width:400px;
      border:solid 1px #ccc;
      border-radius:5px;
      padding:10px;
    }

    .feedback_form .item{
      margin-bottom:10px;
      width:100%;
      float:left;
    }

    .feedback_form .item label{
      display:block;
      width:150px;
      float:left;
    }

    .feedback_form .item input[type='text'], .feedback_form .item textarea{
      width:200px;
      float:right;
    }

    .feedback_form .item textarea{
      height:200px;
    }

## site/test.php
На странице размещаем компонент:

    $APPLICATION->IncludeComponent( "damir:feedback.send", "", Array( false ));
