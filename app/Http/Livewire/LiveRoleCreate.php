<?php

namespace App\Http\Livewire;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Support\Str;
use Livewire\Component;

class LiveRoleCreate extends Component
{
    public ?Role $role = null;
    public $selectedRolePermissions = [];
    public $selectGroup;
    public $showRoleCreateForm = false;

    protected $listeners = ['createRole'];

    protected $rules = [
        'role.name' => 'required|string|unique:roles,name',
    ];

    public function saveRole()
    {
        $this->validate();

        $this->role->save();

        if ($this->selectedRolePermissions) {
            $this->role->syncPermissions($this->selectedRolePermissions);
        }

        $this->emit('roleCreated');
        $this->closeShowRoleCreateForm();
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

    public function closeShowRoleCreateForm()
    {
        $this->showRoleCreateForm = false;
        $this->resetValidation();
        $this->reset();
    }

    public function updatedShowRoleCreateForm()
    {
        $this->resetValidation();
        $this->reset();
    }

    public function createRole()
    {
        $this->role = new Role();
        $this->showRoleCreateForm = true;
    }

    public function render()
    {
        $permissionGroup = null;

        if ($this->role) {
            $permissionGroup = Permission::all()->groupBy('group_name');
        }

        return view('livewire.live-role-create', [
            'permissionGroups' => $permissionGroup
        ]);
    }
}
