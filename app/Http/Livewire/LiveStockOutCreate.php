<?php

namespace App\Http\Livewire;

use App\Models\Item;
use App\Models\StockOut;
use App\Models\StockOutType;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class LiveStockOutCreate extends Component
{
    use AuthorizesRequests;

    public $stock_out_date;
    public $item_id;
    public $qty;
    public $price;
    public $stock_out_type_id;
    public $remark;
    public $created_user_id;

    public $stockOutTypes = [];
    public $items;
    public $itemSearch = '';

    public $showStockoutCreateForm = false;

    protected $listeners = ['createStockout'];

    protected $rules = [
        'stock_out_date' => 'required|date',
        'item_id' => 'required|integer',
        'qty' => 'required|numeric',
        'price' => 'required|numeric|between:0,999999999.99',
        'stock_out_type_id' => 'required|integer',
        'remark' => 'nullable|string',
        'created_user_id' => 'required|integer',
    ];

    public function create()
    {
        $validated = $this->validate();

        DB::transaction(function () use ($validated) {
            StockOut::create($validated);
            Item::find($validated['item_id'])->decrement('current_qty', $validated['qty']);

            $this->emit('stockoutCreated');
            $this->showStockoutCreateForm = false;
        });
    }

    public function createStockout()
    {
        $this->authorize('create', StockOut::class);

        $this->resetValidation();
        $this->reset();

        $this->stockOutTypes = StockOutType::all('id', 'stock_out_type_name');
        $this->stock_out_date = today()->toDateString();
        $this->created_user_id = auth()->id();
        $this->showStockoutCreateForm = true;
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
        if ($this->showStockoutCreateForm) {
            $this->items = Item::select('id', 'item_name')
            ->when(strlen($this->itemSearch) > 2 ? $this->itemSearch : false, function ($query) {
                $query->where('item_name', 'like', '%' . $this->itemSearch . '%');
            })
            ->orderBy('item_name')
            ->get();
        }

        return view('livewire.live-stock-out-create');
    }
}
