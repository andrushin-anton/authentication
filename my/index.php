<?php

// change the following paths if necessary
$yii=dirname(__FILE__).'/../framework/yiilite.php';

require_once(dirname(__FILE__) . '/../system/components/Utils.php');
require_once(dirname(__FILE__) . '/../system/components/config/ConfigBase.php');
require_once(dirname(__FILE__) . '/../system/components/config/ConfigApplication.php');

$appConfig = new ConfigApplication($_SERVER['HTTP_HOST'], dirname(__FILE__) . '/protected');
$appConfig->setCheckAccess(true);
$config = $appConfig->getPathToConfigFile();

if ($appConfig->hasDebug()) {
	defined('YII_DEBUG') or define('YII_DEBUG', true);
} else {
	defined('YII_DEBUG') or define('YII_DEBUG', false);
}

define('IS_TECHNICAL_WORK', false);

// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

require_once($yii);

defined('ROOT_PATH') or define('ROOT_PATH', dirname(__FILE__) . '/../');

Yii::createWebApplication($config)->run();
