<?php 

namespace App\Transformers;

use League\Fractal;
use App\Models\Location;

class LocationTransformer extends Fractal\TransformerAbstract
{
	public function transform(Location $location)
	{
		return [
			'zipcode_ini'  => $location->cep_inicial, 
			'zipcode_end'  => $location->cep_final, 
			'state' 	   => $location->uf,
			'city' 		   => empty($location->cidade_nome) ? $location->localidade : $location->cidade_nome,
			'neighborhood' => $location->bairro_nome 
		];
	}
}