<?php

namespace App\Http\Controllers;

use App\Fabricante;
use Illuminate\Http\Request;

class FabricanteController extends Controller
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
    public function index()
    {
        //return Fabricante::all();
        return response()->json(["Datos"=>Fabricante::all()],202);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!$request->get('nombre') || !$request->get('telefono')) {
            return response()->json(["Mensaje"=>"Datos inconpletos","codigo"=>"422"],422);    
        }
        Fabricante::create($request->all());
        return response()->json(["Mensaje"=>"El fabricante ha sido creado exitosamente"],202);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Fabricante  $fabricante
     * @return \Illuminate\Http\Response
     */
    public function show($id)//Fabricante $fabricante
    {
        $fabricante=Fabricante::find($id);
        //dd($fabricante);
        if ( $fabricante == null) {
            return response()->json(["Mensaje"=>"Fabriacnte no encontrado"],404);
        }
        return response()->json(["Datos"=>$fabricante],202);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Fabricante  $fabricante
     * @return \Illuminate\Http\Response
     */
    public function edit(Fabricante $fabricante)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Fabricante  $fabricante
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $metodo=$request->method();
        $fabricante=Fabricante::find($id);
        $sw=false;
        if ($metodo==="PATCH") {//compara el valor y el tipo de metodo         
        //con el motodo PATCH stas obligado a cambiar todos los campos
            $nombre=$request->get('nombre');
            if ($nombre!=null && $nombre!='') {
                $fabricante->nombre=$nombre;
                 $sw=true;
            }
            $telefono=$request->get('telefono');
            if ($telefono!=null && $telefono!='') {
                $fabricante->telefono=$telefono;
                 $sw=true;
            }
            if ($sw) {
                $fabricante->save();
                return response()->json(["Mensaje"=>"El fabricante ha sido editado con PATCH"],202);
            }
            return response()->json(["Mensaje"=>"Datos nulos para PATCH"],200);
        }
           //con el metodo PUT se puede cambiar parcialmente los campos
           $nombre=$request->get('nombre');
           $telefono=$request->get('telefono');
           if (!$nombre || !$telefono) {
            return response()->json(["Mensaje"=>"Datos invalidos para"],404);
           }
           $fabricante->nombre=$nombre;
           $fabricante->telefono=$telefono;
           $fabricante->save();
           return response()->json(["Mensaje"=>"El fabricante ha sido editado con PUT"],202);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Fabricante  $fabricante
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $fabricante=Fabricante::find($id);
        if (!$fabricante) {
            return response()->json(["Mensaje"=>"El fabricante no existe", "error"=>404],404);
        }
        $vehiculos=$fabricante->vehiculos;
        if (sizeof($vehiculos)>0) {
            return response()->json(["Mensaje"=>"El fabricante posee vehiculos y no se puede eliminar",
            "elimine los vehiculo primero", "error"=>404],404);
        }
        $fabricante->delete();
        return response()->json(["Mensaje"=>"El fabricante ha sido eliminado"],200);
    }
}
