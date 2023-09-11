@extends('layouts.app')
@section('nav_name', '広告管理')
@section('content')
    <div class="mt-3">
        <form id="search_form">
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
                    <select class="custom-select" name="type_search">
                        <option value="">検索項目を選んで下さい</option>
                        <option value="status" {{app('request')->input('type_search') == 'status' ? 'selected' : ''}}>状態</option>
                        <option value="ad_code" {{app('request')->input('type_search') == 'ad_code' ? 'selected' : ''}}>出稿内容/コード</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <input type="text" class="form-control" value="{{ app('request')->input('keyword') }}"
                           placeholder="こちらに検索内容を入力します" name="keyword">
                </div>
                <div class="col-md-4 d-flex mb-3">
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
                    <button type="button" class="btn btn-secondary mb-2" id="add_button">新規</button>
                    <button type="button" class="btn btn-info mb-2 d-none" id="merge_button">修正/一括変更</button>
                </div>
            </div>
        </form>
        @if (!(empty(app('request')->input('start_date')) ||
               empty(app('request')->input('end_date'))))
            <div>
                <div class="table-responsive">
                    <table class="table table-bordered" id="find-table">
                        <thead>
                        <tr>
                            <th scope="col" rowspan="2">選択</th>
                            <th scope="col" rowspan="2">状態</th>
                            <th scope="col" rowspan="2">出稿内容/コード</th>
                            <th scope="col" rowspan="2">出稿開始日</th>
                            <th scope="col" rowspan="2">最終入会日</th>
                            <th scope="col" colspan="{{ count($days) }}">新規入会数</th>
                            <th scope="col" colspan="{{ count($days) }}">売上合計</th>
                            <th scope="col" rowspan="2">売上平均</th>
                            <th scope="col" rowspan="2">費用合計</th>
                            <th scope="col" rowspan="2">出稿収支</th>
                        </tr>
                        <tr>
                    @foreach($days as $day)
                        <th>{{ $day }}</th>
                    @endforeach
                    @foreach($days as $day)
                        <th>{{ $day }}</th>
                    @endforeach
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($adcodes as $adcode)
                            <tr>
                                <td>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" value="{{$adcode->id}}"
                                            id="customCheck{{$adcode->id}}"
                                            data-date="{{ date_create($adcode->add_date)->format('Y/m/d H:i')}}"
                                            data-ad-code="{{ $adcode->ad_code }}"
                                            data-status="{{ $adcode->status }}"
                                            data-site-name="{{ $adcode->site_name }}"
                                            data-start-date="{{ date_create($adcode->start_date)->format('Y-m-d') }}"
                                            data-url="{{ $adcode->url }}"
                                            data-unit-price="{{ $adcode->unit_price }}"
                                            data-banner="{{ $adcode->banner }}"
                                            data-mod-date="{{ $adcode->mod_date }}"
                                        >
                                        <label class="custom-control-label" for="customCheck{{$adcode->id}}"></label>
                                    </div>
                                </td>
                                <td class="h5">
                                    @switch($adcode->status)
                                        @case(0)
                                            <span class="badge badge-danger">無効</span>
                                            @break
                                        @case(1)
                                            <span class="badge badge-primary">有効</span>
                                            @break
                                    @endswitch
                                </td>
                                <td>
                                    {{ $adcode->site_name }}：{{ $adcode->ad_code }}
                                </td>
                                <td>{{ date_create($adcode->start_date)->format('Y/m/d') }}</td>
                                <td>{{ $adcode->last_date ? date_create($adcode->last_date)->format('Y/m/d H:i') : '' }}</td>
                            @for($i = 0; $i < count($days); $i++)
                                <td class="text-right">{{ number_format($adcode->{"sum{$i}"}) }}</td>
                            @endfor
                            @for($i = 0; $i < count($days); $i++)
                                <td class="text-right">{{ number_format($adcode->{"point{$i}"}) }}円</td>
                            @endfor
                                <td class="text-right">{{ number_format($adcode->point_average) }}円</td>
                                <td class="text-right">{{ number_format($adcode->cost_summary) }}円</td>
                                <td class="text-right">{{ number_format($adcode->income_and_expenditure) }}円</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>

    <div class="modal" id="approveChangeMultiAdcodesStatus">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">ADコード管理</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form method="post" action="{{ route('adcodes.changeAllStatus') }}">
                    @csrf
                    <input type="hidden" name="ids" class="ids">
                    <input type="hidden" name="mod_dates" class="mod_dates">
                    <!-- Modal body -->
                    <div class="modal-body">
                        <p>※この状態変更は選択したすべての広告に適用されます。</p>
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">キャンセル</button>
                        <button type="submit" class="btn btn-danger" name="status" value="0">無効にする</button>
                        <button type="submit" class="btn btn-primary" name="status" value="1">有効にして適用</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal" id="approveChangeEachAdcodeStatus">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">ADコード管理</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form method="post" action="{{ route('adcodes.changeEachStatus') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="ids" class="ids">

                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="form-group">
                            <legend>登録日</legend>
                            <label id="add_date" class="control-label"></label>
                        </div>
                        <div class="form-group">
                            <legend>出稿開始日</legend>
                            <input type="date" class="form-control" name="start_date" required>
                        </div>
                        <div class="form-group">
                            <legend>サイト名</legend>
                            <input class="form-control" name="site_name" required>
                        </div>
                        <div class="form-group">
                            <legend>ADコード</legend>
                            <input class="form-control" name="ad_code" required>
                        </div>
                        <div class="form-group">
                            <legend>リンク先URL</legend>
                            <input class="form-control" name="url" required>
                        </div>
                        <div class="form-group">
                            <legend>広告単価</legend>
                            <input type="number" class="form-control" name="unit_price" required>
                        </div>
                        <div class="form-group">
                            <legend>バナー画像</legend>
                            <div id="previewOn">
                                <div class="d-flex align-items-start justify-content-center">
                                    <img id="preview" src="" style="max-width:200px;height:auto;">
                                </div>
                            </div>

                            <div id="previewOff">
                                <div id="previewNoImage" class="mx-auto d-block d-flex align-items-center justify-content-center bg-secondary text-white" style="width:200px;height:200px;">
                                    <span>NO IMAGE</span>
                                </div>
                            </div>

                            <div class="d-flex justify-content-center mt-2">
                                <button id="btnFileUpload" type="button" class="btn btn-outline-primary">写真をアップロード</button>
                                <input id="imageFile" type="file" class="d-none" name="image_file">
                                <input id="imageStatus" type="hidden" name="image_status" value="0">
                                <input id="imageBefore" type="hidden">
                            </div>
                        </div>

                        <input type="hidden" name="mod_dates">
                        <span class="form-text text-danger error-msg"></span>
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">キャンセル</button>
                        <button type="submit" class="btn btn-danger" name="status" value="0">無効にする</button>
                        <button type="submit" class="btn btn-primary" name="status" value="1">有効にして適用</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function () {
            $('#find-table input:checkbox').change(
                function(){
                    let searchIDs = $("#find-table input:checkbox:checked").map(function(){
                        return $(this).val();
                    }).get();
                    if (searchIDs.length) {
                        $("#merge_button").removeClass('d-none');
                    } else {
                        $("#merge_button").addClass('d-none');
                    }
                }
            );

            $('#add_button').click(function(event){
                var $dialog = $('#approveChangeEachAdcodeStatus');
                $dialog.find('form')[0].reset();
                $dialog.find(".ids").val('-1')
                $("#imageStatus").val('0');
                $("#add_date").parent().hide();
                $('#previewOn').hide();
                $('#previewOff').show();
                $(".error-msg").html('');
                $dialog.modal('show')
            });

            $("#merge_button").click(function(event){
                event.preventDefault();
                let searchIDs = $("#find-table input:checkbox:checked").map(function(){
                    return $(this).val();
                }).get(); // <----
                if (searchIDs.length) {
                    $('.ids').val(searchIDs);
                    $('.mod_dates').val($("#find-table input:checkbox:checked").map(function(){
                        return $(this).data('mod-date');
                    }).get());
                    if (searchIDs.length === 1) {
                        var $dialog = $('#approveChangeEachAdcodeStatus');
                        var $check = $(`#customCheck${searchIDs[0]}`);
                        $dialog.find('form')[0].reset();
                        $("#add_date").text($check.data('date')).parent().show();
                        $dialog.find('[name="start_date"]').val($check.data('start-date'));
                        $dialog.find('[name="site_name"]').val($check.data('site-name'));
                        $dialog.find('[name="ad_code"]').val($check.data('ad-code'));
                        $dialog.find('[name="url"]').val($check.data('url'));
                        $dialog.find('[name="unit_price"]').val($check.data('unit-price'));
                        $dialog.find('[name="url"]').val($check.data('url'));
                        var banner = $check.data('banner');
                        if (banner) {
                            $('#preview').attr('src', banner);
                            $('#previewOn').show();
                            $('#previewOff').hide();
                        }
                        else {
                            $('#previewOn').hide();
                            $('#previewOff').show();
                        }
                        $dialog.find('[name="mod_dates"]').val($check.data('mod-date'))
                        $("#imageStatus").val('0');
                        $(".error-msg").html('');
                        $dialog.modal('show')
                    } else {
                        $('#approveChangeMultiAdcodesStatus').modal('show')
                    }
                }
            });

            $('#approveChangeMultiAdcodesStatus').on('show.bs.modal', function(e) {
                var $this = $(this);
                $this.find("form")[0].reset();
            });

            $('#approveChangeEachAdcodeStatus form').submit(function() {
                var $this = $(this);
                var errors = [];
                if ($this.find('[name="site_name"]').val().length > 64) {
                    errors.push('サイト名は64文字以内で入力してください。');
                }
                if ($this.find('[name="ad_code"]').val().length > 64) {
                    errors.push('ADコードは64文字以内で入力してください。');
                }
                if (!$this.find(".ids").val()){
                    if ($('#imageStatus').val() == 0) {
                        errors.push('バナー画像をアップロードしてください。');
                    }
                }

                if (errors.length > 0) {
                    $this.find('.error-msg').html(errors.join('<br />'));
                    return false;
                }
            });

            $('#btnFileUpload').on('click', function() {
                $('#imageFile').trigger("click");
            });

            $('#imageFile').change(function(e) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#preview').attr('src', e.target.result);
                };
                reader.readAsDataURL(this.files[0]);
                $('#imageStatus').val('1');
                $('#previewOn').show();
                $('#previewOff').hide();
                $("#btnFileDelete").show();
            });

            $('#search_form').submit(function() {

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
        })
    </script>
@endsection
