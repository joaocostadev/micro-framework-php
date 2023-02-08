<?php
namespace Livro\Widgets\Datagrid;

use Livro\Control\ActionInterface;

class Datagrid
{
    private $columns;
    private $action;
    private $items;

    public function __construct()
    {
        $this->columns = [];
        $this->actions  = [];
        $this->items   = [];
    }

    public function addColumn(DatagridColumn $object)
    {
        $this->columns[] = $object;
    }

    public function addAction($label, ActionInterface $action, $field, $image = null)
    {
        $this->action[] = [ 'label'  => $label,
            'action' => $action,
            'field'  => $field,
            'image'  => $image ];
    }

    public function addItem( $object )
    {
        $this->items[] = $object;
    }

    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }

    public function getActions()
    {
        return $this->action;
    }

    public function clear()
    {
        $this->items = [];
    }

}