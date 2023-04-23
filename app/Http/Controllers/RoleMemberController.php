<?php

namespace App\Http\Controllers;

use App\Models\Instansi;
use App\Models\KtgrProcus;
use App\Models\Kurir;
use App\Models\Partner;
use App\Models\Payment;
use App\Models\ProdukCustom;
use App\Models\Sablon;
use App\Models\Shop_cart;
use App\Models\User;
use App\Models\Warna;
use Illuminate\Support\Facades\Auth;

class RoleMemberController extends Controller
{
    public function GetHome()
    {
        $isiKeranjang = Shop_cart::where('user_id', '=', Auth::user()->user_id)->get()->count(); //hitung isi keranjang berdasarkan user yang login 'isiKeranjang' => $isiKeranjang,
        $procategori = KtgrProcus::joinToKategori()->get();
        $produkCustom = ProdukCustom::joinKategoriProdukCostum()->get();
        // $testing = ProdukCustom::joinKategoriProdukCostum()->orderBy('ktgr_procus_id', 'desc')->paginate(1);
        // dd($testing);
        $instansi = Instansi::all();
        $sablon = Sablon::all();
        $payment = Payment::all();
        $kurir = Kurir::all();
        return view('members.home', [
            'title' => 'home',
            'instansi' => $instansi,
            'kategoricustom' => $procategori,
            'produk_custom' => $produkCustom,
            'sablon' => $sablon,
            'payment' => $payment,
            'kurir' => $kurir,
            // 'testing' => $testing,
            'isiKeranjang' => $isiKeranjang,
        ]);
    }

    public function GetInvoice()
    {
        $data = Instansi::all();
        $isiKeranjang = Shop_cart::where('user_id', '=', Auth::user()->user_id)->get()->count();
        return view('members.yourInvoice', [
            'title' => 'histori invoice anda',
            'instansi' => $data,
            'isiKeranjang' => $isiKeranjang,
        ]);
    }
    public function GetProfile()
    {
        $isiKeranjang = Shop_cart::where('user_id', '=', Auth::user()->user_id)->get()->count();
        $data = Instansi::select('logo')->get();
        $users = User::all();
        return view('members.profile', [
            'title' => 'profile anda',
            'instansi' => $data,
            'users' => $users,
            'isiKeranjang' => $isiKeranjang,
        ]);
    }

    // detail custom sebelum login
    public function DetailCustom($id)
    {
        $instansi = Instansi::all();
        $data_produk_custom = ProdukCustom::joinWarna()->joinKategoriProdukCostum()->get();
        $get_size_with_nama_warna = ProdukCustom::joinWarna()->select('size', 'nama_warna')->get();
        $data_id_produk = $id;
        $partnerperusahaan = Partner::select('nama_prshn', 'logo_prshn')->get();
        return view('home.pilihbaju', [
            'instansi' => $instansi,
            'data_produk_custom_id' => $data_id_produk,
            'data_produk_custom' => $data_produk_custom,
            'get_size_with_nama_warna' => $get_size_with_nama_warna,
            'partnerperusahaan' => $partnerperusahaan
        ]);
    }

    // detail produk custom setelah login
    public function DetailProdukCustomSebelumCheckout($produk_custom_id)
    {
        $instansi = Instansi::select('logo')->get();
        $isiKeranjang = Shop_cart::where('user_id', '=', Auth::user()->user_id)->get()->count(); //hitung isi keranjang berdasarkan user yang login 'isiKeranjang' => $isiKeranjang,
        $data_produk_custom = ProdukCustom::joinWarna()->joinKategoriProdukCostum()->get();
        $get_size_with_nama_warna = ProdukCustom::joinWarna()->select('size', 'nama_warna')->get();
        $data_id_produk = $produk_custom_id;
        return view('members.detailcustom', [
            'instansi' => $instansi,
            'isiKeranjang' => $isiKeranjang,
            'data_produk_custom_id' => $data_id_produk,
            'data_produk_custom' => $data_produk_custom,
            'get_size_with_nama_warna' => $get_size_with_nama_warna
        ]);
    }
}
