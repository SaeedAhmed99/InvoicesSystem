@extends('layouts.master')

@section('title')
الفواتير المدفوعة جزئيا
@stop

@section('css')
<!-- Internal Data table css -->
<link href="{{URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
<!--Internal   Notify -->
<link href="{{URL::asset('assets/plugins/notify/css/notifIt.css')}}" rel="stylesheet"/>
@endsection
@section('page-header')
				<!-- breadcrumb -->
				<div class="breadcrumb-header justify-content-between">
					<div class="my-auto">
						<div class="d-flex">
							<h4 class="content-title mb-0 my-auto">الفواتير</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ قائمة الفواتير المدفوعة جزئيا </span>
						</div>
					</div>
				</div>
				<!-- breadcrumb -->
@endsection
@section('content')

                @if (session()->has('delete_invoice'))
                    <script>
                        window.onload = function() {
                            notif({
                                msg: "تم حذف الفاتورة بنجاح",
                                type: "success"
                            });
                        }
                    </script>
                @endif
                @if (session()->has('trashed_delete'))
                    <script>
                        window.onload = function() {
                            notif({
                                msg: "تم تغيير حالة الدفع بنجاح",
                                type: "success"
                            });
                        }
                    </script>
                @endif
                @if (session()->has('Status_Update'))
                    <script>
                        window.onload = function() {
                            notif({
                                msg: "تم نقل الفاتورة الى سلة المهملات",
                                type: "success"
                            });
                        }
                    </script>
                @endif
				<!-- row -->
				<div class="row">
                        <div class="col-xl-12">
                            <div class="card">
                                    <div class="card-header pb-0">
                                        <div class="">
                                            @can('تصدير EXCEL')
                                            <a href="{{route('export.partial')}}" class="modal-effect btn btn-sm btn-primary" style="color:white"><i
                                            class="fas fa-file"></i>&nbsp; تصدير اكسل</a>
                                            @endcan
                                        </div>
                                    </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table text-md-nowrap" id="example1" data-page-length='50' style="text-align: center">
                                            <thead>
                                                <tr>
                                                    <th class="border-bottom-0">#</th>
                                                    <th class="border-bottom-0">رقم الفاتورة</th>
                                                    <th class="border-bottom-0">تاريخ الفاتورة</th>
                                                    <th class="border-bottom-0">تاريخ الاستحقاق</th>
                                                    <th class="border-bottom-0">القسم</th>
                                                    <th class="border-bottom-0">المنتج</th>
                                                    <th class="border-bottom-0">مبلغ التحصيل</th>
                                                    <th class="border-bottom-0">مبلغ العمولة</th>
                                                    <th class="border-bottom-0">الخصم</th>
                                                    <th class="border-bottom-0">نسبة الضريبة</th>
                                                    <th class="border-bottom-0">قيمة الضريبة</th>
                                                    <th class="border-bottom-0">الاجمالي</th>
                                                    <th class="border-bottom-0">الحالة</th>
                                                    <th class="border-bottom-0">ملاحظات</th>
                                                    <th class="border-bottom-0">العمليات</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                $i = 1
                                                @endphp
                                                @foreach($invoices as $invoice)
                                                    <tr>
                                                        <td>{{$i}}</td>
                                                        <td>
                                                            <a href="{{route('invoice.details.show', $invoice->id)}}">{{$invoice->invoice_number}}</a>
                                                        </td>
                                                        <td>{{$invoice->invoice_Date}}</td>
                                                        <td>{{$invoice->Due_date}}</td>
                                                        <td>{{$invoice->section->section_name}}</td>
                                                        <td>{{$invoice->product->product_name}}</td>
                                                        <td>{{$invoice->Amount_collection}}</td>
                                                        <td>{{$invoice->Amount_Commission}}</td>
                                                        <td>{{$invoice->Discount}}</td>
                                                        <td>{{$invoice->Rate_VAT}}</td>
                                                        <td>{{$invoice->Value_VAT}}</td>
                                                        <td>{{$invoice->Total}}</td>
                                                        <td>
                                                        @if ($invoice->Value_Status == 1)
                                                            <span class="text-success">{{ $invoice->Status }}</span>
                                                        @elseif($invoice->Value_Status == 2)
                                                            <span class="text-danger">{{ $invoice->Status }}</span>
                                                        @else
                                                            <span class="text-warning">{{ $invoice->Status }}</span>
                                                        @endif
                                                        </td>
                                                        <td>{{$invoice->note}}</td>
                                                        <td>
                                                            <div class="dropdown">
                                                                <button aria-expanded="false" aria-haspopup="true"
                                                                    class="btn ripple btn-primary btn-sm" data-toggle="dropdown"
                                                                    type="button">العمليات<i class="fas fa-caret-down ml-1"></i></button>
                                                                <div class="dropdown-menu tx-13">
                                                                    @can('تعديل الفاتورة')
                                                                    <a class="dropdown-item"
                                                                        href="{{route('invoices.edit', $invoice->id)}}">
                                                                        <i class="text-success fas fa-pen-alt"></i>&nbsp;&nbsp;
                                                                        تعديل الفاتورة</a>
                                                                    @endcan

                                                                    @can('حذف الفاتورة')
                                                                    <a class="dropdown-item" href="#" data-invoice_id="{{ $invoice->id }}"
                                                                        data-toggle="modal" data-target="#delete_invoice"><i
                                                                            class="text-danger fas fa-trash-alt"></i>&nbsp;&nbsp;حذف
                                                                        الفاتورة</a>
                                                                    @endcan

                                                                    @can('تغير حالة الدفع')
                                                                    <a class="dropdown-item"
                                                                        href="{{route('status.edit.show', $invoice->id)}}"><i
                                                                            class=" text-success fas fa-money-bill"></i>&nbsp;&nbsp;تغير
                                                                        حالة
                                                                        الدفع</a>
                                                                    @endcan

                                                                    @can('طباعةالفاتورة')
                                                                    <a class="dropdown-item" href="{{route('print.invoice', $invoice->id)}}"><i
                                                                            class="text-success fas fa-print"></i>&nbsp;&nbsp;طباعة
                                                                        الفاتورة
                                                                    </a>
                                                                    @endcan
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @php
                                                        $i++
                                                    @endphp
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
				</div>
                    <!-- حذف الفاتورة -->
                <div class="modal fade" id="delete_invoice" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">حذف الفاتورة</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <form action="{{route('invoice.soft.delete')}}" method="post">
                                    @csrf
                                    @method('post')
                            </div>
                            <div class="modal-body">
                                هل انت متاكد من عملية الحذف ؟   
                                <input type="hidden" name="invoice_id" id="invoice_id" value="">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">الغاء</button>
                                <button type="submit" class="btn btn-danger" name='force_delete'>حذف نهائي</button>
                                <button type="submit" class="btn btn-danger" name='trash_delete'>نقل الى سلة المهملات</button>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
				<!-- row closed -->
			</div>
			<!-- Container closed -->
		</div>
		<!-- main-content closed -->
@endsection
@section('js')
<!-- Internal Data tables -->
<script src="{{URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/responsive.dataTables.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/jquery.dataTables.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/jszip.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/pdfmake.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/vfs_fonts.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.html5.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.print.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js')}}"></script>
<!--Internal  Datatable js -->
<script src="{{URL::asset('assets/js/table-data.js')}}"></script>
<!--Internal  Notify js -->
<script src="{{URL::asset('assets/plugins/notify/js/notifIt.js')}}"></script>
<script src="{{URL::asset('assets/plugins/notify/js/notifit-custom.js')}}"></script>
<script>
    $('#delete_invoice').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget)
        var invoice_id = button.data('invoice_id')
        var modal = $(this)
        modal.find('.modal-body #invoice_id').val(invoice_id);
    })  
</script>
@endsection