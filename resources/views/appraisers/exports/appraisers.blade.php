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
        @php($total = count($appraisers))
        @foreach($appraisers as $key => $appraiser)
        <tr>
            <td>
                {{ $appraiser->user->info->email }}
            </td>
            <td>
                {{ $appraiser->name }}
            </td>
            <td>
                {{ $appraiser->user->info->last_login_date->format('Y/m/d H:i') }}
            </td>
            <td>
                {{ number_format($appraiser->point) }}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>