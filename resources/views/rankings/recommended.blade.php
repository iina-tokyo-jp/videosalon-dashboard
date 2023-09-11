@extends('layouts.app')
@section('nav_name', 'ランキング管理')

@section('content')
@include('rankings.selector')

@php
    $csv = "";
    if (isset($works_appraiser_lists)) {
        $cnt = count($works_appraiser_lists);
        if ($cnt > 0) {
            for ($i = 0; $i < $cnt - 1; $i++) {
                $csv .= $works_appraiser_lists[$i]->appraiser_id . ",";
            }
            $csv .= $works_appraiser_lists[$cnt - 1]->appraiser_id;
        }
    }
@endphp

    <div class="mt-3">
        <form method="get" action="{{ route('rankings.recommended') }}" id="select_target">
            <div class="form-row">
                <div class="col-md-4 mb-3">
                    <select class="custom-select" name="select_recommendedrank" id="type_date" form="select_target">
                        <option value="">対象とするランキングを選んで下さい</option>
                        <option value="0" {{$recommendedrank == 0 ? 'selected' : ''}}>自動更新しない</option>
                        <option value="31" {{$recommendedrank == 31 ? 'selected' : ''}}>初回リピート数</option>
                        <option value="33" {{$recommendedrank == 33 ? 'selected' : ''}}>鑑定回数</option>
                        <option value="34" {{$recommendedrank == 34 ? 'selected' : ''}}>デビュー日が若い</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <button type="submit" class="btn btn-primary mb-2" form="select_target">設定</button>
                </div>
            </div>
        </form>
        <hr />

@if ($recommendedrank != 0)
        <form method="get" action="{{ route('rankings.recommended') }}" id="recommended" name="recommended">
            <div class="col-md-4">
                <div class="mb-3">
                    <span>CSV (ID)</span>
                </div>
                <div class="mb-3">
                    <input type="text" name="csv" style="width:100%; height:2rem" value="{{$csv}}" />
                </div>
                <div class="col-md-4 mb-3">
                    <button type="submit" class="btn btn-primary mb-2" form="recommended">確認</button>
                </div>
            </div>
        </form>

        <div class="col-md-4" id="areaOneDate">
            <form method="get" action="{{ route('rankings.recommendedUpdate') }}" id="recommended-update" name="recommended-update">
                <table class="table table-bordered apr_list" id="find-table">
                    <thead>
                        <tr>
                            <th scope="col">rank</th>
                            <th scope="col">ID</th>
                            <th scope="col">名前</th>
                            <th scope="col">写真</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (isset($works_appraiser_lists))
                            @foreach($works_appraiser_lists as $works_appraiser)
                            <tr>
                                <td scope="col" class="text-left">{{ $works_appraiser->sort_no }}</td>
                                <td scope="col" class="text-left">{{ $works_appraiser->appraiser_id }}</td>
                                <td scope="col" class="text-left">{{ $works_appraiser->name }}</td>
                                <td scope="col" class="text-left"><img src="{{ $works_appraiser->image }}" width="100px"/></td>
                            </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
                <div class="col-md-4 mb-3">
                    <button type="submit" class="btn btn-primary mb-2" form="recommended-update">更新</button>
                </div>
                <input type="hidden" name="csv" value="{{$csv}}" />
            </form>
        </div>
@endif
    </div>
@endsection


