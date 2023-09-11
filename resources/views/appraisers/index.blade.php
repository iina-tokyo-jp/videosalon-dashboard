@extends('layouts.app')
@section('nav_name', '占い師管理')
@section('content')
    <div class="mt-3">
        <form>
            <div class="form-row">
                <div class="col-md-4 mb-3">
                    <select class="custom-select" name="type_search">
                        <option value="">検索項目を選んで下さい</option>
                        <option
                            value="username" {{app('request')->input('type_search') == 'username' ? 'selected' : ''}}>
                            ユーザー名
                        </option>
                        <option
                            value="actual_name" {{app('request')->input('type_search') == 'actual_name' ? 'selected' : ''}}>
                            氏名
                        </option>
                        <option
                            value="phone_number" {{app('request')->input('type_search') == 'phone_number' ? 'selected' : ''}}>
                            電話番号
                        </option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <input type="text" class="form-control" value="{{ app('request')->input('keyword') }}"
                           placeholder="こちらに検索内容を入力します" name="keyword">
                </div>
                <div class="col-md-4 mb-3">
                    <button type="submit" class="btn btn-primary mb-2">検索</button>
                </div>
            </div>
        </form>

        <div>
            <div class="d-flex align-items-center">
                <div class="mb-3">
                    <span>UU：{{ $appraisers->total() }}</span>
                </div>
                <div class="ml-2 mb-3">
                    <button id="btnCsvDl" class="btn btn-primary">CSV DL</button>
                </div>
                <div class="ml-3">
                    {{ $appraisers->appends($queryParams)->links() }}
                </div>
            </div>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th scope="col">ユーザー名</th>
                    <th scope="col">氏名</th>
                    <th scope="col">最終ログイン</th>
                    <th scope="col">ポイント</th>
                </tr>
                </thead>
                <tbody>
                @foreach($appraisers as $appraiser)
                    <tr>
                        <th scope="row">
                            <a href="{{ route('showAppraiser', ['id' => $appraiser->id]) }}">
                                {{ $appraiser->user->info->email }}
                            </a>
                        </th>
                        <td>{{ $appraiser->name }}</td>
                        <td>{{ $appraiser->user->info->last_login_date->format('Y/m/d H:i') }}</td>
                        <td>{{ number_format($appraiser->user->info->point) }}pt</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('script')
<script type="text/javascript">
    $(function() {
        $('#btnCsvDl').click(function(e) {
            var $form = $('form');
            $form.attr('action', '{{ route("downloadAppraisers") }}').submit();
            setTimeout(function(){
                $form.removeAttr('action');
            }, 1);
        });
    });
</script>
@endsection
