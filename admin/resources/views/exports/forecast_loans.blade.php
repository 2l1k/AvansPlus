<table>
    <thead>
    <tr>
        <th>ID заявки</th>
        <th>Фамилия</th>
        <th>Имя</th>
        <th>Отчество</th>
        <th>Дата заявки</th>
        <th>Период</th>
        <th>Статус</th>
        <th>Сумма займа</th>
        <th>Сумма к возврату</th>
        <th>Чистая прибыль</th>
        <th>Временная точка</th>
    </tr>
    </thead>
    <tbody>
    @foreach($loans as $loan)
        <tr>
            <td>{{ $loan->id }}</td>
            <td>{{ $loan->borrower->lastname }}</td>
            <td>{{ $loan->borrower->firstname }}</td>
            <td>{{ $loan->borrower->fathername }}</td>
            <td>{{ $loan->created_at }}</td>
            <td>{{ $loan->duration_actual }} / {{ $loan->duration_agreement }}</td>
            <td>{{ $loan->loanStatus->text }}</td>
            <td>{{ $loan->sum }}</td>
            <td>{{ $loan->amount_maturity }}</td>
            <td>{{ $loan->net_profit }}</td>
            <td>{{ $loan->timepoint }}</td>
        </tr>
    @endforeach
    </tbody>
    <tbody>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>{{ $totals["total_sum_by_issued"] }}</td>
            <td>{{ $totals["total_sum_maturity"] }}</td>
            <td>{{ $totals["total_net_profit"] }}</td>
            <td></td>
        </tr>
    </tbody>
</table>