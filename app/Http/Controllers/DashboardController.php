<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Orders;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(){
        $title = "Dashboard";

        // Get counts
        $totalUsers = DB::table('customers')->count();
        $completedOrders = DB::table('order_histories')->count();
        $activeOrders = DB::table('orders')
            ->where('status', 'in_progress')
            ->count();
        $retentionCount = DB::table('orders')
            ->whereNotNull('paid_at')
            ->where('status', 'paid')
            ->where('link_status', 'active')
            ->whereNotNull('access_token')
            ->count();

        // For bar chart
        $barChart = app()->chartjs
                ->name('barChart')
                ->type('bar')
                ->size(['width' => 800, 'height' => 400])
                ->labels(['Total Customers', 'Completed Orders', 'Active Orders', 'Order Retention'])
                ->datasets([
                    [
                        'label' => ' ',
                        'backgroundColor' => [
                            '#7bb13c',
                            '#1E9FF2',
                            '#FF9149',
                            '#FF4961'
                        ],
                        'hoverBackgroundColor' => [
                            '#7bb13c',
                            '#1E9FF2',
                            '#FF9149',
                            '#FF4961'
                        ],
                        'data' => [$totalUsers, $completedOrders, $activeOrders, $retentionCount]
                    ]
                ])
                ->options([
                    'scales' => [
                        'yAxes' => [
                            [
                                'ticks' => [
                                    'beginAtZero' => true
                                ]
                            ]
                        ]
                    ],
                    'responsive' => true,
                    'maintainAspectRatio' => false,
                    'legend' => [
                        'display' => false
                    ]
                ]);

        return view('dashboard',compact(
            'title',
            'barChart',
            'totalUsers',
            'completedOrders',
            'activeOrders',
            'retentionCount'
        ));
    }
}