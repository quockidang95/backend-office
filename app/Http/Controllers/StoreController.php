<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Store\StoreRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class StoreController extends Controller
{
    protected $storeRepository;

    public function __construct(StoreRepositoryInterface $storeRepository) {
        $this->storeRepository = $storeRepository;
    }

    public function index(){
        if(Auth::user()->is_admin == 1){
            $stores = $this->storeRepository->getAll();
        Session::put('success', 'Load danh sách cửa hàng thành công');
        return view('backend.store.index', compact('stores'));
        }

        Session::put('error', 'Account không có quyền');
        return Redirect::to('dashboard');
    }
}
