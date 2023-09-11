<table class="table table-bordered">
    <thead>
        <tr>
            <th scope="col">占い師ID</th>
            <th scope="col">占い師名</th>
            <th scope="col">待機時間(分)</th>
            <th scope="col">休憩時間(分)</th>
            <th scope="col">鑑定数</th>
            <th scope="col">即時</th>
            <th scope="col">予約鑑定(予約数)</th>
            <th scope="col">予約鑑定(実鑑定数)</th>
            <th scope="col">ビデオ総鑑定時間(分)</th>
            <th scope="col">ビデオ平均鑑定時間(分)</th>
            <th scope="col">音声総鑑定時間(分)</th>
            <th scope="col">音声平均鑑定時間(分)</th>
            <th scope="col">エラー終了数</th>
            <th scope="col">ブログ執筆数</th>
            <th scope="col">売上(税込)</th>
            <th scope="col">売上(税抜)</th>
        </tr>
    </thead>
    <tbody>
        @foreach($records as $req)
        <tr>
            <td>
                @if (empty($req->appraiser_id))
                    {{ '' }}
                @else
                    {{ $req->appraiser_id }}
                @endif
            </td>
            <td>
                {{ $req->appraiser_name }}
            </td>
            <td>
                {{ number_format($req->stb_time) }}
            </td>
            <td>
                {{ number_format($req->rest_time) }}
            </td>
            <td>
                {{ number_format($req->appraisal_count) }}
            </td>
            <td>
                {{ number_format($req->rightnow_count) }}
            </td>
            <td>
                {{ number_format($req->reserve_count) }}
            </td>
            <td>
                {{ number_format($req->active_count) }}
            </td>
            <td>
                {{ number_format($req->video_time) }}
            </td>
            <td>
                {{ number_format($req->video_avg_time) }}
            </td>
            <td>
                {{ number_format($req->sound_time) }}
            </td>
            <td>
                {{ number_format($req->sound_avg_time) }}
            </td>
            <td>
                {{ number_format($req->error_count) }}
            </td>
            <td>
                {{ number_format($req->review_count) }}
            </td>
            <td>
                {{ number_format($req->blog_count) }}
            </td>
            <td>
                {{ number_format($req->sales_wtax) }}
            </td>
            <td>
                {{ number_format($req->sales_wotax) }}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>