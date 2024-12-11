<?php

namespace App\View\Components;

use Illuminate\View\Component;

class DataTable extends Component
{
    public $pageTitle;
    public $headerAction;
    public $dataTable;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($pageTitle = 'List', $headerAction = null, $dataTable)
    {
        $this->pageTitle = $pageTitle;
        $this->headerAction = $headerAction;
        $this->dataTable = $dataTable;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.data-table');
    }
}
