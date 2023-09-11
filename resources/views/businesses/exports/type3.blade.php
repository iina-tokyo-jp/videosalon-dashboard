<table class="table table-bordered">
    <thead>
        <tr>
            @foreach($days as $day)
            <th scope="col">新規入会者鑑定数{{ $day }}</th>
            @endforeach
            @foreach($days as $day)
            <th scope="col">新規入会者鑑定時間{{ $day }}</th>
            @endforeach
            @foreach($days as $day)
            <th scope="col">新規入会者売上{{ $day }}</th>
            @endforeach
            @foreach($days as $day)
            <th scope="col">既存会員鑑定数{{ $day }}</th>
            @endforeach
            @foreach($days as $day)
            <th scope="col">既存会員鑑定時間{{ $day }}</th>
            @endforeach
            @foreach($days as $day)
            <th scope="col">既存会員売上{{ $day }}</th>
            @endforeach
            @foreach($days as $day)
            <th scope="col">累計会員鑑定数{{ $day }}</th>
            @endforeach
            @foreach($days as $day)
            <th scope="col">累計会員鑑定時間{{ $day }}</th>
            @endforeach
            @foreach($days as $day)
            <th scope="col">累計会員売上{{ $day }}</th>
            @endforeach
            <th scope="col">累計未収額</th>
            <th scope="col">累計広告費</th>
            <th scope="col">累計売上率</th>
            <th scope="col">累計回収率</th>
        </tr>
    </thead>
    <tbody>
        @foreach($records as $req)
        <tr>
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
            <td>
                {{ number_format($req->cost) }}
            </td>
            <td>
                {{ $req->sales_rate }}
            </td>
            <td>
                {{ $req->recovery_rate }}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>