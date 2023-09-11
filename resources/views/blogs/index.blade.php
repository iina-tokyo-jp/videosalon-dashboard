@extends('layouts.app')
@section('nav_name', 'blog管理')
@section('content')
    <div class="mt-3">
        <form>
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
                        <option value="written" {{app('request')->input('type_search') == 'written' ? 'selected' : ''}}>送信元</option>
                        <option value="status" {{app('request')->input('type_search') == 'status' ? 'selected' : ''}}>状態</option>
                        <option value="authorizer" {{app('request')->input('type_search') == 'authorizer' ? 'selected' : ''}}>承認者</option>
                        <option value="date" {{app('request')->input('type_search') == 'date' ? 'selected' : ''}}>日時</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <input type="text" class="form-control" value="{{ app('request')->input('keyword') }}"
                           placeholder="こちらに検索内容を入力します" name="keyword">
                </div>
                <div class="col-md-4 mb-3">
                    <button type="submit" class="btn btn-primary mb-2">検索</button>
                    <button type="button" class="btn btn-info mb-2 d-none" id="merge_button">承認/変更</button>
                </div>
            </div>
        </form>
        <div>
            <div class="d-flex align-items-center">
                <div class="mb-3">
                    <span>{{ $blogs->firstItem() }} - {{ $blogs->lastItem() }} を表示</span>
                </div>
                <div class="ml-3">
                    {{ $blogs->appends($queryParams)->links() }}
                </div>
            </div>
            <table class="table table-bordered" id="find-table">
                <thead>
                <tr>
                    <th scope="col">選択</th>
                    <th scope="col">送信元</th>
                    <th scope="col">文面</th>
                    <th scope="col">状態</th>
                    <th scope="col">承認者</th>
                    <th scope="col">日時</th>

                </tr>
                </thead>
                <tbody>
                @foreach($blogs as $blog)
                    <tr>
                        <td>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" value="{{$blog->id}}"
                                       id="customCheck{{$blog->id}}"
                                       data-date="{{$blog->add_date->format('Y/m/d H:i')}}"
                                       data-written="{{ $blog->appraiser_name }} ({{$blog->appraiser_account}})"
                                       data-body="{{ $blog->body }}"
                                       data-image="{{ $blog->image1 }}"
                                       data-status="{{ $blog->status }}"
                                       data-authorizer-report="{{ $blog->authorizer_report }}"
                                       data-appraiser-account="{{ $blog->appraiser_account }}"
                                >
                                <label class="custom-control-label" for="customCheck{{$blog->id}}"></label>
                            </div>
                        </td>
                        <td>
                            {{ $blog->appraiser_name }}
                            {{ $blog->appraiser_account }}
                        </td>
                        <td class="text-truncate" style="max-width: 300px;">{{ $blog->body }}</td>
                        <td class="h5">
                            @switch($blog->status)
                                @case(-1)
                                    <span class="badge badge-danger">{{ $status_options[$blog->status] }}</span>
                                    @break
                                @case(1)
                                    <span class="badge badge-primary">{{ $status_options[$blog->status] }}</span>
                                    @break
                                @case(2)
                                    <span class="badge badge-success">{{ $status_options[$blog->status] }}</span>
                                    @break
                                @default
                                    <span class="badge badge-secondary">{{ $status_options[$blog->status] }}</span>
                                    @break
                            @endswitch
                        </td>
                        <td>
                            {{ $blog->authorizer_name }}
                            {{ $blog->authorizer_account }}
                        </td>
                        <td>{{ $blog->add_date->format('Y/m/d H:i') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal" id="approveChangeMultiBlogsStatus">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">blog承認/変更(複数)</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form method="post" action="{{ route('blogs.changeAllStatus') }}">
                    @csrf
                    <input type="hidden" name="ids" class="ids">
                    <!-- Modal body -->
                    <div class="modal-body">
                        <p>※この状態変更は選択したすべてのblogに適用されます。</p>
                        <legend>掲載状態変更</legend>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="status" id="statusAll1" value="2">
                            <label class="form-check-label" for="statusAll1">
                                すべて掲載(修正)
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="status" id="statusAll2" value="1" checked>
                            <label class="form-check-label" for="statusAll2">
                                すべて掲載
                            </label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="status" id="statusAll3" value="0">
                            <label class="form-check-label" for="statusAll3">
                            すべて未確認
                            </label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="status" id="statusAll4" value="-1">
                            <label class="form-check-label" for="statusAll4">
                                すべて非掲載
                            </label>
                        </div>
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

    <div class="modal" id="approveChangeEachBlogStatus">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title"><span id="blog_appraiser_account"></span> blog承認/変更</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form method="post" action="{{ route('blogs.changeEachStatus') }}">
                    @csrf
                    <input type="hidden" name="ids" class="ids">

                    <!-- Modal body -->
                    <div class="modal-body">

                        <div class="row">
                            <div class="col-12 col-md-8">
                                <div class="border px-3">
                                    <div class="row">
                                        <label for="staticEmail" class="col-sm-5 col-form-label">日時:</label>
                                        <div class="col-sm-7">
                                            <label class="col-form-label" id="blog_date"></label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label for="inputPassword" class="col-sm-5 col-form-label">送信元:</label>
                                        <div class="col-sm-7">
                                            <label class="col-form-label"  id="blog_written"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <img src="" class="w-100 d-none" id="blog_img">
                            </div>
                        </div>
                        
                        <textarea id="blog_body" class="form-control mt-3" rows="4" name="body" required></textarea>

                        <div class="form-group mt-2">
                            <legend>掲載状態変更</legend>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="blog_status1" value="2">
                                <label class="form-check-label" for="blog_status1">
                                掲載(修正)
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="blog_status2" value="1">
                                <label class="form-check-label" for="blog_status2">
                                    掲載
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="blog_status3" value="0">
                                <label class="form-check-label" for="blog_status3">
                                    未確認
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="blog_status4" value="-1">
                                <label class="form-check-label" for="blog_status4">
                                    非掲載
                                </label>
                            </div>
                        </div>

                        <div class="form-group mt-2">
                            <legend>業務対応申し送り(全角200文字)</legend>
                            <textarea id="blog_authorizer_report" class="form-control" rows="3" name="authorizer_report"></textarea>
                        </div>
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

            $("#merge_button").click(function(event){
                event.preventDefault();
                let searchIDs = $("#find-table input:checkbox:checked").map(function(){
                    return $(this).val();
                }).get(); // <----
                if (searchIDs.length) {
                    $('.ids').val(searchIDs);
                    if (searchIDs.length === 1) {
                        $('#blog_date').text($(`#customCheck${searchIDs[0]}`).data('date'))
                        $('#blog_written').text($(`#customCheck${searchIDs[0]}`).data('written'))
                        $('#blog_body').val($(`#customCheck${searchIDs[0]}`).data('body'))
                        if ($(`#customCheck${searchIDs[0]}`).data('image')) {
                            $('#blog_img').attr("src", $(`#customCheck${searchIDs[0]}`).data('image'));
                            $('#blog_img').removeClass('d-none');
                        } else {
                            $('#blog_img').addClass('d-none');
                        }
                        $('#blog_status1, #blog_status2, #blog_status3, #blog_status4').prop('checked', false);
                        switch($(`#customCheck${searchIDs[0]}`).data('status')) {
                            case 2:
                                $('#blog_status1').prop('checked', true);
                                break;
                            case 1:
                                $('#blog_status2').prop('checked', true);
                                break;
                                case 0:
                                $('#blog_status3').prop('checked', true);
                                break;
                            case -1:
                                $('#blog_status4').prop('checked', true);
                                break;
                            default:
                                break;
                        }
                        $('#blog_authorizer_report').text($(`#customCheck${searchIDs[0]}`).data('authorizer-report'))
                        $('#blog_appraiser_account').text($(`#customCheck${searchIDs[0]}`).data('appraiser-account'))
                        $(".error-msg").html('');
                        $('#approveChangeEachBlogStatus').modal('show')
                    } else {
                        $('#approveChangeMultiBlogsStatus').modal('show')
                    }
                }
            });

            $('#approveChangeMultiBlogsStatus').on('show.bs.modal', function(e) {
                var $this = $(this);
                $this.find("form")[0].reset();
            });

            $('#approveChangeEachBlogStatus form').submit(function() {
                var $this = $(this);
                var errors = [];
                if ($this.find('[name="status"]:checked').length == 0) {
                    errors.push('掲載または非掲載を選択してください。');
                }
                if ($this.find('[name="authorizer_report"]').val().replaceAll(/\n/g, '\r\n').length > 200) {
                    errors.push('業務対応申し送りは200文字以内で入力してください。');
                }
                if (errors.length > 0) {
                    $this.find('.error-msg').html(errors.join('<br />'));
                    return false;
                }
            });
        })
    </script>
@endsection
