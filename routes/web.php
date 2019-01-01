<?php

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
use POS\Pembelian;
Route::get('/', 'HomeController@index')->name('home');
Auth::routes();

Route::group(['middleware'=>['web','cekuser:1']],
  function () {
  //kategori
    Route::get('kategori/data', 'KategoriController@listData')->name('kategori.data');
    Route::resource('kategori', 'KategoriController');
  
  //produk
    Route::get('produk/data', 'ProdukController@listData')->name('produk.data');
    Route::post('produk/hapus', 'ProdukController@deleteSelected');
    Route::post('produk/cetak', 'ProdukController@printBarcode');
    Route::resource('produk', 'ProdukController');
  
  //supplier
    Route::get('supplier/data', 'SupplierController@listData')->name('supplier.data');
    Route::resource('supplier', 'SupplierController');

  //member
    Route::get('member/data', 'MemberController@listData')->name('member.data');
    Route::post('member/cetak', 'MemberController@printCard');
    Route::resource('member', 'MemberController');

  //pengeluaran
    Route::get('pengeluaran/data', 'PengeluaranController@listData')->name('pengeluaran.data');
    Route::resource('pengeluaran', 'PengeluaranController');

  //manajemen user dan ubah profil
    Route::get('user/data', 'UserController@listData')->name('user.data');
    Route::resource('user', 'UserController',['only'=> ['index','create','store']]);

  //pembelian
    Route::get('pembelian/data', 'PembelianController@listData')->name('pembelian.data');
    Route::get('pembelian/{pembelian}/tambah', 'PembelianController@create');
    Route::get('pembelian/{pembelian}/lihat', 'PembelianController@show');
    Route::resource('pembelian', 'PembelianController');

  //pembelian detail
    Route::get('pembelian_detail/{pembelian_detail}/data', 'PembelianDetailController@listData')->name('pembelian_detail.data');
    Route::get('pembelian_detail/loadform/{diskon}/{total}', 'PembelianDetailController@loadForm');
    Route::resource('pembelian_detail', 'PembelianDetailController');

  });

  Route::group(['middleware' => 'web'], function() {
    //user
    Route::get('user/profil', 'UserController@profil')->name('user.profil');
    Route::patch('user/{user}/change', 'UserController@changeProfil');
  });


  



