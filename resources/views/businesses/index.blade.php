@extends('layouts.app')
@section('nav_name', '事業管理')
@section('content')
    <div class="mt-3">
        <form>
            <div class="form-row">
                <div class="col-md-6 mb-3">
                    <select class="custom-select" name="type">
                        @if (request()->user()->authority != 3)
                        <option value="0" {{app('request')->input('type') == '0' ? 'selected' : ''}}>広告動向</option>
                        <option value="1" {{app('request')->input('type') == '1' ? 'selected' : ''}}>広告動向詳細</option>
                        @endif
                        @if (request()->user()->authority != 4)
                        <option value="2" {{app('request')->input('type') == '2' ? 'selected' : ''}}>占い師動向詳細</option>
                        @endif
                        <option value="3" {{app('request')->input('type') == '3' ? 'selected' : ''}}>サイト全体情報</option>
                    </select>
                </div>
                <div class="col-md-6 d-flex mb-3">
                    <input id="start_date" type="date" class="form-control" name="start_date"
                           value="{{ app('request')->input('start_date') }}" required>
                    <span class="mx-2"> ～ </span>
                    <input id="end_date" type="date" class="form-control" name="end_date"
                           value="{{ app('request')->input('end_date') }}" required>
                </div>
                <div class="col-12 search-error-area d-none">
                    <div class="alert alert-danger" role="alert">
                        <div id="search-error-msg"></div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <button type="submit" class="btn btn-primary mb-2">検索</button>
                    <button id="btnCsvDl" class="btn btn-primary mb-2">CSV DL</button>
                </div>
            </div>
        </form>

        @if (!(empty(app('request')->input('start_date')) ||
               empty(app('request')->input('end_date'))))
            <div>
                <div class="table-responsive">
                    <table class="table table-bordered" id="find-table">

                    @if (app('request')->input('type') == '0')

                        <thead>
                            <tr>
                                <th scope="col" rowspan="2">状態</th>
                                <th scope="col" rowspan="2">出稿内容/コード</th>
                                <th scope="col" rowspan="2">出稿開始日</th>
                                <th scope="col" rowspan="2">最終入会日</th>
                                <th scope="col" colspan="{{ count($days) }}">新規入会数</th>
                                <th scope="col" colspan="{{ count($days) }}">売上合計</th>
                                <th scope="col" colspan="{{ count($days) }}">費用合計</th>
                                <th scope="col" rowspan="2">新規入会数(期間平均)</th>
                                <th scope="col" rowspan="2">売上合計(期間平均)</th>
                                <th scope="col" rowspan="2">費用合計(期間平均)</th>
                            </tr>
                            <tr>
                            @foreach($days as $day)
                                <th>{{ $day }}</th>
                            @endforeach
                            @foreach($days as $day)
                                <th>{{ $day }}</th>
                            @endforeach
                            @foreach($days as $day)
                                <th>{{ $day }}</th>
                            @endforeach
                        </thead>

                        <tbody>
                        @foreach($records as $record)
                            <tr>
                                <td class="h5">
                        @if (!empty($record->ad_code))
                            @switch($record->status)
                                @case(0)
                                    <span class="badge badge-danger">無効</span>
                                    @break
                                @case(1)
                                    <span class="badge badge-primary">有効</span>
                                    @break
                            @endswitch
                        @endif
                                </td>
                                <td>
                                @if (empty($record->ad_code))
                                    {{ '直接入会' }}：{{ $record->referer_type }}
                                @else
                                    {{ $record->site_name }}：{{ $record->ad_code }}
                                @endif
                                </td>
                                <td>{{ date_create($record->start_date)->format('Y/m/d') }}</td>
                                <td>{{ $record->last_date ? date_create($record->last_date)->format('Y/m/d H:i') : '' }}</td>
                            @for($i = 0; $i < count($days); $i++)
                                <td class="text-right">{{ number_format($record->{"sum{$i}"}) }}</td>
                            @endfor
                            @for($i = 0; $i < count($days); $i++)
                                <td class="text-right">{{ number_format($record->{"point{$i}"}) }}円</td>
                            @endfor
                            @for($i = 0; $i < count($days); $i++)
                                <td class="text-right">{{ number_format($record->{"cost{$i}"}) }}円</td>
                            @endfor
                                <td class="text-right">{{ number_format($record->count_average) }}</td>
                                <td class="text-right">{{ number_format($record->point_average) }}円</td>
                                <td class="text-right">{{ number_format($record->cost_average) }}円</td>
                            </tr>
                        @endforeach
                        </tbody>


                    @elseif (app('request')->input('type') == '1')

                        <thead>
                            <tr>
                                <th scope="col" rowspan="2">状態</th>
                                <th scope="col" rowspan="2">出稿内容/コード</th>
                                <th scope="col" rowspan="2">出稿開始日</th>
                                <th scope="col" rowspan="2">最終入会日</th>
                                <th scope="col" colspan="{{ count($days) }}">新規入会数</th>
                                <th scope="col" colspan="{{ count($days) }}">新規入会者売上合計</th>
                                <th scope="col" colspan="{{ count($days) }}">新規入会者売上(クレジットカード)</th>
                                <th scope="col" colspan="{{ count($days) }}">新規入会者売上(銀行振込)</th>
                                <th scope="col" colspan="{{ count($days) }}">新規入会者売上(他)</th>
                                <th scope="col" colspan="{{ count($days) }}">既存会員数</th>
                                <th scope="col" colspan="{{ count($days) }}">既存会員売上合計</th>
                                <th scope="col" colspan="{{ count($days) }}">既存会員売上(クレジットカード)</th>
                                <th scope="col" colspan="{{ count($days) }}">既存会員売上(銀行振込)</th>
                                <th scope="col" colspan="{{ count($days) }}">既存会員売上(他)</th>
                                <th scope="col" colspan="{{ count($days) }}">累積会員数</th>
                                <th scope="col" colspan="{{ count($days) }}">累積売上合計</th>
                                <th scope="col" colspan="{{ count($days) }}">累積売上(クレジットカード)</th>
                                <th scope="col" colspan="{{ count($days) }}">累積売上(銀行振込)</th>
                                <th scope="col" colspan="{{ count($days) }}">累積売上(他)</th>
                                <th scope="col" rowspan="2">広告単価</th>
                                <th scope="col" rowspan="2">広告費用</th>
                                <th scope="col" rowspan="2">未収額</th>
                                <th scope="col" rowspan="2">売上率</th>
                                <th scope="col" rowspan="2">回収率</th>
                            </tr>
                            <tr>
                            @foreach($days as $day)
                                <th>{{ $day }}</th>
                            @endforeach
                            @foreach($days as $day)
                                <th>{{ $day }}</th>
                            @endforeach
                            @foreach($days as $day)
                                <th>{{ $day }}</th>
                            @endforeach
                            @foreach($days as $day)
                                <th>{{ $day }}</th>
                            @endforeach
                            @foreach($days as $day)
                                <th>{{ $day }}</th>
                            @endforeach
                            @foreach($days as $day)
                                <th>{{ $day }}</th>
                            @endforeach
                            @foreach($days as $day)
                                <th>{{ $day }}</th>
                            @endforeach
                            @foreach($days as $day)
                                <th>{{ $day }}</th>
                            @endforeach
                            @foreach($days as $day)
                                <th>{{ $day }}</th>
                            @endforeach
                            @foreach($days as $day)
                                <th>{{ $day }}</th>
                            @endforeach
                            @foreach($days as $day)
                                <th>{{ $day }}</th>
                            @endforeach
                            @foreach($days as $day)
                                <th>{{ $day }}</th>
                            @endforeach
                            @foreach($days as $day)
                                <th>{{ $day }}</th>
                            @endforeach
                            @foreach($days as $day)
                                <th>{{ $day }}</th>
                            @endforeach
                            @foreach($days as $day)
                                <th>{{ $day }}</th>
                            @endforeach
                        </thead>

                        <tbody>
                        @foreach($records as $record)
                            <tr>
                                <td class="h5">
                        @if (!empty($record->ad_code))
                            @switch($record->status)
                                @case(0)
                                    <span class="badge badge-danger">無効</span>
                                    @break
                                @case(1)
                                    <span class="badge badge-primary">有効</span>
                                    @break
                            @endswitch
                        @endif
                                </td>
                                <td>
                                @if (empty($record->ad_code))
                                    {{ '直接入会' }}：{{ $record->referer_type }}
                                @else
                                    {{ $record->site_name }}：{{ $record->ad_code }}
                                @endif
                                </td>
                                <td>{{ date_create($record->start_date)->format('Y/m/d') }}</td>
                                <td>{{ $record->last_date ? date_create($record->last_date)->format('Y/m/d H:i') : '' }}</td>
                            @for($i = 0; $i < count($days); $i++)
                                <td class="text-right">{{ number_format($record->{"sum{$i}"}) }}</td>
                            @endfor
                            @for($i = 0; $i < count($days); $i++)
                                <td class="text-right">¥{{ number_format($record->{"point{$i}"}) }}</td>
                            @endfor
                            @for($i = 0; $i < count($days); $i++)
                                <td class="text-right">¥{{ number_format($record->{"point{$i}"}) }}</td>
                            @endfor
                            @for($i = 0; $i < count($days); $i++)
                                <td class="text-right">¥{{ number_format(0) }}</td>
                            @endfor
                            @for($i = 0; $i < count($days); $i++)
                                <td class="text-right">¥{{ number_format(0) }}</td>
                            @endfor
                            @for($i = 0; $i < count($days); $i++)
                                <td class="text-right">{{ number_format($record->{"sumold{$i}"}) }}</td>
                            @endfor
                            @for($i = 0; $i < count($days); $i++)
                                <td class="text-right">¥{{ number_format($record->{"pointold{$i}"}) }}</td>
                            @endfor
                            @for($i = 0; $i < count($days); $i++)
                                <td class="text-right">¥{{ number_format($record->{"pointold{$i}"}) }}</td>
                            @endfor
                            @for($i = 0; $i < count($days); $i++)
                                <td class="text-right">¥{{ number_format(0) }}</td>
                            @endfor
                            @for($i = 0; $i < count($days); $i++)
                                <td class="text-right">¥{{ number_format(0) }}</td>
                            @endfor
                            @for($i = 0; $i < count($days); $i++)
                                <td class="text-right">{{ number_format($record->{"sumall{$i}"}) }}</td>
                            @endfor
                            @for($i = 0; $i < count($days); $i++)
                                <td class="text-right">¥{{ number_format($record->{"pointall{$i}"}) }}</td>
                            @endfor
                            @for($i = 0; $i < count($days); $i++)
                                <td class="text-right">¥{{ number_format($record->{"pointall{$i}"}) }}</td>
                            @endfor
                            @for($i = 0; $i < count($days); $i++)
                                <td class="text-right">¥{{ number_format(0) }}</td>
                            @endfor
                            @for($i = 0; $i < count($days); $i++)
                                <td class="text-right">¥{{ number_format(0) }}</td>
                            @endfor
                                <td class="text-right">¥{{ number_format($record->unit_price) }}</td>
                                <td class="text-right">¥{{ number_format($record->cost_summary) }}</td>
                                <td class="text-right">¥{{ number_format(0) }}</td>
                                <td class="text-right">{{ $record->rate }}%</td>
                                <td class="text-right">{{ $record->rate }}%</td>
                            </tr>
                        @endforeach
                        </tbody>

                    @elseif (app('request')->input('type') == '2')
