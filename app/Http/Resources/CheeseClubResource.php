<?php

namespace App\Http\Resources;

use App\Models\CheeseClub;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\CheeseClub
 * */
class CheeseClubResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @param \Illuminate\Http\Request $request
	 */
	public function toArray($request): array
	{
		return [
			'id' => $this->id,
			'name' => $this->name,
			'surname' => $this->surname,
			'email' => $this->email,
			'club_type' => $this->club_type ? CheeseClub::getTypeName($this->club_type) : null,
			'date_of_birth' => $this->date_of_birth,
			'points' => $this->points,
			'card_number' => $this->card_number,
		];
	}
}
