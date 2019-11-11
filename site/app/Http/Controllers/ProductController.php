<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Transformers\ProductTransformer;

class ProductController extends Controller
{        
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $product = Product::where('user_id',auth()->user()->id)->paginate(10);

        if (count($product) > 0)
            return responder()->success($product, ProductTransformer::class)->respond();

        return responder()->error('Sem produtos', 'Você não tem produtos')->respond(404);
    }    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request)
    {
        $product_req = Product::where('name',$request->name)->get();
        
        if (count($product_req) > 0)
            return responder()->error('Produto Encontrado', 'Um produto com este nome já existe na base de dados.')->respond(422);
        
        $validated = $request->validated();

        if ($validated) {
            $product = Product::create([
                'name'  => $request->name,
                'price'  => $request->price,
                'weight'  => $request->weight,
                'user_id'  => auth()->user()->id,
            ]);

            return responder()->success($product, ProductTransformer::class)->respond(201);
        }        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        if ($product)
            return responder()->success($product, ProductTransformer::class)->respond();    
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $user_id = auth()->user()->id;
                
        // Produto com nome igual a outro na base de dados, porém de outro usuário.
        $product_req = Product::where('name',$request->name)->where('user_id','!=',$user_id)->get();
        
        if (count($product_req) > 0){
            return responder()->error('Não autorizado', 'Um produto de outro usuário e com este nome já existe na base de dados.')->respond(422);
        }
        
        
        if ($product->user_id === $user_id){
            foreach($request->all() as $k => $v) {
                $product->$k = $v;
            }
            $product->update();

            return responder()->success($product, ProductTransformer::class)->respond(200);
        }        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $user_id = auth()->user()->id;
        $user_id = 3;

        if ($product->user_id === $user_id){
            
            $product->delete();
            return response()->json(['data' => ['message' => $product->name . ' excluído com sucesso']], 200);
        }

        return responder()->error('Não autorizado', 'Você não tem permissão para excluir esse produto')->respond(403);

    }
}
