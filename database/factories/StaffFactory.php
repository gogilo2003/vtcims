<?php

namespace Database\Factories;

use App\Models\Staff;
use Illuminate\Database\Eloquent\Factories\Factory;

class StaffFactory extends Factory
{
    use Util;
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Staff::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        return [
            "idno" => $this->faker->randomNumber(8),
            "pfno" => $this->faker->numerify($this->faker->numberBetween(1999, 2021) . '######'),
            "manno" => $this->faker->randomNumber(6),
            "surname" => $this->faker->lastName,
            "first_name" => $this->faker->firstName,
            "middle_name" => $this->faker->lastName,
            "box_no" => $this->faker->randomNumber(4),
            "post_code" => $this->faker->randomNumber(5),
            "town" => $this->faker->city,
            "email" => $this->faker->safeEmail(),
            "phone" => $this->faker->e164PhoneNumber,
            "employer" => $this->faker->shuffle(config('eschool.employer'))[0],
            "gender" => $this->faker->shuffle(['Male', 'Female'])[0],
            "staff_role_id" => 1,
            'photo' => $this->placeholder(public_path(config('admin.path_prefix') . 'images/staff/'))
        ];
    }
}
