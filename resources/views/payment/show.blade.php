<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payment Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            padding: 40px;
        }
        .card {
            max-width: 600px;
            margin: auto;
            background: #fff;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        }
        h1 {
            margin-top: 0;
        }
        .row {
            margin-bottom: 12px;
        }
        .label {
            font-weight: bold;
        }
        .status {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 8px;
            background: #eee;
        }
    </style>
</head>
<body>
    <div class="card">
        @if(session('success'))
            <div style="margin-bottom:15px;padding:10px;background:#d4edda;color:#155724;border-radius:6px;">
                {{ session('success') }}
            </div>
        @endif

        <h1>Payment Page</h1>

        <div class="row">
            <span class="label">Invoice ID:</span>
            {{ $payment->invoice_id }}
        </div>

        <div class="row">
            <span class="label">Invoice Number:</span>
            {{ $payment->invoice_number }}
        </div>

        <div class="row">
            <span class="label">Customer:</span>
            {{ $payment->customer_name ?? '-' }}
        </div>

        <div class="row">
            <span class="label">Email:</span>
            {{ $payment->customer_email ?? '-' }}
        </div>

        <div class="row">
            <span class="label">Amount:</span>
            {{ number_format($payment->amount, 2) }} {{ $payment->currency }}
        </div>

        <div class="row">
            <span class="label">Payment Reference:</span>
            {{ $payment->payment_reference }}
        </div>

        <div class="row">
            <span class="label">Status:</span>
            <span class="status" style="
                @if($payment->status === 'paid')
                    background:#d4edda;color:#155724;
                @elseif($payment->status === 'failed')
                    background:#f8d7da;color:#721c24;
                @elseif($payment->status === 'pending')
                    background:#fff3cd;color:#856404;
                @else
                    background:#eee;color:#333;
                @endif
            ">
                {{ $payment->status }}
            </span>
        </div>

        <div style="margin-top:20px;">
            @if($payment->status === 'paid')
                <button disabled style="padding:10px 16px;background:#ccc;color:#666;border:none;border-radius:6px;">
                    Already Paid
                </button>
            @else
            <form method="POST" action="/pay/{{ $payment->payment_reference }}/checkout">
                @csrf
                    <button type="submit" style="padding:10px 16px;background:#4CAF50;color:#fff;border:none;border-radius:6px;">
                        Pay Now
                    </button>
            </form>
            @endif
        </div>
    </div>

    @if($payment->status !== 'paid')
        <script>
            setTimeout(() => location.reload(), 3000);
        </script>
    @endif
</body>
</html>