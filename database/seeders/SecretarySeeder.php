<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class SecretarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $user = User::create([
            'first_name'=>'secretary',
            'number' => '0000000000',


        ]);
                Role::create(['name' => 'secretary']);

                $user->assignRole('secretary'); 
    }
}
