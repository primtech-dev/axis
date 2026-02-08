<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Bukti Pembayaran Hutang Biaya</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        td { padding: 4px; vertical-align: top; }
        .border td { border: 1px solid #000; }
        .text-right { text-align: right; }
    </style>
</head>
<body onload="window.print()">

<h3>BUKTI PEMBAYARAN HUTANG BIAYA</h3>

<table>
    <tr>
        <td width="140">No Biaya</td>
        <td>: {{ $expense->expense_number }}</td>
    </tr>
    <tr>
        <td>Supplier</td>
        <td>: {{ $expense->supplier->name }}</td>
    </tr>
    <tr>
        <td>Tanggal Bayar</td>
        <td>: {{ \Carbon\Carbon::parse($payment->date)->format('Y-m-d') }}</td>
    </tr>
    <tr>
        <td>Akun Pembayaran</td>
        <td>:
            @if ($payment->cashAccount)
                {{ $payment->cashAccount->code }} - {{ $payment->cashAccount->name }}
            @else
                -
            @endif
        </td>
    </tr>
</table>

<table class="border">
    <tr>
        <td>Jumlah Dibayar</td>
        <td class="text-right">
            <strong>{{ number_format($payment->amount, 2, ',', '.') }}</strong>
        </td>
    </tr>
</table>

<p style="margin-top: 30px;">
    Dicetak pada {{ now()->format('Y-m-d H:i') }}
</p>

</body>
</html>
