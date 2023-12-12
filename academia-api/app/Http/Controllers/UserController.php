<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();

        return response()->json($users, 200);
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
            'name' => 'required',
            'email'=> 'required',
            'password' => 'required'
        ]);

        $user = User::create([
            'name' => $request->post('name'),
            'email' => $request->post('email'),
            'password' => Hash::make($request->post('password'))
        ]);

        return response()->json(
            [
                'tipo' => 'info',
                'conteudo' => "User Criado."
            ],
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(
                [
                    'tipo' => 'erro',
                    'conteudo' => "Usuario não encontrado."
                ],
                404
            );
        }
        
        return response()->json($user, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(
                [
                    'tipo' => 'erro',
                    'conteudo' => "Usuario não encontrado."
                ],
                404
            );
        }
        
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return response()->json(
            [
                'tipo' => 'info',
                'conteudo' => "Usuario editado."
            ],
            200
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(
                [
                    'tipo' => 'erro',
                    'conteudo' => "Usuário não encontrado."
                ],
                404
            );
        }
        $user->delete();

        return response()->json(
            [
                'tipo' => 'info',
                'conteudo' => "Usuário deletado"
            ],
            200
        );
    }
}