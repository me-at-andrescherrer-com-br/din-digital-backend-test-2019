<?php

namespace App\Transformers;

use App\Product;
use Flugg\Responder\Transformers\Transformer;

class ProductTransformer extends Transformer
{
    /**
     * List of available relations.
     *
     * @var string[]
     */
    protected $relations = [];

    /**
     * List of autoloaded default relations.
     *
     * @var array
     */
    protected $load = [];

    /**
     * Transform the model.
     *
     * @param  \App\Product $product
     * @return array
     */
    public function transform(Product $product): array
    {
        return [
            'id' => (int) $product->id,
            'name' => (string) $product->name,
            'price (R$)' => (float) $product->price,
            'weight (kg)' => (float) $product->weight,
        ];
    }
}
