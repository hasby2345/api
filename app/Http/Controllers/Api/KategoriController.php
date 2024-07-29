<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kategori;
use Validator;
use Str;

class KategoriController extends Controller
{
    public function index(){
        $kategori = Kategori::latest()->get();
        $res = [
            'success'=>true,
            'message'=>'Daftar Kategori',
            'data'=>$kategori,
        ];
        return response()->json($res, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'nama_kategori'=>'required|unique:kategoris',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $kategori = new Kategori();
            $kategori->nama_kategori = $request->nama_kategori;
            $kategori->slug = Str::slug($request->nama_kategori);
            $kategori->save();
            return response()->json([
                'success' => true,
                'message' => 'data berhasil dibuat',
                'data' => $kategori,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'terjadi kesalahan',
                'data' => $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        try{
            $kategori = Kategori::findOrFail($id);
            return response()->json([
                'success' => true,
                'message' => 'detail kategori',
                'data' => $kategori,
            ], 200);
        } catch(\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'data tidak ditemukan',
                'errors' => $e->getMessage(),
            ], 404);
        }
    }
}
