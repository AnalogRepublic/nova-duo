<?php

namespace AnalogRepublic\NovaDuo;

use Illuminate\Http\Request;
use Laravel\Nova\Tool;

class NovaDuo extends Tool
{
    /**
     * Perform any tasks that need to happen when the tool is booted.
     *
     * @return void
     */
    public function boot()
    {
        // Unused
    }

    /**
     * Build the view that renders the navigation links for the tool.
     *
     * @return \Illuminate\View\View
     */
    public function menu(Request $request)
    {
        // Unused
    }
}
