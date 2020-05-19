@extends('layouts.admin')
@section('content')
<nav aria-label="breadcrumb col-8">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('promotion.index')}}">Khuyến mãi</a></li>
        <li class="breadcrumb-item active" aria-current="page">Cập nhật coupon</li>
    </ol>
</nav>
<div class="row">
<div class="pb-4">
@foreach ($errors->all() as $message)
    <div class="badge badge-danger">{{ $message }}</div><br />
@endforeach
</div>
<div class="col-6 justify-content-center">
    <form class="needs-validation" novalidate action="{{ route('promotion.update', ['id' => $promotion->id]) }}" method="POST">
        @csrf
        <div class="form-group ">
            <input type="text" name="title" class="form-control  form-control-sm" value="{{ $promotion->title}}" required placeholder="Title">
        </div>

        <div class="form-group ">
            <textarea type="text" name="body" class="form-control  form-control-sm" value="{{ $promotion->body}}" row="2" required placeholder="Mô tả ngắn"></textarea>
        </div>
        <div class="form-group">
            <input type="text" name="promotion_code" class="form-control  form-control-sm" value="{{ $promotion->promotion_code}}" required placeholder="Mã giảm giá">
        
        </div>
        <div class="form-group ">
            <input type="number" name="adjuster" class="form-control  form-control-sm" value="{{ $promotion->adjuster}}" required placeholder="Phần trăm giảm trên đơn hàng">
            
        </div>
        <div class="form-group">
            <label>Ngày bắt đầu và kết thúc</label>
            <div class="input-group input-daterange">
                <input type="text" class="form-control" name="start_date" value="{{ $promotion->start_date }}"  data-toggle="datepicker">
                <div class="input-group-addon"> đến </div>
                <input type="text" class="form-control" name="end_date" value="{{ $promotion->end_date }}"  data-toggle="datepicker">
            </div>
        </div>
        <div class="form-group">
            <label>Giờ bắt đầu và kết thúc</label>
            <div class="input-group">
                <input type="text" class="form-control timepicker" name="start_hour" value="{{ $promotion->start_hour }}">
                <div class="input-group-addon"> đến </div>
                <input type="text" class="form-control timepicker" name="end_hour" value="{{ $promotion->end_hour }}">
            </div>
        </div>

        <button class="btn btn-primary fa-pull-right mr-5 w-60" type="submit">Cập nhật</button>
    </form>

</span>
</div>
@endsection
@section('script')
<link  href="{{ asset('datepicker/dist/datepicker.css') }}" rel="stylesheet">
<script src="{{ asset('datepicker/dist/datepicker.js') }}"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>

<script type="text/javascript">
    $('.timepicker').timepicker({
    timeFormat: 'HH:mm:ss',
    interval: 30,
    dynamic: false,
    dropdown: true,
    scrollbar: true
});
</script>
<script>
    $('[data-toggle="datepicker"]').datepicker(
        {format: 'yyyy-mm-dd'}
    );
</script>
 <script>
        $('.input-daterange input').each(function() {
    $(this).datepicker('clearDates');
});
</script>
@endsection
