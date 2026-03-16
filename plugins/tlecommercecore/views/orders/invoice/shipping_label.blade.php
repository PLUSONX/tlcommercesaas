<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 10px; }
        body {
            font-family: '{{ $font_family }}', sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        td { padding: 8px; vertical-align: top; }
        .bordered td { border: 1px solid #dee2e6; }
        .logo-img { max-width: 150px; height: auto; }
        .barcode-img { width: 100%; height: 60px; display: block; }
        .qr-code-img { width: 120px; height: 120px; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .section-title {
            background-color: #f0f0f0;
            font-weight: bold;
            padding: 5px 8px;
            border: 1px solid #dee2e6;
        }
        .label { font-weight: bold; color: #555; }
        .divider { border-top: 2px dashed #000; margin: 8px 0; }
        .outer-border { border: 2px solid #000; padding: 5px; }
    </style>
</head>
<body>
<div class="outer-border">

    {{-- ======= HEADER: Logo + Store Info + Date ======= --}}
    <table class="bordered">
        <tr>
            <td width="30%">
                @if (!empty($order_info['system_properties']['logo']))
                    <img src="{{ $order_info['system_properties']['logo'] }}" class="logo-img">
                @else
                    <h3>{{ $order_info['system_properties']['title'] ?? '' }}</h3>
                @endif
            </td>
            <td width="40%">
                <p class="label">{{ $order_info['system_properties']['title'] ?? '' }}</p>
                <p>{{ $order_info['system_properties']['address'] ?? '' }}</p>
                <p>{{ $order_info['system_properties']['phone'] ?? '' }}</p>
                <p>{{ $order_info['system_properties']['email'] ?? '' }}</p>
            </td>
            <td width="30%" class="text-right">
                <p class="label">Date:</p>
                <p>{{ $order_info['date'] }}</p>
                <p class="label">Order #:</p>
                <p>{{ $order_info['order_code'] }}</p>
            </td>
        </tr>
    </table>

    {{-- ======= ORDER CODE BARCODE ======= --}}
    <table>
        <tr>
            <td class="text-center" style="border: 1px solid #dee2e6;">
                <img src="{{ $order_code_bar_code }}" class="barcode-img">
                <p>{{ $order_info['order_code'] }}</p>
            </td>
        </tr>
    </table>

    <div class="divider"></div>

    {{-- ======= RECIPIENT + QR CODE ======= --}}
    <table class="bordered">
        <tr>
            <td width="65%">
                <p class="section-title">SHIP TO</p>
                <p><span class="label">Name:</span> {{ $order_info['shipping_info']['name'] ?? $order_info['customer_name'] }}</p>
                <p><span class="label">Address:</span> {{ $order_info['shipping_info']['address'] ?? 'N/A' }}</p>
                <p><span class="label">City:</span> {{ $order_info['shipping_info']['city'] ?? 'N/A' }}</p>
                <p><span class="label">State:</span> {{ $order_info['shipping_info']['state'] ?? 'N/A' }}</p>
                <p><span class="label">Country:</span> {{ $order_info['shipping_info']['country'] ?? 'N/A' }}</p>
                @if (!empty($order_info['shipping_info']['postal_code']))
                    <p><span class="label">Postal Code:</span> {{ $order_info['shipping_info']['postal_code'] }}</p>
                @endif
                <p><span class="label">Phone:</span> {{ $order_info['shipping_info']['phone'] ?? 'N/A' }}</p>
            </td>
            <td width="35%" class="text-center">
                <img src="{{ $qr_code }}" class="qr-code-img">
                <p>Scan for details</p>
            </td>
        </tr>
    </table>

    <div class="divider"></div>

    {{-- ======= TRACKING BARCODE ======= --}}
    <!-- <table>
        <tr>
            <td class="text-center" style="border: 1px solid #dee2e6;">
                <img src="{{ $tracking_id_bar_code }}" class="barcode-img">
                <p><span class="label">Tracking ID:</span> {{ $order_info['tracking_id'] }}</p>
            </td>
        </tr>
    </table>

    <div class="divider"></div> -->

    {{-- ======= SHIPMENT DETAILS ======= --}}
    <table class="bordered">
        <tr>
            <td colspan="2" class="section-title">SHIPMENT DETAILS</td>
        </tr>
        <tr>
            <td width="50%">
                <p><span class="label">Shipping Type:</span> {{ $order_info['shipping_type'] ?? 'N/A' }}</p>
                <p><span class="label">Shipping Method:</span> {{ $order_info['shipping_method'] ?? 'N/A' }}</p>
                <p><span class="label">Shipping Zone:</span> {{ $order_info['shipping_zone'] ?? 'N/A' }}</p>
            </td>
            <td width="50%">
                <p><span class="label">Payment Method:</span> {{ $order_info['payment_method'] ?? 'N/A' }}</p>
                <p><span class="label">No. of Products:</span> {{ $order_info['num_of_products'] ?? 'N/A' }}</p>
                <p><span class="label">Total Weight:</span> {{ $order_info['total_product_weight'] ?? '0' }} kg</p>
            </td>
        </tr>
    </table>

    {{-- ======= TOTAL AMOUNT ======= --}}
    <table class="bordered">
        <tr>
            <td class="text-right">
                <span class="label" style="font-size: 14px;">Total Payable Amount:</span>
                <span style="font-size: 16px; font-weight: bold; font-family: '{{ $currency_font }}';">
                    {{ $order_info['total_payable_amount'] }}
                </span>
            </td>
        </tr>
    </table>

</div>
</body>
</html>