<!-- 占い師動向詳細 thead -->
                        <thead>
                            <tr>
                                <th scope="col" rowspan="2">ログインID</th>
                                <th scope="col" rowspan="2">占い師名</th>
                                <th scope="col" rowspan="2">鑑定価格(分)</th>
                                <th scope="col" rowspan="2">累計待機時間</th>
                                <th scope="col" rowspan="2">累計鑑定数</th>
                                <th scope="col" rowspan="2">累計鑑定時間</th>
                                <th scope="col" rowspan="2">累計売上</th>
                                <th scope="col" rowspan="2">累計入金額</th>
                                <th scope="col" rowspan="2">平均待機時間</th>
                                <th scope="col" rowspan="2">平均鑑定数</th>
                                <th scope="col" rowspan="2">平均鑑定時間</th>
                                <th scope="col" rowspan="2">平均売上</th>
                                <th scope="col" rowspan="2">平均入金額</th>

                                <th scope="col" colspan="{{ count($days) }}">新規入会者鑑定回数</th>
                                <th scope="col" colspan="{{ count($days) }}">新規入会者鑑定時間</th>
                                <th scope="col" colspan="{{ count($days) }}">新規入会者鑑定売上</th>
                                <th scope="col" colspan="{{ count($days) }}">既存会員鑑定回数</th>
                                <th scope="col" colspan="{{ count($days) }}">既存会員鑑定時間</th>
                                <th scope="col" colspan="{{ count($days) }}">既存会員鑑定売上</th>
                                <th scope="col" colspan="{{ count($days) }}">累積会員鑑定回数</th>
                                <th scope="col" colspan="{{ count($days) }}">累積会員鑑定時間</th>
                                <th scope="col" colspan="{{ count($days) }}">累積会員鑑定売上</th>
                                <th scope="col" rowspan="2">未収額</th>

                            </tr>

                            <tr>
                            @foreach($days as $day)
                                <th>{{ $day }}</th>
                            @endforeach
                            @foreach($days as $day)
                                <th>{{ $day }}</th>
                            @endforeach
                            @foreach($days as $day)
                                <th>{{ $day }}</th>
                            @endforeach
                            @foreach($days as $day)
                                <th>{{ $day }}</th>
                            @endforeach
                            @foreach($days as $day)
                                <th>{{ $day }}</th>
                            @endforeach
                            @foreach($days as $day)
                                <th>{{ $day }}</th>
                            @endforeach
                            @foreach($days as $day)
                                <th>{{ $day }}</th>
                            @endforeach
                            @foreach($days as $day)
                                <th>{{ $day }}</th>
                            @endforeach
                            @foreach($days as $day)
                                <th>{{ $day }}</th>
                            @endforeach
                            </tr>
                        </thead>

