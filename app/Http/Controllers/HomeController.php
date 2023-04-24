<?php

namespace App\Http\Controllers;

use App\Models\ContactUs;
use App\Models\Instansi;
use App\Models\KategoriProduk;
use App\Models\KtgrProcus;
use App\Models\KtgrProsoft;
use App\Models\Partner;
use App\Models\ProdukCustom;
use App\Models\Sablon;
use App\Models\Tutorial;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function SendToIndex()
    {
        $instansi = Instansi::all();
        $ktgrprdk = KategoriProduk::all();
        $ktgrProsoft = KtgrProsoft::joinToKategoriProduk()->get();
        $ktgrCustom = KtgrProcus::joinToKategori()->get();
        $partnerPerusahaan = Partner::select('nama_prshn', 'logo_prshn')->get();
        return view('home.landingpage', [
            'title' => 'index',
            'instansi' => $instansi,
            'ktgrproduk' => $ktgrprdk, //load kategori produk for slide
            'ktgrprosoft' => $ktgrProsoft, //load kategori produk software
            'ktgrprocus' => $ktgrCustom, //load kategori produk custom
            'partnerperusahaan' => $partnerPerusahaan
        ]);
    }

    public function SendToAbout()
    {
        $partnerPerusahaan = Partner::select('nama_prshn', 'logo_prshn')->get();
        $instansi = Instansi::all();
        return view('home.index', [
            'title' => 'tentang',
            'instansi' => $instansi,
            'partnerperusahaan' => $partnerPerusahaan
        ]);
    }

    public function SendToBlog()
    {
        $partnerPerusahaan = Partner::select('nama_prshn', 'logo_prshn')->get();
        $instansi = Instansi::all();
        $ktgrprdk = KategoriProduk::all();
        $produkCustom = ProdukCustom::all();
        return view('home.blog', [
            'title' => 'blog',
            'instansi' => $instansi,
            'ktgrproduk' => $ktgrprdk,
            'produk' => $produkCustom,
            'partnerperusahaan' => $partnerPerusahaan
        ]);
    }

    public function SendToTutorial()
    {
        $partnerPerusahaan = Partner::select('nama_prshn', 'logo_prshn')->get();
        $instansi = Instansi::all();
        $tutorial = Tutorial::all();
        return view('home.tutorial', [
            'title' => 'tutorial',
            'instansi' => $instansi,
            'tutorials' => $tutorial,
            'partnerperusahaan' => $partnerPerusahaan
        ]);
    }

    public function SendToVideo()
    {
        $partnerPerusahaan = Partner::select('nama_prshn', 'logo_prshn')->get();
        $instansi = Instansi::all();
        $video = Video::orderBy('video_id', 'desc')->limit(6)->get();
        return view('home.video', [
            'title' => 'video',
            'instansi' => $instansi,
            'videos' => $video,
            'partnerperusahaan' => $partnerPerusahaan
        ]);
    }

    public function SendToProduk()
    {
        $partnerPerusahaan = Partner::select('nama_prshn', 'logo_prshn')->get();
        $instansi = Instansi::all();
        $procategori = KtgrProcus::joinToKategori()->orderBy('ktgr_procus_id', 'desc')->limit(6)->get();
        $sablon = Sablon::orderBy('sablon_id', 'desc')->limit(6)->get();
        $procus = ProdukCustom::joinKategoriProdukCostum()->orderBy('procus_id', 'desc')->limit(6)->get();
        return view('home.produk', [
            'title' => 'produk',
            'kategoricustom' => $procategori,
            'procus' => $procus,
            'instansi' => $instansi,
            'partnerperusahaan' => $partnerPerusahaan,
            'sablon' => $sablon
        ]);
    }

    // get view contact us or testimonial
    public function SendToContact()
    {
        $partnerPerusahaan = Partner::select('nama_prshn', 'logo_prshn')->get();
        $instansi = Instansi::all();
        return view('home.contact', [
            'title' => 'kontak',
            'instansi' => $instansi,
            'partnerperusahaan' => $partnerPerusahaan
        ]);
    }
}
