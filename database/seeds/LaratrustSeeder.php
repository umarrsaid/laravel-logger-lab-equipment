<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Role;
use App\User;
use Illuminate\Support\Str;
class LaratrustSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return  void
     */
    public function run()
    {
        $role_su = new Role();
        $role_su->name         = 'superuser';
        $role_su->display_name = 'Super User'; // optional
        $role_su->description  = 'Super User'; // optional
        $role_su->save();

        $user_su = new User();
        $user_su->name     = 'SuperUser';
        $user_su->password = bcrypt('Superuser@Bpbl2019');
        $user_su->email = 'superuser@bpbl.com';
        $user_su->api_token    = Str::random(60);
        $user_su->save();
        $user_su->attachRole($role_su);


        $role_lab = new Role();
        $role_lab->name         = 'Lab';
        $role_lab->display_name = 'Lab'; // optional
        $role_lab->description  = 'Lab User'; // optional
        $role_lab->save();

        $user_lab = new User();
        $user_lab->name     = 'Lab';
        $user_lab->password = bcrypt('Lab@Bpbl2019');
        $user_lab->email    = 'lab@bpbl.com';
        $user_lab->api_token    = Str::random(60);
        $user_lab->save();
        $user_lab->attachRole($role_lab);

    }

    /**
     * Truncates all the laratrust tables and the users table
     *
     * @return    void
     */
    public function truncateLaratrustTables()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('permission_role')->truncate();
        DB::table('permission_user')->truncate();
        DB::table('role_user')->truncate();
        \App\User::truncate();
        \App\Role::truncate();
        \App\Permission::truncate();
        Schema::enableForeignKeyConstraints();
    }
}
