<?php

if (isset($_SERVER["HTTP_REFERER"])) $referer = $_SERVER["HTTP_REFERER"];
else $referer = SITE_NAME_AS_FULL_DOMAIN;

$parts = explode("/", $referer);
$parts[3] = "rss";
$referer = implode("/", $parts);

$this->_redirect($referer);
exit;

