<?php

namespace App\Http\Livewire;

use App\Models\Role;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class LiveRoleDelete extends Component
{
    use AuthorizesRequests;

    public $role;
    public $showRoleDeleteModal = false;

    protected $listeners = ['deleteRole'];

    public function delete()
    {
        if ($this->role->permissions->count() > 0) {
            $this->showRoleDeleteModal = false;
            $this->dispatchBrowserEvent('failure-notify', ['title' => "Role Delete Unsuccessful", 'body' => "Cannot delete role cause other related data exist. Please delete related data first to delete this role."]);
        } else {
            $this->role->delete();
            $this->emit('reloadRoles');
            $this->showRoleDeleteModal = false;
        }
    }

    public function deleteRole(Role $role)
    {
        $this->authorize('delete role');

        $this->role = $role;
        $this->showRoleDeleteModal = true;
    }

    public function render()
    {
        return view('livewire.live-role-delete');
    }
}
