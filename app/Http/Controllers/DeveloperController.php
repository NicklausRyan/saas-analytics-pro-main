<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DeveloperController extends Controller
{    /**
     * Show the Combined Developer Documentation page.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return redirect()->route('developers');
    }
    
    /**
     * Show the Combined Developer Documentation page with all sections.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function combined()
    {
        return view('developers.combined');
    }

    /**
     * Show the Developer Stats page.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function stats()
    {
        return view('developers.stats.index');
    }

    /**
     * Show the Developer Websites page.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function websites()
    {
        return view('developers.websites.index');
    }

    /**
     * Show the Developer Account page.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function account()
    {
        return view('developers.account.index');
    }
}
