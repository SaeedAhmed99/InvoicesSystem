<?php

namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SectionController extends Controller
{
    function __construct()
{
    $this->middleware('permission:الاقسام', ['only' => ['index']]);
    $this->middleware('permission:اضافة قسم', ['only' => ['store']]);
    $this->middleware('permission:تعديل قسم', ['only' => ['update']]);
    $this->middleware('permission:حذف قسم', ['only' => ['destroy']]);
}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sections = Section::all();
        return view('sections.sections', compact('sections'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->has('save_section')) {
            $validator  = Validator::make($request->all(),[
                'section_name' => 'required|unique:sections,section_name|max:255',
            ],[
                'section_name.required' =>'يرجي ادخال اسم القسم',
                'section_name.unique' =>'اسم القسم مسجل مسبقا',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
    
            Section::create([
                'section_name' => $request->section_name,
                'description' => $request->description,
                'Created_by' => (Auth::user()->name),
            ]);
            session()->flash('Add', 'تم اضافة القسم بنجاح ');
            return redirect()->back();
        }else{
            session()->flash('Error', 'يرجى التاكد من البيانات المدخلة');
            return redirect()->back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if ($request->has('edit_data')) {
            $section = Section::findOrFail($request->id);

            if ($section) {
                $validator  = Validator::make($request->all(),[
                    'section_name' => 'required||max:255',
                    'id' => 'required',
                ],[
                    'section_name.required' =>'يرجي ادخال اسم القسم',
                ]);
    
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }

                $section->update($request->all());
        
                session()->flash('Add', 'تم تعديل القسم بنجاح');
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
     * @param  \App\Models\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if ($request->has('delete_data')) {
            $section = Section::findOrFail($request->id);
            if ($section) {
                $products = $section->products;
                foreach ($products as $product) {
                    $product->delete();
                }
                $section->delete();
                session()->flash('Delete', 'تم حذف القسم بنجاح');
                return redirect()->back();
            }
            
        }else{
            session()->flash('Error', 'يرجى التاكد من صحة العملية');
            return redirect()->back();
        }
    }
}
