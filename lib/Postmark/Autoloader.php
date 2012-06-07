<?php

namespace Postmark;

class Autoloader
{
    static public function register()
    {
        ini_set('unserialize_callback_func', 'spl_autoload_call');
        spl_autoload_register(array(new self, 'autoload'));
    }

    static public function autoload($class)
    {
        if (0 !== strpos($class, 'Postmark\Inbound'))
        {
            return;
        }

        if (file_exists($file = dirname(__FILE__) . '/' . str_replace('Postmark/', '', preg_replace("{\\\}", "/",($class))) . '.php'))
        {
            require $file;
        }
    }
}