@extends('layouts.app')

@section('title')
    Daftar Produk
@endsection

@section('breadcrumb')
    @parent
    <li>produk</li>
@endsection

@section('content')
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <a onclick="addForm()" class="btn btn-success"><i class="fa fa-plus-circle"></i> Tambah</a>
            <a onclick="deleteAll()" class="btn btn-danger"><i class="fa fa-trash"></i> Hapus</a>
            <a onclick="printBarcode()" class="btn btn-info"><i class="fa fa-barcode"></i> Cetak Barcode</a>
          </div>

          <div class="box-body">
            <form method="POST" id="form-produk">
              {{ csrf_field() }}
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th width="20"><input type="checkbox" value="1" id="select-all"></th>
                    <th width="20">No</th>
                    <th>Kode Produk</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Merk</th>
                    <th>Harga Beli</th>
                    <th>Harga Jual</th>
                    <th>Diskon</th>
                    <th>Stok</th>
                    <th width="100">Aksi</th>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>
            </form>
          </div>
        </div>
      </div>
    </div>
  @include('produk.form')
@endsection

@section('script')
<script type="text/javascript">
  var table, save_method;

  $(function() {
    //Menampilkan data dengan Plugin
    table = $('.table').DataTable({
      "processing" : true,
      "serverside" : true,
      "ajax" : {
        "url" : "{{ route('produk.data') }}",
        "type" : "GET"
      },
      'columnDefs' : [{
        'targets' : 0,
        'searchable' : false,
        'orderable' : false
      }],
      "order" : [1, 'asc']
    });
    

    //Centang semua checkbox ketika checkbox dengan id #select-all dicentang
    $('#select-all').click(function(){
      $('input[type = "checkbox"]').prop('checked', this.checked);
    });

    //menyimpan data dari form tambah / edit
    $('#modal-form form').validator().on('submit', function(e){
      if(!e.isDefaultPrevented()){
        var id = $('#id').val();
        if(save_method == "add") url = "{{ route('produk.store') }}";
        else url = "produk/"+id;

        $.ajax ({
          url : url,
          type : "POST",
          data : $('#modal-form form').serialize(),
          dataType : 'JSON',
          success : function(data){
            if(data.msg == "error"){
              alert("kode produk sudah digunakan");
              $('#kode').focus().select();
            }
            else{
              $('#modal-form').modal('hide');
              table.ajax.reload();
            }
          },
          error : function(){
            alert("Tidak dapat meyimpan data");
          }
        });
        return false;
      }
    });
  });

  //Menampilkan Form tambah data
  function addForm(){
    save_method = "add";
    $('input[name = _method]').val('POST');
    $('#modal-form').modal('show');
    $('#modal-form form')[0].reset();
    $('.modal-title').text('Tambah Produk');
    $('#kode').attr('readonly',false);
  }

  //Menampilkan Form edit data
  function editForm(id){
    save_method = "edit";
    $('input[name = _method]').val('PATCH');
    $('#modal-form form')[0].reset();
        
    $.ajax({
      url : "produk/"+id+"/edit",
      type : "GET",
      dataType : "JSON",      
      success: function(data){
        $('#modal-form').modal('show');
        $('.modal-title').text('Edit Karakter');

        $('#id').val(data.id_produk);
        $('#kode').val(data.kode_produk).attr('readonly',true);
        $('#nama').val(data.nama_produk);
        $('#kategori').val(data.id_kategori);
        $('#merk').val(data.merk);
        $('#harga_beli').val(data.harga_beli);
        $('#diskon').val(data.diskon);
        $('#harga_jual').val(data.harga_jual);
        $('#stok').val(data.stok);
      },
      error: function(){
        alert("Tidak dapet menampilkan data");
      }
    });
  }

  //Menghapus data
  function deleteData(id){
    if(confirm("Apakah anda yakin data akan dihapus ?")){
      $.ajax({
        url : "produk/"+id,
        type : "POST",
        headers: {
          'X-CSRF-TOKEN': $('meta[ name= csrf-token]').attr('content')
        },
        data : {'_method' : 'DELETE', '_token' :
         $('meta[ name = csrf-token]').attr('content')},
        //salah
        success : function(data){
          table.ajax.reload();
        },
        error:function(){
          alert("Tidak dapat menghapus data !");
          //table.ajax.reload();
        }
      });
    }
  }

  //Menghapus semua data yang di Centang
  function deleteAll(){
    if($('input:checked').length < 1 ){
      alert('Pilih data yang akan dihapus!');
    }
    else if (confirm("Apakah yakin akan menghapus semua data yang terpih")){
      $.ajax({
        url : "produk/hapus",
        type : "POST",
        headers: {
          'X-CSRF-TOKEN': $('meta[ name= csrf-token]').attr('content')
        },
        data : $('#form-produk').serialize(),
        success : function(data){
          table.ajax.reload();
        },
        error : function(){
          alert("Tidak dapat menghapus data !");
          //table.ajax.reload();
        }
      });
    }
  }

  //Mencetak barcode ketika tombol cetak Barcode dikiclk
  function printBarcode(){
    if($('input:checked').length < 1){
      alert('Pilih data yang akan dicetak !');
    }
    else{
      $('#form-produk').attr('target','_blank').attr('action',"produk/cetak").submit();
    }
  }
</script>
    
@endsection




