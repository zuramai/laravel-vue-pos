<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Siswa;

class SiswaController extends Controller
{
    public function index() {
        return Siswa::all();
    }

    public function create(Request $request) {
        $siswa = new Siswa;
        $siswa->nama = $request->nama;
        $siswa->email = $request->email;
        $siswa->alamat = $request->alamat;
        $siswa->nohp = $request->nohp;
        $siswa->save();
        return "Data berhasil";
    }

    public function update(Request $request, $id) {
        $nama = $request->nama;
        $alamat = $request->alamat;
        $nohp = $request->nohp;
        $email = $request->email;

        $siswa = Siswa::find($id);
        $siswa->nama = $nama;
        $siswa->alamat = $alamat;
        $siswa->nohp = nohp;
        $siswa->save();

        return response()->json(['status' => true, 'message' => 'Data berhasil diupdate']);
    }

    public function delete($id) {
        $siswa = Siswa::find($id);
        $siswa->delete();

        return response()->json(['status'=>true,'message'=>'Data berhasil dihapus!']);
    }


}
