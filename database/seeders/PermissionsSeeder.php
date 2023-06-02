<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles=[
            ["name"=>"create_outpast","guard_name"=>"students"],
            ["name"=>"view_outpast","guard_name"=>"web"],
            ["name"=>"update_outpast","guard_name"=>"web"],
            ["name"=>"view_outpast","guard_name"=>"students"],
        ];

        foreach($roles as $role)
        {
            $save_role=new Permission();
            $save_role->name=$role["name"];
            $save_role->guard_name=$role["guard_name"];
            $save_role->save();
        }

        //assigning Permissions admin
        $admin=Role::where("name","admin")->first();
        $admin->givePermissionTo(['update_outpast','view_outpast']);

         //assigning Permissions wardan
         $admin=Role::where("name","warden")->first();
         $admin->givePermissionTo('view_outpast');

         //assigning Permissions student
         $admin=Role::where("name","student")->first();
         $admin->givePermissionTo(['create_outpast','view_outpast']);
    }
}
