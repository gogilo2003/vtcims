<?php

namespace Database\Factories;

use App\Models\Student;
use DateInterval;
use DateTime;
use DateTimeZone;
use Illuminate\Database\Eloquent\Factories\Factory;
use Ogilo\Eschool\Models\Intake;

class StudentFactory extends Factory
{
    use Util;
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Student::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $intake = Intake::find(rand(1, Intake::all()->count()));
        $intake_date_start = new DateTime($intake->start_date, new DateTimeZone('Africa/Nairobi'));
        $interval = \date_interval_create_from_date_string('1 month');
        $intake_date_end = clone $intake_date_start;
        $intake_date_end->add($interval);

        return [
            "surname" => $this->faker->lastName,
            "first_name" => $this->faker->firstName,
            "middle_name" => $this->faker->lastName,
            "phone" => $this->faker->e164PhoneNumber,
            "email" => $this->faker->safeEmail,
            "box_no" => $this->faker->randomNumber(),
            "post_code" => $this->faker->randomNumber(),
            "town" => $this->faker->city,
            "physical_address" => $this->faker->address,
            "date_of_birth" => date_sub(date_create(), \date_interval_create_from_date_string(rand(168, 360) . ' days')),
            "birth_cert_no" => $this->faker->numerify("P########"),
            "idno" => $this->faker->randomNumber(8),
            "gender" => $this->faker->shuffle([1, 0])[0],
            // "date_of_admission" => $this->faker->dateTimeBetween("-1 month", $intake_date_end->format('Y-m-d'), 'Africa/Nairobi'),
            "date_of_admission" => date_add(date_create($intake->start_date), \date_interval_create_from_date_string(rand(1, 28) . ' days')),
            "intake_id" => $intake->id,
            "program_id" => 1,
            "sponsor_id" => 1,
            "student_role_id" => 1,
            "status" => $this->faker->shuffle(['In session', 'On Attachment', 'Completed', 'Dropout'])[0],
            'photo' => $this->placeholder(public_path(config('admin.path_prefix') . 'images/students/'))
        ];
    }
}
