<?php

return [
    'mode'                     => '',
    'format'                   => 'A4',
    'default_font_size'        => '12',
    'default_font'             => 'sans-serif',
    'margin_left'              => 3,
    'margin_right'             => 3,
    'margin_top'               => 3,
    'margin_bottom'            => 3,
    'margin_header'            => 10,
    'margin_footer'            => 5,
    'orientation'              => 'L',
    'title'                    => 'Laravel mPDF',
    'subject'                  => '',
    'author'                   => '',
    'watermark'                => '',
    'show_watermark'           => false,
    'show_watermark_image'     => false,
    'watermark_font'           => 'sans-serif',
    'display_mode'             => 'fullpage',
    'watermark_text_alpha'     => 0.1,
    'watermark_image_path'     => '',
    'watermark_image_alpha'    => 0.2,
    'watermark_image_size'     => 'D',
    'watermark_image_position' => 'P',

    // For some reason it works without the below config ----

    // 'custom_font_dir' => base_path('resources/fonts/'),
    // 'custom_font_data' => [
    //     'tharlon' => [
    //         'R' => 'Tharlon-Regular.ttf',
    //     ]
    // ],

    'auto_language_detection'  => false,
    'temp_dir'                 => rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR),
    'pdfa'                     => false,
    'pdfaauto'                 => false,
    'use_active_forms'         => false,
];
