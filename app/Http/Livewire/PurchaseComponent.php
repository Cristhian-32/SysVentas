<?php

namespace App\Http\Livewire;

use App\Models\Purchase;
use App\Models\User;
use Livewire\Component;

class PurchaseComponent extends Component
{
    public $search, $pageTitle, $componentName;
    private $pagination = 5;

    public function mount()
    {
        $this->pageTitle = 'Listado';
        $this->componentName = 'Historial de Compras';
    }
    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }

    public function render()
    {
        if (strlen($this->search) > 0) {
            $purchases = Purchase::join('users as c', 'c.id', 'purchases.user_id')
                ->join('purchase_details as sd', 'sd.purchase_id', 'purchases.id')
                ->join('products as p', 'p.id', 'sd.product_id')
                ->select('purchases.*', 'c.name as user', 'p.name as product_name', 'sd.quantity as product_quantity')
                ->where('purchases.id', 'like', '%' . $this->search . '%')
                ->orWhere('c.name', 'like', '%' . $this->search . '%')
                ->orWhere('p.name', 'like', '%' . $this->search . '%')
                ->orderBy('purchases.id', 'asc')
                ->paginate($this->pagination);
        } else {
            $purchases = Purchase::join('users as c', 'c.id', 'purchases.user_id')
                ->join('purchase_details as sd', 'sd.purchase_id', 'purchases.id')
                ->join('products as p', 'p.id', 'sd.product_id')
                ->select('purchases.*', 'c.name as user', 'p.name as product_name', 'sd.quantity as product_quantity')
                ->orderBy('purchases.id', 'asc')
                ->paginate($this->pagination);
        }

        return view('livewire.records.purchase-component', [
            'data' => $purchases,
            'users' => User::orderBy('name', 'asc')->get(),
        ])
            ->extends('layouts.theme.app')
            ->section('content');
    }
}
