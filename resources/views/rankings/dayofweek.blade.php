@extends('layouts.app')
@section('nav_name', 'ランキング管理')

@section('content')
@include('rankings.selector')

@php

@endphp
    <div class="mt-3">
<div>集計開始曜日</div>
        <form method="get" action="{{ route('rankings.dayofweek') }}" id="select_dayofweek" name="select_dayofweek">
            <div class="form-row">
                <div class="col-md-4 mb-3">
                    <input type="radio" name="dayofweek" value="0" {{$dayofweek == 0 ? 'checked' : ''}}>日</input>
                    <input type="radio" name="dayofweek" value="1" {{$dayofweek == 1 ? 'checked' : ''}}>月</input>
                    <input type="radio" name="dayofweek" value="2" {{$dayofweek == 2 ? 'checked' : ''}}>火</input>
                    <input type="radio" name="dayofweek" value="3" {{$dayofweek == 3 ? 'checked' : ''}}>水</input>
                    <input type="radio" name="dayofweek" value="4" {{$dayofweek == 4 ? 'checked' : ''}}>木</input>
                    <input type="radio" name="dayofweek" value="5" {{$dayofweek == 5 ? 'checked' : ''}}>金</input>
                    <input type="radio" name="dayofweek" value="6" {{$dayofweek == 6 ? 'checked' : ''}}>土</input>
                </div>
                <div class="col-md-4 mb-3">
                    <button type="submit" class="btn btn-primary mb-2" form="select_dayofweek">設定</button>
                </div>
            </div>
        </form>
    </div>
@endsection


