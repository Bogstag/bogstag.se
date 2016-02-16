<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Class ApiDataPreview.
 */
class ApiDataPreviewController extends Controller
{
    /**
     * ApiDataPreview constructor.
     *
     * @param Request $request
     *
     * @internal param $id
     */
    public function __construct(Request $request)
    {
        $this->model = $request->route('model');
        $this->model = ucwords($this->model);
        $this->NamespacedModel = '\\App\\'.$this->model;
        $this->id = $request->route('id');
    }

    /**
     * @return $this
     */
    public function index()
    {
        $NamespaceModel = $this->NamespacedModel;

        return view('pages.DataPreview')->with([
            'header' => $this->model,
            'posts'  => $NamespaceModel::limit(10)->get()->toarray(),

        ]);
    }

    /**
     * @return $this
     */
    public function show()
    {
        $NamespacedModel = $this->NamespacedModel;

        return view('pages.DataPreview')->with([
            'header' => $this->model,
            'posts'  => $NamespacedModel::where('id', $this->id)->get()->toarray(),
        ]);
    }
}
