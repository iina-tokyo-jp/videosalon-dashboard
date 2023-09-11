@extends('layouts.app')
@section('nav_name', '数値管理')

@section('content')
@include('numerics.selector')

    <div class="mt-3">
        <form method="get" action="{{ route('numerics.advertiseSearch') }}" id="select_date">
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
            <div class="adv_listArea">
            <!--<div>-->
                <div class="d-flex align-items-center">
                    <div class="mb-3">
                        <span>{{ $numerics->firstItem() }} - {{ $numerics->lastItem() }} を表示</span>
                    </div>
                    <div class="ml-3">
                        {{ $numerics->appends($queryParams)->links() }}
                    </div>
                </div>
                <table class="table table-bordered adv_list" id="find-table">
                    <thead>
                    <tr>
                        <th scope="col" rowspan="2" class="adv_list_item2 align-middle">ADコード</th>
                        <th scope="col" rowspan="2" class="adv_list_item2 align-middle">アクセス数</th>
                        <th scope="col" colspan="3">登録</th>
                        <th scope="col" class="adv_list_item2">通電</th>
                        <th scope="col" colspan="6">収支</th>
                        <th scope="col" colspan="3">無料ポイント</th>
                        <th scope="col" colspan="3">事前決済</th>
                        <th scope="col" colspan="3">事後決済</th>
                        <th scope="col" class="adv_list_item2">自動精算</th>
                        <th scope="col" colspan="2">未払</th>
                    </tr>
                    <tr>
                        <th scope="col" class="adv_list_item2">登録計UU</th>
                        <th scope="col" class="adv_list_item2">無料UU</th>
                        <th scope="col" class="adv_list_item2">課金UU</th>
                        <th scope="col">通電UU</th>
                        <th scope="col" class="adv_list_item1">売上計</th>
                        <th scope="col" class="adv_list_item1">広告費計</th>
                        <th scope="col" class="adv_list_item1">無料pt計</th>
                        <th scope="col" class="adv_list_item1">粗利(税込)</th>
                        <th scope="col" class="adv_list_item1">粗利(税抜)</th>
                        <th scope="col" class="adv_list_item2">回収率</th>
                        @if (isset($freeDefs))
                            @foreach($freeDefs as $def)
                                @if (mb_strlen($def->name) > 5)
                                    <th scope="col" class="adv_list_item1">{{ $def->name }}</th>
                                @else
                                    <th scope="col" class="adv_list_item2">{{ $def->name }}</th>
                                @endif
                            @endforeach
                        @endif
                        <th scope="col" class="adv_list_item2">クレカ</th>
                        <th scope="col" class="adv_list_item2">銀行振込</th>
                        <th scope="col" class="adv_list_item3">その他</th>
                        <th scope="col" class="adv_list_item2">クレカ</th>
                        <th scope="col" class="adv_list_item2">銀行振込</th>
                        <th scope="col" class="adv_list_item3">その他</th>
                        <th scope="col">クレカ</th>

                        <th scope="col" class="adv_list_item2">未収額</th>
                        <th scope="col" class="adv_list_item3">未収率</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($numerics as $numeric)
                        <tr>
                            <td class="text-right">{{ $numeric->ad_code }}</td>
                            <td class="text-right">{{ number_format($numeric->pv_count) }}</td>
                            <td class="text-right">{{ number_format($numeric->signup_count) }}</td>
                            <td class="text-right">{{ number_format($numeric->noncharge_count) }}</td>
                            <td class="text-right">{{ number_format($numeric->charge_count) }}</td>
                            <!--ここに通電UUが入る予定（今は仮で登録計UUをセット）-->
                            <td class="text-right">{{ number_format($numeric->signup_count) }}</td>
                            <!--ここに通電UUが入る予定 END-->
                            <td class="text-right">&yen;{{ number_format($numeric->total_sales) }}</td>
                            <td class="text-right">&yen;{{ number_format($numeric->total_adcosts) }}</td>
                            <td class="text-right">&yen;{{ number_format($numeric->total_freepoints) }}</td>
                            <td class="text-right">&yen;{{ number_format($numeric->gross_profit_wtax) }}</td>
                            <td class="text-right">&yen;{{ number_format($numeric->gross_profit_wotax) }}</td>
                            @if ($numeric->total_adcosts > 0)
                                <!-- 小数点第3位で四捨五入、小数点第2位まで表示 -->
                                <td class="text-right">{{ round(($numeric->total_sales / $numeric->total_adcosts * 100), 2) }} &#037;</td>
                            @else
                                <td class="text-right">0 &#037;</td>
                            @endif
                        <!-- 無料ポイント -->
                            @if (isset($freePoints))
                                @foreach($freePoints as $free)
                                    @if ($numeric->ad_code == $free->ad_code)
                                        <td class="text-right">&yen;{{ number_format($free->point_amount) }}</td>
                                    @endif
                                @endforeach
                            @endif
                            <td class="text-right">&yen;0</td>       <!-- その他 -->

                        <!-- 事前決済 -->
                            <td class="text-right">&yen;{{ number_format($numeric->prepaied_card) }}</td>   <!-- クレカ -->
                            <td class="text-right">&yen;{{ number_format($numeric->prepaied_bank) }}</td>   <!-- 銀行振込 -->
                            <td class="text-right">&yen;{{ number_format($numeric->prepaied_other) }}</td>  <!-- その他 -->
                        <!-- 事後決済 -->
                            <td class="text-right">&yen;{{ number_format($numeric->postpaied_card) }}</td>   <!-- クレカ -->
                            <td class="text-right">&yen;{{ number_format($numeric->postpaied_bank) }}</td>   <!-- 銀行振込 -->
                            <td class="text-right">&yen;{{ number_format($numeric->postpaied_other) }}</td>  <!-- その他 -->
                        <!-- 自動精算 -->
                            <td class="text-right">&yen;{{ number_format($numeric->eachpaied_card) }}</td>   <!-- クレカ -->
                        <!-- 未払 -->
                            <td class="text-right">&yen;{{ number_format($numeric->nonpaied) }}</td>    <!-- 未収額 -->
                            @if ($numeric->total_sales > 0)                                             <!-- 未収率 -->
                                <!-- 小数点第3位で四捨五入、小数点第2位まで表示 -->
                                <td class="text-right">{{ round(($numeric->nonpaied / $numeric->total_sales * 100), 2)  }} &#037;</td>
                            @else
                                <td class="text-right">0 &#037;</td>
                            @endif
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
        });
    </script>
@endsection
