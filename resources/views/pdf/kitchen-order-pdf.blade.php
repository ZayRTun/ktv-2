<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">

    <title>Kitchen Order</title>

    <link rel="stylesheet" href="{{ asset('css/mpdf.css') }}">

</head>

<body class="text-gray-700" style="background: white">
    <header class="text-center">
        <table border="0" style="width: 100%; border-collapse: collapse">
            <thead>
                <tr>
                    <th class="text-sm font-normal" colspan="4" align="center" class="border-b border-t font-normal">Kitchen Order</th>
                </tr>
                <tr>
                    <th class="text-sm font-normal" align="left" colspan="2">Date: {{ now()->format('Y-m-d') }}</th>
                    <th class="text-sm font-normal" align="right" colspan="2">Time: {{ now()->format('g:i A') }}</th>
                </tr>
                <tr>
                    <th class="text-sm font-normal" align="left" colspan="2" valign="top">Room: {{ $data['room'] }}</th>
                    <th class="text-sm font-normal" align="right" colspan="2" valign="top">Cashier: {{ $data['cashier'] }}</th>
                </tr>
                <tr>
                    <th class="text-sm font-normal" align="left" colspan="2">Invoice: {{ $data['invoice_no'] }}</th>
                    <th class="text-sm font-normal" align="right" colspan="2">Order Time: {{ $data['order_time'] }}</th>
                </tr>

                <tr>
                    <th class="text-sm font-normal border-t border-b" align="left" colspan="3">Item</th>
                    <th class="text-sm font-normal border-t border-b" align="right" colspan="1">Qty</th>
                </tr>
            </thead>
        </table>
    </header>

    <table border="0" style="width: 100%; border-collapse: collapse">
        <tbody class="bg-white text-xs">
            @foreach ($data['order_details'] as $item)
                <tr>
                    <td class="border-dotted-b" colspan="2">{{ $item['food_name'] }} <br> {{ $item['remark'] }}</td>
                    <td class="border-dotted-b" align="right">{{ $item['qty'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
