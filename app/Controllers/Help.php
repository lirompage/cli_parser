<?php

namespace app\Controllers;

use app\Views\MainView;

class Help
{
    public function show()
    {
        return MainView::help();
    }
}