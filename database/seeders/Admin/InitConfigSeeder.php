<?php

namespace Database\Seeders\Admin;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InitConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        /** @var \Illuminate\Database\Eloquent\Model $modelUser */
        $modelUser = app(config('admin.database.users_model'));
        /** @var \Illuminate\Database\Eloquent\Model $modelMenu */
        $modelMenu = app(config('admin.database.menu_model'));
        /** @var \Illuminate\Database\Eloquent\Model $modelRole */
        $modelRole = app(config('admin.database.roles_model'));
        /** @var \Illuminate\Database\Eloquent\Model $modelPermission */
        $modelPermission = app(config('admin.database.permissions_model'));

        # 初始化一整批
        $modelUser->where('is_superuser', '=', false)->update(['is_superuser' => true]);
        $modelMenu->where('is_superuser', '=', false)
            ->where('id', '>=', 2)
            ->update(['is_superuser' => true]);
        $modelRole->where('is_superuser', '=', false)->update(['is_superuser' => true]);
        $modelPermission->where('is_superuser', '=', false)->update(['is_superuser' => true]);

        # 增加新選單

        $numCount = $modelMenu->count();
        $rootMenu = $modelMenu->create([
            'title' => '管理者',
            'icon'  => 'fa-user',
            'order' => $numCount++
        ]);

        $modelMenu->create([
            'title'     => '用戶',
            'parent_id' => $rootMenu->id,
            'icon'      => 'fa-user',
            'uri'       => 'employee/user',
            'order'     => $numCount++
        ]);

        $modelMenu->create([
            'title'     => '角色',
            'parent_id' => $rootMenu->id,
            'icon'      => 'fa-users',
            'uri'       => 'employee/role',
            'order'     => $numCount++
        ]);
    }
}
