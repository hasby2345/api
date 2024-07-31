<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::latest()->get();
        $res = [
            'success'=>true,
            'message'=>'Daftar User',
            'data'=>$user,
        ];
        return response()->json($res, 200);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name'=>'required',
            'email'=>'required|unique:users',
            'password'=>'required|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->save();
            return response()->json([
                'success' => true,
                'message' => 'data berhasil dibuat',
                'data' => $user,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'terjadi kesalahan',
                'data' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try{
            $user = User::findOrFail($id);
            return response()->json([
                'success' => true,
                'message' => 'detail User',
                'data' => $user,
            ], 200);
        } catch(\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'data tidak ditemukan',
                'errors' => $e->getMessage(),
            ], 404);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'name'=>'required',
            'email'=>'required',
            'password'=>'required|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $user = User::findOrFail($id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->save();
            return response()->json([
                'success' => true,
                'message' => 'data berhasil diperbarui',
                'data' => $user,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'terjadi kesalahan',
                'data' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try{
            $user = User::findOrFail($id);
            $user->delete();
            return response()->json([
                'success' => true,
                'message' => 'data' .  $user->name  . 'Berhasil dihapus',
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
