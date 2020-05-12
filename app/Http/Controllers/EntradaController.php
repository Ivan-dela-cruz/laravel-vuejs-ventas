<?php

namespace App\Http\Controllers;

use App\Entrega;
use Illuminate\Http\Request;

class EntradaController extends Controller
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

        if ($buscar == '') {
            $entregas = Entrega::join('proveedores', 'entregas.id_provedor', '=', 'proveedores.id')
                ->select('entregas.id', 'entregas.id_provedor', 'entregas.cantidadM', 'entregas.cantidadT', 'proveedores.contacto as nombre_provedor', 'entregas.precio', 'entregas.total', 'entregas.valor','entregas.condicion')
                ->orderBy('entregas.id', 'desc')->paginate(3);
        } else {
            $entregas = Articulo::join('proveedores', 'entregas.id_provedor', '=', 'proveedores.id')
                ->select('entregas.id', 'entregas.id_provedor', 'entregas.cantidadM', 'entregas.cantidadT', 'proveedores.contacto as nombre_provedor', 'entregas.precio', 'entregas.total', 'entregas.valor','entregas.condicion')
                ->where('entregas.' . $criterio, 'like', '%' . $buscar . '%')
                ->orderBy('entregas.id', 'desc')->paginate(3);
        }


        return [
            'pagination' => [
                'total' => $entregas->total(),
                'current_page' => $entregas->currentPage(),
                'per_page' => $entregas->perPage(),
                'last_page' => $entregas->lastPage(),
                'from' => $entregas->firstItem(),
                'to' => $entregas->lastItem(),
            ],
            'entregas' => $entregas
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
        $entregas = Entrega::join('proveedores', 'entregas.id_provedor', '=', 'proveedores.id')
            ->select('entregas.id', 'entregas.id_provedor', 'entregas.cantidadM', 'entregas.cantidadT', 'proveedores.contacto as nombre_provedor', 'entregas.precio', 'entregas.total', 'entregas.valor','entregas.created_at as fecha')
            ->orderBy('entregas.id', 'desc')->get();

        $cont = Entrega::count();
        $totallitros =0;
        $totalpago = 0;
        foreach ($entregas as $entrega){
            $totallitros =$totallitros+$entrega->total;
            $totalpago =$totalpago+$entrega->valor;
        }

        $pdf = \PDF::loadView('pdf.entregaspdf', ['entregas' => $entregas, 'cont' => $cont,'totallitros'=>$totallitros,'totalpago'=>$totalpago]);
        return $pdf->download('entregas.pdf');
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
        $entrega = new Entrega();
        $entrega->id_provedor = $request->id_provedor;
        $entrega->cantidadM = $request->cantidadM;
        $entrega->cantidadT = $request->cantidadT;
        $entrega->total = $entrega->cantidadM+$entrega->cantidadT;
        $entrega->precio = $request->precio;
        $entrega->valor = $entrega->total*$entrega->precio;
        $entrega->save();
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $entrega = Entrega::findOrFail($request->id);
        $entrega->condicion = '0';
        $entrega->save();
    }


    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $entrega = Entrega::findOrFail($request->id);
        $entrega->condicion = '1';
        $entrega->save();
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
        $entrega = Entrega::findOrFail($request->id);
        $entrega->id_provedor = $request->id_provedor;
        $entrega->cantidadM = $request->cantidadM;
        $entrega->cantidadT = $request->cantidadT;
        $entrega->total = $request->total;
        $entrega->precio = $request->precio;
        $entrega->valor = $request->valor;
        $entrega->save();
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
