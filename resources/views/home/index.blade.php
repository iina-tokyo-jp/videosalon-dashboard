@extends('layouts.app')
@section('nav_name', 'スーパートップ')
@section('content')

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>&nbsp;</th>
            @for ($i = count($days) -1; $i > -1; $i--)
                <th>{{ $days[$i] }}</th>
            @endfor
                <th>合計</th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <th>入会者数</th>
            @for ($i = count($days) -1; $i > -1; $i--)
                <td class="text-right">{{ number_format($usersValues->{"cnt_{$i}"} ? intval($usersValues->{"cnt_{$i}"}) : 0) }}</td>
            @endfor
                <td class="text-right">{{ number_format($usersValues->sum) }}</td>
            </tr>
            <tr>
                <th>入会者数累計</th>
            @for ($i = count($days) -1; $i > -1; $i--)
                <td class="text-right">{{ number_format($usersTotalValues->{"cnt_{$i}"} ? intval($usersTotalValues->{"cnt_{$i}"}) : 0) }}</td>
            @endfor
<!-- <td class="text-center">-</td> -->
                <td class="text-right">{{ number_format($usersTotalValues->sum) }}</td>
            </tr>

            <tr>
                <th>決済額</th>
            @for ($i = count($days) -1; $i > -1; $i--)
                <td class="text-right">￥{{ number_format($payValues->{"pay_{$i}"} ? intval($payValues->{"pay_{$i}"}) : 0) }}</td>
            @endfor
                <td class="text-right">￥{{ number_format($payValues->sum) }}</td>
            </tr>
            <tr>
                <th>決済額累計</th>
            @for ($i = count($days) -1; $i > -1; $i--)
                <td class="text-right">￥{{ number_format($payTotalValues->{"pay_{$i}"} ? intval($payTotalValues->{"pay_{$i}"}) : 0) }}</td>
            @endfor
<!-- <td class="text-center">-</td> -->
                <td class="text-right">￥{{ number_format($payTotalValues->sum) }}</td>
            </tr>

            <tr>
                <th>売上額</th>
            @for ($i = count($days) -1; $i > -1; $i--)
                <td class="text-right">￥{{ number_format($salesValues->{"sales_{$i}"} ? intval($salesValues->{"sales_{$i}"}) : 0) }}</td>
            @endfor
                <td class="text-right">￥{{ number_format($salesValues->sum) }}</td>
            </tr>
            <tr>
                <th>売上額累計</th>
            @for ($i = count($days) -1; $i > -1; $i--)
                <td class="text-right">￥{{ number_format($salesTotalValues->{"sales_{$i}"} ? intval($salesTotalValues->{"sales_{$i}"}) : 0) }}</td>
            @endfor
<!-- <td class="text-center">-</td> -->
                <td class="text-right">￥{{ number_format($salesTotalValues->sum) }}</td>
            </tr>

            <tr>
                <th>広告費</th>
            @for ($i = count($days) -1; $i > -1; $i--)
                <td class="text-right">￥{{ number_format($adcosValues->{"adcost_{$i}"} ? intval($adcosValues->{"adcost_{$i}"}) : 0) }}</td>
            @endfor
                <td class="text-right">￥{{ number_format($adcosValues->sum) }}</td>
            </tr>
            <tr>
                <th>広告費累計</th>
            @for ($i = count($days) -1; $i > -1; $i--)
                <td class="text-right">￥{{ number_format($adcosTotalValues->{"adcost_{$i}"} ? intval($adcosTotalValues->{"adcost_{$i}"}) : 0) }}</td>
            @endfor
<!-- <td class="text-center">-</td> -->
                <td class="text-right">￥{{ number_format($adcosTotalValues->sum) }}</td>
            </tr>

        </tbody>
    </table>
@endsection
