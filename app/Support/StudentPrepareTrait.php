<?php
namespace App\Support;

use Illuminate\Support\Carbon;

trait StudentPrepareTrait
{
    public function prepareForValidation(): void
    {
        $this->merge(['phone' => Util::formatPhoneNumber($this->phone)]);
        $this->merge(['guardian_phone' => Util::formatPhoneNumber($this->guardian_phone)]);
        $this->merge(['date_of_admission' => Carbon::parse($this->date_of_admission)]);
        $this->merge(['date_of_birth' => Carbon::parse($this->date_of_birth)]);
    }
}
