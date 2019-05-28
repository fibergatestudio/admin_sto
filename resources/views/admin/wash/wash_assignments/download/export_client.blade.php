<table>
    <thead>
    <tr>
        <th>Имя</th>
        <th>Телефон</th>
        <th>Тип Клиента</th>
        <th>Название фирмы</th>
        <th>Название банка</th>
        <th>Код банка</th>
        <th>Юридический адрес</th>
        <th>Банковский счет</th>
        <th>Фиксальный код</th>
        <th>Код НДС</th>
        <th>Код НДС</th>
    </tr>
    </thead>
    <tbody>
    @foreach($car_wash_clients as $client)
        <tr>
            <td>{{ $client->name }}</td>
            <td>{{ $client->phone }}</td>
            <td>{{ $client->client_type }}</td>
            <td>{{ $client->firm_name }}</td>
            <td>{{ $client->bank_name }}</td>
            <td>{{ $client->bank_code }}</td>
            <td>{{ $client->legal_address }}</td>
            <td>{{ $client->bank_account }}</td>
            <td>{{ $client->fiscal_code }}</td>
            <td>{{ $client->VAT_code }}</td>
            <td><img src="http://www.site-dev.com/storage/employee/1/nXm9whVvynUfY6buGfROJS9un3Q0sWEofBxLwE2d.jpeg"></td>
        </tr>
    @endforeach
    </tbody>
</table>