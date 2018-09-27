<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{   
    public function index()
    {
        return response()->json(\App\User::all()->toArray(),200);
    }   
    
    public function store(Request $request)
    {
        $user = \App\User::create($request->all());
        return response()->json($user, 201);
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
            return response()->json( ['message'  => 'Not Found'], 404);
        }
    }

    // public static function getByDescription($description)
    // {
    //     $category = Category::where('description', $description)->first();
    //     if($category){
    //         return $category;
    //     }else{
    //         return Category::create(['description' => $description]);
    //     }
    // }

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
        $user->update($request->all());
        return response()->json($user, 201);
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
        return response()->json(['message' => 'Dados removicos com sucesso!'], 200);

    }
}
