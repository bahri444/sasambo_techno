<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InstansiController;
use App\Http\Controllers\KategoriProdukController;
use App\Http\Controllers\KtgrProCusController;
use App\Http\Controllers\KtgrProSoftController;
use App\Http\Controllers\KurrirController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\ProCusController;
use App\Http\Controllers\ProSoftController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\RoleMemberController;
use App\Http\Controllers\SablonController;
use App\Http\Controllers\ShopCartController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TutorialController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\WarnaController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Auth::routes([
//     'is_email_verify' => true
// ]);
// route landing page
Route::get('/', [LandingPageController::class, 'Index'])->name('login');
Route::get('/produk', [LandingPageController::class, 'SendToProduk'])->name('produk');
Route::get('/tutorials', [LandingPageController::class, 'SendToTutorial'])->name('tutorial');
Route::get('/videos', [LandingPageController::class, 'SendToVideo'])->name('video');
Route::get('/contact', [LandingPageController::class, 'SendToContact'])->name('contact');
Route::get('/detailcustom/{id}', [RoleMemberController::class, 'DetailCustom']); //route detail produk custom sebelum login

// route login and register
Route::get('/login', [AuthController::class, 'GetViewLogin'])->name('login');
Route::get('/logout', [AuthController::class, 'Logout'])->name('logout');
Route::post('/login-post', [AuthController::class, 'AuthLogin'])->name('login-post');
Route::get('/register', [AuthController::class, 'GetViewRegister'])->name('register');
Route::post('/register-post', [AuthController::class, 'RegisterPost'])->name('register-post');
Route::get('/verify/{token}', [AuthController::class, 'VerifyAccount'])->name('verify'); //route verifikasi email

// route for reset password
Route::get('/requestReset', [ResetPasswordController::class, 'ViewResetPasswd'])->name('RequestReset');
Route::post('/sendResetPasswd', [ResetPasswordController::class, 'SendResetPasswd'])->name('SendResetPasswd');
Route::get('/resetpasswdform/{token}', [ResetPasswordController::class, 'ResetPasswdForm'])->name('resetpasswdform.get');
Route::post('/resetpasswdform', [ResetPasswordController::class, 'SendResetForm'])->name('resetpasswdform');


// route for role super admin
// route for table user
Route::prefix('user')->group(function () {
    Route::get('/', [AuthController::class, 'GetAllUser'])->name('akun');
    Route::get('/data', [AuthController::class, 'GetAll'])->name('data');
    Route::post('/adduser', [AuthController::class, 'Adduser'])->name('adduser');
    Route::post('/change', [AuthController::class, 'ChangeRole'])->name('change');
    Route::get('/delete/{id}', [AuthController::class, 'Delete'])->name('delete');
});

// route dashboard
Route::prefix('dashboard')->group(function () {
    Route::get('/', [DashboardController::class, 'GetViewDashboard'])->name('dashboard');
});

// route table instansi
Route::prefix('instansi')->group(function () {
    Route::get('/', [InstansiController::class, 'GetInstansi'])->name('instansi');
    Route::post('/addinstansi', [InstansiController::class, 'AddInstansi'])->name('addInstansi');
    Route::post('/updtinstansi', [InstansiController::class, 'UpdtInstansi'])->name('updtinstansi');
    Route::get('/delete/{id}', [InstansiController::class, 'Delete'])->name('delete');
});

// route table kategori produk
Route::prefix('kategoriProduk')->group(function () {
    Route::get('/', [KategoriProdukController::class, 'GetKategori'])->name('kategori');
    Route::post('/addkategori', [KategoriProdukController::class, 'AddKategori'])->name('addKategori');
    Route::post('/updtkategori', [KategoriProdukController::class, 'UpdtKategori'])->name('updtKategori');
    Route::get('/delete/{id}', [KategoriProdukController::class, 'Delete'])->name('delete');
});

// route table kategori produk custom
Route::prefix('kategoriProcus')->group(function () {
    Route::get('/', [KtgrProCusController::class, 'GetAll'])->name('procus');
    Route::post('/addktgrprocus', [KtgrProCusController::class, 'AddKtgrProcus'])->name('addKtgrProcus');
    Route::post('/updtktgrprocus', [KtgrProCusController::class, 'UpdtKtgrProcus'])->name('updtKtgrProcus');
    Route::get('/delktgrprocus/{id}', [KtgrProCusController::class, 'DelKtgrProcus'])->name('delKtgrProcus');
});

// route table kategori produk software
Route::prefix('kategoriProsoft')->group(function () {
    Route::get('/', [KtgrProSoftController::class, 'GetAll'])->name('prosoft');
    Route::post('/addktgrprosoft', [KtgrProSoftController::class, 'AddKtgrProsoft'])->name('addKtgrProsoft');
    Route::post('/updtktgrprosoft', [KtgrProSoftController::class, 'UpdtKtgrProsoft'])->name('updtKtgrProsoft');
    Route::get('/delktgrprosoft/{id}', [KtgrProSoftController::class, 'DelKtgrProsoft'])->name('delKtgrProsoft');
});

// route table produk software
Route::prefix('prosoft')->group(function () {
    Route::get('/', [ProSoftController::class, 'GetAll'])->name('prosoft');
    Route::post('/addprosoft', [ProSoftController::class, 'AddProsoft'])->name('addProsoft');
    Route::post('/updtprosoft', [ProSoftController::class, 'UpdtProsoft'])->name('updtProsoft');
    Route::get('/delprosoft/{id}', [ProSoftController::class, 'DelProsoft'])->name('delProsoft');
});

// route table produk custom
Route::prefix('procus')->group(function () {
    Route::get('/', [ProCusController::class, 'GetAllProduk'])->name('produk');
    Route::post('/addproduk', [ProCusController::class, 'AddProduct'])->name('addproduk');
    Route::post('/updtproduk', [ProCusController::class, 'UpdtProduct'])->name('updtproduk');
    Route::get('/deleteproduk/{id}', [ProCusController::class, 'DeleteProduk'])->name('deleteproduk');
});

// route table supplier
Route::prefix('supplier')->group(function () {
    Route::get('/', [SupplierController::class, 'GetSupplier'])->name('supplier');
    Route::post('/addsupplier', [SupplierController::class, 'AddSupplier'])->name('addsupplier');
    Route::post('/updtsupplier', [SupplierController::class, 'UpdtSupplier'])->name('updtsupplier');
    Route::get('/delete/{id}', [SupplierController::class, 'DeleteSupplier'])->name('deletesupplier');
});

// route table kurir
Route::prefix('kurir')->group(function () {
    Route::get('/', [KurrirController::class, 'GetKurir'])->name('kurir');
    Route::post('/addkurir', [KurrirController::class, 'AddKurir'])->name('addkurir');
    Route::post('/updtkurir', [KurrirController::class, 'UpdtKurir'])->name('updtkurir');
    Route::get('/delete/{id}', [KurrirController::class, 'DeleteKurir'])->name('deletekurir');
});

// route table sablon
Route::prefix('sablon')->group(function () {
    Route::get('/', [SablonController::class, 'GetSablon'])->name('sablon');
    Route::post('/addsablon', [SablonController::class, 'AddSablon'])->name('addsablon');
    Route::post('/updtsablon', [SablonController::class, 'UpdtSablon'])->name('updtsablon');
    Route::get('/delete/{id}', [SablonController::class, 'DeleteSablon'])->name('deletesablon');
});

// route table warna
Route::prefix('warna')->group(function () {
    Route::get('/', [WarnaController::class, 'GetWarna'])->name('warna');
    Route::post('/addwarna', [WarnaController::class, 'AddWarna'])->name('addwarna');
    Route::post('/updtwarna', [WarnaController::class, 'UpdtWarna'])->name('updtwarna');
    Route::get('/delete/{id}', [WarnaController::class, 'DeleteWarna'])->name('deletewarna');
});

// route table pesanan pakaian custom dan sablon
Route::prefix('pesanan')->group(function () {
    Route::get('/', [PesananController::class, 'GetPesanan'])->name('pesanan')->middleware('verified'); // load view pesanan for all role user
    Route::post('validasipesanan', [PesananController::class, 'ValidasiPesanan'])->name('validasipesanan'); //for superadmin
    Route::post('validasiproduksi', [PesananController::class, 'ValidasiProduction'])->name('validasiproduksi'); //for role production
    Route::post('/addpesanan', [PesananController::class, 'AddPesanan'])->name('addPesanan'); //checkout sablon dari halaman home
    Route::post('/checkoutnow', [PesananController::class, 'CheckoutNow'])->name('checkoutNow'); //checkout pesanan dari halaman detail produk custom
    Route::post('/bayar', [PesananController::class, 'BayarProdukCustom'])->name('bayar'); //kirim data bayar DP oleh member
    Route::post('/bayarlunas', [PesananController::class, 'BayarLunas'])->name('bayarlunas'); //kirim data bayar lunas oleh member
    Route::get('/ekspedisidandiskon/{pesanan_id}', [PesananController::class, 'GetViewEkspedisiDanPesanan'])->name('ekspedisidandiskon'); //load view form ekspedisi dan diskon
    Route::post('/ekspedisidandiskon', [PesananController::class, 'EkspedisiDanDiskon'])->name('ekspedisidandiskon'); //save data form ekspedisi dan diskon
    Route::get('/delpesanan/{id}', [PesananController::class, 'DeletePesanan'])->name('delpesanan'); //delete pesanan oleh superadmin
    Route::post('/pesananselesai', [PesananController::class, 'PesananSelesai'])->name('pesananselesai'); //route konfirmasi pesanan selesai oleh member
});

Route::prefix('contactus')->group(function () {
    Route::get('/', [ContactUsController::class, 'GetContactUs'])->name('contactus');
    Route::post('/add', [ContactUsController::class, 'Comment'])->name('addcomments');
    Route::post('/addcomments', [ContactUsController::class, 'AddComment'])->name('comment');
    Route::post('/updtcontactus', [ContactUsController::class, 'UpdtContactUs'])->name('updtcontactus');
    Route::get('/delete/{id}', [ContactUsController::class, 'DelContactUs'])->name('delcontactus');
});

// route role table tutorial
Route::prefix('tutorial')->group(function () {
    Route::get('/', [TutorialController::class, 'GetAllTutorial'])->name('tutorial');
    Route::post('/addtutorial', [TutorialController::class, 'AddTutorial'])->name('addtutorial');
    Route::post('/updttutorial', [TutorialController::class, 'UpdtTutorial'])->name('uptTutorial');
    Route::get('/deltutorial/{id}', [TutorialController::class, 'DelTutorial'])->name('delTutorial');
});

// route role table video
Route::prefix('video')->group(function () {
    Route::get('/', [VideoController::class, 'GetAllVideo']);
    Route::post('/addvideo', [VideoController::class, 'AddVideo']);
    Route::post('/updtvideo', [VideoController::class, 'UpdtVideo']);
    Route::get('/delvideo/{id}', [VideoController::class, 'DelVideo']);
});

// route role table payment
Route::prefix('payment')->group(function () {
    Route::get('/', [PaymentController::class, 'GetPayment']);
    Route::post('/addpayment', [PaymentController::class, 'AddPayment']);
    Route::post('/updtpayment', [PaymentController::class, 'UpdtPayment']);
    Route::get('/delpayment/{id}', [PaymentController::class, 'DelPayment']);
});

// route role table partner perusahaan
Route::prefix('partner')->group(function () {
    Route::get('/', [PartnerController::class, 'GetPartner'])->middleware('verified');
    Route::post('/addpartner', [PartnerController::class, 'AddPartner'])->middleware('verified');
    Route::post('/updtpartner', [PartnerController::class, 'UpdtPartner'])->middleware('verified');
    Route::get('/delpartner/{id}', [PartnerController::class, 'DelPartner'])->middleware('verified');
});

// route role admin
Route::name('admin')->group(function () {
    Route::get('/index', [DashboardController::class, 'GetViewDashboard'])->name('viewdashboard')->middleware('verified');
});

// route for role access member or client
Route::name('members')->group(function () {
    Route::get('/home', [RoleMemberController::class, 'Home'])->name('home')->middleware('is_verify_email'); //load halaman home after login
    Route::get('/details/{produk_custom_id}', [RoleMemberController::class, 'DetailProdukCustomSebelumCheckout'])->name('details')->middleware('is_verify_email'); // detail custom setelah login dan sebelum menambahkan ke keranjang barang

    //route lengkapi profile oleh pelanggan
    Route::get('/form', [AuthController::class, 'GetForm'])->name('form')->middleware('is_verify_email'); //get form lengkapi akun
    Route::post('/updtakun', [AuthController::class, 'UpdtUser'])->name('updtakun')->middleware('is_verify_email'); //kirim nilai yang di input dari form

    Route::get('/profile', [AuthController::class, 'GetAllUser'])->name('Profile')->middleware('is_verify_email'); //load profile member
    Route::get('/pesanananda', [PesananController::class, 'GetPesanan'])->name('pesanananda')->middleware('is_verify_email'); //load all pesanan role member
    Route::get('/invoice', [RoleMemberController::class, 'GetInvoice'])->name('invoice')->middleware('is_verify_email');  //cetak invoice in member

    Route::get('/cart', [ShopCartController::class, 'GetDataCart'])->name('cart')->middleware('is_verify_email'); //load data keranjang belanja
    Route::post('/keranjang', [ShopCartController::class, 'AddToCart'])->name('keranjang')->middleware('is_verify_email'); //route untuk menambahkan pakaian custom ke keranjang
    Route::post('/sabloncart', [ShopCartController::class, 'AddSablonToKeranjang'])->middleware('is_verify_email'); //route untuk menambahkan sablon ke keranjang
    Route::post('/addsablontocart', [ShopCartController::class, 'AddSablonToCart'])->name('addsablontocart')->middleware('is_verify_email'); //route add sablon to shop cart
});

Route::prefix('cart')->group(function () {
    Route::get('/delete/{id}', [ShopCartController::class, 'DelBarangOnKeranjang'])->name('delete'); //delete isi keranjang belanja oleh member
    Route::post('/checkout', [ShopCartController::class, 'TrxPakaiancustom'])->name('checkout'); //checkout pakaian custom from cart shop in role member
});
