<?php

namespace Devsbuddy\LiquidLite\ViewComposers;

use Illuminate\View\View;

class MenuComposer
{
    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\View\View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $menu = \Devsbuddy\LiquidLite\Models\LiquidCrud::select('menu')->get();
        $view->with('liquid_cruds', $menu);
    }
}