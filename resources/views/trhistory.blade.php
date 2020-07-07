@if(count($history)>0)
<table class="table table-striped table-borderless">
    <thead>
        <tr>
            <th style="width: 25%;">Loại giao dịch</th>
            <th style="width: 25%;">Số tiền giao dịch</th>
            <th style="width: 25%;">Thông tin chi tiết</th>
            <th style="width: 25%;">Thời điểm giao dịch</th>
        </tr>
    </thead>
    <tbody>
    @foreach($history as $transaction)
        <tr>
            @if($transaction->is_in == 1)
            <td class="text-primary">Tiền vào</td>
            @else
            <td class="text-danger">Tiền ra</td>
            @endif
            <td>{{$transaction->amount}} USD</td>
            <td>{{$transaction->description}}</td>
            <td>{{$transaction->created_at}}</td>
        </tr>
        @endforeach
    </tbody>
</table>
<div class="mt-4">
{!! $history->render() !!}
</div>
@else
<div class="stretch-card">
    <div class="card bg-light">
        <div class="card-body">
            <h4 class="font-weight-bold pt-2 text-center">Chưa có giao dịch</h4>
        </div>
    </div>
</div>
@endif