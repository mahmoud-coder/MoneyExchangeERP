<table class="table table-bordered table-hover table-sm">
    <thead>
        <tr>
            <th>Code</th>
            <th>Name</th>
            <th>Revenues</th>
            <th>Expenses</th>
        </tr>
    </thead>
    <tbody>
        @foreach($revenues as $account)
        <tr>
            <td>{{ $account->code }}</td>
            <td>{{ $account->name }}</td>
            <td>{{ $account->sum }}</td>
            <td></td>
        </tr>
        @endforeach
        @foreach($expenses as $account)
        <tr>
            <td>{{ $account->code }}</td>
            <td>{{ $account->name }}</td>
            <td></td>
            <td>{{ $account->sum }}</td>
        </tr>
        @endforeach
    </tbody>
</table>