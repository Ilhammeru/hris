<?php

namespace App\View\Components;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redis;
use Illuminate\View\Component;
use Modules\Recruitment\Events\MessageFromVacancy;

class BaseLayout extends Component
{
    public $action_header;
    public $btn_type;
    public $text;
    public $onclick;
    public $onclick_href;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $action_header = false,
        $btn_type = 'button',
        $text = 'none',
        $onclick = false,
        $onclick_href = 'none'
    )
    {
        $this->action_header = $action_header;
        $this->btn_type = $btn_type;
        $this->text = $text;
        $this->onclick = $onclick;
        $this->onclick_href = $onclick_href;
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
        $message_count_notif = Redis::get('message_count_notif');
        return view('layouts.master')
            ->with([
                'pageTitle' => $pageTitle,
                'menus' => $menus,
                'message_count_notif' => $message_count_notif,
                'has_action_header' => $this->action_header,
                'btn_type' => $this->btn_type,
                'text' => $this->text,
                'onclick' => $this->onclick,
                'onclick_href' => $this->onclick_href
            ]);
    }
}
