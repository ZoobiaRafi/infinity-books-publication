<?php

namespace App\Voyager\FormFields;

use TCG\Voyager\FormFields\AbstractHandler;

/**
 * A Voyager BREAD field type that lets the admin pick from a curated tray
 * of icons instead of hand-pasting SVG markup. Selecting one fills the same
 * underlying textarea the model expects, so nothing downstream (the Service
 * model, the frontend views) needs to know this field exists — it still
 * just receives a plain <svg>...</svg> string.
 */
class IconPickerHandler extends AbstractHandler
{
    protected $codename = 'icon_picker';

    public function createContent($row, $dataType, $dataTypeContent, $options)
    {
        return view('admin.formfields.icon-picker', [
            'row' => $row,
            'options' => $options,
            'dataType' => $dataType,
            'dataTypeContent' => $dataTypeContent,
            'icons' => $this->icons(),
        ]);
    }

    /**
     * Curated set of icons — every one of these is already used and
     * rendering correctly somewhere on the live site, so nothing in the
     * tray risks showing a broken/malformed icon.
     */
    private function icons(): array
    {
        return [
            'Ghostwriting' => '<path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"></path>',
            'Editing' => '<path d="M21 5H3"></path><path d="M17 12H3"></path><path d="M21 19H3"></path><path d="m17 8 4 4-4 4"></path>',
            'Cover Design' => '<circle cx="13.5" cy="6.5" r=".5"></circle><circle cx="17.5" cy="10.5" r=".5"></circle><circle cx="8.5" cy="7.5" r=".5"></circle><circle cx="6.5" cy="12.5" r=".5"></circle><path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10c.926 0 1.648-.746 1.648-1.688 0-.437-.18-.835-.437-1.125-.29-.289-.438-.652-.438-1.125a1.64 1.64 0 0 1 1.668-1.668h1.996c3.051 0 5.555-2.503 5.555-5.554C21.965 6.012 17.461 2 12 2z"></path>',
            'Globe' => '<circle cx="12" cy="12" r="10"></circle><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"></path><path d="M2 12h20"></path>',
            'Megaphone' => '<path d="m3 11 18-5v12L3 14v-3z"></path><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"></path>',
            'Audio Waveform' => '<path d="M2 10v4"></path><path d="M6 6v12"></path><path d="M10 3v18"></path><path d="M14 8v9"></path><path d="M18 5v14"></path><path d="M22 10v4"></path>',
            'Open Book' => '<path d="M12 7v14"></path><path d="M3 18a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h5a4 4 0 0 1 4 4 4 4 0 0 1 4-4h5a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1h-6a3 3 0 0 0-3 3 3 3 0 0 0-3-3z"></path>',
            'Phone' => '<path d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.804 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384"></path>',
            'Mail' => '<rect x="2" y="4" width="20" height="16" rx="2"></rect><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>',
            'Clock' => '<circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline>',
            'Shield' => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>',
            'Team' => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path>',
            'Award' => '<path d="M8.21 13.89 7 23l5-3 5 3-1.21-9.12"></path><circle cx="12" cy="8" r="6"></circle>',
            'Arrow Right' => '<path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path>',
            'Check' => '<polyline points="20 6 9 17 4 12"></polyline>',
            'Star' => '<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>',
            'Target' => '<circle cx="12" cy="12" r="10"></circle><circle cx="12" cy="12" r="6"></circle><circle cx="12" cy="12" r="2"></circle>',
        ];
    }
}
