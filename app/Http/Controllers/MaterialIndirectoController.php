<?php

namespace App\Http\Controllers;

use App\MaterialDirecto;
use Illuminate\Http\Request;

class MaterialIndirectoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        if ($buscar==''){
            $materiales = MaterialDirecto::orderBy('id', 'desc')->paginate(3);
        }
        else{
            $materiales = MaterialDirecto::where($criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'desc')->paginate(3);
        }

        return [
            'pagination' => [
                'total' => $materiales->total(),
                'current_page' => $materiales->currentPage(),
                'per_page' => $materiales->perPage(),
                'last_page' => $materiales->lastPage(),
                'from' => $materiales->firstItem(),
                'to' => $materiales->lastItem(),
            ],
            'materiales' => $materiales
        ];
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

    public function listarPdf()
    {

        $materiales = MaterialDirecto::where('condicion','=','1')->orderBy('id', 'desc')->get();

        $cont = MaterialDirecto::where('condicion','=','1')->count();
       /* $totallitros =0;
        $totalpago = 0;
        foreach ($entregas as $entrega){
            $totallitros =$totallitros+$entrega->total;
            $totalpago =$totalpago+$entrega->valor;
        }*/

        $pdf = \PDF::loadView('pdf.materialpdf', ['materiales' => $materiales, 'cont' => $cont]);
        return $pdf->download('materialIndirecto.pdf');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $materiales = new MaterialDirecto();
        $materiales->nombre = $request->nombre;
        $materiales->cantidad = $request->cantidad;
        $materiales->detalle = $request->detalle;
        $materiales->save();
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $material = MaterialDirecto::findOrFail($request->id);
        $material->condicion = '0';
        $material->save();
    }


    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $material = MaterialDirecto::findOrFail($request->id);
        $material->condicion = '1';
        $material->save();
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
    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $material = MaterialDirecto::findOrFail($request->id);
        $material->nombre = $request->nombre;
        $material->cantidad = $request->cantidad;
        $material->detalle = $request->detalle;
        $material->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
