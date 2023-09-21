<?php

namespace App\Http\Controllers;

use App\Models\CartModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function getAllData()
    {
        $data = CartModel::with('menu')->get();
        return response()->json([
            'data' => $data
        ]);
    }


    public function countData()
    {
        $totalItems = CartModel::distinct('id_menu')->count('id_menu');

        return response()->json([
            'message' => 'Success',
            'total_items' => $totalItems
        ]);
    }


    public function createData(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'id_menu' => 'required',
            'quantity' => 'required'
        ]);

        if ($validation->fails()) {
            return response()->json([
                'message' => 'check your valdiation',
                'errors' => $validation->errors()
            ]);
        }

        try {
            $contMenu = CartModel::where('id_menu', $request->input('id_menu'))->first();

            if ($contMenu) {
                $contMenu->quantity += $request->input('quantity');
                $contMenu->save();
                $data = $contMenu;
            } else {
                $data = new CartModel;
                $data->id_menu = $request->input('id_menu');
                $data->quantity = $request->input('quantity');
                $data->save();
            }
        } catch (\Throwable $th) {
            return response()->json([
                'errors' => $th->getMessage()
            ]);
        }

        return response()->json([
            'message' => 'success add cart',
            'data' => $data
        ]);
    }
}
