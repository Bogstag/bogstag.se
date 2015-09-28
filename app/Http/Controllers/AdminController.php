<?php namespace App\Http\Controllers;

/**
 * Class AdminController
 * @package App\Http\Controllers
 */
class AdminController extends Controller
{

    /**
     * Initializer.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

}
