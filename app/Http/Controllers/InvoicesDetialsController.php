<?php

namespace App\Http\Controllers;

use App\Models\InvoicesDetials;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\InvoiceAttachments;
use Illuminate\Support\Facades\Storage;

class InvoicesDetialsController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:حذف المرفق', ['only' => ['delete_attachment']]);
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
     * @param  \App\Models\InvoicesDetials  $invoicesDetials
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $invoice = Invoice::where('id', $id)->first();
        if($invoice){
            $attachments = InvoiceAttachments::where('invoice_id', $id)->get();
        }else{
            return view('404');
        }
        $details = InvoicesDetials::where('invoice_id', $id)->get();
        return view('invoices.invoice_details', compact('invoice', 'details', 'attachments'));
    }

    public function show_attachment($attachment_name){
        $file = Storage::disk('public_uploads')->getDriver()->getAdapter()->applyPathPrefix($attachment_name);
        return response()->file($file);
    }

    public function download_attachment($attachment_name){
        $file = Storage::disk('public_uploads')->getDriver()->getAdapter()->applyPathPrefix($attachment_name);
        return response()->download($file);
    }

    public function delete_attachment(Request $request){
        if($request->has('delete_attach')){
            $attachment = InvoiceAttachments::findOrFail($request->id_file);
            $attachment->delete();
            Storage::disk('public_uploads')->delete($request->file_name);
            session()->flash('Delete', 'تم حذف المرفق بنجاح');
            return redirect()->back();
        }else{
            session()->flash('Error', 'يرجى التاكد من البيانات');
            return redirect()->back();
        }
    }


    public function add_new_attachment(Request $request){
        return $request;
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\InvoicesDetials  $invoicesDetials
     * @return \Illuminate\Http\Response
     */
    public function edit(InvoicesDetials $invoicesDetials)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\InvoicesDetials  $invoicesDetials
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InvoicesDetials $invoicesDetials)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\InvoicesDetials  $invoicesDetials
     * @return \Illuminate\Http\Response
     */
    public function destroy(InvoicesDetials $invoicesDetials)
    {
        //
    }
}
