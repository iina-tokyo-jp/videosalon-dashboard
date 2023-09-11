@extends('layouts.app')
@section('nav_name', '占い師管理')
@section('content')
<div class="mt-3">
    <div class="row">
        @if (count($errors) > 0)
        <div class="col-12">
            <div class="alert alert-danger" role="alert">
                @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
                @endforeach
            </div>
        </div>
        @endif
        <div class="col-2">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#home">基本情報</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">占い師管理</a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item nav-link" data-toggle="tab" href="#menu1">占い師ログ表示</a>
                        <a class="dropdown-item nav-link" href="#" data-toggle="modal" data-target="#downloadAppraiserLogs">ログ調査</a>
                        <a class="dropdown-item nav-link" href="#" data-toggle="modal" data-target="#changePointOfAppraiser">売上額変更</a>
                        <a class="dropdown-item nav-link" href="#" data-toggle="modal" data-target="#updateStatusOfAppraiser">占い師情報変更</a>
                        <a class="dropdown-item nav-link" href="#" data-toggle="modal" data-target="#changeStatusOfAppraiser">デビュー可否</a>
                    </div>

                </li>
            </ul>
        </div>
        <div class="col-10">
            <!-- Tab panes -->
            <div class="tab-content">
                <div class="tab-pane container active" id="home">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th scope="row">ID有効性</th>
                                <td>
                            @switch($appraiser->user->user->state)
                                @case(1)
                                    有効
                                    @break
                                @case(2)
                                    有効(ブラック)
                                    @break
                                @case(0)
                                    無効
                                    @break
                                @case(-1)
                                    無効(ブラック)
                                    @break
                                @default
                            @endswitch
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">ユーザーID</th>
                                <td>{{ $appraiser->user->info->email }}</td>
                            </tr>

                            <tr>
                                <th scope="row">最終ログイン日時</th>
                                <td>{{ $appraiser->user->info->last_login_date->format('Y/m/d H:i') }}</td>
                            </tr>

                            <tr>
                                <th scope="row">有効ポイント数</th>
                                <td>{{ number_format($appraiser->user->info->point) }}pt</td>
                            </tr>

                            <tr>
                                <th scope="row">ポイント更新日時</th>
                                <td>{{ $pointHistory->add_date ? $pointHistory->add_date->format('Y/m/d H:i') : '' }}</td>
                            </tr>

                            <tr>
                                <th scope="row">本名</th>
                                <td>{{ $appraiser->user->info->name }}</td>
                            </tr>

                            <tr>
                                <th scope="row">占い師名</th>
                                <td>{{ $appraiser->name }}</td>
                            </tr>

                            <tr>
                                <th scope="row">電話番号</th>
                                <td>{{ $appraiser->user->info->phoneno }}</td>
                            </tr>

                            <tr>
                                <th scope="row">SMS認証</th>
                                <td>{{ empty($appraiser->user->info->phoneno) ? '未認証' : "認証済({$appraiser->user->info->regphone_date->format('Y/m/d H:i')})" }}</td>
                            </tr>

                            <tr>
                                <th scope="row">LINE連携</th>
                                <td>{{ empty($userLine->line_id) ? '未連携' : 'LINE連携済' }}</td>
                            </tr>

                            <tr>
                                <th scope="row">クーポン適用/流入元</th>
                                <td>{{ empty($adlink->ad_code) ? $adlink->referer_type : $adlink->ad_code }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane container fade" id="menu1">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">アクション</th>
                                <th scope="col">実行ID</th>
                                <th scope="col">適用日時</th>
                                <th scope="col">ビデオ記録</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php($total = count($logs))
                            @foreach($logs as $key => $log)
                            <tr>
                                <th scope="row">{{ $total - $key }}</th>
                                <td>
                                    {{ $log->title }}
                                </td>
                                <td>
                                    {{ $log->user->login_id }}
                                </td>
                                <td>
                                    {{ $log->add_date->format('Y/m/d H:i') }}
                                </td>
<td>
@if ($log->kind == 311)
@switch($_SERVER['HTTP_HOST'])
    @case('127.0.0.1:8000')
        <a href="https://dev.videosalon.org/ext_cooperation/videosalon/s3VideoDownload.php?reservation_id={{$log->gp4app}}">ダウンロード</a>
        <!-- <a href="https://dev.videosalon.org/ext_cooperation/videosalon/s3VideoPlay.php?reservation_id={{$log->gp4app}}">再生</a> -->
    @break
    @case('dev-dashboard.videosalon.org')
        <a href="https://dev.videosalon.org/ext_cooperation/videosalon/s3VideoDownload.php?reservation_id={{$log->gp4app}}">ダウンロード</a>
        <!-- <a href="https://dev.videosalon.org/ext_cooperation/videosalon/s3VideoPlay.php?reservation_id={{$log->gp4app}}">再生</a> -->
    @break
    @case('stg-dashboard.videosalon.org')
        <a href="https://stg.videosalon.org/ext_cooperation/videosalon/s3VideoDownload.php?reservation_id={{$log->gp4app}}">ダウンロード</a>
        <!-- <a href="https://stg.videosalon.org/ext_cooperation/videosalon/s3VideoPlay.php?reservation_id={{$log->gp4app}}">再生</a> -->
    @break
    @case('dashboard.videosalon.org')
        <a href="https://videosalon.org/ext_cooperation/videosalon/s3VideoDownload.php?reservation_id={{$log->gp4app}}">ダウンロード</a>
        <!-- <a href="https://videosalon.org/ext_cooperation/videosalon/s3VideoPlay.php?reservation_id={{$log->gp4app}}">再生</a> -->
    @break
    @default
@endswitch
@endif
@if ($log->kind == 361)
@switch($_SERVER['HTTP_HOST'])
    @case('127.0.0.1:8000')
        <a href="https://dev.videosalon.org/ext_cooperation/videosalon/s3VideoDownload.php?rightnow_id={{$log->gp4app}}">ダウンロード</a>
        <!-- <a href="https://dev.videosalon.org/ext_cooperation/videosalon/s3VideoPlay.php?rightnow_id={{$log->gp4app}}">再生</a> -->
    @break
    @case('dev-dashboard.videosalon.org')
        <a href="https://dev.videosalon.org/ext_cooperation/videosalon/s3VideoDownload.php?rightnow_id={{$log->gp4app}}">ダウンロード</a>
        <!-- <a href="https://dev.videosalon.org/ext_cooperation/videosalon/s3VideoPlay.php?rightnow_id={{$log->gp4app}}">再生</a> -->
    @break
    @case('stg-dashboard.videosalon.org')
        <a href="https://stg.videosalon.org/ext_cooperation/videosalon/s3VideoDownload.php?rightnow_id={{$log->gp4app}}">ダウンロード</a>
        <!-- <a href="https://stg.videosalon.org/ext_cooperation/videosalon/s3VideoPlay.php?rightnow_id={{$log->gp4app}}">再生</a> -->
    @break
    @case('dashboard.videosalon.org')
        <a href="https://videosalon.org/ext_cooperation/videosalon/s3VideoDownload.php?rightnow_id={{$log->gp4app}}">ダウンロード</a>
        <!-- <a href="https://videosalon.org/ext_cooperation/videosalon/s3VideoPlay.php?rightnow_id={{$log->gp4app}}">再生</a> -->
    @break
    @default
@endswitch
@endif
</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- The Modal -->
<div class="modal" id="downloadAppraiserLogs">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">{{ $appraiser->email }} ログ調査</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form method="post" action="{{ route('downloadAppraiserLogs', ['id' => $appraiser->id]) }}">
                @csrf
                <!-- Modal body -->
                <div class="modal-body">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="date" id="all" value="all" checked>
                        <label class="form-check-label" for="all">
                            全件
                        </label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="date" id="last_month" value="last_month">
                        <label class="form-check-label" for="last_month">
                            直近1ヶ月
                        </label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="date" id="last_3_month" value="last_3_month">
                        <label class="form-check-label" for="last_3_month">
                            直近3ヶ月
                        </label>
                    </div>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">キャンセル</button>
                    <button type="submit" class="btn btn-primary">ログデータをダウンロード(CSV)</button>
                </div>
            </form>

        </div>
    </div>
</div>

<div class="modal" id="changePointOfAppraiser">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">{{ $appraiser->user->info->email }} 売上額変更</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <form method="post" action="{{ route('updateAppraiserPoint', ['id' => $appraiser->id]) }}">
                @csrf
                <!-- Modal body -->
                <div class="modal-body">
                    <legend>変更内容</legend>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="type" id="addition" value="addition" checked>
                        <label class="form-check-label" for="addition">
                            加算
                        </label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="type" id="subtraction" value="subtraction">
                        <label class="form-check-label" for="subtraction">
                            減算
                        </label>
                    </div>

                    <div class="form-group mt-2">
                        <legend>売上額</legend>
                        <input type="number" class="form-control" name="point" required>
                    </div>
                    <div class="form-group mt-2">
                        <legend>変更理由(全角200文字)</legend>
                        <textarea class="form-control" rows="3" name="reason"></textarea>
                    </div>
                    <input type="hidden" name="mod_date" value="{{ $appraiser->mod_date }}">
                    <span class="form-text text-danger error-msg"></span>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">キャンセル</button>
                    <button type="submit" class="btn btn-primary">適用</button>
                </div>
            </form>

        </div>
    </div>
</div>

<div class="modal" id="updateStatusOfAppraiser">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">{{ $appraiser->user->info->email }} 占い師情報変更</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form method="post" action="{{ route('updateAppraiserProfile', ['id' => $appraiser->id]) }}" enctype="multipart/form-data">
                @csrf
                <!-- Modal body -->
                <div class="modal-body">

                    <div class="form-group">
                        <legend>占い師ID</legend>
                        <label class="control-label">{{ $appraiser->id }}</label>
                    </div>

                    <div class="form-group">
                        <legend>写真</legend>

                        <div id="previewOn">
                            <div class="d-flex align-items-start justify-content-center">
                                <button class="close invisible dummy">&times;</button>
                                <img id="preview" src="" style="width:200px;height:auto">
                                <button id="btnFileDelete" type="button" class="close">&times;</button>
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
                            <input id="imageBefore" type="hidden" value="{{ $appraiser->image }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <legend>名前</legend>
                        <input class="form-control" name="name" value="{{ $appraiser->name }}" required>
                    </div>

                    <div class="form-group">
                        <legend>性別</legend>
                        <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" id="gender1"
                                       name="gender" value="0" {{ $appraiser->gender == 0 ? 'checked' : '' }}>
                            <label class="custom-control-label" for="gender1">未設定</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" id="gender2"
                                       name="gender" value="1" {{ $appraiser->gender == 1 ? 'checked' : '' }}>
                            <label class="custom-control-label" for="gender2">女性</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" id="gender3"
                                       name="gender" value="2" {{ $appraiser->gender == 2 ? 'checked' : '' }}>
                            <label class="custom-control-label" for="gender3">男性</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" id="gender4"
                                       name="gender" value="3" {{ $appraiser->gender == 3 ? 'checked' : '' }}>
                            <label class="custom-control-label" for="gender4">隠す</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <legend>プロフィール１</legend>
                        <textarea class="form-control" name="profile1" rows="5" required>{{ $appraiser->profile1 }}</textarea>
                    </div>

                    <div class="form-group">
                        <legend>プロフィール２</legend>
                        <textarea class="form-control" name="profile2" rows="5" required>{{ $appraiser->profile2 }}</textarea>
                    </div>

                    <div class="form-group">
                        <legend>占い種類</legend>
                        <div class="row">
                            @foreach ($types as $type)
                            <div class="col-12 col-md-6">
                                <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="type_{{ $loop->index }}"
                                                name="types[]" value="{{ $type }}" {{ in_array($type, $appraiser_types) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="type_{{ $loop->index }}">{{ $type }}</label>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <label class="control-label">他</label>
                        <input class="form-control" name="other_types" value="{{ implode(',', $other_types) }}">
                    </div>

                    <div class="form-group">
                        <legend>都道府県</legend>
                        <select class="custom-select" name="pref_no">
                            @foreach (App\Consts\CodeConsts::PREFECTURES as $name => $code)
                            <option value="{{ $code }}" {{ $code == $appraiser->pref_no ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <legend>１分あたりのポイント数</legend>
                        <input class="form-control" name="point_purchase" value="{{ $appraiser_point->point_purchase }}">
                    </div>

                    <div class="form-group">
                        <legend>プロフィールリンクボタン</legend>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input" id="link1" name="link" value="1" {{ $appraiser->link == 1 ? 'checked' : '' }}>
                            <label class="custom-control-label" for="link1">有効</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input" id="link0" name="link" value="0" {{ $appraiser->link == 0 ? 'checked' : '' }}>
                            <label class="custom-control-label" for="link0">無効</label>
                        </div>

                        <div>
                            <div>ボタン名</div>
                            <input type="text" class="form-control" name="link_name" maxlength="64" placeholder="" value="{{ $appraiser->link_name }}" />
                        </div>
                        <div>
                            <div>リンク先URL</div>
                            <input type="url" class="form-control" name="link_url"  maxlength="128"  placeholder="https://" value="{{ $appraiser->link_url }}" />
                        </div>
                    </div>

                    <input type="hidden" name="mod_date" value="{{ $appraiser->mod_date }}">
                    <span class="form-text text-danger error-msg"></span>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">キャンセル</button>
                    <button type="submit" class="btn btn-primary">適用</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal" id="changeStatusOfAppraiser">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">{{ $appraiser->user->info->email }} デビュー可否</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <form method="post" action="{{ route('changeAppraiserStatus', ['id' => $appraiser->id]) }}">
                @csrf
                <!-- Modal body -->
                <div class="modal-body">
                    <legend>本サイトへ表示する</legend>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status" id="status1" value="1" {{ $appraiser->status == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="status1">
                            表示
                        </label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status" id="status2" value="0" {{ $appraiser->status == 0 ? 'checked' : '' }}>
                        <label class="form-check-label" for="status2">
                            非表示
                        </label>
                    </div>

                    <div class="form-group mt-2">
                        <legend>申し送り事項(全角200文字)</legend>
                        <textarea class="form-control" rows="3" name="authorizer_report">{{ $appraiser->authorizer_report }}</textarea>
                    </div>
                    <input type="hidden" name="mod_date" value="{{ $appraiser->mod_date }}">
                    <span class="form-text text-danger error-msg"></span>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">キャンセル</button>
                    <button type="submit" class="btn btn-primary">適用</button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection

@section('script')
<script type="text/javascript">
    $(function() {

        $('#changePointOfUser, #updateStatusOfAppraiser, #changeStatusOfAppraiser').on('show.bs.modal', function(e) {
            var $this = $(this);
            $this.find('form')[0].reset();
            if ($this.is('#updateStatusOfAppraiser')) {
                var image = $('#imageBefore').val();
                if (image) {
                    $('#preview').attr('src', image);
                    $('#previewOn').show();
                    $('#previewOff').hide();
                    $("#btnFileDelete").show();
                }
                else {
                    $('#previewOn').hide();
                    $('#previewOff').show();
                    $("#btnFileDelete").hide();
                }
                $("#imageStatus").val('0');
            }
            $this.find('.error-msg').html('');
        });
        $('#changePointOfUser form').submit(function() {
            var $this = $(this);
            var errors = [];
            if ($this.find('[name="type"]:checked').length == 0) {
                errors.push('加算または減算を選択してください。');
            }
            if ($this.find('[name="reason"]').val().replaceAll(/\n/g, '\r\n').length > 200) {
                errors.push('変更理由は200文字以内で入力してください。');
            }
            if (errors.length > 0) {
                $this.find('.error-msg').html(errors.join('<br />'));
                return false;
            }
        });

        // $('#updateStatusOfAppraiser form').submit(function() {
        //     var $this = $(this);
        //     var errors = [];
        //     if ($this.find('[name="profile1"]').val().replaceAll(/\n/g, '\r\n').length > 200) {
        //         errors.push('プロフィール１は200文字以内で入力してください。');
        //     }
        //     if ($this.find('[name="profile2"]').val().replaceAll(/\n/g, '\r\n').length > 200) {
        //         errors.push('プロフィール２は200文字以内で入力してください。');
        //     }
        //     if (errors.length > 0) {
        //         $this.find('.error-msg').html(errors.join('<br />'));
        //         return false;
        //     }
        // });

        $('#changeStatusOfAppraiser form').submit(function() {
            var $this = $(this);
            var errors = [];
            if ($this.find('[name="status"]:checked').length == 0) {
                errors.push('表示または非表示を選択してください。');
            }
            if ($this.find('[name="authorizer_report"]').val().replaceAll(/\n/g, '\r\n').length > 200) {
                errors.push('申し送り事項は200文字以内で入力してください。');
            }
            if (errors.length > 0) {
                $this.find('.error-msg').html(errors.join('<br />'));
                return false;
            }
        });

        $('#btnFileUpload').on('click', function() {
            $('#imageFile').trigger("click");
        });

        $('#btnFileDelete').on('click', function() {
            $('#imageFile').val('');
            $('#imageStatus').val($('#imageBefore').val() ? '-1' : '0');
            $('#previewOn').hide();
            $('#previewOff').show();
            $("#btnFileDelete").hide();
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
    });
</script>
@endsection