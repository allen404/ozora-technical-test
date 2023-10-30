<?php

namespace App\Http\Controllers;


use DB;
use File;
use Illuminate\Http\Request;
use App\Models\Barang;
use Illuminate\Validation\Rule;
use Str;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $barang = DB::table('barang')->orderBy('updated_at', 'desc')->paginate(4);

        echo view('main', compact('barang'));
    }

    public function searchBarang(Request $request)
    {
        if ($request->searchTerm == 'all') {
            $barang = DB::table('barang')->orderBy('updated_at', 'desc')->paginate(4);
            $barang->withPath('/barang');
            $data = array();
            $data['success'] = 1;
            $data['html'] = view('tabel_barang', compact('barang'))->render();
            return response()->json($data);
        } else {
            $number_of_barang = DB::table('barang')->where('nama_barang', 'like', '%' . $request->searchTerm . '%')->count();
            $barang = DB::table('barang')->where('nama_barang', 'like', '%' . $request->searchTerm . '%')->paginate(4);
            $data = array();
            if ($number_of_barang == 0) {
                $data['success'] = 0;
                $data['message'] = 'Data Barang Tidak Ditemukan';
            } else {
                $data['success'] = 1;
                $data['html'] = view('tabel_barang', compact('barang'))->render();
            }
            return response()->json($data);
        }
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        //
        $barang_model = new Barang();

        $daftar_barang = $barang_model->select('nama_barang')->get()->all();
        $undercase_barang = array_map('strtolower', $daftar_barang);


        $request->validate([
            'nama_barang' => [
                'required',
                'unique:barang,nama_barang',
                Rule::notIn($undercase_barang)
            ],
            'harga_jual' => 'required|numeric',
            'stok' => 'required|numeric',
            'harga_beli' => 'required|numeric',
            'foto_barang' => 'required|image|mimes:jpeg,png|file|max:100',
        ]);


        $barang_model->nama_barang = $request->nama_barang;
        $barang_model->harga_beli = $request->harga_beli;
        $barang_model->stok = $request->stok;
        $barang_model->harga_jual = $request->harga_jual;

        if ($request->file('foto_barang')) {
            $file_foto_barang = $request->file('foto_barang');

            $name_foto_barang = $request->nama_barang . '_foto_' . Str::random(32) . '.' . $file_foto_barang->extension();
            $file_foto_barang->move(public_path() . '/upload/barang/', $name_foto_barang);

            $barang_model->foto_barang = $name_foto_barang;
        }

        $barang_model->save();


        if ($request->source == 'version1') {
            return redirect()->route('barang.index')->with('success', 'Data Barang Berhasil Ditambahkan');
        } else {
            return redirect()->route('barangv2')->with('success', 'Data Barang Berhasil Ditambahkan');
        }


    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Barang $barang
     * @return \Illuminate\Http\Response
     */
    public function show(Barang $barang)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Barang $barang
     * @return \Illuminate\Http\Response
     */
    public function edit(Barang $barang)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Barang $barang
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Barang $barang)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Barang $barang
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        //


        $barang_model = Barang::find($id);

        File::delete(public_path() . '/upload/barang/' . $barang_model->foto_barang);

        $barang_model->delete();


        if ($request->source == 'version1') {
            return redirect()->route('barang.index')->with('success', 'Data Barang Berhasil Dihapus');
        } else {
            return redirect()->route('barangv2')->with('success', 'Data Barang Berhasil Dihapus');
        }


    }

    public function updateBarang(Request $request)
    {
        $barang_model = Barang::find($request->id_barang);

        $undercase_barang = array_map('strtolower', Barang::select('nama_barang')->get()->all());

        $request->validate([
            'nama_barang' => [
                'required',
                Rule::unique('barang')->ignore($barang_model->id),
                Rule::notIn($undercase_barang)
            ],
            'harga_jual' => 'required|numeric',
            'stok' => 'required|numeric',
            'harga_beli' => 'required|numeric',
            'foto_barang' => 'image|mimes:jpeg,png|file|max:100',
        ]);


        $barang_model->nama_barang = $request->nama_barang;
        $barang_model->harga_beli = $request->harga_beli;
        $barang_model->stok = $request->stok;
        $barang_model->harga_jual = $request->harga_jual;

        if ($request->file('foto_barang')) {
            File::delete(public_path() . '/upload/barang/' . $barang_model->foto_barang);

            $file_foto_barang = $request->file('foto_barang');
            $name_foto_barang = $request->nama_barang . '_foto_' . Str::random(32) . '.' . $file_foto_barang->extension();
            $file_foto_barang->move(public_path() . '/upload/barang/', $name_foto_barang);
            $barang_model->foto_barang = $name_foto_barang;
        }

        $barang_model->update();

        if ($request->source == 'version1') {
            return redirect()->route('barang.index')->with('success', 'Data Barang Berhasil Diubah');
        } else {
            return redirect()->route('barangv2')->with('success', 'Data Barang Berhasil Diubah');
        }


    }
}
