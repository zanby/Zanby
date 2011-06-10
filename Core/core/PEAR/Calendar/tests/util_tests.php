<?php
// $Id: util_tests.php,v 1.1 2007/02/22 09:30:01 meleshko Exp $

require_once('simple_include.php');
require_once('calendar_include.php');

class UtilTests extends GroupTest {
    function UtilTests() {
        $this->GroupTest('Util Tests');
        $this->addTestFile('util_uri_test.php');
        $this->addTestFile('util_textual_test.php');
    }
}

if (!defined('TEST_RUNNING')) {
    define('TEST_RUNNING', true);
    $test = &new UtilTests();
    $test->run(new HtmlReporter());
}
?>