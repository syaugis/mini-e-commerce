<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FormInput extends Component
{
    public string $name;
    public string $id;
    public string $label;
    public ?string $value;
    public string $placeholder;
    public bool $required;
    public string $type;

    /**
     * Create a new component instance.
     *
     * @param string $name
     * @param string $id
     * @param string $label
     * @param string|null $value
     * @param string $placeholder
     * @param bool $required
     * @param string $type
     */
    public function __construct(
        string $name,
        string $id,
        string $label,
        ?string $value = null,
        string $placeholder = '',
        bool $required = false,
        string $type = 'text'
    ) {
        $this->name = $name;
        $this->id = $id;
        $this->label = $label;
        $this->value = $value;
        $this->placeholder = $placeholder;
        $this->required = $required;
        $this->type = $type;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.form-input');
    }
}
