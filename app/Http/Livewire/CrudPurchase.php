<?php

namespace App\Http\Livewire;

use App\Models\Cash;
use App\Models\Denomination;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Livewire\Component;

class CrudPurchase extends Component
{
    public $total, $itemsQuantity, $denominations = [], $efectivo, $change, $igv, $subtotal, $saldo, $saldoFinal, $search;

    public function mount()
    {
        $this->efectivo = 0;
        $this->change = 0;
        $this->saldoFinal = 0;
        $this->updateValues();
    }

    public function render()
    {
        // dd(Cart::getContent()->sortBy('name'));
        $products = Product::where('name', 'like', '%' . $this->search . '%')->get();
        $this->denominations = Denomination::all();
        return view('livewire.purchase.component', [
            'denominations' => Denomination::orderBy('value', 'desc')->get(),
            'cart' => Cart::getContent()->sortBy('name')
        ], compact('products'))
            ->extends('layouts.theme.app')
            ->section('content');
    }

    public function ACash($value)
    {
        $this->efectivo += ($value == 0 ? $this->total : $value);
        $this->change = ($this->efectivo - $this->total);
        $this->saldo = $this->getSaldoFinal();
        $this->saldoFinal = $this->saldo - $this->total;
    }

    protected $listeners = [
        'scan-code' => 'ScanCode',
        'removeItem' => 'removeItem',
        'clearCart' => 'clearCart',
        'saveSale' => 'saveSale'
    ];


    public function ScanCode($productId, $cant = 1)
    {
        //dd($barcode); //** LLega el barcode OK!!
        $product = Product::find($productId);
        //dd($product); //Producto encontrado!
        if ($product == null || empty($product)) {
            $this->emit('scan-notfound', 'El producto no fue encontrado');
        } else {
            if ($this->InCart($product->id)) {
                $this->increaseQty($product->id);
                return;
            }

            if ($product->stock < 1) {
                $this->emit('no-stock', 'Stock insuficiente');
                return;
            }

            Cart::add($product->id, $product->name, $product->cost, $cant, $product->image);
            /*$carro = Cart::getContent();
            dd($carro);*/

            $this->updateValues();
            $this->emit('scan-ok', 'Producto agregado');
        }
    }

    public function updateValues()
    {
        $this->subtotal = Cart::getTotal();
        $this->itemsQuantity = Cart::getTotalQuantity();
        $this->igv = ($this->subtotal * 0.18);
        $this->total = $this->subtotal + $this->igv;
    }

    public function InCart($productId)
    {
        $exist = Cart::get($productId);
        if ($exist)
            return true;
        else
            return false;
    }

    public function increaseQty($productId, $cant = 1)
    {
        $title = '';
        $product = Product::find($productId);
        $exist = Cart::get($productId);
        if ($exist)
            $title = 'Cantidad actualizada';
        else
            $title = 'Producto agregada';

        if ($product->stock < ($cant + $exist->quantity)) {
            $this->emit('no-stock', 'Stock insuficiente');
            return;
        }

        Cart::add($product->id, $product->name, $product->cost, $cant, $product->image);
        $this->updateValues();
        $this->emit('scan-ok', $title);
    }

    public function updateQty($productId, $cant = 1)
    {
        $title = '';
        $product = Product::find($productId);
        $exist = Cart::get($productId);
        if ($exist)
            $title = 'Cantidad actualizada';
        else
            $title = 'Producto agregado';

        if ($exist) {
            if ($product->stock < $cant) {
                $this->emit('no-stock', 'Stock insuficiente :/');
                return;
            }
        }

        $this->removeItem($productId);
        if ($cant > 0) {
            Cart::add($product->id, $product->name, $product->cost, $cant, $product->image);
            $this->updateValues();
            $this->emit('scan-ok', $title);
        }
    }

    public function removeItem($productId)
    {
        Cart::remove($productId);
        $this->updateValues();
        $this->emit('scan-ok', 'Producto eliminado');
    }

    public function decreaseQty($productId)
    {
        $item = Cart::get($productId);
        Cart::remove($productId);
        $newQty = ($item->quantity) - 1;
        if ($newQty > 0)
            Cart::add($item->id, $item->name, $item->cost, $newQty);
        $this->updateValues();
        $this->emit('scan-ok', 'Cantidad actualizada');
    }

    public function clearCart()
    {
        Cart::clear();
        $this->efectivo = 0;
        $this->change = 0;
        $this->updateValues();
        $this->emit('scan-ok', 'Carro vacío');
    }

    public function getSaldoFinal()
    {
        $lastSaldo = Cash::latest('id')->first();

        if ($lastSaldo) {
            return $lastSaldo->total;
        } else {
            return 0;
        }
    }

    public function saveSale()
    {
        if ($this->total <= 0) {
            $this->emit('sale-error', 'AGREGA PRODUCTOS A LA VENTA');
            return;
        }
        if ($this->efectivo <= 0) {
            $this->emit('sale-error', 'INGRESA EL EFECTIVO');
            return;
        }
        if ($this->total > $this->efectivo) {
            $this->emit('sale-error', 'EL EFECTIVO DEBE SER MAYOR O IGUAL AL TOTAL');
            return;
        }
        DB::beginTransaction();
        try {
            $purchase = Purchase::create([
                'total' => $this->total,
                'subtotal' => $this->subtotal,
                'igv' => $this->igv,
                'items' => $this->itemsQuantity,
                'cash'  => $this->efectivo,
                'change'  => $this->change,
                'date' => now(),
                'user_id' => Auth()->user()->id
            ]);
            if ($purchase) {
                $items = Cart::getContent();
                foreach ($items as $item) {
                    PurchaseDetail::create([
                        'price'          => $item->price,
                        'quantity'       => $item->quantity,
                        'product_id'  => $item->id,
                        'purchase_id' => $purchase->id,
                    ]);

                    //update STOCK
                    $product = Product::find($item->id);
                    $product->stock = $product->stock + $item->quantity;
                    $product->save();
                }
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $this->emit('sale-error', $e->getMessage());
        }
        try {

            $movement = $this->total >= 0 ? '-' . $this->total : $this->total;

            Cash::create([
                'detail' => 'Compra',
                'date' => now(),
                'movement' => $movement,
                'type' => '-',
                'total' => $this->saldoFinal,

            ]);
            DB::commit();

            Cart::clear();
            $this->efectivo = 0;
            $this->change = 0;
            $this->updateValues();

            $this->emit('scan-ok', 'Compra registrada con éxito');
        } catch (Exception $e) {
            DB::rollBack();
            $this->emit('sale-error', $e->getMessage());
        }
    }

    public function printTicket($sale)
    {
        return Redirect::To("print://$sale->id");
    }

}
