<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Orders;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(){
        $title = "Dashboard";

        $pieChart = app()->chartjs
                ->name('pieChart')
                ->type('pie')
                ->size(['width' => 400, 'height' => 200])
                ->labels(['Total Customers', 'Customers Orders'])
                ->datasets([
                    [
                        'backgroundColor' => ['#36A2EB','#7bb13c','#FF6384'],
                        'hoverBackgroundColor' => ['#36A2EB','#7bb13c','#FF6384'],
                        'data' => [Customer::count(),Orders::count()]
                    ]
                ])
                ->options([]);
        return view('dashboard',compact(
            'title','pieChart',
        ));
    }
}