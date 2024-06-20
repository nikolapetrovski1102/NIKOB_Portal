<table>
    <tbody>
        <tr></tr>
        <tr></tr>
        <tr></tr>
        <tr>
            <td></td>
            <td colspan="2">ДАТУМ НА УПЛАТА: {{ \Carbon\Carbon::now()->format('d.m.Y') }}</td>
        </tr>
        <tr></tr>
        <tr>
            <td id="empty"> </td>
            <td><br>ШИФРА НА КУПУВАЧ/БРОЈ НА<br> КЛИЕНТ<br></td>
            <td><br>НАЗИВ НА КУПУВАЧ/КЛИЕНТ<br></td>
            <td><br>БРОЈ НА ФАКТУРА<br></td>
            <td><br>ИЗНОС НА УПЛАТА<br></td>
            <td><br>ДАТУМ, ЧАС И МИНУТА НА <br>ИЗВРШЕНА УПЛАТА<br></td>
            <td id="end"> </td>
        </tr>
        @foreach($transactions as $transaction)
        @php 
            $invoices = [];
            foreach($transaction['invoices'] as $invoice) {
                $invoices[] = $invoice['invoice_id'];
            }
        @endphp 
        <tr>
            <td></td>
            <td><br>{{$transaction['accountNum']}}</td>
            <td><br>
                @isset($transaction['user'][0])
                    {{$transaction['user'][0]['name'].' '.$transaction['user'][0]['surname']}}
                @else
                    {{$transaction['notify']}}
                @endisset
            </td>
            <td><br>{{implode(', ', $invoices)}}</td>
            <td><br>{{ number_format($transaction['amount'], 2)  }} ден</td>
            <td><br>{{ \Carbon\Carbon::parse($transaction['created_at'])->addHours(1)->format('d-m-Y H:i:s')}}</td>
            <td></td>
        </tr>
        @endforeach
    </tbody>
</table>