<?php

namespace App\Views;

class MainView
{
    static public function help()
    {
        echo "\nCommands:
        php parse 'url'   - parse url for pictures;
        php report 'url'  - results analysis domain;
        php help          - display command list;
        \n";
        return;
    }

}