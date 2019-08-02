<?php

namespace App\Http\Controllers;
use App\Vehiculo;
use App\Fabricante;

use Illuminate\Http\Request;

class FabricanteVehiculoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.basic',['only'=>['store','update','destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $fabricante=Fabricante::find($id);
        $vehiculos=$fabricante->vehiculos;
       // dd($vehiculos);
        if ( $fabricante == null) {
            return response()->json(["Mensaje"=>"Fabriacante no encontrado"],404);
        }
        return response()->json(["Datos"=>$fabricante],202);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        if (!$request->get('color') || !$request->get('cilindraje') || !$request->get('peso') || !$request->get('potencia')) {
        return response()->json(["Mensaje"=>"Datos imconpletos","codigo"=>"422"],422);    
    }

    $fabricante=Fabricante::find($id);        
    if (!$fabricante) {
        return response()->json(["Mensaje"=>"Fabricante no exite","codigo"=>"404"],404);   
    }

    Vehiculo::create([
        'color'=>$request->get('color'),
        'potencia'=>$request->get('potencia'),
        'cilindraje'=>$request->get('cilindraje'),
        'peso'=>$request->get('peso'),
        'fabricante_id'=>$id
    ]);
    return response()->json(["Mensaje"=>"Vehiculo creado"],201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $idFabricante, $idVehiculo)
    {
        $metodo=$request->method();
        $fabricante=Fabricante::find($idFabricante);
        if (!$fabricante) {
            return response()->json(["Mensaje"=>"El fabricante no existe", "error"=>404],404);
        }
        $vehiculo=$fabricante->vehiculos()->find($idVehiculo);
        if (!$vehiculo) {
            return response()->json(["Mensaje"=>"No se encuentra el vehiculo", "error"=>404],404);
        }
        $cilindraje=$request->get('cilindraje');
        $color=$request->get('color');
        $potencia=$request->get('potencia');
        $peso=$request->get('peso');
        $sw=false;
        if ($metodo==="PATCH") {//compara el valor y el tipo de metodo 
            if ($color!=null && $color!='') {
                $vehiculo->color=$color;
                $sw=true;
            }
            if ($peso!=null && $peso!='') {
                $vehiculo->peso=$peso;
                $sw=true;
            }
            if ($cilindraje!=null && $cilindraje!='') {
                $vehiculo->cilindraje=$cilindraje;
                $sw=true;
            }
            if ($potencia!=null && $potencia!='') {
                $vehiculo->potencia=$potencia;
                $sw=true;
            }
            if ($sw) {
                $vehiculo->save();
                return response()->json(["Mensaje"=>"El vehiculo ha sido editado con PATCH"],202);
            }
            return response()->json(["Mensaje"=>"No se han guardado los cambios con PATCH","codigo"=>200],200);
        }
           
           if (!$color || !$peso || !$cilindraje || !$potencia) {
            return response()->json(["Mensaje"=>"Datos invalidos para PUT"],404);
           }
            $vehiculo->color=$color;
            $vehiculo->peso=$peso;
            $vehiculo->cilindraje=$cilindraje;
            $vehiculo->potencia=$potencia;
           $vehiculo->save();
           return response()->json(["Mensaje"=>"El vehiculo ha sido editado con PUT"],202);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($idFabricante,$idVehiculo) //importa el orden de los IDs
                                                       // por la URL fabricantes/{fabricante}/vehiculos/{vehiculo} 
    {
        $fabricante=Fabricante::find($idFabricante);
        if (!$fabricante) {
            return response()->json(["Mensaje"=>"El fabricante no existe", "error"=>404],404);
        }
        $vehiculo=$fabricante->vehiculos()->find($idVehiculo);
        if (!$vehiculo) {
            return response()->json(["Mensaje"=>"No se encuentra el vehiculo", "error"=>404],404);
        }
        $vehiculo->delete();
        return response()->json(["Mensaje"=>"El vehiculo ha sido eliminado"],200);
    }
}
