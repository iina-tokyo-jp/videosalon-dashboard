@php
    if (session()->has('queryParams')) {
        $sesQueryParams = json_decode(session('queryParams'));
    }
    else {
        $sesQueryParams = '';
    }
    $type_order = session('typeOrder');
@endphp

@extends('layouts.app')
@section('nav_name', '占い師表示順管理')

@section('content')
@include('disporder.selector')

    <div class="mt-3">
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
        </div>

        <form>
            @csrf
            <div class="form-row">
                <div class="col-md-4 mb-3">
                    <select class="custom-select" name="type_search">
                        <option value="">検索項目を選んで下さい</option>
                        <option
                            value="username" {{ $sesQueryParams->type_search == 'username' ? 'selected' : '' }}>
                            ユーザー名
                        </option>
                        <option
                            value="actual_name" {{ $sesQueryParams->type_search == 'actual_name' ? 'selected' : '' }}>
                            氏名
                        </option>
                        <option
                            value="phone_number" {{ $sesQueryParams->type_search == 'phone_number' ? 'selected' : '' }}>
                            電話番号
                        </option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <input type="text" class="form-control" value="{{ $sesQueryParams->keyword }}"
                           placeholder="こちらに検索内容を入力します" name="keyword">
                </div>
                <div class="col-md-4 mb-3">
                    <button type="submit" class="btn btn-primary mb-2">検索</button>
                </div>
            </div>
        </form>

        <form method="post" action="{{ route('disporder.changeorder') }}" id="set_order">
            @csrf
            <div>
                <div class="d-flex align-items-center">
                    <div class="mb-3">
                        <span>UU：{{ $appraisers->total() }}</span>
                    </div>
                    <div class="ml-3">
                        {{ $appraisers->appends($queryParams)->links() }}
                    </div>
                    <div class="ml-3">
                        <button type="submit" class="btn btn-primary mb-2">更新</button>
                    </div>
                    <div class="mb-3 ml-3">
                        <span>{{ session('flashmessage') }}</span>
                    </div>
                </div>
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th scope="col">ユーザー名</th>
                        <th scope="col">氏名</th>
                        <th scope="col">重み付け<br>（0〜999、標準値：500）</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($appraisers as $appraiser)
                        <tr>
                            <td>{{ $appraiser->user->info->email }}</td>
                            <td>{{ $appraiser->appraiserinfo->name }}</td>
                            <td>
                                <input type="text" class="form-control" name="disporderNum-{{ $loop->index }}"
                                    value="{{ number_format($appraiser->disp_order_num) }}">
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </form>
    </div>
@endsection
