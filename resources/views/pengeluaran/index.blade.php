@extends('layouts.app')

@section('title')
    Daftar Pengeluaran
@endsection

@section('breadcrumb')
    @parent
    <li>Pengeluaran</li>
@endsection

@section('content')
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <a onclick="addForm()" class="btn btn-success"><i class="fa fa-plus-circle"></i> Tambah</a>
          </div>

          <div class="box-body">
            <form method="POST" id="form-member">
              {{ csrf_field() }}
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th width="30">No</th>
                    <th>Tanggal</th>
                    <th>Jenis Pengeluaran</th>
                    <th>Nominal</th>
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
    @include('pengeluaran.form')
@endsection

@section('script')
    <script type="text/javascript">
      
      var table, save_method;

      $(function(){
        table = $('.table').DataTable({
          "processing" : true,
          "ajax" : {
            "url" : "{{ route('pengeluaran.data') }}",
            "type" : "GET"
          }
        });

        $('#modal-form form').validator().on('submit' , function(e){
          if(!e.isDefaultPrevented()){
            var id = $('#id').val();
            if(save_method == "add") url = "{{route('pengeluaran.store')}}";
            else url = "pengeluaran/"+id;
    
            $.ajax({
              url : url,
              type : 'POST',
              data : $('#modal-form form').serialize(),
              success : function(data){
                $('#modal-form').modal('hide');
                table.ajax.reload();
              },
              error : function(){
                alert("tidak dapat meyimpan data");
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
        $('.modal-title').text('Tambah Pengeluaran');
      }

      //Menampilkan Form edit data
      function editForm(id){
        save_method = "edit";
        $('input[name=_method]').val('PATCH');
        $('#modal-form form')[0].reset();
            
        $.ajax({
          url : "pengeluaran/"+id+"/edit",
          type : "GET",
          dataType : "JSON",      
          success: function(data){
            $('#modal-form').modal('show');
            $('.modal-title').text('Edit Pengeluaran');
    
            $('#id').val(data.id_pengeluaran);
            $('#jenis').val(data.jenis_pengeluaran);
            $('#nominal').val(data.nominal);
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
            url : "pengeluaran/"+id,
            type : "POST",
            headers: {
              'X-CSRF-TOKEN': $('meta[ name= csrf-token]').attr('content')
            },
            data : {'_method' : 'DELETE', '_token' :
             $('meta[ name = csrf-token]').attr('content')
            },
            //salah
            success : function(data){
              table.ajax.reload();
            },
            error : function(){
              alert("Tidak dapat menghapus data !");
            }
          });
        }
      }

    </script>
@endsection