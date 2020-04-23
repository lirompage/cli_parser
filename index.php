<?php

use app\Controllers as Controllers;

require_once __DIR__ . '/vendor/autoload.php';

switch($argv[0]) {
    case 'help':
        $help = new Controllers\Help($argv[1]);
        $help->show();
        break;
    case 'parse':
        $parse = new Controllers\Parse();
        $parse->checkExists($argv[1]);
        break;
    case 'report':
        $checkProtocol = new Controllers\Parse();
        $checkProtocol->scheme($argv[1]);
        $report = new Controllers\Report();
        $report->showReport($checkProtocol->scheme($argv[1]));
        break;
}
