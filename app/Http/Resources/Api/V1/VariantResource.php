<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class VariantResource extends JsonResource
{
    public function toArray($request): array
    {
        return parent::toArray($request);

        return [
            'id'        =>  $this->id,
            'type'      =>  'variant',
            'attributes'=>  [
                'key'   => $this->key,
                'name'  => $this->name,
                'description'=> $this->description,
                'price' => [
                    'cost'   => $this->cost,
                    'retail' => $this->retail, // product.attributes.price.retail
                ],
                'active'  => $this->active,
                'vat'  => $this->vat,
            ],

            'relationships' =>  [
                'category'    =>  new CategoryResource(
                    resource: $this->whenLoaded(
                        relationship: 'category',
                        value: $this->category
                    )
                ),
                'range'    =>  new RangeResource(
                    resource: $this->whenLoaded(
                        relationship: 'range',
                        value: $this->range
                    )
                ),
                // 'variants'    =>  VariantResource::collection(
                //     resource: $this->whenLoaded(
                //         relationship: 'variants',
                //         value: $this->variants
                //     )
                // )
            ],
            'links' =>  [
                '_parent' => route('api:v1:products:index'),
                '_self' => route('api:v1:products:show', $this->key)
            ]
        ];
    }
}
