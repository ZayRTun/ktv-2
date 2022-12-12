<?php

namespace App\Http\Livewire;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Str;
use Livewire\Component;

class LiveRoleEdit extends Component
{
    use AuthorizesRequests;

    public ?Role $role = null;
    public $selectedRolePermissions = [];
    public $selectGroup;
    public $showRoleEditForm = false;

    protected $listeners = ['editRole'];

    protected function rules()
    {
        return [
        'role.name' => 'required|string|unique:roles,name,' . $this->role->id,
        ];
    }

    public function updateRole()
    {
        $this->validate();

        $this->role->update();

        $this->role->syncPermissions($this->selectedRolePermissions);

        $this->emit('reloadRoles');
        $this->closeShowRoleEditForm();
    }

    public function updated($key, $value)
    {
        $explode = Str::of($key)->explode('.');
        if ($explode[0] === 'selectGroup' && $value) {
            $permissionIds = Permission::where('group_name', $value)->pluck('id')->map(fn ($id) => (string) $id)->toArray();
            $this->selectedRolePermissions = array_values(array_unique(array_merge_recursive($this->selectedRolePermissions, $permissionIds)));
        } elseif ($explode[0] === 'selectGroup' && $value === false) {
            $permissionIds = Permission::where('group_name', $explode[1])->pluck('id')->map(fn ($id) => (string) $id)->toArray();
            $this->selectedRolePermissions = array_merge(array_diff($this->selectedRolePermissions, $permissionIds));
        }
    }

    public function closeShowRoleEditForm()
    {
        $this->showRoleEditForm = false;
        $this->resetValidation();
        $this->reset();
    }

    public function updatedShowRoleEditForm()
    {
        $this->resetValidation();
        $this->reset();
    }

    public function editRole(Role $role)
    {
        $this->authorize('edit role');
        $this->role = $role;
        $this->selectedRolePermissions = $role->permissions->pluck('id')->toArray();
        $this->showRoleEditForm = true;
    }

    public function render()
    {
        $permissionGroup = null;


        if ($this->role) {
            $permissionGroup = Permission::all()->groupBy('group_name');
        }

        return view('livewire.live-role-edit', [
            'permissionGroups' => $permissionGroup
        ]);
    }
}
