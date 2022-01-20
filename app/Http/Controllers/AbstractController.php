<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

abstract class AbstractController extends Controller
{
    public function __construct() {
        $this->class = (new \ReflectionClass(get_called_class()))->getShortName();

        $this->model = get_model_name_from_controller($this->class);

        $this->resource = $this->model . 'Resource';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
    }

    /**
     * Create a query builder for a model based on request input
     * @param  Request $request
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function buildIndexQuery(Request $request)
    {
        $model = $this->model;
        $query = $model::select('*');

        // set the order field and direction
        $this->applyOrderByToQuery($query, $request);

        // filter records if there is a query
        if ($this->searching) {
            $this->applyKeywordsToQuery($query, (count($this->searchFields) ? $this->searchFields : $this->indexFields), $request->keywords);
        }

        $this->applyDefaultFilters($query, $request);


        return $query;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
