<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/10
 * Time: 18:03
 */

$log['master'] = array(
    'type' => 'FileLog',
    'file' => WEBPATH . '/logs/app.log',
);
$log['test'] = array(
    'type' => 'FileLog',
    'file' => WEBPATH . '/logs/test.log',
);
return $log;