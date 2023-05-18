<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datas = Product::all();
        $contents = array(
            array(
                'field'     =>  'image',
                'label'     =>  'Image',
                'type'      =>  'image',
            ),
            array(
                'field'     =>  'product_name',
                'label'     =>  'Product Name',
                'type'      =>  'title',
            ),
            array(
                'field'     =>  'price',
                'ref_field' =>  [
                    'currency',
                    'discount'
                ],
                'label'     =>  'Price',
                'type'      =>  'price',
            ),
        );
        $view_options = array(
            'table_class_override'      =>  'table-bordered table-striped table-responsive-stack',
            'enable_add'                =>  false,
            'enable_delete'             =>  false,
            'enable_edit'               =>  false,
            'enable_action'             =>  true,
            'enable_buy'                =>  true,
            'enable_detail'             =>  true,
        );
        return view('page.content.index-card-lists')
        ->with('datas', $datas)
        ->with('contents', $contents)
        ->with('view_options', $view_options);
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
