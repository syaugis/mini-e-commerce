<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class DataTable extends Component
{
    public string $pageTitle;
    public array|string|null $headerAction;
    public $dataTable;

    /**
     * Create a new component instance.
     *
     * @param string $pageTitle
     * @param mixed $headerAction
     * @param mixed $dataTable
     */
    public function __construct(string $pageTitle = 'List', $dataTable, $headerAction = null)
    {
        $this->pageTitle = $pageTitle;
        $this->headerAction = is_array($headerAction) ? $headerAction : [$headerAction];
        $this->dataTable = $dataTable;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View
     */
    public function render(): View
    {
        return view('components.data-table');
    }
}
