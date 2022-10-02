<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $all = Invoice::count();
        $paid = round(Invoice::where('Value_Status', 1)->count() / $all * 100);
        $unpaid = round(Invoice::where('Value_Status', 2)->count() / $all * 100);
        $patial = round(Invoice::where('Value_Status', 3)->count() / $all * 100);

        $chartjs = app()->chartjs
         ->name('barChartTest')
         ->type('bar')
         ->size(['width' => 400, 'height' => 200])
         ->labels(['Label x'])
         ->datasets([
             [
                 "label" => "الفواتير المدفوعة",
                 'backgroundColor' => ['#FA7070'],
                 'data' => [25]
             ],
             [
                 "label" => "الفواتير الغير مدفوعة",
                 'backgroundColor' => ['#400D51'],
                 'data' => [50]
             ],
             [
                 "label" => "الفواتير المدفوعة جزئيا",
                 'backgroundColor' => ['#367E18'],
                 'data' => [40]
             ]
         ])
         ->options([]);
        return view('home', compact('chartjs'));
    }
}
