@foreach ($item['receipts'] as $receipt)
    <div class="m-b-lg">
        @if (isset($receipt['receipt']['document']['receipt']['user']))
            <p><strong>Юридическое лицо: </strong>{{ $receipt['receipt']['document']['receipt']['user'] }}</p>
        @endif

        @if (isset($receipt['receipt']['document']['receipt']['userInn']))
            <p><strong>ИНН: </strong>{{ $receipt['receipt']['document']['receipt']['userInn'] }}</p>
        @endif

        @if (isset($receipt['receipt']['document']['receipt']['retailPlace']))
            <p><strong>Место покупки: </strong>{{ $receipt['receipt']['document']['receipt']['retailPlace'] }}</p>
        @endif

        @if (isset($receipt['receipt']['document']['receipt']['retailPlaceAddress']))
            <p><strong>Адрес: </strong>{{ $receipt['receipt']['document']['receipt']['retailPlaceAddress'] }}</p>
        @endif
    </div>

    <div class="table-responsive">
        <table class="table table-hover">
            <tbody>
                <tr>
                    <th>Продукт</th>
                    <th>Количество</th>
                    <th>Сумма</th>
                </tr>
                @foreach ($receipt['receipt']['document']['receipt']['items'] ?? [] as $productItem)
                    <tr>
                        <td>{{ $productItem['name'] }}</td>
                        <td>{{ $productItem['quantity'] }}</td>
                        <td>{{ $productItem['sum'] / 100 }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <hr>
@endforeach
