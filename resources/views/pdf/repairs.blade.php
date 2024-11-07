<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BMY Mobile Store</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #f4f4f4;
        }
        .invoice-box {
            width: 80%;
            margin: auto;
            padding: 20px;
            background: white;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header, .footer {
            text-align: center;
        }
        h1, h2, h3 {
            margin: 0;
        }
        .header h1 {
            color: #333;
        }
        .info, .device-info, .charges {
            width: 100%;
            margin: 20px 0;
        }
        .info td, .device-info td, .charges td {
            padding: 5px 0;
        }
        .table-header {
            font-weight: bold;
            background: #eee;
        }
        .text-right {
            text-align: right;
        }
        .totals {
            width: 100%;
            margin-top: 20px;
        }
        .totals td {
            padding: 5px 0;
        }
        .totals .total-label {
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="invoice-box">
    <div class="header">
        <h1>BMY Store</h1>
        <p>Address: Shop No 4/41 Kiran Path Madhyam Marg, Mansarovar Sector 4, near Kiran Path, Chauraha | City:jaipur | State:Rajasthan | Zip Code:302020 
        <p>Email: BMY@gmail.com | Phone: 080585 82021</p>
    </div>

    <hr>

    <table class="info">
        <tr>
            <td><strong>Invoice No:</strong> INV-00123</td>
            <td class="text-right"><strong>Date:</strong> {{ (new DateTime)->format('d-m-Y') }}</td>
        </tr>
        <tr>
            <td><strong>Customer ID:</strong> {{ $repair->customer_id ?? '' }} </td>
        </tr>
        <tr>
            <td><strong>Customer Name:</strong> {{ $repair->customer_name ?? '' }} </td>
        </tr>
        <tr>
            <td><strong>Contact:</strong> {{ $repair->customer_contact ?? '' }} </td>
        </tr>
    </table>

    <table class="device-info">
        <tr class="table-header">
            <td>id</td>
            <td>Device Brand</td>
            <td>Model</td>
            <td>Issue</td>
            <td>Date<td>
        </tr>
        <tr>
            @php

                $brand = App\Models\Brand::select('name')->where('id', $repair->brand_id)->first();
                $dueAmount  =  $repair->estimated_cost - $repair->received_amount;
            @endphp
         
                <tr>
                    <td>{{ $repair->id ?? '' }}</td>
                    <td>{{ $brand->name ?? '' }}</td>
                    <td>{{ $repair->device_model ?? '' }}</td>
                    <td>{{ $repair->issue ?? '' }}</td>
                    <td>{{ $repair->date_time ?? '' }}</td>
                </tr>
 
        </tr>
    </table>

    <table class="charges">
        <tr class="table-header">
            <td>Description</td>
            <td>Amount</td>
        </tr>
        <tr>
            <td>Estimated Cost</td>
            <td class="text-right"> {{ $repair->estimated_cost ?? '0.00' }} </td>
        </tr>
        <tr>
            <td>Received Amount</td>
            <td class="text-right"> {{ $repair->received_amount ?? '0.00' }} </td>
        </tr>
    </table>

    <table class="totals">
        <tr>
            <td class="total-label">Total Amount Due:</td>
            <td class="text-right">{{  number_format($dueAmount ?? 0,2) }}</td>
        </tr>
    </table>

    <div class="footer">
        <p>Thank you for choosing Mobile Store!</p>
        <p><strong>Note:</strong> Payment is due upon receipt.</p>
    </div>
</div>

</body>
</html>
