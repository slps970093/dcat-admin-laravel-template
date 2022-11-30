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

        try {
            \DB::beginTransaction();
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

            # 增加新權限
            $numCount = $modelPermission->count();

            $rootPermission = $modelPermission->create([
                'name'  => '後台管理',
                'slug'  => '後台管理',
                'order' => $numCount++
            ]);

            $modelPermission->create([
                'name'      => '用戶管理',
                'slug'      => '用戶管理',
                'order'     => $numCount++,
                'http_path' => '/employee/user*',
                'parent_id' => $rootPermission->id
            ]);

            $modelPermission->create([
                'name'      => '角色管理',
                'slug'      => '角色管理',
                'order'     => $numCount++,
                'http_path' => '/employee/role*',
                'parent_id' => $rootPermission->id
            ]);

            # 初始化最高管理者角色權限
            $adminRole = $modelRole->where('slug', '=', 'administrator')->first();
            $adminMenu = $modelMenu->where('is_superuser', '=', true)->get();
            foreach ($adminMenu as $item) {
                \DB::table(config('admin.database.role_menu_table'))->insert([
                    'role_id' => $adminRole->id,
                    'menu_id' => $item->id
                ]);
            }
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            echo "失敗 錯誤訊息 {$e->getMessage()}";
        }
    }
}
