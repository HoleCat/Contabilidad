<?php

namespace App\Http\Controllers;

use App\Mayorventa;
use Illuminate\Http\Request;

class MayorventaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Mayorventa  $mayorventa
     * @return \Illuminate\Http\Response
     */
    public function show(Mayorventa $mayorventa)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Mayorventa  $mayorventa
     * @return \Illuminate\Http\Response
     */
    public function edit(Mayorventa $mayorventa)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Mayorventa  $mayorventa
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Mayorventa $mayorventa)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Mayorventa  $mayorventa
     * @return \Illuminate\Http\Response
     */
    public function destroy(Mayorventa $mayorventa)
    {
        //
    }

    public function import(Request $request)
    {
        $this->validate($request, [
            'myfile' => 'required|mimes:xls,xlsx'
        ]);
        Excel::import(new MayorventasImport, $request->file('myfile'));

        $data = DB::table('Mayorventas')->get();

        return response()->json($data,200);
    }
}