<!-- 占い師動向詳細 tbody -->
                        <tbody>
                        @foreach($records as $record)
                            <tr>
                                <td>{{ $record->login_id }}</td>
                                <td>{{ $record->appraiser_name }}</td>
<td class="text-right">
<!-- ¥{{ number_format($record->unit_point) }} -->
¥{{ number_format($record->unit_point) }}/ ¥{{ number_format($record->unit_point_purchase) }}
</td>

                                <td class="text-right">{{ Functions::FormatTime($record->taiki) }}</td>
                                <td class="text-right">{{ number_format($record->counts) }}回</td>
                                <td>{{ Functions::FormatTime($record->exec_time) }}</td>
                                <td class="text-right">¥{{ number_format($record->point) }}</td>

                                <!-- <td class="text-right">¥{{ number_format($record->nyukin) }}</td> -->
                                <td class="text-right">[---]</td>

                                <td class="text-right">{{ Functions::FormatTime($record->avg_taiki) }}</td>
                                <td class="text-right">{{ number_format($record->avg_counts) }}回</td>
                                <td class="text-right">{{ Functions::FormatTime($record->avg_exec_time) }}</td>
                                <td class="text-right">¥{{ number_format($record->avg_point) }}</td>

                                <!-- <td class="text-right">¥{{ number_format($record->avg_nyukin) }}</td> -->
                                <td class="text-right">[---]</td>

                            @for($i = 0; $i < count($days); $i++)
                                <td class="text-right">{{ number_format($record->{"count_new{$i}"}) }}回</td>
                            @endfor
                            @for($i = 0; $i < count($days); $i++)
                                <td class="text-right">{{ Functions::FormatTime($record->{"exec_time_new{$i}"}) }}</td>
                            @endfor
                            @for($i = 0; $i < count($days); $i++)
                                <td class="text-right">¥{{ number_format($record->{"point_new{$i}"}) }}</td>
                            @endfor
                            @for($i = 0; $i < count($days); $i++)
                                <td class="text-right">{{ number_format($record->{"count_old{$i}"}) }}回</td>
                            @endfor
                            @for($i = 0; $i < count($days); $i++)
                                <td class="text-right">{{ Functions::FormatTime($record->{"exec_time_old{$i}"}) }}</td>
                            @endfor
                            @for($i = 0; $i < count($days); $i++)
                                <td class="text-right">¥{{ number_format($record->{"point_old{$i}"}) }}</td>
                            @endfor
                            @for($i = 0; $i < count($days); $i++)
                                <td class="text-right">{{ number_format($record->{"counts{$i}"}) }}回</td>
                            @endfor
                            @for($i = 0; $i < count($days); $i++)
                                <td class="text-right">{{ Functions::FormatTime($record->{"exec_time{$i}"}) }}</td>
                            @endfor
                            @for($i = 0; $i < count($days); $i++)
                                <td class="text-right">¥{{ number_format($record->{"point{$i}"}) }}</td>
                            @endfor
                                <td class="text-right">¥{{ number_format($record->misyunou) }}</td>

                            </tr>
                        @endforeach
                        </tbody>

                    @elseif (app('request')->input('type') == '3')

                        <thead>
                            <tr>
                                <th scope="col" colspan="{{ count($days) }}">新規入会者鑑定数</th>
                                <th scope="col" colspan="{{ count($days) }}">新規入会者鑑定時間</th>
                                <th scope="col" colspan="{{ count($days) }}">新規入会者売上</th>
                                <th scope="col" colspan="{{ count($days) }}">既存会員鑑定数</th>
                                <th scope="col" colspan="{{ count($days) }}">既存会員鑑定時間</th>
                                <th scope="col" colspan="{{ count($days) }}">既存会員売上</th>
                                <th scope="col" colspan="{{ count($days) }}">累計会員鑑定数</th>
                                <th scope="col" colspan="{{ count($days) }}">累計会員鑑定時間</th>
                                <th scope="col" colspan="{{ count($days) }}">累計会員売上</th>
                                <th scope="col" rowspan="2">累計未収額</th>
                                <th scope="col" rowspan="2">累計広告費</th>
                                <th scope="col" rowspan="2">累計売上率</th>
                                <th scope="col" rowspan="2">累計回収率</th>
                            </tr>
                            <tr>
                            @foreach($days as $day)
                                <th>{{ $day }}</th>
                            @endforeach
                            @foreach($days as $day)
                                <th>{{ $day }}</th>
                            @endforeach
                            @foreach($days as $day)
                                <th>{{ $day }}</th>
                            @endforeach
                            @foreach($days as $day)
                                <th>{{ $day }}</th>
                            @endforeach
                            @foreach($days as $day)
                                <th>{{ $day }}</th>
                            @endforeach
                            @foreach($days as $day)
                                <th>{{ $day }}</th>
                            @endforeach
                            @foreach($days as $day)
                                <th>{{ $day }}</th>
                            @endforeach
                            @foreach($days as $day)
                                <th>{{ $day }}</th>
                            @endforeach
                            @foreach($days as $day)
                                <th>{{ $day }}</th>
                            @endforeach
                        </thead>

                        <tbody>
                        @foreach($records as $record)
                            <tr>
                            @for($i = 0; $i < count($days); $i++)
                                <td class="text-right">{{ number_format($record->{"count_new{$i}"}) }}回</td>
                            @endfor
                            @for($i = 0; $i < count($days); $i++)
                                <td class="text-right">{{ Functions::FormatTime($record->{"exec_time_new{$i}"}) }}</td>
                            @endfor
                            @for($i = 0; $i < count($days); $i++)
                                <td class="text-right">¥{{ number_format($record->{"point_new{$i}"}) }}</td>
                            @endfor
                            @for($i = 0; $i < count($days); $i++)
                                <td class="text-right">{{ number_format($record->{"count_old{$i}"}) }}回</td>
                            @endfor
                            @for($i = 0; $i < count($days); $i++)
                                <td class="text-right">{{ Functions::FormatTime($record->{"exec_time_old{$i}"}) }}</td>
                            @endfor
                            @for($i = 0; $i < count($days); $i++)
                                <td class="text-right">¥{{ number_format($record->{"point_old{$i}"}) }}</td>
                            @endfor
                            @for($i = 0; $i < count($days); $i++)
                                <td class="text-right">{{ number_format($record->{"counts{$i}"}) }}回</td>
                            @endfor
                            @for($i = 0; $i < count($days); $i++)
                                <td class="text-right">{{ Functions::FormatTime($record->{"exec_time{$i}"}) }}</td>
                            @endfor
                            @for($i = 0; $i < count($days); $i++)
                                <td class="text-right">¥{{ number_format($record->{"point{$i}"}) }}</td>
                            @endfor
                                <td class="text-right">¥{{ number_format($record->misyunou) }}</td>
                                <td class="text-right">¥{{ number_format($record->cost) }}</td>
                                <td class="text-right">{{ $record->sales_rate }}%</td>
                                <td class="text-right">{{ $record->recovery_rate }}%</td>
                            </tr>
                        @endforeach
                        </tbody>
                    @endif

                    </table>
                </div>
            </div>
        @endif
    </div>
@endsection

@section('script')
<script type="text/javascript">
    $(function() {
        $('#btnCsvDl').click(function(e) {
            var $form = $('form');
            if ($('input:invalid').length > 0) {
                return true;
            }
            $form.attr('action', '{{ route("businesses.downloadCsv") }}').submit();
            setTimeout(function(){
                $form.removeAttr('action');
            }, 1);
        });

        $('form').submit(function() {

            var $errorMsg = $('#search-error-msg');

            $errorMsg.html('');
            $errorMsg.closest('.search-error-area').addClass('d-none');

            var errors = [];
            var startDate = new Date($('#start_date').val());
            var endDate = new Date($('#end_date').val());
            if (startDate > endDate) {
                errors.push('期間の入力に誤りがあります。');
            }
            else if (((endDate - startDate) / 86400000) + 1 > 100) {
                errors.push('期間の入力は100日以内で行ってください。');
            }
            if (errors.length > 0) {
                $errorMsg.html(errors.join('<br />'));
                $errorMsg.closest('.search-error-area').removeClass('d-none');
                return false;
            }
        });
    });
</script>
@endsection
