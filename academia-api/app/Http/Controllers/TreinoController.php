<?php

namespace App\Http\Controllers;

use App\Models\Treino;
use Illuminate\Http\Request;
use App\Models\Aluno;

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
            'aluno_id' => 'required|integer'
        ]);
       
        if (!Aluno::find($request->post('aluno_id'))) {
            return response()->json(
                [
                    'tipo' => 'erro',
                    'conteudo' => "Aluno não encontrado."
                ],
                500
            );
        }

        Treino::create([
            'chest_day' => $request->post('chest_day'),
            'leg_day' => $request->post('leg_day'),
            'back_day' => $request->post('back_day'),
            'aluno_id' => (int) $request->post('aluno_id') 
        ]);

        return response()->json(
            [
                'tipo' => 'info',
                'conteudo' => "Treino Criado."
            ],
            201
        );

    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $treino = Treino::find($id);
        if (!$treino) {
            return response()->json(
                [
                    'tipo' => 'erro',
                    'conteudo' => "Treino não encontrado."
                ],
                404
            );
        }
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

        if (!$treino) {
            return response()->json(
                [
                    'tipo' => 'erro',
                    'conteudo' => "Treino $id não encontrado."
                ],
                404
            );
        }
        
        if (!Aluno::find($request->aluno_id)) {
            return response()->json(
                [
                    'tipo' => 'erro',
                    'conteudo' => "Aluno não encontrado."
                ],
                500
            );
        }
    
        $treino->update([
            'chest_day' => $request->chest_day,
            'leg_day' => $request->leg_day,
            'back_day' => $request->back_day,
            'aluno_id' => $request->aluno_id
        ]);

        return response()->json(
            [
                'tipo' => 'info',
                'conteudo' => "Treino Editado."
            ],
            200
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $treino = Treino::find($id);
        if (!$treino) {
            return response()->json(
                [
                    'tipo' => 'erro',
                    'conteudo' => "Treino $id não encontrada para delete."
                ],
                404
            );
        }
        $treino->delete();

        return response()->json(
            [
                'tipo' => 'info',
                'conteudo' => "Treino deletado."
            ],
            200
        );    
    }
}
