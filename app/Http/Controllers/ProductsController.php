<?php

namespace App\Http\Controllers;

use App\Models\products;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductsController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:المنتجات', ['only' => ['index']]);
        $this->middleware('permission:اضافة منتج', ['only' => ['store']]);
        $this->middleware('permission:تعديل منتج', ['only' => ['update']]);
        $this->middleware('permission:حذف منتح', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sections = Section::all();
        $products = products::all();
        return view('products.products', compact('sections', 'products'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->has('save_product')) {
            $validator  = Validator::make($request->all(),[
                'product_name' => 'required|max:255',
                'section_id' => 'required',
            ],[
                'product_name.required' =>'يرجي ادخال اسم المنتج',
                'section_id.required' =>'يرجى تحديد اسم القسم',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
    
            products::create([
                'product_name' => $request->product_name,
                'description' => $request->description,
                'section_id' => $request->section_id,
            ]);
            session()->flash('Add', 'تم اضافة المنتج بنجاح ');
            return redirect()->back();
        }else{
            session()->flash('Error', 'يرجى التاكد من البيانات المدخلة');
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\products  $products
     * @return \Illuminate\Http\Response
     */
    public function show(products $products)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\products  $products
     * @return \Illuminate\Http\Response
     */
    public function edit(products $products)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\products  $products
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if ($request->has('edit_product')) {
            $product = products::findOrFail($request->pro_id);

            if ($product) {
                $validator  = Validator::make($request->all(),[
                    'pro_id' => 'required',
                    'product_name' => 'required|max:255',
                    'section_id' => 'required',
                ],[
                    'product_name.required' =>'يرجي ادخال اسم المنتج',
                    'section_id.required' =>'يرجى تحديد اسم القسم',
                ]);
    
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }

                $product->update($request->all());
        
                session()->flash('Add', 'تم تعديل المنتج بنجاح');
                return redirect()->back();
            }
            
        }else{
            session()->flash('Error', 'يرجى التاكد من البيانات المدخلة');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\products  $products
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if ($request->has('delete_data')) {
            $product = products::findOrFail($request->id);
            if ($product) {
                $product->delete();
                session()->flash('Delete', 'تم حذف المنتج بنجاح');
                return redirect()->back();
            }
            
        }else{
            session()->flash('Error', 'يرجى التاكد من صحة العملية');
            return redirect()->back();
        }
    }
}
