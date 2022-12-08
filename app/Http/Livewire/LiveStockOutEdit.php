<?php

namespace App\Http\Livewire;

use App\Models\Item;
use App\Models\StockOut;
use App\Models\StockOutType;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class LiveStockOutEdit extends Component
{
    use AuthorizesRequests;

    public $stockout_id;
    public $stock_out_date;
    public $item_id;
    public $original_item_id;
    public $qty;
    public $price;
    public $stock_out_type_id;
    public $remark;
    public $updated_user_id;

    public $original_stock_out_qty;

    public $stockOutTypes = [];
    public $items;
    public $itemSearch = '';

    public $showStockoutEditForm = false;

    protected $listeners = ['editStockout'];

    protected $rules = [
        'stock_out_date' => 'required|date',
        'item_id' => 'required|integer',
        'qty' => 'required|numeric',
        'price' => 'required|numeric|between:0,999999999.99',
        'stock_out_type_id' => 'required|integer',
        'remark' => 'nullable|string',
        'updated_user_id' => 'required|integer',
    ];

    public function update()
    {
        $validated = $this->validate();
        DB::transaction(function () use ($validated) {
            $stockout = StockOut::find($this->stockout_id);

            $stockout->update($validated);

            if ($this->original_item_id == $validated['item_id']) {
                if ($validated['qty'] > $this->original_stock_out_qty) {
                    $decrementingQty = $validated['qty'] - $this->original_stock_out_qty;
                    Item::find($validated['item_id'])->decrement('current_qty', $decrementingQty);
                } elseif ($validated['qty'] < $this->original_stock_out_qty) {
                    $incrementingQty = $this->original_stock_out_qty - $validated['qty'];
                    Item::find($validated['item_id'])->increment('current_qty', $incrementingQty);
                }
            } else {
                Item::find($this->original_item_id)->increment('current_qty', $this->original_stock_out_qty);
                Item::find($validated['item_id'])->decrement('current_qty', $validated['qty']);
            }

            $this->emit('stockoutUpdated');
            $this->showStockoutEditForm = false;
        });
    }

    public function editStockout(StockOut $stockOut)
    {
        $this->authorize('update', $stockOut);

        $this->resetValidation();
        $this->reset();

        $this->fill($stockOut);
        $this->original_item_id = $stockOut->item_id;
        $this->itemSearch = $stockOut->item?->item_name;
        $this->stockout_id = $stockOut->id;
        $this->original_stock_out_qty = $this->qty;
        $this->updated_user_id = auth()->id();

        $this->stockOutTypes = StockOutType::all('id', 'stock_out_type_name');



        $this->showStockoutEditForm = true;
    }

    public function setFirstResult()
    {
        $item = $this->items->first();
        $this->itemSearch = $item->item_name;
        $this->item_id = $item->id;
    }

    public function setItem($id, $name)
    {
        $this->itemSearch = $name;
        $this->item_id = $id;
    }

    public function render()
    {
        if ($this->showStockoutEditForm) {
            $this->items = Item::select('id', 'item_name')
            ->when(strlen($this->itemSearch) > 2 ? $this->itemSearch : false, function ($query) {
                $query->where('item_name', 'like', '%' . $this->itemSearch . '%');
            })
            ->orderBy('item_name')
            ->get();
        }

        return view('livewire.live-stock-out-edit');
    }
}
