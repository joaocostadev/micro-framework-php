<?php
namespace Livro\Widgets\Dialog;

use Livro\Widgets\Base\Element;

class Message
{
    public function __construct($type, $message)
    {
        $div = new Element('div');
        if ($type == 'info')
        {
            $div->class = 'alert alert-info';
        }
        elseif ($type == 'error')
        {
            $div->class = 'alert alert-danger';
        }

        $div->add($message);
        $div->show();
    }
}