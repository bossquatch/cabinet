<?php

namespace Database\Factories;

use App\Models\DiskLog;
use App\Models\Disk;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DiskLogFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DiskLog::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $types = collect(config('logging.disk.types'));
        return [
            'disk_id' => Disk::all()->random()->id,
            'user_id' => User::all()->random()->id,
            'file' => $this->faker->company() . '.' . $this->faker->fileExtension(),
            'type' => ($types->random())['name'],
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ];
    }
}
