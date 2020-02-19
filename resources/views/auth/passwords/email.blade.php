@extends('layouts.app')

@section('content')



<div class="container">

    <div class="row justify-content-center" style="padding-top: 140px">

        <div class="col-xl-6 col-lg-12 col-md-9">

            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <div class="row">

                        <div class="col-lg-12">
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-2">Quên mật khẩu?</h1>
                                    <p class="mb-4">Chỉ cần nhập địa chỉ email của bạn dưới đây và chúng tôi sẽ gửi cho
                                        bạn một liên kết để đặt lại mật khẩu của bạn!</p>
                                </div>
                                @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                                @endif
                                <form class="user" method="POST" action="{{ route('password.email') }}">
                                    {{ csrf_field() }}
                                    <div class="form-group">
                                        <input type="email"
                                            class="form-control form-control-user  @error('email') is-invalid @enderror"
                                            name="email" value="{{ old('email') }}" required autocomplete="email"
                                            autofocus id="exampleInputEmail" aria-describedby="emailHelp"
                                            placeholder="Enter Email Address...">
                                        @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <button type="submit" class="btn btn-warning btn-user btn-block">
                                        Xác nhận
                                    </button>
                                </form>
                                <hr>

                                <div class="text-center">
                                    <a style="color: black" class="small" href="{{ route('login')}}">Bạn đã có tài
                                        khoảng? Đăng nhập!</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
