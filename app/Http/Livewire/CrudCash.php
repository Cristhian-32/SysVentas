<?php

namespace App\Http\Livewire;

use App\Models\Cash;
use Livewire\Component;
use Livewire\WithPagination;

class CrudCash extends Component
{
    use WithPagination;

    public $permissionName, $search, $selected_id, $pageTitle, $componentName, $caja;
    private $pagination = 10;

    public function mount()
    {
        $this->pageTitle = 'Listado';
        $modelo = Cash::latest('id')->first();
        $this->caja = $modelo ? $modelo->total : '0.00';
        $this->componentName = 'Historial de Movimientos';

    }

    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }

    public function render()
    {
        if(strlen($this->search) > 0)
            $cashes = Cash::where('detail', 'like', '%' . $this->search . '%')->paginate($this->pagination);
        else
            $cashes = Cash::orderBy('date', 'asc')->paginate($this->pagination);

        return view('livewire.cash.crud-cash', [
            'cashes' => $cashes
        ])
        ->extends('layouts.theme.app')
        ->section('content');
    }

    public function CreatePermission()
    {
        $rules = ['permissionName' => 'required|min:2|unique:permissions,name'];

        $messages = [
            'permissionName.required' => 'Elnombre del cash es requerido',
            'permissionName.min'      => 'El nombre del cash debe tener al menos 2 carateres',
            'permissionName.unique'   => 'El cash ya existe'
        ];

        $this->validate($rules, $messages);

        Cash::create([
            'name' => $this->permissionName
        ]);

        $this->emit('cash-added', 'Se registró el cash con exito');
        $this->resetUI();
    }

    public function Edit(Cash $cash)
    {
        //$cash = Cash::find($id);
        $this->selected_id = $cash->id;
        $this->permissionName = $cash->name;

        $this->emit('show-modal', 'Show modal');
    }

    public function UpdatePermission()
    {
        $rules = ['permissionName' => "required|min:2|unique:permissions,name, {$this->selected_id}"];

        $messages = [
            'permissionName.required' => 'Elnombre del cash es requerido',
            'permissionName.min'      => 'El nombre debe tener al menos 3 carateres',
            'permissionName.unique'   => 'El cash ya existe'
        ];
        $this->validate($rules, $messages);

        $cash = Cash::find($this->selected_id);
        $cash->name = $this->permissionName;
        $cash->save();

        $this->emit('cash-updated', 'Se actualizó el cash con éxito');
        $this->resetUI();
    }

    protected $listeners = [
        'destroy' => 'Destroy'
    ];

    public function Destroy($id)
    {
        //dd($id);
        $rolesCount = Cash::find($id)->getRoleNames()->count();
        if ($rolesCount > 0)
        {
            $this->emit('permission-error', 'No se puede eliminar el cash, porque tiene roles asociados.');
            return;
        }
        Cash::find($id)->delete();
        $this->emit('cash-deleted', 'Se eliminó el cash con éxito');
    }

    public function resetUI()
    {
        $this->permissionName = '';
        $this->search = '';
        $this->selected_id = 0;
        $this->resetValidation();
    }
}
