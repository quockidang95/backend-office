@extends('layouts.admin')
@section('content')
<?php
    $stores = App\Store::all();
?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Thông kê doanh thu theo ngày</a></li>
    </ol>
</nav>
<div class="input-group col-3">
    <input type="text" id="date_selected" class="form-control bg-lightlight border-0 small"data-toggle="datepicker" placeholder="Select date...">
</div>


<div class="input-group col-3 pt-3 pb-3">
    <select class="form-control form-control-sm" required
        aria-placeholder="Chọn danh mục">
        <option id="store_code" disabled>Chọn cửa hàng</option>
        @foreach ($stores as $key => $item)
        <option value="{{$item->store_code}}">{{$item->name}}</option>
        @endforeach
    </select>
</div>

<div>
    <h4 id="total_price"></h4>
</div>

<table class="table">
    <thead class="thead-dark">
        <tr>
            <th scope="col">Ca làm việc</th>
            <th scope="col">Nhân viên</th>
            <th scope="col">Doanh thu của ca</th>
        </tr>
    </thead>
    <tbody>
                            
    </tbody>
</table>


@endsection
@section('script')

<link  href="{{ asset('datepicker/dist/datepicker.css') }}" rel="stylesheet">
<script src="{{ asset('datepicker/dist/datepicker.js') }}"></script>
<script>
    $('[data-toggle="datepicker"]').datepicker(
        {format: 'yyyy-mm-dd'}
    );
</script>


<script>
        $('#date_selected').on('change',function(){
                    $date_selected = $(this).val();
                    $store_code = $('#store_code').val();
                    $.ajax({
                        type: 'get',
                        url: '{{ URL::to('lay-doanh-thu-theo-ngay') }}',
                        data: {
                            'dateselected': $date_selected,
                            'storecode': $store_code
                        },
                        success:function(data){
                            var getData = $.parseJSON(data);
                           console.log(getData[0]);
                           $('tbody').html(getData[0].a);
                           $('#total_price').html('Tổng tiền: ' + new Intl.NumberFormat('de-DE', { style: 'currency', currency: 'VND' }).format(getData[0].b));
                        }
                    });
                })
                $.ajaxSetup({ headers: { 'csrftoken' : '{{ csrf_token() }}' } });
    </script>

@endsection