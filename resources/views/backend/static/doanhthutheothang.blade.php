@extends('layouts.admin')
@section('content')
<?php
$stores = App\Store::all();
?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Thông kê doanh thu theo tháng</a></li>
    </ol>
</nav>
<div class="row">
<div class="col-xl-3 col-md-6 mb-4">
    <form action="{{ route('export.revenue')}}" method="post">
        @csrf
    <div class="input-group">
        <input type="text" id="date_selected" name="date_selected" class="form-control bg-lightlight border-0 small"data-toggle="datepicker" placeholder="Select date...">
    </div>
    <div class="input-group pt-3 pb-3">
        <select class="form-control form-control-sm" required name="store_code" id="storecode">
            @foreach ($stores as $key => $item)
            <option value="{{$item->store_code}}">{{$item->name}}</option>
            @endforeach
        </select>
    </div>

    <div class="p-3">
        <button class="btn btn-warning"type="submit">Xuất ra excel</button>
    </div>
    </form>
    </div>
    
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

        <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                  <div class="card-body">
                    <div class="row no-gutters align-items-center">
                      <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">So với tháng trước.</div>
                        <div class="row no-gutters align-items-center">
                          <div class="col-auto">
                            <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800" id="diff_month"></div>
                          </div>
                          <div class="col">
                          <div class=" mr-2" id="icon">
                            
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-auto">
                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
    
<table class="table">
    <thead class="thead-dark">
        <tr>
            <th scope="col">Bàn</th>
            <th scope="col">ID KH</th>
            <th scope="col">Tổng bill</th>
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
        {format: 'yyyy-mm'}
    );
</script>


<script>
        $('#date_selected').on('change',function(){
                    $date_selected = $(this).val();
                    $store_code = document.getElementById('storecode').value;
                    const icon = $('#icon');
                    const diff_month = $('#diff_month');
                    $.ajax({
                        type: 'get',
                        url: '{{ URL::to('lay-doanh-thu-theo-thang') }}',
                        data: {
                            'dateselected': $date_selected,
                            'storecode': $store_code
                        },
                        success:function(data){
                            var getData = $.parseJSON(data);

                            $('tbody').html(getData[0].a);
                            $('#total_price').html('Tổng tiền: ' + new Intl.NumberFormat('de-DE', { style: 'currency', currency: 'VND' }).format(getData[0].b));
                            diff_month.html(getData[0].diff_month.toFixed(2) + ' %')

                        }
                    });
                })
                $.ajaxSetup({ headers: { 'csrftoken' : '{{ csrf_token() }}' } });
    </script>

@endsection