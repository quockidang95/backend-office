<?php

namespace App\Http\Controllers;

use App\Store;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use App\Repositories\Store\StoreRepositoryInterface;

class StoreController extends Controller
{
    protected $storeRepository;

    public function __construct(StoreRepositoryInterface $storeRepository)
    {
        $this->storeRepository = $storeRepository;
    }

    public function index()
    {
        $stores = $this->storeRepository->getAll();
           
        return view('backend.store.index', compact('stores'));
    }
    public function update(Request $request, $id)
    {
        $store = Store::find($id);
        if ($store) {
            $store->update($request->all());
            session(['success' => 'Cập nhật thành công']);
            return redirect()->route('store.index');
        }

        session(['error' => 'Cập nhật thất bại']);
        return redirect()->route('store.index');
    }
}
