<?php

namespace App\Http\Controllers;

use App\Models\MenuModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class MenuController extends Controller
{
    public function getAllData()
    {
        $data = MenuModel::all();
        return response()->json([
            'data' => $data
        ], 200);
    }

    public function createData(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'nama_menu' => 'required',
            'gambar_menu' => 'required',
            'harga' => 'required'
        ]);

        if ($validation->fails()) {
            return response()->json([
                'message' => 'check your validation',
                'errors' => $validation->errors()
            ]);
        }

        try {
            $data = new MenuModel;
            $data->nama_menu = $request->input('nama_menu');
            if ($request->hasFile('gambar_menu')) {
                $file = $request->file('gambar_menu');
                $extention = $file->getClientOriginalExtension();
                $filename = 'MENU-' . Str::random(15) . '.' . $extention;
                Storage::makeDirectory('uploads/menu');
                $file->move(public_path('uploads/menu'), $filename);
                $data->gambar_menu = $filename;
            }
            $data->harga = $request->input('harga');
            $data->save();
        } catch (\Throwable $th) {
            return response()->json([
                'errors' => $th->getMessage()
            ]);
        }

        return response()->json([
            'message' => 'success',
            'data' => $data
        ]);
    }
}
