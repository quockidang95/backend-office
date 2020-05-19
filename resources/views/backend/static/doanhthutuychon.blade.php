@extends('layouts.admin')
@section('content')
<?php
    $stores = App\Store::all();
?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Thông kê doanh thu tuỳ chọn</a></li>
    </ol>
</nav>
<div class="row">
<div class="input-group input-daterange col-4">
    <input type="text" class="form-control"  data-toggle="datepicker" id="start_date">
    <div class="input-group-addon">đến</div>
    <input type="text" class="form-control"  data-toggle="datepicker" id="end_date">
</div>
</div>
    <div class="input-group col-3 pt-3 pb-3">
        <select class="form-control form-control-sm" required name="store_code" id="storecode">
            @foreach ($stores as $key => $item)
            <option value="{{$item->store_code}}">{{$item->name}}</option>
            @endforeach
        </select>
    </div>
<div class="row">
<div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Tổng doanh thu</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="total_price"></div>
                            </div>
                            <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                            </div>
                        </div>
                        </div>
                    </div>
        </div>
</div>


@endsection
@section('script')
<link  href="{{ asset('datepicker/dist/datepicker.css') }}" rel="stylesheet">
<script src="{{ asset('datepicker/dist/datepicker.js') }}"></script>

<script>
    $('[data-toggle="datepicker"]').datepicker(
        {format: 'yyyy-mm-dd'}
    );
</script>
</script>
<script>
        $('#end_date').on('change',function(){
                    $end_date = $(this).val();
                    $start_date = $('#start_date').val()
                    $store_code = document.getElementById('storecode').value;
                    const icon = $('#icon');
                    const diff_month = $('#diff_month');
                    $.ajax({
                        type: 'get',
                        url: '{{ URL::to('lay-doanh-thu-tuy-chon') }}',
                        data: {
                            'start_date': $start_date,
                            'end_date': $end_date,
                            'store_code': $store_code,
                        },
                        success:function(data){
                        
                           $('#total_price').html('Tổng tiền: ' + new Intl.NumberFormat('de-DE', { style: 'currency', currency: 'VND' }).format(data.revenueDate));

                        }
                    });
                })
                $.ajaxSetup({ headers: { 'csrftoken' : '{{ csrf_token() }}' } });
    </script>

@endsection