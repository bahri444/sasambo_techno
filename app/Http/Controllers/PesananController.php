<?php

namespace App\Http\Controllers;

use App\Models\Instansi;
use App\Models\Pesanan;
use App\Models\Shop_cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PesananController extends Controller
{
    // load data pesanan for all role user
    public function GetPesanan()
    {
        if (Auth::user()->role == 'superadmin') {
            $data = Pesanan::joinToProdukCustom()->joinToKategoriProdukCustom()->joinToWarna()
                ->joinToUser()->joinToSablon()->joinToKurir()->joinToPayment()->orderBy('pesanan_id', 'desc')->get();
            $instansi = Instansi::select('logo')->get();
            return view('superadmin.pesanan', [
                'title' => 'pesanan pakaian custom',
                'instansi' => $instansi,
                'pesanan' => $data,
            ]);
        } elseif (Auth::user()->role == 'kasir') {
            $data = Pesanan::joinToProdukCustom()->joinToKategoriProdukCustom()->joinToWarna()
                ->joinToUser()->joinToSablon()->joinToKurir()->joinToPayment()->orderBy('pesanan_id', 'desc')->get();
            $instansi = Instansi::select('logo')->get();
            return view('superadmin.pesanan', [
                'title' => 'pesanan pakaian custom',
                'instansi' => $instansi,
                'pesanan' => $data,
            ]);
        } elseif (Auth::user()->role == 'produksi') {
            $data = Pesanan::joinToProdukCustom()->joinToKategoriProdukCustom()->joinToWarna()
                ->joinToUser()->joinToSablon()->joinToKurir()->joinToPayment()->orderBy('pesanan_id', 'desc')->get();
            $instansi = Instansi::select('logo')->get();
            return view('superadmin.pesanan', [
                'title' => 'pesanan pakaian custom',
                'instansi' => $instansi,
                'pesanan' => $data,
            ]);
        } elseif (Auth::user()->role == 'pelanggan') {
            $instansi = Instansi::select('logo', 'whatsapp')->get();
            $isiKeranjang = Shop_cart::where('user_id', '=', Auth::user()->user_id)->get()->count(); //hitung isi keranjang berdasarkan user yang login

            $dataPesanan = Pesanan::joinToProdukCustom()->joinToKategoriProdukCustom()->joinToWarna()
                ->joinToUser()->joinToSablon()->joinToKurir()->joinToPayment()->orderBy('pesanan_id', 'desc')->get();
            // dd($dataPesanan);
            return view('members.pesananAnda', [
                'title' => 'history transaksi',
                'instansi' => $instansi,
                'data_pesanan' => $dataPesanan,
                'isiKeranjang' => $isiKeranjang,
            ]);
        } else {
            echo '403 forbidden';
        }
    }

    // pesan sablon secara langsung dari halaman home
    public function AddPesanan(Request $req)
    {
        $req->validate([
            'user_id' => 'required',
            'sablon_id' => 'required',
            'kurir_id' => 'required',
            'payment_id' => 'required',
            'jml_order' => 'required',
            't_pesan' => 'required',
            'tgl_order' => 'required',
        ]);
        try {
            Pesanan::create($req->all());
            return redirect('pesanananda')->with('success', 'pesanan berhasil');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect('home')->with('errors', $e);
        }
    }

    // function bayar DP produk custom atau sablon
    public function BayarProdukCustom(Request $req)
    {
        $req->validate([
            'jml_dp' => 'required',
            'b_dp' => 'required|image|mimes:png, jpg, jpeg|max:2048',
            'pay_status' => 'required',
        ]);
        try {
            $buktiDP = time() . '.' . $req->b_dp->extension();
            $req->b_dp->move(public_path('pembayaran/bukti_dp'), $buktiDP);
            $bayar = array(
                'jml_dp' => $req->post('jml_dp'),
                'b_dp' => $buktiDP,
                'pay_status' => $req->post('pay_status'),
            );
            // dd($bayar);
            Pesanan::where('pesanan_id', '=', $req->post('pesanan_id'))->update($bayar);
            return redirect('pesanananda')->with("success", 'proses pembayaran berhasil');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect('pesanananda')->with('errors', 'pembayaran gagal silahkan coba lagi');
        }
    }

    // function bayar lunas atau bayar cash produk custom dan sablon
    public function BayarLunas(Request $req)
    {
        $req->validate([
            'jml_lunas' => 'required',
            'b_lunas' => 'required|image:png. jpg, jpeg|max:2048',
            'pay_status' => 'required',
        ]);
        try {
            $buktiLunas = time() . '.' . $req->b_lunas->extension();
            $req->b_lunas->move(public_path('pembayaran/bukti_lunas'), $buktiLunas);

            $bayarLunas = array(
                'jml_lunas' => $req->post('jml_lunas'),
                'b_lunas' => $buktiLunas,
                'pay_status' => $req->post('pay_status'),
            );
            Pesanan::where('pesanan_id', '=', $req->post('pesanan_id'))->update($bayarLunas);
            return redirect('invoice')->with('success', 'pembayaran berhasil');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect('pesanananda')->with('errors', 'pembayaran gagal silahkan coba lagi');
        }
    }

    // function validasi pesanan oleh kasir
    public function ValidasiPesanan(Request $req)
    {
        $req->validate([
            'pay_status' => 'required',
            'status_pesanan' => 'required'
        ]);
        try {
            $validationPayment = array(
                'pay_status' => $req->post('pay_status'),
                'status_pesanan' => $req->post('status_pesanan'),
            );
            Pesanan::where('pesanan_id', '=', $req->post('pesanan_id'))->update($validationPayment);
            return redirect('pesanan')->with('success', 'validasi berhasil');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect('pesanan')->with('errors', 'validasi pembayaran');
        }
    }

    // validasi pesanan oleh produksi
    public function ValidasiProduction(Request $req)
    {
        $req->validate([
            'stts_produksi' => 'required'
        ]);
        try {
            $validasiProduksi = array(
                'stts_produksi' => $req->post('stts_produksi'),
            );
            Pesanan::where('pesanan_id', '=', $req->post('pesanan_id'))->update($validasiProduksi);
            return redirect('pesanan')->with('success', 'rubah status produksi berhasil');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect('pesanan')->with('errors', 'rubah status produksi gagal');
        }
    }

    // function input diskon oleh kasir
    public function Discount(Request $req)
    {
        $req->validate([
            '' => '',
        ]);
        try {
            $diskon = array(
                '' => $req->post(''),
                '' => $req->post(''),
                '' => $req->post(''),
            );
            Pesanan::where('pesanan_id', '=', $req->post('pesanan_id'))->update();
            return redirect('pesanan')->with('success', 'diskon berhasil');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect('pesanan')->with('errors', 'diskon gagal');
        }
    }

    // function konfirmasi pesanan selesai oleh customer
    public function PesananSelesai(Request $req)
    {
        $req->validate([
            'pesanan_id' => 'required',
            'status_pesanan' => 'required'
        ]);
        try {
            $data = array(
                'pesanan_id' => $req->post('pesanan_id'),
                'status_pesanan' => $req->post('status_pesanan')
            );
            // dd($data);
            Pesanan::where('pesanan_id', $req->post('pesanan_id'))->update($data);
            return redirect('pesanananda')->with('success', 'konfirmasi pesanan selesai, berhasil');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect('pesanan')->with('success', 'konfirmasi pesanan selesai, gagal');
        }
    }

    // function delete pesanan oleh super admin
    public function DeletePesanan($id)
    {
        try {
            Pesanan::where('pesanan_id', '=', $id)->delete();
            return redirect('pesanan')->with('success', 'pesanan berhasil di hapus');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect('pesanan')->with('success', 'pesanan gagal di hapus');
        }
    }

    // checkout now on halaman detail produk custom
    public function CheckoutNow(Request $req)
    {
        $roles = [
            'data.procus_id' => 'required',
            'data.size_order' => 'required',
            'data.jumlah_order' => 'required',
            'data.harga_totals' => 'required',
            'data_jasa.kurir_id' => 'required',
            'data_jasa.payment_id' => 'required',
            'data_jasa.t_pesan' => 'required',
        ];
        $messages = [
            'data.procus_id.required' => 'produk tidak boleh kosong',
            'data.size_order.required' => 'ukuran tidak boleh kosong',
            'data.jumlah_order.required' => 'jumlah order tidak boleh kosong',
            'data.harga_totals.required' => 'harga total tidak boleh kosong',
            'data_jasa.kurir_id.required' => 'kurir tidak boleh kosong',
            'data_jasa.payment_id.required' => 'metode pembayaran tidak boleh kosong',
            'data_jasa.t_pesan.required' => 'pesan tidak boleh kosong',
        ];
        $validasi = Validator::make($req->all(), $roles, $messages);
        if ($validasi->fails()) {
            return response()->json(['errors' => $validasi->errors()]);
        }

        $pesananNow = new Pesanan();
        $pesananNow->procus_id = $req->data['procus_id'];
        $pesananNow->user_id = Auth::user()->user_id;
        $pesananNow->size_orders = $req->data['size_order'];
        $pesananNow->jml_order = $req->data['jumlah_order'];
        $pesananNow->all_total = $req->data['harga_totals'];
        $pesananNow->kurir_id = $req->data_jasa['kurir_id'];
        $pesananNow->payment_id = $req->data_jasa['payment_id'];
        $pesananNow->t_pesan = $req->data_jasa['t_pesan'];
        $pesananNow->tgl_order = date('Y-m-d');
        $pesananNow->save();
        return response()->json(['success' => 'berhasil di checkout']);
    }
}
