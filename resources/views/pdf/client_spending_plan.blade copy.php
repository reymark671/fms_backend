<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Client Spending Plan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .summary-box {
            border: 1px solid #000;
            padding: 10px;
            margin-bottom: 20px;
        }
        .summary-item {
            margin-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }
        th {
            background-color: #f0f0f0;
        }
        .category-header {
            font-weight: bold;
            background-color: #e0e0e0;
        }
        .header_title{
            background-color: #548235;
            color: #f0f0f0;
        }
        .sub-header{
         
            color: #548235;
        }
        .title_top{
            justify-content:space-between;
        }
    </style>
</head>
<body>
    <div class="header">
       <div class="title_top">
            <div class="header_top_logo">
                <h1>test</h1>
            </div>
            <div class="header_top_right">
                <h2>test</h2>
            </div>
       </div>
        <div class="header_title p-4">
        <h1>Spending Summary as of {{ date('m/d/Y', strtotime($clientplan->to)) }}</h1>
        </div>
        <div class="sub-header">
        <h3>Lanterman Regional Center</h3>
        </div>
    </div>

    <div class="summary-box">
        <div class="summary-item">SDP Year: {{ date('F j', strtotime($clientplan->from)) }} - {{ date('j Y', strtotime($clientplan->to)) }}</div>
        <div class="summary-item">Participant Name: {{ $clientplan->client->first_name }} {{ $clientplan->client->last_name }}</div>
        <div class="summary-item">Participant UCI # {{ $clientplan->client->ss_number }}</div>
        <div class="summary-item">Total Budget Approved: {{ number_format($clientplan->total_budget, 2) }}</div>
        <div class="summary-item">Total Budget Approved: {{ number_format($clientplan->total_budget, 2) }}</div>
        <div class="summary-item">Total Budget Used: {{ number_format($clientplan->items->sum('used_budget'), 2) }}</div>
        <div class="summary-item">Total Funds Available: {{ number_format($clientplan->total_budget - $clientplan->items->sum('used_budget'), 2) }}</div>
    </div>

    @foreach ($groupedItems as $categoryId => $items)
        @php
            $category = $items->first()->serviceCode->category;
            $categoryTotal = $items->sum('allocated_budget');
            $categoryUsed = $items->sum('used_budget');
        @endphp
        <div class="category-header">{{ $category->category_description }}: {{ number_format($categoryTotal, 2) }}</div>
        <table>
            <thead>
                <tr>
                    <th>CODE</th>
                    <th>Description</th>
                    <th>Total Budget</th>
                    <th>Total Used</th>
                    <th>Spent Prior Month</th>
                    <th>Pre-Authorize Hold</th>
                    <th>Available Fund</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
                    <tr>
                        <td>{{ $item->serviceCode->code }}</td>
                        <td>{{ $item->serviceCode->service_code_description }}</td>
                        <td>{{ number_format($item->allocated_budget, 2) }}</td>
                        <td>{{ number_format($item->used_budget, 2) }}</td>
                        <td>{{ number_format($item->spent_prior_month, 2) }}</td>
                        <td>{{ number_format($item->pre_authorize_hold, 2) }}</td>
                        <td>{{ number_format($item->allocated_budget - $item->used_budget, 2) }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="2">Total Amount Used:</td>
                    <td>{{ number_format($categoryTotal, 2) }}</td>
                    <td>{{ number_format($categoryUsed, 2) }}</td>
                    <td colspan="2"></td>
                    <td>{{ number_format($categoryTotal - $categoryUsed, 2) }}</td>
                </tr>
            </tbody>
        </table>
    @endforeach
</body>
</html>