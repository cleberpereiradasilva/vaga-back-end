<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Validator;

class ClienteController extends Controller
{    

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(\App\Cliente::paginate(15)->toArray(),200);
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
            'email' => 'required|unique:clientes,email',
            'nome' => 'required',
            'telefone' => 'required'            
        ]);
    
        if($validation->fails()){
            $errors = $validation->errors();
            return response()->json($errors, 400);
        } else{
            $dados = $request->all();        
            $user = Auth::user();
            $dados['user_id'] = $user->id;
            $cliente = \App\Cliente::create($dados);
            return response()->json($cliente, 201);
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
        $cliente = \App\Cliente::where('id',$id)
            ->with('dependentes')
            ->with('user')
            ->first();
        
        if($cliente){
            return response()->json( $cliente, 200);
        }else{
            return response()->json( ['message'  => 'Cliente não encontrado'], 404);
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
        $cliente = \App\Cliente::find($id);
        if(!$cliente){
            return response()->json( ['message'  => 'Cliente não encontrado'], 404);
        }

        $validation = Validator::make($request->all(),[ 
            'email' => 'required|unique:clientes,email',
            'nome' => 'required',
            'telefone' => 'required'            
        ]);
    
        if($validation->fails()){
            $errors = $validation->errors();
            return response()->json($errors, 400);
        } else{
            $user = Auth::user();
            if($user->id != $cliente->user_id){
                return response()->json( ['message'  => 'Cliente não pertence ao usuário'], 401);
            }
            $cliente->update($request->all());
            return response()->json($cliente, 201);
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
        $cliente = \App\Cliente::find($id);
        if(!$cliente){
            return response()->json( ['message'  => 'Cliente não encontrado'], 404);
        }

        $user = Auth::user();
        if($user->id != $cliente->user_id){
            return response()->json( ['message'  => 'Cliente não pertence ao usuário'], 401);
        }
        
        $cliente->delete();
        return response()->json(['message' => 'Dados removidos com sucesso!'], 200);
    }
}
