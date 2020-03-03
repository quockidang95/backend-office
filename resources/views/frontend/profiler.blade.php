@extends('layouts.customer')


<div class="mt-5 ">
@if (isset($user))
    <div class="text-center">
        <img src="{{asset('img/account.png')}}" width="35" height="35">
    </div>
<div class="col-xl-4 col-md-6 mb-4 mt-5">
    <div class="card border-left-warning shadow py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">{{$user->name}}</div>
                    <div class="row no-gutters align-items-center">
                        <div class="col-auto">
                            <div class="h6 mb-0 mr-3 font-weight-bold text-gray-800">
                                Số điện thoại: {{$user->phone}}.</div>

                            <div class="h6 mb-0 mr-3 font-weight-bold text-gray-800">
                                {{number_format($user->wallet)}}
                                <i class="fas fa-money-check-alt"></i>
                            </div>
                            <div class="h6 mb-0 mr-3 font-weight-bold text-gray-800">
                                {{$user->point}}
                                <i class="fas fa-palette"></i>
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


