<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use Illuminate\Http\Request;

class AlunoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $alunos = Aluno::all();

        return response()->json($alunos, 200);
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
            'nome' => 'required|string',
            'idade' => 'required|integer',
            'peso' => 'required|float',
            'altura' => 'required|float',
        ]);

        Aluno::create([
            'nome' => $request->post('nome'),
            'idade' => $request->post('idade'),
            'peso' => $request->post('peso'),
            'altura' => $request('altura'),
            'pago' => true,
        ]);

        return response()->json(
            [
                'tipo' => 'info',
                'conteudo' => "Aluno Criado."
            ],
            201
        );

    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $aluno = Aluno::find($id);

        if (!$aluno) {
            return response()->json(
                [
                    'tipo' => 'erro',
                    'conteudo' => "Aluno não encontrado."
                ],
                404
            );
        }
        
        return response()->json($aluno);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Aluno $aluno)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $aluno = Aluno::find($id);

        if (!$aluno) {
            return response()->json(
                [
                    'tipo' => 'erro',
                    'conteudo' => "Aluno não encontrado."
                ],
                404
            );
        }
        

        $aluno->update([
            'nome' => $request->nome,
            'idade' => $request->idade,
            'peso' => $request->peso,
            'altura' => $request->altura,
            'pago' => $request->pago,
        ]);
        
        return response()->json(
            [
                'tipo' => 'info',
                'conteudo' => "Aluno editado."
            ],
            200
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $aluno = Aluno::find($id);

        if (!$aluno) {
            return response()->json(
                [
                    'tipo' => 'erro',
                    'conteudo' => "Aluno não encontrado.."
                ],
                404
            );
        }

        $aluno->delete();
        
        return response()->json(
            [
                'tipo' => 'info',
                'conteudo' => "Aluno excluído."
            ],
            200
        ); 
    }
}
