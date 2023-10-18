<?php

namespace App\Http\Controllers;

use App\Models\Treino;
use Illuminate\Http\Request;

class TreinoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $treinos = Treino::all();
        
        return response()->json($treinos, 200);
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
        $request->validate([
            'chest_day' => 'required|string',
            'leg_day' => 'required|string',
            'back_day' => 'required|string',
            'aluno_id' => 'required|string'
        ]);

        Treino::create([
            'chest_day' => $request->post('chest_day'),
            'leg_day' => $request->post('leg_day'),
            'back_day' => $request->post('back_day'),
            'aluno_id' => $request->post('aluno_id')
        ]);

        return response()->setStatusCode(200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $treino = Treino::find($id);
        
        return response()->json($treino, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Treino $treino)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $treino = Treino::find($id);
        $treino->update([
            'chest_day' => $request->chest_day,
            'leg_day' => $request->leg_day,
            'back_day' => $request->back_day,
            'aluno_id' => $request->aluno_id
        ]);

        return response()->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $treino = Treino::find($id);
        $treino->delete();

        return response()->setStatusCode(200);
    }
}
