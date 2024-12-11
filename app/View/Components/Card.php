<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Card extends Component
{
    public ?string $header;
    public ?string $action;
    public ?int $id;
    public ?string $createdAt;
    public ?string $updatedAt;

    /**
     * Create a new component instance.
     *
     * @param string|null   $header
     * @param string|null   $action
     * @param int|null      $id
     */
    public function __construct(?string $header = null, ?string $action = null, int $id = null, ?string $createdAt = null, ?string $updatedAt = null)
    {
        $this->header = $header;
        $this->action = $action;
        $this->id = $id;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function render(): View
    {
        return view('components.card');
    }
}
