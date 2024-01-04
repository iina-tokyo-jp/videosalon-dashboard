@extends('layouts.app')
@section('nav_name', '録画管理')
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

                <div class="container">
                    <div class="row">
                        <div class="col-md-1">
                            利用者
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="user_id">ID</label>
                                    <input type="text" class="form-control" id="user_id" placeholder="ID" name="user_id" value="{{app('request')->input('user_id')}}">
                                </div>
                                <div class="col-md-8">
                                    <label for="user_name">名前</label>
                                    <input type="text" class="form-control" id="user_name" placeholder="名前" name="user_name" value="{{app('request')->input('user_name')}}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-1">
                            占い師
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="appraiser_id">ID</label>
                                    <input type="text" class="form-control" id="appraiser_id" placeholder="ID" name="appraiser_id" value="{{app('request')->input('appraiser_id')}}">
                                </div>
                                <div class="col-md-8">
                                    <label for="appraiser_name">名前</label>
                                    <input type="text" class="form-control" id="appraiser_name" placeholder="名前" name="appraiser_name" value="{{app('request')->input('appraiser_name')}}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <label for="start_date">開始日</label>
                            <input id="start_date" type="date" class="form-control" name="start_date" value="{{app('request')->input('start_date')}}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <button type="submit" class="btn btn-primary mb-2">検索</button>
                            <input type="hidden" name="experienced" value="1" />
                        </div>
                    </div>
                </div>

            </div>
        </form>

        <hr />
        <div>
            <div class="table-responsive">
                <table class="table table-bordered" id="find-table">
                    <thead>
                        <tr>
                            <th scope="col">利用者ID</th>
                            <th scope="col">利用者</th>
                            <th scope="col">占い師ID</th>
                            <th scope="col">占い師名</th>
                            <th scope="col">開始時間</th>
                            <th scope="col">終了時間</th>
                            <th scope="col">ステータス</th>
                            <th scope="col">ダウンロード</th>
                        </tr>
                    </thead>

                    <tbody>
                    @foreach($mediacaptures as $mediacapture)
                        <tr>
                            <td>{{$mediacapture->user_id}}</td>
                            <td>{{$mediacapture->user_name}}</td>
                            <td>{{$mediacapture->appraiser_id}}</td>
                            <td>{{$mediacapture->appraiser_name}}</td>
                            <td>{{date_create($mediacapture->video_begin_date)->format('Y/m/d H-i-s')}}</td>
                            <td>{{date_create($mediacapture->video_end_date)->format('Y/m/d H-i-s')}}</td>
                            <td>
                                @if ($mediacapture->vm_status == 0)
                                    未動作
                                @elseif ($mediacapture->vm_status == 1)
                                    録画中
                                @elseif ($mediacapture->vm_status == 2)
                                    録画終了
                                @elseif ($mediacapture->vm_status == 3)
                                    合成中
                                @elseif ($mediacapture->vm_status == 4)
                                    再生可能
                                @endif
                            </td>
                            <td>
                                @if ($mediacapture->vm_status == 4)
                                    <a target="vs_video" href="https://{{$myname}}/ext_cooperation/videosalon/s3VideoPlay.php?video_id={{$mediacapture->video_id}}&site_id={{$mediacapture->site_id}}">PLAY</a>
                                    <a href="https://{{$myname}}/ext_cooperation/videosalon/s3VideoDownload.php?video_id={{$mediacapture->video_id}}&site_id={{$mediacapture->site_id}}">DL</a>
                                @else
                                    PLAY
                                    DL
                                @endif

                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
