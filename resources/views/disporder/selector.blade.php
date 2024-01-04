@php
    if (session()->has('typeOrder')) {
        $typeOrder = session('typeOrder');
    }
    else {
        $typeOrder = 0;
    }
@endphp

<div class="mt-3">
    <form id="select_order">
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
                <select class="custom-select" name="type_order" id="type_order" form="select_order">
                    <option value="" selected>管理内容を選んで下さい</option>
                    <option value=2 @php if ($typeOrder == 2) { echo 'selected'; } @endphp>レビューが多い順</option>
                    <option value=3 @php if ($typeOrder == 3) { echo 'selected'; } @endphp>新着順(登録日の早い順)</option>
                    <option value=4 @php if ($typeOrder == 4) { echo 'selected'; } @endphp>金額が高い順</option>
                    <option value=5 @php if ($typeOrder == 5) { echo 'selected'; } @endphp>金額が安い順</option>
                </select>
            </div>

        </div>
    </form>
</div>

@section('script')
    <script>
        $(function () {
            $('#type_order').change(
                function(){
                    if ($('#type_order').val() != "") {
                        var url = "{{ route('disporder.select') }}" + '?type_order=' + $('#type_order').val();
                        window.location.href = url;
                    }
                }
            );
        });
    </script>
@endsection
