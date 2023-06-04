<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\StoreCategoriaRequest;
use App\Http\Resources\CategoriaResource;
use App\Models\Categoria;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use \Illuminate\Validation\ValidationException;
use \Illuminate\Support\Facades\Validator;


class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categorias = Categoria::all();

        return response() -> json([
            'status' => 200,
            'mensagem' => 'Lista de categorias retornada',
            'categorias' => CategoriaResource::collection($categorias)
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCategoriaRequest $request)
    {
        // Cria o objeto 
        $categoria = new Categoria();

        // Transfere os valores
        $categoria->nomedacategoria = $request->nome_da_categoria;

        // Salva
        $categoria->save();

        // Retorna o resultado
        return response() -> json([
            'status' => 200,
            'mensagem' => 'Categoria criada',
            'categoria' => new CategoriaResource($categoria)
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Categoria  $categoria
     * @return \Illuminate\Http\Response
     */
    public function show($categoriaid)
    {
        try {
            /*
            * Validação da entrada para ter certeza que o valor é numerico
            */
            $validator =  Validator::make(['id' => $categoriaid],
            [
                'id' => 'integer'
            ]);
            //Caso não seja válido, levantar exceção
            if($validator->fails()) {
                throw ValidationException::withMessages(['id' => 'O campo Id deve ser numérico']);
            }

            /*
            * Continua o fluxo para a execução
            */
            $categoria = Categoria::findorFail($categoriaid);

            return response() -> json([
                'status' => 200,
                'mensagem' => 'Categoria retornada',
                'categoria' => new CategoriaResource($categoria)
            ]);
        } catch(\Exception $ex) {
            /*
            * Tratamento das exceções levantadas
            */
            $class = get_class($ex); // Pega a classe da exceção 
            switch($class) {
                case ModelNotFoundException::class: //Caso não exista o id na base
                    return response() -> json([
                        'status' => 404,
                        'mensagem' => 'Categoria não encontrada',
                        'categoria' => []
                    ], 404);
                    break;
                case \Illuminate\Validation\ValidationException::class: //Caso seja erro de validação
                    return response() -> json([
                        'status' => 406,
                        'mensagem' =>  $ex->getMessage(),
                        'categoria' => []
                    ], 406);
                    break;
                default: // Algum erro interno ocorreu 
                    return response() -> json([
                        'status' => 500,
                        'mensagem' => 'Erro Interno',
                        'categoria' => []
                    ], 500);
                    break;
            }
        }
        
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Categoria  $categoria
     * @return \Illuminate\Http\Response
     */
    public function update(StoreCategoriaRequest $request, $categoriaid)
    {
        try {
            /*
            * Validação da entrada para ter certeza que o valor é numerico
            */
            $validator =  Validator::make(['id' => $categoriaid],
            [
                'id' => 'integer'
            ]);
            //Caso não seja válido, levantar exceção
            if($validator->fails()) {
                throw ValidationException::withMessages(['id' => 'O campo Id deve ser numérico']);
            }

            /*
            * Continua o fluxo para a execução
            */
            $categoria = Categoria::findorFail($categoriaid);
            $categoria->nomedacategoria = $request->nome_da_categoria;
            $categoria->update();
    
            return response() -> json([
                'status' => 200,
                'mensagem' => 'Categoria atualizada'
            ], 200);
        } catch(\Exception $ex) {
            /*
            * Tratamento das exceções levantadas
            */
            $class = get_class($ex); // Pega a classe da exceção
            switch($class) {
                case ModelNotFoundException::class: //Caso não exista o id na base
                    return response() -> json([
                        'status' => 404,
                        'mensagem' => 'Categoria não encontrada',
                        'categoria' => []
                    ], 404);
                    break;
                case \Illuminate\Validation\ValidationException::class: //Caso seja erro de validação
                    return response() -> json([
                        'status' => 406,
                        'mensagem' => $ex->getMessage(),
                        'categoria' => []
                    ], 406);
                    break;
                default: // Algum erro interno ocorreu 
                    return response() -> json([
                        'status' => 500,
                        'mensagem' => 'Erro Interno',
                        'categoria' => []
                    ], 500);
                    break;
            }
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Categoria  $categoria
     * @return \Illuminate\Http\Response
     */
    public function destroy($categoriaid)
    {

        try {
            /*
            * Validação da entrada para ter certeza que o valor é numerico
            */
            $validator =  Validator::make(['id' => $categoriaid],
            [
                'id' => 'integer'
            ]);
            //Caso não seja válido, levantar exceção
            if($validator->fails()) {
                throw ValidationException::withMessages(['id' => 'O campo Id deve ser numérico']);
            }

            /*
            * Continua o fluxo para a execução
            */
            $categoria = Categoria::findorFail($categoriaid);

            // Apagar a categoria
            $categoria->delete();

            // Retorna a mensagem final
            return response() -> json([
                'status' => 200,
                'mensagem' => 'Categoria apagada'
            ], 200);
        } catch(\Exception $ex) {
            /*
            * Tratamento das exceções levantadas
            */
            $class = get_class($ex); // Pega a classe da exceção 
            switch($class) {
                case ModelNotFoundException::class: //Caso não exista o id na base
                    return response() -> json([
                        'status' => 404,
                        'mensagem' => 'Categoria não encontrada',
                        'categoria' => []
                    ], 404);
                    break;
                case \Illuminate\Validation\ValidationException::class: //Caso seja erro de validação
                    return response() -> json([
                        'status' => 406,
                        'mensagem' => $ex->getMessage(),
                        'categoria' => []
                    ], 406);
                    break;
                default: // Algum erro interno ocorreu 
                    return response() -> json([
                        'status' => 500,
                        'mensagem' => 'Erro Interno',
                        'categoria' => []
                    ], 500);
                    break;
            }
        }
    }
}
