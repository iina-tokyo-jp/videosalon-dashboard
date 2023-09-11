@php
    if (session()->has('typeManage')) {
        $typeManageValue = session('typeManage');
    }
    else {
        $typeManageValue = 'NoSet';
    }
@endphp

<div class="mt-3">
    <form id="select_manage">
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
                <select class="custom-select" name="type_manage" id="type_manage" form="select_manage">
                    <option value="">管理内容を選んで下さい</option>
                    <option value="dayofweek" @php if ($typeManageValue == 'dayofweek') { echo 'selected'; } @endphp>集計開始曜日</option>
                    <option value="weekly" @php if ($typeManageValue == 'weekly') { echo 'selected'; } @endphp>週間</option>
                    <option value="monthly" @php if ($typeManageValue == 'monthly') { echo 'selected'; } @endphp>月間</option>
                    <option value="recommended" @php if ($typeManageValue == 'recommended') { echo 'selected'; } @endphp>おすすめ</option>
                </select>
            </div>
        </div>
    </form>
</div>

@section('script')
    <script>
        $(function () {
            $('#type_manage').change(
                function(){
                    if ($('#type_manage').val() != "") {
                        var url = "{{ route('rankings.select') }}" + '?type=' + $('#type_manage').val();
                        window.location.href = url;
                    }
                }
            );
        });
    </script>
@endsection
