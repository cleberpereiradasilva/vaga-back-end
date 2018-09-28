<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Validator;

class DependenteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(\App\Dependente::with('cliente')->get()->toArray(),200);
    }   

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(),[ 
            'email' => 'required|unique:dependentes,email',
            'nome' => 'required',
            'celular' => 'required',
            'cliente_id' => 'required'                    
        ]);
    
        if($validation->fails()){
            $errors = $validation->errors();
            return response()->json($errors, 400);
        } else{
            $dados = $request->all();        
            $user = Auth::user();
            $dados['user_id'] = $user->id;
            $dependente = \App\Dependente::create($dados);
            return response()->json($dependente, 201);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $dependente = \App\Dependente::where('id', $id)->with('cliente')->first();
        if($dependente){
            return response()->json( $dependente, 200);
        }else{
            return response()->json( ['message'  => 'Dependente não encontrado'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $dependente = \App\Dependente::find($id);
        if(!$dependente){
            return response()->json( ['message'  => 'Dependente não encontrado'], 404);
        }

        $validation = Validator::make($request->all(),[ 
            'email' => 'required|unique:dependentes,email',
            'nome' => 'required',
            'celular' => 'required',
            'cliente_id' => 'required'                    
        ]);
    
        if($validation->fails()){
            $errors = $validation->errors();
            return response()->json($errors, 400);
        } else{
            $user = Auth::user();
            if($user->id != $dependente->user_id){
                return response()->json( ['message'  => 'Dependente não pertence ao usuário'], 401);
            }
            $dependente->update($request->all());
            return response()->json($dependente, 201);
        }


        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $dependente = \App\Dependente::find($id);
        if(!$dependente){
            return response()->json( ['message'  => 'Dependente não encontrado'], 404);
        }
        
        $user = Auth::user();
        if($user->id != $dependente->user_id){
            return response()->json( ['message'  => 'Dependente não pertence ao usuário'], 401);
        }
        
        $dependente->delete();
        return response()->json(['message' => 'Dados removidos com sucesso!'], 200);
    }
}
 