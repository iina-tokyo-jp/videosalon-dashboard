<table class="table table-bordered">
    <thead>
        <tr>
            <th scope="col">ログインID</th>
            <th scope="col">占い師名</th>
            <th scope="col">鑑定価格（分）</th>
            <th scope="col">累計待機時間</th>
            <th scope="col">累計鑑定数</th>
            <th scope="col">累計鑑定時間</th>
            <th scope="col">累計売上</th>
            <th scope="col">累計入金額</th>
            <th scope="col">平均待機時間</th>
            <th scope="col">平均鑑定数</th>
            <th scope="col">平均鑑定時間</th>
            <th scope="col">平均売上</th>
            <th scope="col">平均入金額</th>
            @foreach($days as $day)
            <th scope="col">新規入会者鑑定回数{{ $day }}</th>
            @endforeach
            @foreach($days as $day)
            <th scope="col">新規入会者鑑定時間{{ $day }}</th>
            @endforeach
            @foreach($days as $day)
            <th scope="col">新規入会者鑑定売上{{ $day }}</th>
            @endforeach
            @foreach($days as $day)
            <th scope="col">既存会員鑑定回数{{ $day }}</th>
            @endforeach
            @foreach($days as $day)
            <th scope="col">既存会員鑑定時間{{ $day }}</th>
            @endforeach
            @foreach($days as $day)
            <th scope="col">既存会員鑑定売上{{ $day }}</th>
            @endforeach
            @foreach($days as $day)
            <th scope="col">累計会員鑑定回数{{ $day }}</th>
            @endforeach
            @foreach($days as $day)
            <th scope="col">累計会員鑑定時間{{ $day }}</th>
            @endforeach
            @foreach($days as $day)
            <th scope="col">累計会員鑑定売上{{ $day }}</th>
            @endforeach
            <th scope="col">未収額</th>
        </tr>
    </thead>
    <tbody>
        @foreach($records as $req)
        <tr>
            <td>
                {{ $req->login_id }}
            </td>
            <td>
                {{ $req->appraiser_name }}
            </td>
            <td>
                <!-- {{ number_format($req->unit_point) }} -->
{{ number_format($req->unit_point) }}/ {{ number_format($req->unit_point_purchase) }}
            </td>

            <td>
                {{ Functions::TimeStringToMinutes($req->taiki) }}
            </td>
            <td>
                {{ number_format($req->counts) }}
            </td>
            <td>
                {{ Functions::TimeStringToMinutes($req->exec_time) }}
            </td>
            <td>
                <!-- {{ number_format($req->point) }} -->
                {{ number_format($req->point) }}
            </td>
            <td>
                <!-- {{ number_format($req->nyukin) }} -->
                [----]
            </td>

            <td>
                {{ Functions::TimeStringToMinutes($req->avg_taiki) }}
            </td>
            <td>
                {{ number_format($req->avg_counts) }}
            </td>
            <td>
                {{ Functions::TimeStringToMinutes($req->avg_exec_time) }}
            </td>
            <td>
                {{ number_format($req->avg_point) }}
            </td>
            <td>
                <!-- {{ number_format($req->avg_nyukin) }} -->
                [----]
            </td>

            @for($i = 0; $i < count($days); $i++)
            <td>
                {{ number_format($req->{"count_new{$i}"}) }}
            </td>
            @endfor
            @for($i = 0; $i < count($days); $i++)
            <td>
                {{ Functions::TimeStringToMinutes($req->{"exec_time_new{$i}"}) }}
            </td>
            @endfor
            @for($i = 0; $i < count($days); $i++)
            <td>
                {{ number_format($req->{"point_new{$i}"}) }}
            </td>
            @endfor

            @for($i = 0; $i < count($days); $i++)
            <td>
                {{ number_format($req->{"count_old{$i}"}) }}
            </td>
            @endfor
            @for($i = 0; $i < count($days); $i++)
            <td>
                {{ Functions::TimeStringToMinutes($req->{"exec_time_old{$i}"}) }}
            </td>
            @endfor
            @for($i = 0; $i < count($days); $i++)
            <td>
                {{ number_format($req->{"point_old{$i}"}) }}
            </td>
            @endfor

            @for($i = 0; $i < count($days); $i++)
            <td>
                {{ number_format($req->{"counts{$i}"}) }}
            </td>
            @endfor
            @for($i = 0; $i < count($days); $i++)
            <td>
                {{ Functions::TimeStringToMinutes($req->{"exec_time{$i}"}) }}
            </td>
            @endfor
            @for($i = 0; $i < count($days); $i++)
            <td>
                {{ number_format($req->{"point{$i}"}) }}
            </td>
            @endfor

            <td>
                {{ number_format($req->misyunou) }}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>