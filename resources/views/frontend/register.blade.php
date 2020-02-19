@extends('layouts.app')

@section('content')

<div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

        <div class="col-xl-5 col-lg-6 col-md-6">

            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <!-- Nested Row within Card Body -->
                    <div class="row">

                        <div class="col-lg-12">
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">
                                    <img src="{{asset('img/office_logo.png')}}" width="100%" alt="">
                                    </h1>
                                </div>
                                <form class="user" method="POST" action="{{route('register')}}">
                                    @csrf
                                    <div class="form-group">
                                    <input type="text" class="form-control form-control-user" name="phone" value="{{$phone}}" readonly>
                                      </div>

                                      <div class="form-group">
                                        <input type="text" class="form-control form-control-user" name="name" placeholder="Họ tên....">
                                          </div>
                                          <div class="form-group">
                                            <input type="text" class="form-control form-control-user" name="address" placeholder="Địa chỉ....">
                                              </div>
                                    <button type="submit" class="btn btn-warning btn-user btn-block" id="sign-in-button">
                                        Đăng kí
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

@endsection
