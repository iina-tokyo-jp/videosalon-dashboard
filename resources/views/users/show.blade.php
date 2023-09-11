@extends('layouts.app')
@section('nav_name', '利用者管理')
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
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">ユーザー管理</a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item nav-link" data-toggle="tab" href="#menu1">利用者ログ表示</a>
                        <a class="dropdown-item nav-link" href="#" data-toggle="modal" data-target="#downloadUserLogs">ログ調査</a>
                        <a class="dropdown-item nav-link" href="#" data-toggle="modal" data-target="#changePointOfUser">ポイント変更</a>
                        <a class="dropdown-item nav-link" href="#" data-toggle="modal" data-target="#changePointToleranceOfUser">許容ポイント数変更</a>
                        <a class="dropdown-item nav-link" href="#" data-toggle="modal" data-target="#updateStatusOfUser">IDステータス 変更
                        </a>
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
                            @switch($user->state)
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
                                <td>{{ $userinfo->email }}</td>
                            </tr>

                            <tr>
                                <th scope="row">最終ログイン日時</th>
                                <td>{{ $userinfo->last_login_date->format('Y/m/d H:i') }}</td>
                            </tr>

                            <tr>
                                <th scope="row">有効ポイント数</th>
                                <td>{{ number_format($userinfo->point) }}pt</td>
                            </tr>

                            <tr>
                                <th scope="row">許容ポイント数</th>
                                <td>{{ number_format($userinfo->point_tolerance) }}pt</td>
                            </tr>

                            <tr>
                                <th scope="row">ポイント更新日時</th>
                                <td>{{ $pointHistory->add_date ? $pointHistory->add_date->format('Y/m/d H:i') : '' }}</td>
                            </tr>

                            <tr>
                                <th scope="row">本名</th>
                                <td>{{ $userinfo->name }}</td>
                            </tr>

                            <tr>
                                <th scope="row">占い師名</th>
                                <td>{{ $user->actor_id == 2 ? $appraiser->name : '(占い活動なし)' }}
<!--
<br />
<a href="https://dev.videosalon.org/_admin_tools/appraisers_edit.html?site_id=1&user_id={{$userinfo->user_id}}">占い師情報-編集</a>
-->
</td>
                            </tr>

                            <tr>
                                <th scope="row">電話番号</th>
                                <td>{{ $userinfo->phoneno }}</td>
                            </tr>

                            <tr>
                                <th scope="row">SMS認証</th>
                                <td>{{ empty($userinfo->phoneno) ? '未認証' : "認証済({$userinfo->regphone_date->format('Y/m/d H:i')})" }}</td>
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
<div class="modal" id="downloadUserLogs">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">{{ $userinfo->email }} ログ調査</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form method="post" action="{{ route('downloadUserLogs', ['id' => $userinfo->id]) }}">
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

<div class="modal" id="changePointOfUser">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">{{ $userinfo->email }} ポイント変更</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <form method="post" action="{{ route('updateUserPoint', ['id' => $userinfo->id]) }}">
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
                        <legend>ポイント</legend>
                        <input type="number" class="form-control" name="point" required>
                    </div>

<div class="form-group mt-2">
    <legend>種別</legend>

                        <select class="custom-select" name="chargereason">
<!-- <option value="charge_card">事前払い(カード)</option> -->
                            <option value="charge_bank">事前払い(銀行振込)</option>
                            <option value="charge_bank">事前払い(その他)</option>
<!-- <option value="immediate_card">事後払い(カード)</option> -->
                            <option value="immediate_bank">事後払い(銀行振込)</option>
                            <option value="immediate_other">事後払い(その他)</option>
                            <option value="benefit">無料ポイント(入会特典)</option>
                            <option value="present_other">無料ポイント(その他)</option>
                        </select>
</div>

                    <div class="form-group mt-2">
                        <legend>変更理由(全角200文字)</legend>
                        <textarea class="form-control" rows="3" name="reason"></textarea>
                    </div>
                    <input type="hidden" name="mod_date" value="{{ $userinfo->mod_date }}" />
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


<div class="modal" id="changePointToleranceOfUser">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">{{ $userinfo->email }} 許容ポイント数変更</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <form method="post" action="{{ route('updateUserPointTolerance', ['id' => $userinfo->id]) }}">
                @csrf
                <!-- Modal body -->
                <div class="modal-body">
                    <legend>変更内容</legend>

                    <div class="form-group mt-2">
                        <legend>許容ポイント数</legend>
                        <input type="number" class="form-control" name="point_tolerance" value="{{ $userinfo->point_tolerance }}" required>
<div>
(許容ポイントはマイナスで指定)
</div>
                    </div>

                    <input type="hidden" name="mod_date" value="{{ $userinfo->mod_date }}" />
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






<div class="modal" id="updateStatusOfUser">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">{{ $userinfo->email }} ステータス変更</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <form method="post" action="{{ route('changeUserStatus', ['id' => $userinfo->user_id]) }}">
                @csrf
                <!-- Modal body -->
                <div class="modal-body">
                    <legend>占い師として活動</legend>
                    <div class="form-check">
                        <input id="actor_id1" class="form-check-input" type="radio" name="actor_id" value="2" {{ $user->actor_id == 2 ? 'checked' : '' }}>
                        <label class="form-check-label" for="actor_id1">
                            有効
                        </label>
                    </div>
                    <div class="form-check">
                        <input id="actor_id2" class="form-check-input" type="radio" name="actor_id" value="1" {{ $user->actor_id == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="actor_id2">
                            無効
                        </label>
                    </div>
                    <legend>利用者のステータス</legend>
                    <div class="form-check">
                        <input id="state1" class="form-check-input" type="radio" name="state" value="1" {{ $user->state == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="state1">
                            有効
                        </label>
                    </div>
                    <div class="form-check">
                        <input id="state2" class="form-check-input" type="radio" name="state" value="2" {{ $user->state == 2 ? 'checked' : '' }}>
                        <label class="form-check-label" for="state2">
                            有効(ブラック)
                        </label>
                    </div>
                    <div class="form-check">
                        <input id="state3" class="form-check-input" type="radio" name="state" value="0" {{ $user->state == 0 ? 'checked' : '' }}>
                        <label class="form-check-label" for="state3">
                            無効
                        </label>
                    </div>
                    <div class="form-check">
                        <input id="state4" class="form-check-input" type="radio" name="state" value="-1" {{ $user->state == -1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="state4">
                            無効(ブラック)
                        </label>
                    </div>
                    <input type="hidden" name="mod_date" value="{{ $user->mod_date }}">
                    <span class="form-text text-danger error-msg"></span>
                </div>
                
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">キャンセル</button>
                    <button type="submit" class="btn btn-success">適用</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
<script type="text/javascript">
    $(function() {

        $('#changePointOfUser, #updateStatusOfUser').on('show.bs.modal', function(e) {
            var $this = $(this);
            $this.find("form")[0].reset();
            $this.find(".error-msg").html('');
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

        $('#updateStatusOfUser form').submit(function() {
            var $this = $(this);
            var errors = [];
            if ($this.find('[name="actor_id"]:checked').length == 0) {
                errors.push('占い師として活動を選択してください。');
            }
            if ($this.find('[name="state"]:checked').length == 0) {
                errors.push('利用者のステータスを選択してください。');
            }
            if (errors.length > 0) {
                $this.find('.error-msg').html(errors.join('<br />'));
                return false;
            }
        });
    });
</script>
@endsection