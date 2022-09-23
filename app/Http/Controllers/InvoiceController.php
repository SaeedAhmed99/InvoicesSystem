<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoicesDetials;
use App\Models\InvoiceAttachments;
use App\Models\Section;
use App\Models\products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;
use App\Notifications\InvoiceEmail;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invoices = Invoice::all();
        return view('invoices.invoices', compact('invoices'));
    }

    public function invoicesPaid(){
        $invoices = Invoice::where('Value_Status', 1)->get();
        return view('invoices.invoices_paid', compact('invoices'));
    }

    public function invoicesUnPaid(){
        $invoices = Invoice::where('Value_Status', 2)->get();
        return view('invoices.invoices_paid', compact('invoices'));
    }

    public function invoicesPartial(){
        $invoices = Invoice::where('Value_Status', 3)->get();
        return view('invoices.invoices_paid', compact('invoices'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response    
     */
    public function create()
    {
        $sections = Section::all();
        return view('invoices.add_invoice', compact('sections'));
    }

    public function getproducts($id)
    {
        $section = Section::findOrFail($id);
        if ($section) {
            $products =  $section->products;
            return json_encode($products);
        }else{
            session()->flash('Error', 'يرجى التاكد من البيانات المدخلة');
            return redirect()->back();
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $invoice = Invoice::create([
            'invoice_number' => $request->invoice_number,
            'invoice_Date' => $request->invoice_Date,
            'Due_date' => $request->Due_date,
            'section_id' => $request->Section,
            'product_id' => $request->product,
            'Amount_collection' => $request->Amount_collection,
            'Amount_Commission' => $request->Amount_Commission,
            'Discount' => $request->Discount,
            'Value_VAT' => $request->Value_VAT,
            'Rate_VAT' => $request->Rate_VAT,
            'Total' => $request->Total,
            'Status' => 'غير مدفوعة',
            'Value_Status' => 2,
            'note' => $request->note,
        ]);

        // $invoice_id = Invoice::latest()->first()->id;
        InvoicesDetials::create([
            'invoice_number' => $request->invoice_number,
            'product' => products::find($request->product)->product_name,
            'Section' => Section::find($request->Section)->section_name,
            'Status' => 'غير مدفوعة',
            'Value_Status' => 2,
            'note' => $request->note,
            'user' => (Auth::user()->name),
            'invoice_id' => $invoice->id
        ]);

        if ($request->hasFile('pic')) {

            // $invoice_id = Invoices::latest()->first()->id;
            $image = $request->file('pic');
            $file_name = $image->getClientOriginalName();
            $invoice_number = $request->invoice_number;

            $attachments = new InvoiceAttachments();
            $attachments->file_name = $file_name;
            $attachments->invoice_number = $invoice_number;
            $attachments->Created_by = Auth::user()->name;
            $attachments->invoice_id = $invoice->id;
            $attachments->save();

            // move pic
            $imageName = $request->pic->getClientOriginalName();
            $request->pic->move(public_path('Attachments/invoiceAttachments'), $imageName);
        }

        
        session()->flash('Add', 'تم انشاء الفاتورة بنجاح');
        return redirect()->route('invoices');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function show(Invoice $invoice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $invoices = Invoice::findOrFail($id);
        $sections = Section::all();
        return view('invoices.edit_invoice', compact('invoices', 'sections'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // return $request->product;
        $invoices = Invoice::findOrFail($request->invoice_id);
        $invoices->update([
            'invoice_number' => $request->invoice_number,
            'invoice_Date' => $request->invoice_Date,
            'Due_date' => $request->Due_date,
            'product' => $request->product,
            'section_id' => $request->Section,
            'Amount_collection' => $request->Amount_collection,
            'Amount_Commission' => $request->Amount_Commission,
            'Discount' => $request->Discount,
            'Value_VAT' => $request->Value_VAT,
            'Rate_VAT' => $request->Rate_VAT,
            'Total' => $request->Total,
            'note' => $request->note,
        ]);

        $invoice_details = $invoices->details;
        $invoice_details->update([
            'invoice_number' => $request->invoice_number,
            'product' => products::find($request->product)->product_name,
            'Section' => Section::find($request->Section)->section_name,
            'Status' => 'غير مدفوعة',
            'Value_Status' => 2,
            'note' => $request->note,
            'user' => (Auth::user()->name),
            'invoice_id' => $invoices->id
        ]);

        session()->flash('Add', 'تم تعديل الفاتورة بنجاح');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $invoice = Invoice::findOrFail($request->invoice_id);
        if ($request->has('trash_delete')){
            $invoice->delete();
            session()->flash('trashed_delete');
            return redirect()->back();
        }elseif($request->has('force_delete')){
            $invoice_attachments = InvoiceAttachments::where('invoice_id', $request->invoice_id)->get();
            foreach($invoice_attachments as $attach){
                Storage::disk('public_uploads')->delete($attach->file_name);
            }
            $invoice->forceDelete();
            session()->flash('delete_invoice');
            return redirect()->back();
        }else{
            return redirect()->back();
        }
    }

    public function trashed_invoices(){
        $invoices = Invoice::onlyTrashed()->get();
        return view('invoices.invoice_trashed', compact('invoices'));
    }

    public function forceDeleteInvoice(Request $request){
        if ($request->has('force_delete')){
            $invoice = Invoice::onlyTrashed()->findOrFail($request->invoice_id);
            $invoice_attachments = InvoiceAttachments::where('invoice_id', $invoice->invoice_id)->get();
            foreach($invoice_attachments as $attach){
                Storage::disk('public_uploads')->delete($attach->file_name);
            }
            $invoice->forceDelete();
            session()->flash('delete_invoice');
            return redirect()->back();
        }else{
            return redirect()->back();
        }
    }

    public function recoveryInvoice(Request $request){
        if($request->has('trashed_recovery')){
            $invoice = Invoice::onlyTrashed()->findOrFail($request->invoice_id);
            $invoice->restore();
            session()->flash('restore_invoice');
            return redirect()->back();
        }else{
            return redirect()->back();
        }
    }

    public function statusEditShow($id){
        $invoices = Invoice::findOrFail($id);
        return view('invoices.status_update', compact('invoices'));
    }

    public function statusUpdateShow(Request $request){
        $invoices = Invoice::findOrFail($request->invoice_id);

        if ($request->Status == 1) {
            $invoices->update([
                'Value_Status' => $request->Status,
                'Status' => 'مدفوعة',
                'Payment_Date' => $request->Payment_Date,
            ]);

            InvoicesDetials::create([
                'invoice_id' => $request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'Section' => $request->Section,
                'Status' => 'مدفوعة',
                'Value_Status' => $request->Status,
                'note' => $request->note,
                'payment_date' => $request->Payment_Date,
                'user' => (Auth::user()->name),
            ]);
            session()->flash('Status_Update');
        }

        elseif ($request->Status == 3) {
            $invoices->update([
                'Value_Status' => $request->Status,
                'Status' => 'مدفوعة جزئيا',
                'Payment_Date' => $request->Payment_Date,
            ]);
            InvoicesDetials::create([
                'invoice_id' => $request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'Section' => $request->Section,
                'Status' => 'مدفوعة جزئيا',
                'Value_Status' => $request->Status,
                'note' => $request->note,
                'payment_date' => $request->Payment_Date,
                'user' => (Auth::user()->name),
            ]);
            session()->flash('Status_Update');
        }
        return redirect()->route('invoices');
    }

    public function printInvoice($id){
        $invoices = Invoice::findOrFail($id);
        return view('invoices.print_invoice', compact('invoices'));
    }

}
