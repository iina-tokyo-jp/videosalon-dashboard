<table class="table table-bordered">
    <thead>
        <tr>
            <th scope="col">ユーザー名</th>
            <th scope="col">氏名</th>
            <th scope="col">最終ログイン</th>
            <th scope="col">ポイント</th>
        </tr>
    </thead>
    <tbody>
        @php($total = count($userinfos))
        @foreach($userinfos as $key => $userinfo)
        <tr>
            <td>
                {{ $userinfo->email }}
            </td>
            <td>
                {{ $userinfo->name }}
            </td>
            <td>
                {{ $userinfo->last_login_date->format('Y/m/d H:i') }}
            </td>
            <td>
                {{ number_format($userinfo->point) }}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>