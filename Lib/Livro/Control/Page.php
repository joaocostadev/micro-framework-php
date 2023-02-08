<?php
namespace Livro\Control;

use Livro\Widgets\Base\Element;

class Page extends Element
{
    public function __construct()
    {
        parent::__construct('div');
    }

    public function show()
    {
        if ($_GET)
        {
            $method = isset($_GET['method']) ? $_GET['method'] : null;

            if (method_exists($this, $method))
            {
                call_user_func([$this, $method], $_REQUEST);
            }
        }

        parent::show();
    }

}