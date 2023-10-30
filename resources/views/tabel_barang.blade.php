<table id="table" name="table" class="table table-responsive table-striped table-bordered">
    <thead>
    <tr>
        <th>ID</th>
        <th>Foto Barang</th>
        <th>Nama Barang</th>
        <th>Harga Beli</th>
        <th>Harga Jual</th>
        <th>Stok</th>
        <th>Aksi</th>
    </tr>
    </thead>
    <tbody>
    @foreach($barang as $b)
        <tr>
            <td>{{$b->id}}</td>
            <td><img src="{{asset('upload/barang/'.$b->foto_barang)}}" alt=""
                     class="img-fluid mx-auto d-block" width="200px"></td>
            <td>{{$b->nama_barang}}</td>
            <td>{{$b->harga_beli}}</td>
            <td>{{$b->harga_jual}}</td>
            <td>{{$b->stok}}</td>
            <td>
                <div class="btn-toolbar justify-content-center align-items-center">
                    <div class="btn-group me-2">
                        <button type="button" id="data_edit" class="btn btn-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#updateBarangModal"
                                data-db-namaBarang="{{$b->nama_barang}}"
                                data-db-hargaBeli="{{$b->harga_beli}}"
                                data-db-hargaJual="{{$b->harga_jual}}"
                                data-db-stok="{{$b->stok}}"
                                data-db-idBarang="{{$b->id}}">Update Informasi Barang
                        </button>
                    </div>
                    <div class="btn-group me-2">
                        <form action="{{route('barang.destroy', $b->id)}}" method="POST">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" value="version1" name="source" id="source"/>
                            <button name="delete" type="submit" class="btn btn-danger"
                                    onclick="return confirm('Apakah anda yakin untuk menghapus data?')">
                                Hapus Barang
                            </button>
                        </form>
                    </div>
                </div>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<div class="row">
    {{ $barang->links()}}
</div>
