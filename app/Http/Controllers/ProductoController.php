<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;


class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $productos = Producto::all();
            //convirtiendo a array
            $response = $productos->toArray();
            $i=0;
            foreach($productos as $producto){
                $response[$i]["marca"] =$producto->marca->toArray();
                $i++;
            }
            //dd ($response);
            return $productos;
        }catch(\Exception $e){
            return $e->getMessage();
        }
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.productos');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            $producto = new Producto();
            $producto->nombre_producto = $request->nombre_producto;
            $producto->cantidad_producto = $request->cantidad_producto;
            $producto->codigo = $request->codigo;
            $producto->precio = $request->precio;
            $producto->imagen = $request->imagen;
            $producto->marca_id =$request->marca['id'];
            if($producto->save()>= 1){
                return response()->json(['status'=> 'ok', 'data' => $producto],201);
            } else {
                return response()->json(['status'=> 'fail', 'data' => null],409);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try{
            $producto = Producto::findOrFail($id);
            $response = $producto->toArray();
            $response["marca"] = $producto->marca->toArray();
            return $response;  
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
            $producto = Producto::findOrFile($id);
            $producto->nombre_producto = $request->nombre_producto;
            $producto->cantidad_producto = $request->cantidad_producto;
            $producto->codigo = $request->codigo;
            $producto->precio = $request->precio;
            $producto->imagen = $request->imagen;
            $producto->marca_id =$request->marca['id'];
            if($producto->save()>= 1){
                return response()->json(['status'=> 'ok', 'data' => $producto],202);
            }else{
                return response()->json(['status'=>'fail','data'=>null],409);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            $producto = Producto::findOrFail($id);
            if ($producto->delete()>= 1){
                return response()->json(['status'=> 'ok', 'data' => $producto],202);
            }
            
        }catch(\Exception $e){
            return $e->getMessage();
    }
}
    }