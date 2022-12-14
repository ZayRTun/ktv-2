<?php

namespace App\Http\Livewire;

use App\Models\Inhouse;
use App\Models\ViewInformationInvoice;
use App\Traits\WithPrinting;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class LiveSalesDetailReport extends Component
{
    use AuthorizesRequests;
    use WithPrinting;

    public $infoInvoices;
    public $dateFrom;
    public $dateTo;
    public $selectedGroups = [1, 2, 3];

    public function print()
    {
        $data['date_from'] = $this->dateFrom;
        $data['date_to'] = $this->dateTo;
        $data['infoInvoices'] = $this->infoInvoices;
        return $this->printToPDF('pdf.sales-detail-pdf', $data, app('OperationDate'), "Sales-Detail", 'L');
    }

    public function mount()
    {
        $this->authorize('view reports');
        $this->dateFrom = today()->subDay()->toDateString();
        $this->dateTo = today()->toDateString();
    }

    public function render()
    {
        $this->infoInvoices = ViewInformationInvoice::with('inhouse')
        ->whereIn('group_no', $this->selectedGroups)
        ->whereHas('inhouse', function ($query) {
            $query->whereDate('operation_date', '>=', $this->dateFrom)
            ->whereDate('operation_date', '<=', $this->dateTo);
        })
        ->get();

        return view('livewire.live-sales-detail-report');
    }
}
