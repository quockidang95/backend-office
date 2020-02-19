@extends('layouts.customer')

<div class="mt-5 ">
    @if (isset($order))

    <div class="col-xl-4 col-md-6 mb-4 mt-5">
        <div class="card border-left-warning shadow py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">

                        <div class="row no-gutters align-items-center">
                            <div class="col-auto">
                                <div class="h6 mb-0 mr-3 font-weight-bold text-gray-800">
                                    Mã cửa hàng: {{$order->store_code}}.</div>

                                <div class="h6 mb-0 mr-3 font-weight-bold text-gray-800">
                                    Bàn: {{$order->table}}
                                </div>
                                <div class="h6 mb-0 mr-3 font-weight-bold text-gray-800">
                                    <?php
                                            if($order->status == 1){
                                                echo 'Trạng thái: ' . '<span class="badge badge-primary">Chưa xử lí</span>';
                                            }else if($order->status == 2){
                                                echo 'Trạng thái: ' . '<span class="badge badge-warning">Đã tiếp nhập</span>';
                                            }else if($order->status == 3){
                                                echo 'Trạng thái: ' . '<span class="badge badge-success">Đã hoàn tất</span>';
                                            }else if($order->status == 4){
                                                echo 'Trạng thái: ' . '<span class="badge badge-danger">Đã hủy</span>';
                                            }

                                        ?>
                                </div>
                                <div class="h6 mb-0 mr-3 font-weight-bold text-gray-800">
                                    Tổng tiền: {{number_format($order->price) . ' ₫'}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    @endif
