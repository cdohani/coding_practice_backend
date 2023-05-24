<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles=[
            ["name"=>"admin","guard_name"=>"web"],
            ["name"=>"warden","guard_name"=>"web"],
            ["name"=>"student","guard_name"=>"students"],
        ];

        foreach($roles as $role)
        {
            $save_role=new Role();
            $save_role->name=$role["name"];
            $save_role->guard_name=$role["guard_name"];
            $save_role->save();
        }
    }
}
