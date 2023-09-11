<table class="table table-bordered">
    <thead>
    <tr>
        <th scope="col">No</th>
        <th scope="col">アクション</th>
        <th scope="col">実行ID</th>
        <th scope="col">適用日時</th>
    </tr>
    </thead>
    <tbody>
    @php($total = count($logs))
    @foreach($logs as $key => $log)
        <tr>
            <th scope="row">{{ $total - $key }}</th>
            <td>
                {{ $log->title }}
            </td>
            <td>
                {{ $log->user->login_id }}
            </td>
            <td>
                {{ $log->add_date->format('Y/m/d H:i') }}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
