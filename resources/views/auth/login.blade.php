@extends('layouts.app')

@section('content')

<div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center" style="padding-top: 140px">

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
                                <form class="user" method="POST" action="{{ route('login') }}">
                                    @csrf
                                    <div class="form-group">
                                        <input type="email"
                                            class="form-control form-control-user @error('email') is-invalid @enderror"
                                            name="email" value="{{ old('email') }}" required autocomplete="email"
                                            id="exampleInputEmail" aria-describedby="emailHelp"
                                            placeholder="Tên đăng nhập...">

                                        @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <input type="password"
                                            class="form-control form-control-user @error('password') is-invalid @enderror"
                                            name="password" required autocomplete="current-password"
                                            id="exampleInputPassword" placeholder="Mật khẩu">

                                        @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox small">
                                            <input type="checkbox" class="custom-control-input" name="remember"
                                                id="customCheck" {{ old('remember') ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="customCheck">Nhớ mật khẩu</label>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-warning btn-user btn-block">
                                        Đăng nhập
                                    </button>

                                </form>
                                <hr>
                                <div class="text-center">
                                    @if (Route::has('password.request'))
                                    <a class="small" style="color: black" href="{{ route('password.request') }}">Quên
                                        mật khẩu?</a>
                                    @endif
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
