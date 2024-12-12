<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ActionShow extends Component
{
    public string $route;
    public string $entity;

    /**
     * Create a new component instance.
     *
     * @param string $route
     * @param string $entity
     */
    public function __construct(string $route, string $entity = 'View')
    {
        $this->route = $route;
        $this->entity = $entity;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('components.action-show');
    }
}
