@extends('layouts.app')

@section('content')


<div class="container">

    <div class="row justify-content-center" style="padding-top: 140px">

        <div class="col-xl-5 col-lg-6 col-md-6">

            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <div class="row">

                        <div class="col-lg-12">
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-2">Lấy lại mật khẩu?</h1>
                                </div>
                                <form class="user" method="POST" action="{{ route('password.update') }}">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="token" value="{{ $token }}">
                                    <div class="form-group">
                                        <input type="email"
                                            class="form-control form-control-user  @error('email') is-invalid @enderror"
                                            name="email" value="{{ $email ?? old('email') }}" required
                                            autocomplete="email" autofocus id="exampleInputEmail"
                                            aria-describedby="emailHelp" placeholder="Enter Email Address...">
                                        @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <input type="password"
                                            class="form-control form-control-user @error('password') is-invalid @enderror"
                                            name="password" required autocomplete="new-password" placeholder="Password...">
                                        @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                            <input type="password"
                                            class="form-control form-control-user @error('password') is-invalid @enderror"
                                            name="password_confirmation" required autocomplete="new-password" placeholder="Confirmation Password....">
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
