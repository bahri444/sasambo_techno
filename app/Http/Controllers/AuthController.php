<?php

namespace App\Http\Controllers;

use App\Models\Instansi;
use App\Models\Partner;
use App\Models\Shop_cart;
use App\Models\User;
use App\Models\UserVerify;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // get data user return to view admin
    public function GetAlluser()
    {
        if (Auth::user()->role == 'superadmin') {
            $in = Instansi::select('logo')->get();
            $user = User::all();
            return view('superadmin.user', [
                'title' => 'akun user',
                'users' => $user,
                'instansi' => $in
            ]);
        } elseif (Auth::user()->role == 'pelanggan') {

            $isiKeranjang = Shop_cart::where('user_id', '=', Auth::user()->id)->get()->count(); //hitung isi keranjang berdasarkan user yang login
            $instansi = Instansi::select('logo')->get();
            $user = User::all();
            return view('members.profile', [
                'title' => 'akun user',
                'users' => $user,
                'instansi' => $instansi,
                'isiKeranjang' => $isiKeranjang,
            ]);
        } else {
            print('akses di tolak');
        }
    }

    // register user di lakukan oleh super admin
    public function AddUser(Request $req)
    {
        try {
            $req->validate([
                'name' => 'required',
                'email' => 'required|unique:users',
                'password' => 'required|min:6',
                'role' => 'required'
            ]);
            $data = new User([
                'name' => $req->name,
                'email' => $req->email,
                'password' => Hash::make('password'),
                'role' => $req->role,
            ]);
            $data->save();
            // dd($data);
            return redirect('user')->with('success', 'register berhasil');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect('user')->with('errors', 'register gagal');
        }
    }

    public function GetViewLogin()
    {
        $partnerPerusahaan = Partner::select('nama_prshn', 'logo_prshn')->get();
        $data = Instansi::all();
        return view('auth.login', [
            'title' => 'halaman login',
            'instansi' => $data,
            'partnerperusahaan' => $partnerPerusahaan
        ]);
    }
    public function AuthLogin(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
        // dd($request);
        $data = $request->only('email', 'password');
        if (Auth::attempt($data)) {
            if (Auth::user()->role == 'superadmin') {
                return redirect()->intended('/dashboard')->withSuccess('anda berhasil login');
            } elseif (Auth::user()->role == 'kasir') {
                return redirect()->intended('/index')->withSuccess('anda berhasil login');
            } elseif (Auth::user()->role == 'produksi') {
                return redirect()->intended('/pesanan')->withSuccess('anda berhasil login');
            } elseif (Auth::user()->role == 'pelanggan') {
                return redirect()->intended('/home')->withSuccess('anda berhasil login');
            } else {
                print_r("anda tidak memiliki hak akses");
            }
        }
        return redirect('login')->withSuccess('invalid username or password');
    }

    public function GetViewRegister()
    {
        $partnerPerusahaan = Partner::select('nama_prshn', 'logo_prshn')->get();
        $data = Instansi::all();
        return view('auth.register', [
            'title' => 'halaman register',
            'instansi' => $data,
            'partnerperusahaan' => $partnerPerusahaan
        ]);
    }

    public function RegisterPost(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);
        // dd($request);
        $token = Str::random(64);
        $data = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'token' => $token,
        ]);
        $data->save();
        Mail::send('email.emailVerification', ['token' => $token], function ($message) use ($request) {
            $message->to($request->email);
            $message->subject('silahkan verifikasi email anda');
        });
        return redirect('/login')->withSuccess('berhasil registrasi');
    }

    public function Home()
    {
        if (Auth::check()) {
            return view('members.home', [
                'title' => 'home'
            ]);
        }
        return redirect('login')->withSuccess('silahkan login dulu');
    }

    public function VerifyAccount($token)
    {
        try {
            $date_time = Carbon::now()->toDateTimeString();
            $data = array(
                'is_email_verified' => 1,
                'email_verified_at' => $date_time,
            );
            User::where('token', '=', $token)->update($data);
            return redirect()->route('login')->with('message', 'verifikasi berhasil');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->route('login')->with('errors', 'verifikasi gagal');
        }
    }

    // function for change role
    public function ChangeRole(Request $req)
    {
        $req->validate([
            'role' => 'required'
        ]);
        try {
            $data = array(
                'role' => $req->post('role')
            );
            User::where('id', '=', $req->post('id'))->update($data);
            return redirect('user')->with('success', 'hak akses berhasil di edit..!');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect('user')->with('message', 'register gagal');
        }
    }

    // update data user oleh pelanggan
    public function UpdtUser(Request $req)
    {
        $req->validate([
            'telepon' => 'required',
            'gender' => 'required',
            'desa' => 'required',
            'kecamatan' => 'required',
            'kabupaten' => 'required',
            'provinsi' => 'required',
        ]);
        try {
            $data = array(
                'telepon' => $req->post('telepon'),
                'gender' => $req->post('gender'),
                'desa' => $req->post('desa'),
                'kecamatan' => $req->post('kecamatan'),
                'kabupaten' => $req->post('kabupaten'),
                'provinsi' => $req->post('provinsi'),
            );
            User::where('id', '=', $req->post('id'))->update($data);
            return redirect('profile')->with('success', 'data berhasil di simpan');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect('profile')->with('message', 'gagal');
        }
    }

    public function Delete($id)
    {
        try {
            User::where('id', '=', $id)->delete();
            return redirect('user')->with('success', 'akun berhasil di hapus..!');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect('user')->with('message', 'register gagal');
        }
    }

    // form lengkapi akun pelanggan
    public function GetForm()
    {
        $instansi = Instansi::select('logo')->get();
        $isiKeranjang = Shop_cart::where('user_id', '=', Auth::user()->id)->get()->count(); //hitung isi keranjang berdasarkan user yang login
        return view('members.getFormProfile', [
            'title' => 'lengkapi akun',
            'instansi' => $instansi,
            'isiKeranjang' => $isiKeranjang,
        ]);
    }

    public function logout()
    {
        Session::flush();
        Auth::logout();
        return redirect('/');
    }
}
