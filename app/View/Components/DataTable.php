<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class DataTable extends Component
{
    public string $pageTitle;
    public ?string $headerAction;
    public $dataTable;

    /**
     * Create a new component instance.
     *
     * @param string        $pageTitle
     * @param string|null   $headerAction     
     */
    public function __construct($pageTitle = 'List', $dataTable, $headerAction = null)
    {
        $this->pageTitle = $pageTitle;
        $this->headerAction = $headerAction;
        $this->dataTable = $dataTable;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function render(): View
    {
        return view('components.data-table');
    }
}
