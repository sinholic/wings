<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Process\Process;

class ProductController extends Controller
{
    public $name           =   'Product';
    public $back_from_list =   'products.index';
    public $back_from_form =   'products.index';

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
            ),
            array(
                'field'     =>  'product_code',
                'label'     =>  'SKU',
            ),
            array(
                'field'     =>  'product_name',
                'label'     =>  'Product Name',
            ),
            array(
                'field'     =>  'currency',
                'label'     =>  'Currency',
            ),
            array(
                'field'     =>  'price',
                'label'     =>  'Price',
            ),
            array(
                'field'     =>  'discount',
                'label'     =>  'Discount',
            ),
            array(
                'field'     =>  'dimensions',
                'label'     =>  'Dimension',
            ),
            array(
                'field'     =>  'unit',
                'label'     =>  'Unit',
            ),
        );
        $view_options = array(
            'table_class_override'      =>  'table-bordered table-striped table-responsive-stack',
            'enable_add'                =>  true,
            'enable_delete'             =>  true,
            'enable_edit'               =>  true,
            'enable_action'             =>  true,
        );
        return view('page.content.index')
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
        $contents   = array(
            array(
                'field'     =>  'image',
                'type'      =>  'text',
                'label'     =>  'Image',
            ),
            array(
                'field'     =>  'product_code',
                'type'      =>  'text',
                'label'     =>  'SKU',
            ),
            array(
                'field'     =>  'product_name',
                'type'      =>  'text',
                'label'     =>  'Product Name',
            ),
            array(
                'field'     =>  'currency',
                'type'      =>  'text',
                'label'     =>  'Currency',
            ),
            array(
                'field'     =>  'price',
                'type'      =>  'currency',
                'label'     =>  'Price',
            ),
            array(
                'field'     =>  'discount',
                'type'      =>  'number',
                'label'     =>  'Discount',
            ),
            array(
                'field'     =>  'dimensions',
                'type'      =>  'text',
                'label'     =>  'Dimension',
            ),
            array(
                'field'     =>  'unit',
                'type'      =>  'text',
                'label'     =>  'Unit',
            ),
        );
        return view('page.content.add')
        ->with('contents', $contents);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'image'                         =>  'required',
                'product_code'                  =>  'required',
                'product_name'                  =>  'required',
                'currency'                      =>  'required',
                'price'                         =>  'required',
                'discount'                      =>  'required',
                'dimensions'                    =>  'required',
                'unit'                          =>  'required',
            ]
        );
        try {
            // Memulai transaksi database
            DB::transaction(function () use ($request) {
                $data = $request->all();
                Product::create($data);
            });
            return redirect()->route($this->back_from_form)->withSuccess("$this->name has been Added Successfully");
        } catch (\Exception $e) {
            // Tangani kesalahan
            // Jika deadlock terjadi, tangani sesuai kebutuhan
            if (strpos($e->getMessage(), 'deadlock detected')) {
                // Tangani deadlock timeout 5 detik
                sleep(5);
                return $this->consumeData($request); // Mencoba kembali
            }

            // Tangani kesalahan lainnya
            return redirect()->route($this->back_from_form)->withDanger("Cannot add $this->name at this moment");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $contents   = array(
            array(
                'field'     =>  'image',
                'type'      =>  'text',
                'label'     =>  'Image',
            ),
            array(
                'field'     =>  'product_code',
                'type'      =>  'text',
                'label'     =>  'SKU',
            ),
            array(
                'field'     =>  'product_name',
                'type'      =>  'text',
                'label'     =>  'Product Name',
            ),
            array(
                'field'     =>  'currency',
                'type'      =>  'text',
                'label'     =>  'Currency',
            ),
            array(
                'field'     =>  'price',
                'type'      =>  'currency',
                'label'     =>  'Price',
            ),
            array(
                'field'     =>  'discount',
                'type'      =>  'number',
                'label'     =>  'Discount',
            ),
            array(
                'field'     =>  'dimensions',
                'type'      =>  'text',
                'label'     =>  'Dimension',
            ),
            array(
                'field'     =>  'unit',
                'type'      =>  'text',
                'label'     =>  'Unit',
            ),
        );
        return view('page.content.edit')
        ->with('model', $product)
        ->with('contents', $contents);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $request->validate(
            [
                'image'                         =>  'required',
                'product_code'                  =>  'required',
                'product_name'                  =>  'required',
                'currency'                      =>  'required',
                'price'                         =>  'required',
                'discount'                      =>  'required',
                'dimensions'                    =>  'required',
                'unit'                          =>  'required',
            ]
        );
        try {
            // Memulai transaksi database
            DB::transaction(function () use ($request, $product){
                $data = $request->all();
                $product->update($data);
            });
            return redirect()->route($this->back_from_form)->withSuccess("$this->name has been Edited Successfully");
        } catch (\Exception $e) {
            // Tangani kesalahan
            // Jika deadlock terjadi, tangani sesuai kebutuhan
            if (strpos($e->getMessage(), 'deadlock detected')) {
                // Tangani deadlock timeout 5 detik
                sleep(5);
            }

            // Tangani kesalahan lainnya
            return redirect()->route($this->back_from_form)->withDanger("Cannot edit $this->name at this moment");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        if (!$product) {
            return redirect()->route($this->back_from_form)->withWarning("$this->name Not Found / has been Deleted");
        }

        try {
            // Memulai transaksi database
            DB::transaction(function () use ($product){
                $product->delete();
            });
            return redirect()->route($this->back_from_form)->withSuccess("$this->name has been Added Successfully");
        } catch (\Exception $e) {
            // Tangani kesalahan
            // Jika deadlock terjadi, tangani sesuai kebutuhan
            if (strpos($e->getMessage(), 'deadlock detected')) {
                // Tangani deadlock timeout 5 detik
                sleep(5);
            }

            // Tangani kesalahan lainnya
            return redirect()->route($this->back_from_form)->withDanger("Cannot edit $this->name at this moment");
        }

        return redirect()->route($this->back_from_form)->withSuccess("$this->name has been Deleted Successfully");
    }
}
