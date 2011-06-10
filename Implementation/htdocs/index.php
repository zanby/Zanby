<?php
/****************** Benchmark *******************/
$time_start = microtime_float();
/************************************************/

/* Init Core and first required constants */
require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'init'.DIRECTORY_SEPARATOR.'Initializing.php';   

/* Bootstrap application and run */
$application->bootstrap()->run();

/**
 * Disable profiling.
 * The first part of this code placed in init/Initializing.php
 */
if ( function_exists('xhprof_disable') ) {
    $cfgInstance = Warecorp_Config_Loader::getInstance()->getAppConfig('cfg.instance.xml');
    if ( $cfgInstance->debug_mode === 'on' ) {
        $xhprof_data = xhprof_disable();

        include_once ENGINE_DIR.'/xhprof_lib/utils/xhprof_lib.php';
        include_once ENGINE_DIR.'/xhprof_lib/utils/xhprof_runs.php';

        $xhprof_runs = new XHProfRuns_Default();
        $namespase = sprintf("xhprof.%s", HTTP_CONTEXT);
        $run_id = $xhprof_runs->save_run($xhprof_data, $namespase);
    }
}
/* Disable profiling */

/****************** Benchmark *******************/
$time = microtime_float() - $time_start;
Warecorp_Debug::analyse($time);

function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}
/************************************************/
