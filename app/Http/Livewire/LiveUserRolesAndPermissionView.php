<?php

namespace App\Http\Livewire;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class LiveUserRolesAndPermissionView extends Component
{
    use AuthorizesRequests;
    public $roles;
    public $selectedRole;
    public $selectedRolePermissions = [];

    protected $listeners = ['reloadRoles'];

    public function reloadRoles()
    {
        $this->roles = Role::with('permissions')->get();
    }

    public function mount()
    {
        $this->authorize('view roles');
        $this->roles = Role::with('permissions')->get();
        $this->selectedRole = $this->roles->first()->id;
    }

    public function render()
    {
        $selectedRole = $this->roles->where('id', $this->selectedRole)->first();
        if ($selectedRole) {
            $this->selectedRolePermissions = $selectedRole->permissions->pluck('id');
        } else {
            $this->roles = Role::with('permissions')->get();
            $this->selectedRole = $this->roles->first()->id;
            $selectedRole = $this->roles->where('id', $this->selectedRole)->first();
            $this->selectedRolePermissions = $selectedRole->permissions->pluck('id');
        }

        return view('livewire.live-user-roles-and-permission-view', [
            'permissionGroups' => Permission::all()->groupBy('group_name')
        ]);
    }
}
