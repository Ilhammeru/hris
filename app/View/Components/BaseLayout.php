<?php

namespace App\View\Components;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redis;
use Illuminate\View\Component;

class BaseLayout extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $pageTitle = Redis::get('page_title');
        $menus = json_decode(Redis::get('menus'), TRUE);
        return view('layouts.master', compact('pageTitle', 'menus'));
    }
}
