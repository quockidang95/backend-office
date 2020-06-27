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
                                <form class="user" id="frmPhone" method="POST" action="{{route('customerlogin')}}">
                                    @csrf
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-user" name="phone" id="phoneNumber" aria-describedby="emailHelp" placeholder="Nhập vào số điện thoại...">
                                      </div>

                                    <button type="button" class="btn btn-warning btn-user btn-block" id="sign-in-button" onclick="submitPhoneNumberAuth();">
                                        Gửi mã xác nhận
                                    </button>
                                    <button type="submit" hidden>Login</button>
                                </form>
                                <div id="recaptcha-container"></div>
                                <form class="user pt-3" id="frmLogin" style="display:none">
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-user" id="veryficationCode" aria-describedby="emailHelp" placeholder="Nhập vào mã xác nhận...">
                                      </div>
                                      <button type="button"  class="btn btn-warning btn-user btn-block" onclick="codeverify();">Đăng nhập</button>

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
@section('script')
<script type="text/javascript">
    $('#frmPhone').keypress( function(e) {
        if (e.keyCode == 13) {
            return false;
        }
    })
</script>
@endsection
