<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    <title>Order Details | Nadimah Tailor</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i%7CQuicksand:300,400,500,700" rel="stylesheet">
    
    <!-- Styles -->
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/material.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/components.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/bootstrap-extended.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/app.css')}}">

    <style>
        body {
            background-color: #f8f9fa;
            padding: 20px;
            margin: 0;
        }
        .order-details-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            max-width: 800px;
            margin: 20px auto;
            padding: 30px;
        }
        .content-wrapper {
            display: flex;
            gap: 30px;
        }
        .details-section {
            flex: 1;
        }
        .image-section {
            width: 300px;
            padding-top: 20px;
        }
        .order-header {
            margin-bottom: 25px;
        }
        .detail-row {
            margin-bottom: 15px;
        }
        .detail-label {
            font-weight: 600;
            color: #666;
            margin-bottom: 3px;
        }
        .detail-value {
            color: #333;
        }
        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 4px;
            font-weight: 500;
            background-color: #28a745;
            color: white;
        }
        .order-image {
            width: 100%;
            height: 300px;
            object-fit: cover;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .success-view {
            text-align: center;
            padding: 20px;
        }
        .thank-you {
            color: #00d1b2;
            font-size: 2.5rem;
            margin-bottom: 10px;
            font-weight: 600;
        }
        .message {
            color: #666;
            font-size: 1.2rem;
            margin-bottom: 30px;
        }
        .appreciation {
            color: #666;
            font-style: italic;
            margin-top: 30px;
        }
        .thank-you-page {
            text-align: center;
            padding: 40px 20px;
        }
        .thank-you-title {
            color: #00d1b2;
            font-size: 3rem;
            margin-bottom: 20px;
        }
        .thank-you-message {
            color: #666;
            font-size: 1.2rem;
            margin-bottom: 40px;
        }
        .order-details-section {
            margin: 30px auto;
            max-width: 600px;
            text-align: left;
        }
        .order-details-title {
            color: #333;
            font-size: 1.5rem;
            margin-bottom: 20px;
            text-align: center;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 15px;
            border-bottom: 1px solid #eee;
        }
        .detail-label {
            color: #666;
            font-weight: 600;
        }
        .appreciation-message {
            color: #666;
            font-style: italic;
            margin-top: 40px;
        }
    </style>
</head>
<body>
    <div class="order-details-card">
        <div class="order-header">
            <h2>Order Details</h2>
        </div>
        
        <div class="order-details-section">
            <div class="detail-row">
                <span class="detail-label">Order Date</span>
                <span>{{ $order->received_on }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Description</span>
                <span>{{ $order->description }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Amount</span>
                <span>RM {{ number_format($order->amount_charged, 2) }}</span>
            </div>
        </div>
    </div>
</body>
</html> 