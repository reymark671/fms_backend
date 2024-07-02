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
            table-layout: fixed;
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
        .header_title {
            background-color: #548235;
            color: #f0f0f0;
        }
        .sub-header {
            color: #548235;
        }
        .title_top {
            justify-content: space-between;
        }
    </style>
</head>
<body>
    <div class="header">
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
        <div class="summary-item">Total Budget Used: {{ number_format($grandTotalAllocated, 2) }}</div>
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
                    <th style="width: 5%;">No.</th>
                    <th style="width: 10%;">CODE</th>
                    <th style="width: 25%;">Service Code Description</th>
                    <th style="width: 20%;">Category</th>
                    <th style="width: 15%;">Total Budget</th>
                </tr>
            </thead>
            <tbody>
                @php 
                $count = 0;
                @endphp
                @foreach ($items as $item)
                @php 
                $count++;
                @endphp
                    <tr>
                        <td>{{ $count }}</td>
                        <td>{{ $item->serviceCode->code }}</td>
                        <td>{{ $item->serviceCode->service_code_description }}</td>
                        <td>{{ $category->category_description }}</td>
                        <td>{{ number_format($item->allocated_budget, 2) }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="4">Total Budget:</td>
                    <td>{{ number_format($categoryTotal - $categoryUsed, 2) }}</td>
                </tr>
            </tbody>
        </table>
    @endforeach

    <table>
        <thead>
            <tr>
                <th colspan="4">Total Approved Budget:</th>
                <th>{{ number_format($clientplan->total_budget, 2) }}</th>
            </tr>
            <tr>
                <th colspan="4">Grand Total Allocated Budget:</th>
                <th>{{ number_format($grandTotalAllocated, 2) }}</th>
            </tr>
            <tr>
                <th colspan="4">Grand Total Funds Available:</th>
                <th>{{ number_format($clientplan->total_budget - $grandTotalAllocated, 2) }}</th>
            </tr>
        </thead>
    </table>
</body>
</html>
