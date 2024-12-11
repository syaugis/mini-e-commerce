<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Image extends Component
{
    public $name;
    public $id;
    public $label;
    public $value;
    public $accept;
    public $placeholder;
    public $required;
    public $multiple;


    /**
     * Create a new component instance.
     *
     * @param string $name
     * @param string $id
     * @param string $label
     * @param string|null $value
     * @param string|null $accept
     * @param string $placeholder
     * @param bool $required
     * @param bool $multiple
     */

    public function __construct($name, $id, $label, $value = '',  $accept = '', $placeholder = '', $required = false, $multiple = false)
    {
        $this->name = $name;
        $this->id = $id;
        $this->label = $label;
        $this->value = $value;
        $this->accept = $accept;
        $this->placeholder = $placeholder;
        $this->required = $required;
        $this->multiple = $multiple;
    }

    public function render()
    {
        return view('components.image');
    }
}