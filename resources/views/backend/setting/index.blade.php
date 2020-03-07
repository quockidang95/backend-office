@extends('layouts.admin')
@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Cài đặt</a></li>

    </ol>
</nav>

<table class="table">
    <thead class="thead-dark">
        <tr>
            <th scope="col">Tích điểm(1 điểm tương với)</th>
            <th scope="col">Chiết khấu hội viên(được giảm bao nhiêu % dựa trên đơn hàng)</th>
            <th scope="col"> Cho phép thanh bằng tiền mặt khi delivery</th>
            <th scope="col">Thao tác</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($setting as $item)
        <tr>
        <th scope="row">{{number_format($item->discount_point) . ' VNĐ'}}</th>
        <td >{{$item->discount_user . ' %'}}</td>
        @if($item->is_payment_delivery === 1)
            <td>true</td>
        @elseif($item->is_payment_delivery === 2)
            <td>false</td>
        @endif
        </td>
        <td>
              <!-- Delete category -->
              <button class="btn btn-warning btn-circle" data-toggle="modal" data-target="#deleteProduct{{$item->id}}"
                title="delete category">
                <i class="fas fa-pen"></i>
            </button>
            <!-- Modal -->
            <div class="modal fade" id="deleteProduct{{$item->id}}" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Chỉnh sửa cài đặt</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                        <form action="{{route('setting.update', ['id' => $item->id])}}" method="post">
                            @csrf
                            <div class="form-group ">
                                <label for="">Tích điểm(1 điểm tương ứng với)</label>
                            <input type="number" name="discount_point" class="form-control  form-control-sm" value="{{$item->discount_point}}"
                                    required placeholder="Tích điểm">
                            </div>
                            <div class="form-group ">
                                <label for="">Chiết khấu hội viên(%)</label>
                            <input type="number" name="discount_user" class="form-control  form-control-sm" value="{{$item->discount_user}}"
                                    required placeholder="Chiết khấu hội viên">
                            </div>
                            <div class="form-group">
                                <label for="">Cho phép thanh bằng tiền mặt khi delivery</label>
                                <select name="is_payment_delivery" id="" class="form-control">
                                    <option value="1">True</option>
                                    <option value="2">False</option>
                                </select>
                            </div>
                            <button style="margin-left: 300px" type="submit" class="btn btn-primary">Cập nhật cài đặt</button>
                        </form>
                        </div>
                        <div class="modal-footer">

                        </div>
                    </div>
                </div>
            </div>
            <!-- end delete category -->
        </td>
        @endforeach
    </tbody>
</table>
@endsection
@section('script')

@endsection
