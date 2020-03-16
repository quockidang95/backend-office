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
<form action="{{ route('export.day')}}" method="post">
    @csrf
    <div class="input-group col-3">
        <input type="text" id="date_selected" name="date_selected" class="form-control bg-lightlight border-0 small"data-toggle="datepicker" placeholder="Select date...">
    </div>

    <div class="input-group col-3 pt-3 pb-3">
        <select class="form-control form-control-sm" required name="store_code" id="storecode">
            @foreach ($stores as $key => $item)
            <option value="{{$item->store_code}}">{{$item->name}}</option>
            @endforeach
        </select>
    </div>

    <button class="btn btn-warning" type="submit">Xuất ra Excel</button>
</form>

<div>
    <h4 id="total_price"></h4>
</div>

<table class="table">
    <thead class="thead-dark">
        <tr>
            <th scope="col">Ca làm việc</th>
            <th scope="col">Nhân viên</th>
            <th scope="col">Dư đầu ca</th>
            <th scope="col">Tổng doanh thu</th>
            <th scope="col">Doanh thu bằng tiền mặt</th>
            <th scope="col">Doanh thu online</th>
            <th scope="col">Dư cuối ca </th>
        </tr>
    </thead>
    <tbody>       
    </tbody>
</table>


<table class="table">
    <thead class="thead-dark">
        <tr>
            <th scope="col">Bàn</th>
            <th scope="col">ID KH</th>
            <th scope="col">Tổng bill</th>
        </tr>
    </thead>
    <tbody id="total_by_date">
                            
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
                    $store_code = document.getElementById('storecode').value;
                    $.ajax({
                        type: 'get',
                        url: '{{ URL::to('lay-doanh-thu-theo-ngay') }}',
                        data: {
                            'dateselected': $date_selected,
                            'storecode': $store_code
                        },
                        success:function(data){
                            var getData = $.parseJSON(data);
                           
                           $('tbody').html(getData.shiftwork);
                           
                           const stringArr = getData.orders.map( item => {
                                return  `
                                    <tr>
                                        <td>` + item.table + `</td>
                                        <td>` + item.customer_id + `</td>
                                        <td>` + new Intl.NumberFormat('de-DE', { style: 'currency', currency: 'VND' }).format(item.price) + `</td>
                                    </tr>
                                `;
                                
                           });
                           const string = stringArr.reduce((init, item) =>{
                                    return init + item;
                               }, null)
                              
                           $('#total_by_date').html(
                               string
                           )
                            
                            const total_price_arr = getData.orders.map( item =>{
                                return item.price;
                            })

                            const total_price = total_price_arr.reduce((init, item) => {
                                return init + parseInt(item);
                            }, 0)

                            $('#total_price').html('Tổng tiền: ' + new Intl.NumberFormat('de-DE', { style: 'currency', currency: 'VND' }).format(total_price));
                        }
                    });
                })
                $.ajaxSetup({ headers: { 'csrftoken' : '{{ csrf_token() }}' } });
    </script>

@endsection