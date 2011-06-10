<?php
    $objResponse = new xajaxResponse;

    if ( trim( $captchaId ) == '' ) return;

    require_once(ENGINE_DIR.'/b2evo_captcha/b2evo_captcha.config.php');
    require_once(ENGINE_DIR.'/b2evo_captcha/b2evo_captcha.class.php');

    $captcha = new b2evo_captcha( $CAPTCHA_CONFIG );
    $imgLoc = $captcha->get_b2evo_captcha();
    $_SESSION['imgLoc'] = $imgLoc;

    $objResponse->addScript('document.getElementById("'.$captchaId.'").src = "/'.$imgLoc.'";');