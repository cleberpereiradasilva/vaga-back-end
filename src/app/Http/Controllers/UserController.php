<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

class UserController extends Controller
{   
    public function index()
    {
        return response()->json(\App\User::all()->toArray(),200);
    }   
    
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(),[ 
            'email' => 'required|unique:users,email',
            'name' => 'required',
            'password' => 'required|confirmed|min:6'            
        ]);
    
        if($validation->fails()){
            $errors = $validation->errors();
            return response()->json($errors, 400);
        } else{
            $user = \App\User::create($request->all());
            return response()->json($user, 201);
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
        $user = \App\User::find($id);
        if($user){
            return response()->json( $user, 200);
        }else{
            return response()->json( ['message'  => 'Usuário não encontrado'], 404);
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

        $user= \App\User::find($id);

        if(!$user){
            return response()->json( ['message'  => 'Usuário não encontrado'], 404);
        }
        
        $validar = [ 
            'email' => 'required|unique:users,email',
            'name' => 'required'
        ];


        if(array_key_exists('password', $request->all())){
            $validar['password'] = 'required|confirmed|min:6';
        }

        $validation = Validator::make($request->all(),$validar);
    
        if($validation->fails()){
            $errors = $validation->errors();
            return response()->json($errors, 400);
        } else{
            $user->update($request->all());
            return response()->json($user, 201);
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
        \App\User::find($id)->delete();
        return response()->json(['message' => 'Dados removidos com sucesso!'], 200);

    }
}
