<table class="table table-bordered">
    <thead>
        <tr>
            <th scope="col">状態</th>
            <th scope="col">コード</th>
            <th scope="col">出稿内容</th>
            <th scope="col">出稿開始日</th>
            <th scope="col">最終入会日</th>
            @foreach($days as $day)
            <th scope="col">新規会員数{{ $day }}</th>
            @endforeach
            @foreach($days as $day)
            <th scope="col">新規入会者売上合計{{ $day }}</th>
            @endforeach
            @foreach($days as $day)
            <th scope="col">新規入会者売上（クレジット）{{ $day }}</th>
            @endforeach
            @foreach($days as $day)
            <th scope="col">新規入会者売上（銀行振込）{{ $day }}</th>
            @endforeach
            @foreach($days as $day)
            <th scope="col">新規入会者売上（他）{{ $day }}</th>
            @endforeach
            @foreach($days as $day)
            <th scope="col">既存会員数{{ $day }}</th>
            @endforeach
            @foreach($days as $day)
            <th scope="col">既存会員売上合計{{ $day }}</th>
            @endforeach
            @foreach($days as $day)
            <th scope="col">既存会員売上（クレジット）{{ $day }}</th>
            @endforeach
            @foreach($days as $day)
            <th scope="col">既存会員売上（銀行振込）{{ $day }}</th>
            @endforeach
            @foreach($days as $day)
            <th scope="col">既存会員売上（他）{{ $day }}</th>
            @endforeach
            @foreach($days as $day)
            <th scope="col">累積会員数{{ $day }}</th>
            @endforeach
            @foreach($days as $day)
            <th scope="col">累積売上合計{{ $day }}</th>
            @endforeach
            @foreach($days as $day)
            <th scope="col">累積売上（クレジット）{{ $day }}</th>
            @endforeach
            @foreach($days as $day)
            <th scope="col">累積売上（銀行振込）{{ $day }}</th>
            @endforeach
            @foreach($days as $day)
            <th scope="col">累積売上（他）{{ $day }}</th>
            @endforeach
            <th scope="col">広告単価</th>
            <th scope="col">広告費用</th>
            <th scope="col">未収額</th>
            <th scope="col">売上率</th>
            <th scope="col">回収率</th>
        </tr>
    </thead>
    <tbody>
        @foreach($records as $req)
        <tr>
            <td>
                @switch($req->status)
                @case(0)
                無効
                @break
                @case(1)
                有効
                @break
                @endswitch
            </td>
            <td>
                {{ $req->ad_code }}
            </td>
            <td>
                {{ $req->site_name }}
            </td>
            <td>
                {{ date_create($req->start_date)->format('Y/m/d H:i') }}
            </td>
            <td>
                {{ date_create($req->last_date)->format('Y/m/d H:i') }}
            </td>
            @for($i = 0; $i < count($days); $i++)
            <td>
                {{ number_format($req->{"sum{$i}"}) }}
            </td>
            @endfor
            @for($i = 0; $i < count($days); $i++)
            <td>
                {{ number_format($req->{"point{$i}"}) }}
            </td>
            @endfor
            @for($i = 0; $i < count($days); $i++)
            <td>
                {{ number_format($req->{"point{$i}"}) }}
            </td>
            @endfor
            @for($i = 0; $i < count($days); $i++)
            <td>
                {{ number_format(0) }}
            </td>
            @endfor
            @for($i = 0; $i < count($days); $i++)
            <td>
                {{ number_format(0) }}
            </td>
            @endfor

            @for($i = 0; $i < count($days); $i++)
            <td>
                {{ number_format($req->{"sumold{$i}"}) }}
            </td>
            @endfor
            @for($i = 0; $i < count($days); $i++)
            <td>
                {{ number_format($req->{"pointold{$i}"}) }}
            </td>
            @endfor
            @for($i = 0; $i < count($days); $i++)
            <td>
                {{ number_format($req->{"pointold{$i}"}) }}
            </td>
            @endfor
            @for($i = 0; $i < count($days); $i++)
            <td>
                {{ number_format(0) }}
            </td>
            @endfor
            @for($i = 0; $i < count($days); $i++)
            <td>
                {{ number_format(0) }}
            </td>
            @endfor

            @for($i = 0; $i < count($days); $i++)
            <td>
                {{ number_format($req->{"sumall{$i}"}) }}
            </td>
            @endfor
            @for($i = 0; $i < count($days); $i++)
            <td>
                {{ number_format($req->{"pointall{$i}"}) }}
            </td>
            @endfor
            @for($i = 0; $i < count($days); $i++)
            <td>
                {{ number_format($req->{"pointall{$i}"}) }}
            </td>
            @endfor
            @for($i = 0; $i < count($days); $i++)
            <td>
                {{ number_format(0) }}
            </td>
            @endfor
            @for($i = 0; $i < count($days); $i++)
            <td>
                {{ number_format(0) }}
            </td>
            @endfor

            <td>
                {{ number_format($req->unit_price) }}
            </td>
            <td>
                {{ number_format($req->cost_summary) }}
            </td>
            <td>
                {{ number_format(0) }}
            </td>
            <td>
                {{ number_format($req->rate) }}
            </td>
            <td>
                {{ number_format($req->rate) }}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>