<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ActionButtons extends Component
{
    public $entity;
    public $id;
    public $editRoute;
    public $deleteRoute;
    public $deleteMessage;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($entity, $id, $editRoute, $deleteRoute, $deleteMessage)
    {
        $this->entity = $entity;
        $this->id = $id;
        $this->editRoute = $editRoute;
        $this->deleteRoute = $deleteRoute;
        $this->deleteMessage = $deleteMessage;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.action-buttons');
    }
}
