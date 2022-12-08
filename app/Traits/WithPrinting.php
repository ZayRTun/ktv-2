<?php

namespace App\Traits;

trait WithPrinting
{
    public function printToPDF($view, $data, $date, $name, $orientation = 'L', $format = 'A4', $marginX = 5, $marginY = 5)
    {
        $printOutTime = now()->format('Y_m_d_Hi');
        $pdf = \PDF::loadView($view, compact('data', 'date'), [], [
            'format' => $format,
            'margin_left' => $marginX,
            'margin_right' => $marginX,
            'margin_top' => $marginY,
            'margin_bottom' => $marginY,
            'orientation' => $orientation,
            'use_kwt' => true
        ]);

        $name = "{$printOutTime}_$name.pdf";
        $pdf->save(storage_path("app/$name"));

        return route('download', $name);
    }

    public function getPrintSize()
    {
        return [80, 100];
        // if (app('Store')->is_slip_printer) {
        //     return [80, 100];
        // } else {
        //     return 'A5';
        // }
    }
}
