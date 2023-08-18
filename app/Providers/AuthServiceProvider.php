<?php

namespace App\Providers;

use App\Models\Group;
use App\Models\User;
use App\Models\Module;
use App\Policies\GroupPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        Group::class => GroupPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        $moduleList = Module::all();
        if ($moduleList->count() > 0) {
            $actionArr = [
                'View' => 'Xem',
                'Add' => 'Thêm',
                'Edit' => 'Sửa',
                'Delete' => 'Xóa',
            ];

            foreach ($actionArr as $key => $value) {
                foreach ($moduleList as $module) {
                    Gate::define(strtolower($module->name . '.' . $key), function (User $user) use ($key, $module) {
                        if ($user->group_id == 1) { //! isSuperadmin pass all define
                            return true;
                        }
                        if ($user->group->active == 1) { //! if group isn't active cannot access to any module
                            return false;
                        }
                        $roleJson = $user->group->permissions;
                        if (!empty($roleJson)) { // check if role can use
                            $roleArr = json_decode($roleJson, true);
                            $check = isRole($roleArr, $module->name, $key);

                            return $check;
                        }
                        return false;
                    });
                }
            }
            Gate::define('group.decentralize', function (User $user) {
                if ($user->group_id == 1) { //! isSuperadmin pass all define
                    return true;
                }
                if ($user->group->active == 1) { //! if group isn't active cannot access to any module
                    return false;
                }
                $roleJson = $user->group->permissions;
                if (!empty($roleJson)) { // check if role can use
                    $roleArr = json_decode($roleJson, true);
                    $check = isRole($roleArr, 'group', 'decentralize');

                    return $check;
                }
                return false;
            });
        }
    }
}
