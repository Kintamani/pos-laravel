<?php

namespace POS\Http\Controllers;

use Illuminate\Http\Request;
use POS\User;
use Hash;
use Auth;

class UserController extends Controller
{
    
    public function index()
    {
        return view('user.index');
    }

    public function listData()
    {
        $user = User::where('level','!=', 1)->orderBy('id', 'desc')->get();
        $no = 0;
        $data = array();
        foreach($user as $list) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $list -> name;
            $row[] = $list -> email;
            $row[] = '<div class="btn-group"> 
                        <a onclick = "editForm('.$list -> id.')" class = "btn btn-primary btn-sm"> 
                            <i class="fa fa-pencil"></i>
                        </a> 
                        <a onclick = "deleteData('.$list -> id.')" class="btn btn-danger btn-sm">
                            <i class="fa fa-trash"></i> 
                        </a> 
                    </div>';
            $data[] = $row;
        }
        $output = array("data" => $data);
        return response() -> json($output);
    }

    public function store(Request $request)
    {
        $user = new User;
        $user -> name       = $request['nama'];
        $user -> email      = $request['email'];
        $user -> password   = bcrypt($request['password']);
        $user -> level      = 2;
        $user -> foto       = "user.png";
        $user -> save();
    }

    public function edit($id)
    {
        $user = User::find($id);
        echo json_encode($user);
    }

    public function update(Request $request, $id)
    {
        $user = User :: find($id);
        $user -> name       = $request['nama'];
        $user -> email      = $request['email'];
        if(!empty($request['password'])) 
            $user -> password   = bcrypt($request['password']);
        $user -> update();
    }

    public function destroy($id)
    {
        $user = User :: find($id);
        $user -> delete();
    }

    public function profil()
    {
        $user = Auth :: User();
        return view('user.profil', compact('user'));
    }

    public function changeProfil(Request $request, $id)
    {
        $msg = "success";
        $user = User :: find($id);

        //cek password
        if(!empty($request['password'])){
            //cek Password(lama) == Password(Database)
            if(Hash :: check($request['passwordlama'], $user -> password)){
                $user -> password = bcrypt($request['password']);
            }
            else{
                $msg = 'error';
            }
        }

        //cek foto != empty
        if($request->hasFile('foto')){
            $file = $request -> file('foto');
            $nama_gambar        = "foto_user_".$id.".".$file->getClientOriginalExtension();
            $lokasi             = public_path('images');
            
            //upload foto ke folder images
            $file               -> move($lokasi, $nama_gambar);
            $user -> foto       = $nama_gambar;
            $datagambar         = $nama_gambar; 
        }
        else{
            $datagambar         = $user -> foto;
        }

        $user -> update();
        echo json_encode(array
                            (
                            'msg' => $msg,
                            'url' => asset('images/'.$datagambar)
                            )
                        );
    }
}
