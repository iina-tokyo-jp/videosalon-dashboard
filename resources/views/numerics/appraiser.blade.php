@extends('layouts.app')
@section('nav_name', '数値管理')

@section('content')
@include('numerics.selector')

    <div class="mt-3">
        <form method="get" action="{{ route('numerics.appraiserSearch') }}" id="select_date" name="select_date">
            <div class="form-row">
                @if (count($errors) > 0)
                    <div class="col-12">
                        <div class="alert alert-danger" role="alert">
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="col-md-4 mb-3">
                    <select class="custom-select" name="type_date" id="type_date" form="select_date">
                        <option value="day" {{app('request')->input('type_date') == 'day' ? 'selected' : ''}}>1日指定</option>
                        <option value="fromto" {{app('request')->input('type_date') == 'fromto' ? 'selected' : ''}}>期間指定</option>
                        <option value="month" {{app('request')->input('type_date') == 'month' ? 'selected' : ''}}>月指定</option>
                        <option value="year" {{app('request')->input('type_date') == 'year' ? 'selected' : ''}}>年指定</option>
                    </select>
                </div>
                <div class="col-md-2 mb-3" id="areaOneDate">
                    <input type="text" class="input-sm form-control" placeholder="YYYY-MM-DD" name="inp_OneDate" id="inp_OneDate"
                        value="{{app('request')->input('type_date') == 'day' ? app('request')->input('inp_OneDate') : ''}}" form="select_date" />
                </div>
                <div class="col-md-2 mb-3 d-none" id="areaStartDate">
                    <input type="text" class="input-sm form-control" placeholder="from YYYY-MM-DD" name="inp_StartDate" id="inp_StartDate" 
                        value="{{app('request')->input('type_date') == 'fromto' ? app('request')->input('inp_StartDate') : ''}}" form="select_date" />
                </div>
                <div class="col-md-2 mb-3 d-none" id="areaEndDate">
                    <input type="text" class="input-sm form-control" placeholder="to YYYY-MM-DD" name="inp_EndDate" id="inp_EndDate" 
                        value="{{app('request')->input('type_date') == 'fromto' ? app('request')->input('inp_EndDate') : ''}}" form="select_date" />
                </div>
                <div class="col-md-2 mb-3 d-none" id="areaMonth">
                    <input type="text" class="input-sm form-control" placeholder="YYYY-MM" name="inp_Month" id="inp_Month" 
                        value="{{app('request')->input('type_date') == 'month' ? app('request')->input('inp_Month') : ''}}" form="select_date" />
                </div>
                <div class="col-md-2 mb-3 d-none" id="areaYear">
                    <input type="text" class="input-sm form-control" placeholder="YYYY" name="inp_Year" id="inp_Year" 
                        value="{{app('request')->input('type_date') == 'year' ? app('request')->input('inp_Year') : ''}}" form="select_date" />
                </div>
                <div class="col-md-4 mb-3">
                    <button type="submit" class="btn btn-primary mb-2" form="select_date">検索</button>
                </div>
            </div>
        </form>

        @if (isset($numerics))
            <div class="apr_listArea">
            <!--<div>-->
                <div class="d-flex align-items-center">
                    <div class="ml-2 mb-3">
                        <button id="btnCsvDl" class="btn btn-primary">CSV DL</button>
                    </div>
                    <div class="mb-3">
                        <span>{{ $numerics->firstItem() }} - {{ $numerics->lastItem() }} を表示</span>
                    </div>
                    <div class="ml-3">
                        {{ $numerics->appends($queryParams)->links() }}
                    </div>
                </div>
                <table class="table table-bordered apr_list" id="find-table">
                    <thead>
                    <tr>
                        <th scope="col" colspan="2">鑑定士</th>
                        <th scope="col" colspan="2">鑑定活動動向</th>
                        <th scope="col" colspan="11">鑑定</th>
                        <th scope="col" colspan="2">売上</th>
                    </tr>
                    <tr>
                        <th scope="col" class="apr_list_item1">占い師ID</th>
                        <th scope="col" class="apr_list_item1">占い師名</th>
                        <th scope="col" class="apr_list_item3">待機時間<br>(分)</th>
                        <th scope="col" class="apr_list_item3">休憩時間<br>(分)</th>
                        <th scope="col" class="apr_list_item4">鑑定数</th>
                        <th scope="col" class="apr_list_item4">即時</th>
                        <th scope="col" class="apr_list_item3">予約鑑定<br>(予約数)</th>
                        <th scope="col" class="apr_list_item3">予約鑑定<br>(実鑑定数)</th>
                        <th scope="col" class="apr_list_item2">ビデオ総鑑定<br>時間(分)</th>
                        <th scope="col" class="apr_list_item2">ビデオ平均鑑定<br>時間(分)</th>
                        <th scope="col" class="apr_list_item2">音声総鑑定<br>時間(分)</th>
                        <th scope="col" class="apr_list_item2">音声平均鑑定<br>時間(分)</th>
                        <th scope="col" class="apr_list_item3">エラー終了数</th>
                        <th scope="col" class="apr_list_item3">レビュー数</th>
                        <th scope="col" class="apr_list_item3">ブログ執筆数</th>
                        <th scope="col" class="apr_list_item4">売上(税込)</th>
                        <th scope="col" class="apr_list_item4">売上(税抜)</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($numerics as $numeric)
                        <tr>
                            <td class="text-left">{{ $numeric->appraiser_id }}</td>
                            <td class="text-left">{{ $numeric->appraiser_name }}</td>
                            <td class="text-right">{{ number_format($numeric->stb_time) }}</td>
                            <td class="text-right">{{ number_format($numeric->rest_time) }}</td>
                            <td class="text-right">{{ number_format($numeric->appraisal_count) }}</td>
                            <td class="text-right">{{ number_format($numeric->rightnow_count) }}</td>
                            <td class="text-right">{{ number_format($numeric->reserve_count) }}</td>
                            <td class="text-right">{{ number_format($numeric->active_count) }}</td>
                            <td class="text-right">{{ number_format($numeric->video_time) }}</td>
                            <td class="text-right">{{ number_format($numeric->video_avg_time) }}</td>
                            <td class="text-right">{{ number_format($numeric->sound_time) }}</td>
                            <td class="text-right">{{ number_format($numeric->sound_avg_time) }}</td>
                            <td class="text-right">{{ number_format($numeric->error_count) }}</td>
                            <td class="text-right">{{ number_format($numeric->review_count) }}</td>
                            <td class="text-right">{{ number_format($numeric->blog_count) }}</td>
                            <td class="text-right">&yen;{{ number_format($numeric->sales_wtax) }}</td>
                            <td class="text-right">&yen;{{ number_format($numeric->sales_wotax) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection

@section('local-script')
    <link rel="stylesheet" type="text/css" href="/css/bootstrap-datepicker.min.css">
    <link rel="stylesheet" type="text/css" href="/css/numeric.css">
    <script type="text/javascript" src="/js/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript" src="/js/bootstrap-datepicker.ja.min.js"></script>
    <script>
        $(function () {
            $('#inp_OneDate, #inp_StartDate, #inp_EndDate').datepicker({
                language: "ja",
                /* startDate: '2022-05-01', */
                endDate: new Date(),
                format: "yyyy-mm-dd",
                autoclose: true
            });
            $('#inp_Month').datepicker({
                language: "ja",
                /* startDate: '2022-05-01', */
                endDate: new Date(),
                format: "mm",
                minViewMode: 1,
                maxViewMode: 2,
                autoclose: true
            });
            $('#inp_Year').datepicker({
                language: "ja",
                /* startDate: '2022-05-01', */
                endDate: new Date(),
                format: "yyyy",
                minViewMode: 1,
                maxViewMode: 2,
                autoclose: true
            });
            window.onload = function() {
                setColumn();
            }
            $('#type_date').change(function(){
                setColumn();
            });
            const setColumn = () => {
                let r = $('#type_date').val();
                let objOneDate = document.querySelector('#areaOneDate');
                let objStart = document.querySelector("#areaStartDate");
                let objEnd = document.querySelector("#areaEndDate");
                let objMonth = document.querySelector("#areaMonth");
                let objYear = document.querySelector("#areaYear");

                switch (r){
                    case "day":
                        objOneDate.classList.remove('d-none');
                        objStart.classList.add('d-none');
                        objEnd.classList.add('d-none');
                        objMonth.classList.add('d-none');
                        objYear.classList.add('d-none');
                        break;
                    case "fromto":
                        objOneDate.classList.add('d-none');
                        objStart.classList.remove('d-none');
                        objEnd.classList.remove('d-none');
                        objMonth.classList.add('d-none');
                        objYear.classList.add('d-none');
                        break;
                    case "month":
                        objOneDate.classList.add('d-none');
                        objStart.classList.add('d-none');
                        objEnd.classList.add('d-none');
                        objMonth.classList.remove('d-none');
                        objYear.classList.add('d-none');
                        break;
                    case "year":
                        objOneDate.classList.add('d-none');
                        objStart.classList.add('d-none');
                        objEnd.classList.add('d-none');
                        objMonth.classList.add('d-none');
                        objYear.classList.remove('d-none');
                }
            }
            $('#btnCsvDl').click(function(e) {
                var $form = $('select_date');
                $form.attr('action', '{{ route("numerics.downloadCsv") }}').submit();
                setTimeout(function(){
                    $form.removeAttr('action');
                }, 1);
            });
        });
    </script>
@endsection
