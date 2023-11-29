<?php

namespace App\Http\Livewire;

use App\Models\Category;
use App\Models\Product;
use App\Models\Provider;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class ProductsComponent extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $name, $barcode, $cost, $price, $stock, $alerts, $category_id, $provider_id, $search, $image, $selected_id, $pageTitle, $componentName;
    private $pagination = 5;

    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }

    public function mount()
    {
        $this->pageTitle = 'Listado';
        $this->componentName = 'Productos';
        $this->category_id = 'Seleccione';
    }
    public function render()
    {
        if (strlen($this->search) > 0) {
            $products = Product::join('categories as c', 'c.id', 'products.category_id')
                ->join('providers as p', 'p.id', 'products.provider_id') // JOIN con la tabla de proveedores
                ->select('products.*', 'c.name as category', 'p.name as provider') // Selecciona el nombre del proveedor
                ->where('products.name', 'like', '%' . $this->search . '%')
                ->orWhere('products.barcode', 'like', '%' . $this->search . '%')
                ->orWhere('c.name', 'like', '%' . $this->search . '%')
                ->orWhere('p.name', 'like', '%' . $this->search . '%') // Filtra también por el nombre del proveedor
                ->orderBy('products.name', 'asc')
                ->paginate($this->pagination);
        } else {
            $products = Product::join('categories as c', 'c.id', 'products.category_id')
                ->join('providers as p', 'p.id', 'products.provider_id') // JOIN con la tabla de proveedores
                ->select('products.*', 'c.name as category', 'p.name as provider') // Selecciona el nombre del proveedor
                ->orderBy('products.name', 'asc')
                ->paginate($this->pagination);
        }

        return view('livewire.products.component', [
            'data' => $products,
            'categories' => Category::orderBy('name', 'asc')->get(),
            'providers' => Provider::orderBy('name', 'asc')->get()
        ])
            ->extends('layouts.theme.app')
            ->section('content');
    }


    public function Store()
    {
        $rules = [
            'name'        => 'required|unique:products|min:3',
            'cost'        => 'required',
            'price'       => 'required',
            'stock'       => 'required',
            'alerts'      => 'required',
            'category_id' => 'required|not_in:Seleccione',
            'provider_id' => 'required|not_in:Seleccione',
        ];
        $messages = [
            'name.required' => 'Nombre del producto es requerido',
            'name.unique' => 'Ya existe el nombre del producto',
            'name.min' => 'El nombre del producto debe tener al menos 3 caracteres.',
            'cost.required' => 'Costo es requerido',
            'price.required' => 'Precio es requerido',
            'stock.required' => 'Stock es requerido',
            'alerts.required' => 'Ingresa el valor mínimo de existencias',
            'category_id.not_in' => 'Elige un nombre de categoría',
            'provider_id.not_in' => 'Elige un Proveedor',
        ];

        $this->validate($rules, $messages);

        $product = Product::create([
            'name'        => $this->name,
            'cost'        => $this->cost,
            'price'       => $this->price,
            'barcode'     => $this->barcode,
            'stock'       => $this->stock,
            'alerts'      => $this->alerts,
            'category_id' => $this->category_id,
            'provider_id' => $this->category_id
        ]);

        if ($this->image) {
            $customFileName = uniqid() . '_.' . $this->image->extension();
            $this->image->storeAs('/public/products', $customFileName);
            $product->image = $customFileName;
            $product->save();
        }
        $this->resetUI();
        $this->emit('product-added', 'Producto Registrado');
    }

    public function Edit(Product $product)
    {
        $this->selected_id = $product->id;
        $this->name = $product->name;
        $this->barcode = $product->barcode;
        $this->cost = $product->cost;
        $this->price = $product->price;
        $this->stock = $product->stock;
        $this->alerts = $product->alerts;
        $this->category_id = $product->category_id;
        $this->provider_id = $product->provider_id;
        $this->image = null;

        $this->emit('show-modal', 'Show modal');
    }

    public function Update()
    {
        $rules = [
            'name'        => "required|min:3|unique:products,name,{$this->selected_id}",
            'cost'        => 'required',
            'price'       => 'required',
            'stock'       => 'required',
            'alerts'      => 'required',
            'category_id' => 'required|not_in:Seleccione',
            'provider_id' => 'required|not_in:Seleccione',
        ];
        $messages = [
            'name.required' => 'Nombre del producto es requerido',
            'name.unique' => 'Ya existe el nombre del producto',
            'name.min' => 'El nombre del producto debe tener al menos 3 caracteres.',
            'cost.required' => 'Costo es requerido',
            'price.required' => 'Precio es requerido',
            'stock.required' => 'Stock es requerido',
            'alerts.required' => 'Ingresa el valor mínimo de existencias',
            'category_id.not_in' => 'Elige un nombre de categoría',
            'provider_id.not_in' => 'Elige un Proveedor',
        ];

        $this->validate($rules, $messages);

        $product = Product::find($this->selected_id);

        $product->update([
            'name'        => $this->name,
            'cost'        => $this->cost,
            'price'       => $this->price,
            'barcode'     => $this->barcode,
            'stock'       => $this->stock,
            'alerts'      => $this->alerts,
            'category_id' => $this->category_id,
            'provider_id' => $this->provider_id
        ]);

        if ($this->image) {
            $customFileName = uniqid() . '_.' . $this->image->extension();
            $this->image->storeAs('/public/products', $customFileName);
            $imageTemp = $product->image; //imagen temporal
            $product->image = $customFileName;
            $product->save();

            if ($imageTemp != null) {
                if (file_exists('storage/products/' . $imageTemp)) {
                    unlink('storage/products/' . $imageTemp);
                }
            }
        }
        $this->resetUI();
        $this->emit('product-updated', 'Producto Actualizado');
    }

    public function resetUI()
    {
        $this->name = '';
        $this->barcode = '';
        $this->cost = '';
        $this->price = '';
        $this->stock = '';
        $this->alerts = '';
        $this->category_id = 'Seleccione';
        $this->provider_id = 'Seleccione';
        $this->image = null;
        $this->search = '';
        $this->selected_id = 0;
    }

    protected $listeners = ['deleteRow' => 'Destroy'];

    public function Destroy(Product $product)
    {
        $imageTemp = $product->image;
        $product->delete();

        if ($imageTemp != null) {
            if (file_exists('storage/products/' . $imageTemp)) {
                unlink('storage/products/' . $imageTemp);
            }
        }

        $this->resetUI();
        $this->emit('product-deleted', 'Producto Eliminado');
    }
}
