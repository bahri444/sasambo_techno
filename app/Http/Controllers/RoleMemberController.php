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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMemberController extends Controller
{
    public function Home()
    {
        $isiKeranjang = Shop_cart::where('user_id', '=', Auth::user()->id)->get()->count(); //hitung isi keranjang berdasarkan user yang login 'isiKeranjang' => $isiKeranjang,
        $procategori = KtgrProcus::joinToKategori()->get();
        $produkCustom = ProdukCustom::joinKategoriProdukCostum()->get();
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
            'isiKeranjang' => $isiKeranjang,
        ]);
    }

    public function GetInvoice()
    {
        $data = Instansi::all();
        $isiKeranjang = Shop_cart::where('user_id', '=', Auth::user()->id)->get()->count();
        return view('members.yourInvoice', [
            'title' => 'histori invoice anda',
            'instansi' => $data,
            'isiKeranjang' => $isiKeranjang,
        ]);
    }
    public function GetProfile()
    {
        $isiKeranjang = Shop_cart::where('user_id', '=', Auth::user()->id)->get()->count();
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
        $partnerperusahaan = Partner::select('nama_prshn', 'logo_prshn')->get();
        $data_produk_custom = ProdukCustom::joinWarna()->joinKategoriProdukCostum()->get();
        $get_size_with_nama_warna = ProdukCustom::joinWarna()->select('size', 'nama_warna')->get();
        $data_id_produk = $id;
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
        $isiKeranjang = Shop_cart::where('user_id', '=', Auth::user()->id)->get()->count(); //hitung isi keranjang berdasarkan user yang login 'isiKeranjang' => $isiKeranjang,
        $data_produk_custom = ProdukCustom::joinWarna()->joinKategoriProdukCostum()->get();
        $jasa_kirim = Kurir::all();
        $metode_pembayaran = Payment::all();
        $get_size_with_nama_warna = ProdukCustom::joinWarna()->select('size', 'nama_warna')->get();
        $data_id_produk = $produk_custom_id;
        return view('members.detailcustom', [
            'instansi' => $instansi,
            'isiKeranjang' => $isiKeranjang,
            'jasaKirim' => $jasa_kirim,
            'payment' => $metode_pembayaran,
            'data_produk_custom_id' => $data_id_produk,
            'data_produk_custom' => $data_produk_custom,
            'get_size_with_nama_warna' => $get_size_with_nama_warna
        ]);
    }
}
