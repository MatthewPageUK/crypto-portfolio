<?php

namespace App\View\Components;

use Illuminate\View\Component;

class AppLayout extends Component
{

    /**
     * Page title
     * 
     * @var string
     */
    public $title;

    /**
     * Create the component instance.
     *
     * @param  string  $title
     * @return void
     */
    public function __construct(string $title)
    {
        $this->title = $title;
    }
    
    /**
     * Get the view / contents that represents the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('layouts.app');
    }
}
