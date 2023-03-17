<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Marca;

class MarcaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //devolver una lista de registros
        try{
            $marcas = Marca::all();
            return $marcas;
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.marcas');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            $marca = new Marca();
            $marca->nombre = $request->nombre;
            if($marca->save()>= 1){
                return response()->json(['status'=>'ok','data'=>$marca],201);
            }else{
                return response()->json(['status'=>'fail','data'=>null],409);
            }
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try{
            $marca = Marca::findOrFail($id);
            return $marca;
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try{
            $marca = Marca::findOrFail($id);
            $marca->nombre = $request->nombre;
            if($marca->update()>= 1){
                return response()->json(['status'=>'ok','data'=>$marca],201);
            }
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            $marca = Marca::findOrFile($id);
            if($marca->delete()>= 1){
                return response()->json(['status'=>'ok','data'=>$marca],200);
            }
        }catch(\Exception $e){
            return $e->getMessage();
    }
}
}
