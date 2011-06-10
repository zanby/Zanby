<?php
require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'init'.DIRECTORY_SEPARATOR.'Initializing.php'; 

header("Location: " . Warecorp::getFullUrl($_SERVER['REQUEST_URI'], HTTP_CONTEXT));
