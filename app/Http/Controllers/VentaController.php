<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Venta;
use App\Models\DetalleVenta;
class VentaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $ventas = Venta::all();
            $response = $ventas->toArray();
            $i=0;
            foreach($ventas as $venta){
                $response[$i]['user'] = $venta->user->toArray();
                $detalle = $venta->detalle_ventas->toArray();
                $f=0;
                foreach($venta->detalle_ventas as $d){
                    $detalle[$f]['producto'] = $d->producto->toArray();
                    $detalle[$f]['producto']['marca'] = $d->producto->toArray();
                    $f++;
                }
                $response[$i]['detalleVenta'] = $detalle;
                $i++;
            }
            return $response;
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            $errores = 0;
            DB::beginTransaction();
            //crea una instancia de ventas
            $venta = new Venta();
            $venta->correlativo = $this->getCorrelativo();
            $venta->fecha_venta = $request->fecha_venta;
            $venta->fecha_envio = $request->fecha_envio;
            $venta->medio_pago = $request->medio_pago;
            $venta->monto_venta = $request->monto_venta;
            $venta->producto_id = $request['user']['id'];
            if($venta->save()>= 0){
                $errores++;
            }
            //obtener el detalle de venta para luego insertar en detalleVenta
            $detalle = $request->detalleVenta;
            foreach ($detalle as $key => $det){
                //creando un objeto de tipo detalleVenta
                $detalleVenta = new DetalleVenta();
                $detalleVenta->cantidad_producto = $det['cantidad_producto'];
                $detalleVenta->producto_id = $det['producto']['id'];
                $detalleVenta->venta_id = $venta->id;
                if ($detalleVenta->save() <= 0) {
                    $errores++;
                }
            }
            if ($errores == 0) {
                DB::commit();
                return response()->json(['status'=> 'ok', 'data' => $venta],201);
            } else {
                DB::rollback();
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
            $venta = Venta::findOrFail($id);
            $response = $venta->toArray();
            $response['user'] = $venta->user->toArray();
            $detalle = $venta->detalle_venta->toArray(); 
            $i=0;
            foreach($venta->detalle_ventas as $d){
                $detalle[$i]['producto'] = $d->producto->toArray();
                $detalle[$i]['producto']['marca'] = $d->producto->marca->toArray();
                $i++;
            }
            $response[$i]['detalleVenta'] = $detalle;
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
            $errores = 0;
            DB::beginTransaction();
            //obteniendo la instancia del alquiler
            $venta = Venta::findOrfail($id);
            //evaluando el estado que viene por $request
            if($request->estado == 'A'){
                //cuando se entrega el o los vehiculos al cliente
                $venta->estado = $request->estado;
                $venta->deposito = $request->deposito;
                if($venta->update()<=0){
                    $errores++;
                }
                $detalle = $request->detalleVenta;
                foreach($detalle as $key => $det){
                    //creando un objeto de tipo DetalleAlquiler
                    $detalleVenta = DetalleVenta::findOrFail($det['id']);
                    $detalleVenta->Km_salida = $det['kmSalida'];
                    $detalleVenta->observacion = $det['observacion'];
                    if($detalleVenta->update()<=0){
                        $errores++;
                    }
                }
            }elseif($request->estado == 'D'){
                //cuando el cliente devuelve los vehiculos
                $venta->estado = $request->estado;
                $venta->fecha_devolucion = $request->fechaDevolucion;
                $venta->observaciones = $request->observaciones;
                if($venta->update()<=0){
                    $errores++;
                }
                $detalle = $request->detalleVenta;
                foreach($detalle as $key => $det){
                      //creando un objeto de tipo DetalleVenta
                    $detalleVenta = DetalleVenta::findOrFail($det['id']);
                    $detalleVenta->Km_entrada = $det['kmEntrada'];
                    $detalleVenta->fecha_devolucion = $det['fechaDevolucion'];
                    $detalleVenta->observacion  = $det['observacion'];
                    if($detalleVenta->update()<=0){
                        $errores++;
                    }
                }
              
            }else{
                //cuando la reserva sea cancelada
                //cambiar el estado a 'c' de cancelado el alquiler
                $venta->estado = $request->estado;
                $venta->observacion = $request->observacion;
                if($venta->update()<=0){
                    $errores++;
                }
                
            }
            if($errores == 0){
                DB::commit();
                return response()->json(['status'=>'ok','data'=>$venta],202);
            }else{
                DB::rollBack();
                return response()->json(['status'=>'ok','data'=>null],409);
            }
        }catch(\Exception $e){
            DB::rollBack();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
