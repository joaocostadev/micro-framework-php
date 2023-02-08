<?php
namespace Livro\Widgets\Datagrid;

use Livro\Control\ActionInterface;


class DatagridColumn
{
    private $name;
    private $label;
    private $align;
    private $width;
    private $action;
    private $transformer;

    public function __construct($name, $label, $align, $width)
    {
        $this->name  = $name;
        $this->label = $label;
        $this->align = $align;
        $this->width = $width;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @return mixed
     */
    public function getAlign()
    {
        return $this->align;
    }

    /**
     * @return mixed
     */
    public function getWidth()
    {
        return $this->width;
    }

    public function setAction(ActionInterface $action)
    {
        $this->action = $action;
    }

    /**
     * @return mixed
     */
    public function getAction()
    {
        return $this->action;
    }

    public function setTransformer(Callable $callback)
    {
        $this->transformer = $callback;
    }

    /**
     * @return mixed
     */
    public function getTransformer()
    {
        return $this->transformer;
    }


}