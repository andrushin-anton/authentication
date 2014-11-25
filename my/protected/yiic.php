<?php

// change the following paths if necessary
$yiic=dirname(__FILE__).'/../../framework/yiic.php';

require_once(dirname(__FILE__) . '/../../system/components/config/ConfigBase.php');
require_once(dirname(__FILE__) . '/../../system/components/config/ConfigConsole.php');

$console = new ConfigConsole($argv, dirname(__FILE__));
$config = $console->getPathToConfigFile();

defined('ROOT_PATH') or define('ROOT_PATH', dirname(__FILE__) . '/../../');

require_once($yiic);
