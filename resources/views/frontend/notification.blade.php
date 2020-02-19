@extends('layouts.customer')
@section('customer')
<h4 class=" mt-3 text-center font-weight-bold">Lịch sử giao dịch</h4>
<div class="mt-4 ">
    @foreach ($rechages as $item)
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-info shadow py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">

                        <div class="row no-gutters align-items-center">
                            <div class="col-auto">
                                <div class="h6 mb-0 mr-3 font-weight-bold text-gray-800">
                                    Tiền nạp: {{number_format($item->price) . ' ₫'}}.</div>

                                <div class="h6 mb-0 font-weight-bold text-gray-800">
                                    Ngày nạp: {{$item->created_at}}.

                                </div>
                                <div class="h6 mb-0 mr-3 font-weight-bold text-gray-800">
                                    Tiền thưởng: {{number_format($item->money_discount)}}
                                </div>
                                <div class="h6 mb-0 mr-3 font-weight-bold text-gray-800">
                                    Điẻm thưởng: {{$item->point_discount}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection
