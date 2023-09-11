<table class="table table-bordered">
    <thead>
        <tr>
            <th scope="col">状態</th>
            <th scope="col">コード</th>
            <th scope="col">出稿内容</th>
            <th scope="col">出稿開始日</th>
            <th scope="col">最終入会日</th>
            @foreach($days as $day)
            <th scope="col">新規入会数{{ $day }}</th>
            @endforeach
            @foreach($days as $day)
            <th scope="col">売上合計{{ $day }}</th>
            @endforeach
            @foreach($days as $day)
            <th scope="col">費用合計{{ $day }}</th>
            @endforeach
            <th scope="col">新規入会数(期間平均)</th>
            <th scope="col">売上合計(期間平均)</th>
            <th scope="col">費用合計(期間平均)</th>
        </tr>
    </thead>
    <tbody>
        @foreach($records as $req)
        <tr>
            <td>
                @if (empty($req->ad_code) && !empty($req->referer_type))
                {{ '' }}
                @else
                    @switch($req->status)
                        @case(0)
                        無効
                        @break
                        @case(1)
                        有効
                        @break
                    @endswitch
                @endif
            </td>
            <td>
                {{ $req->ad_code }}
            </td>
            <td>
                @if (empty($req->ad_code))
                {{ '直接入会' }}：{{ $req->referer_type }}
                @else
                {{ $req->site_name }}
                @endif
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
                {{ number_format($req->{"cost{$i}"}) }}
            </td>
            @endfor
            <td>
                {{ number_format($req->count_average) }}
            </td>
            <td>
                {{ number_format($req->point_average) }}
            </td>
            <td>
                {{ number_format($req->cost_average) }}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>