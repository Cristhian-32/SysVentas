<?php

namespace App\Http\Livewire;

use App\Models\Sale;
use App\Models\User;
use Livewire\Component;

class SaleComponent extends Component
{
    public $search, $pageTitle, $componentName;
    private $pagination = 5;

    public function mount()
    {
        $this->pageTitle = 'Listado';
        $this->componentName = 'Historial de Ventas';
    }
    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }

    public function render()
    {
        if (strlen($this->search) > 0) {
            $sales = Sale::join('users as c', 'c.id', 'sales.user_id')
                ->join('sale_details as sd', 'sd.sale_id', 'sales.id')
                ->join('products as p', 'p.id', 'sd.product_id')
                ->select('sales.*', 'c.name as user', 'p.name as product_name', 'sd.quantity as product_quantity')
                ->where('sales.id', 'like', '%' . $this->search . '%')
                ->orWhere('c.name', 'like', '%' . $this->search . '%')
                ->orWhere('p.name', 'like', '%' . $this->search . '%')
                ->orderBy('sales.id', 'asc')
                ->paginate($this->pagination);
        } else {
            $sales = Sale::join('users as c', 'c.id', 'sales.user_id')
                ->join('sale_details as sd', 'sd.sale_id', 'sales.id')
                ->join('products as p', 'p.id', 'sd.product_id')
                ->select('sales.*', 'c.name as user', 'p.name as product_name', 'sd.quantity as product_quantity')
                ->orderBy('sales.id', 'asc')
                ->paginate($this->pagination);
        }

        return view('livewire.records.sale-component', [
            'data' => $sales,
            'users' => User::orderBy('name', 'asc')->get(),
        ])
            ->extends('layouts.theme.app')
            ->section('content');
    }
}
