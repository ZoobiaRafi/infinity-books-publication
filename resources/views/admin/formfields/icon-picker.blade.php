@php
    $currentValue = old($row->field, $dataTypeContent->{$row->field} ?? $options->default ?? '');
    $fieldId = 'icon-picker-'.$row->field;
@endphp

<div class="icon-picker" id="{{ $fieldId }}">
    <div class="icon-picker-current">
        <span class="icon-picker-preview" data-role="preview">{!! $currentValue !!}</span>
        <span class="icon-picker-hint">Click an icon below to use it, or edit the SVG markup directly.</span>
    </div>

    <div class="icon-picker-grid">
        @foreach ($icons as $label => $innerSvg)
            @php
                $fullSvg = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">'.$innerSvg.'</svg>';
            @endphp
            <button type="button" class="icon-picker-option" title="{{ $label }}" data-svg="{{ $fullSvg }}">
                {!! $fullSvg !!}
            </button>
        @endforeach
    </div>

    <textarea @if($row->required == 1) required @endif class="form-control icon-picker-textarea" name="{{ $row->field }}" rows="3" data-role="textarea">{{ $currentValue }}</textarea>
</div>

<style>
    .icon-picker-current { display: flex; align-items: center; gap: .75rem; margin-bottom: .75rem; }
    .icon-picker-preview { display: flex; align-items: center; justify-content: center; width: 44px; height: 44px; flex-shrink: 0; border: 1px solid #e2e2e2; border-radius: 6px; background: #fff; color: #333; }
    .icon-picker-preview svg { width: 24px; height: 24px; }
    .icon-picker-hint { font-size: .85rem; color: #888; }
    .icon-picker-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(52px, 1fr)); gap: .5rem; max-width: 560px; margin-bottom: .75rem; }
    .icon-picker-option { display: flex; align-items: center; justify-content: center; width: 52px; height: 52px; border: 1px solid #e2e2e2; border-radius: 6px; background: #fff; color: #555; cursor: pointer; transition: border-color .15s ease, color .15s ease, background-color .15s ease; padding: 0; }
    .icon-picker-option svg { width: 22px; height: 22px; pointer-events: none; }
    .icon-picker-option:hover { border-color: #22a7f0; color: #22a7f0; }
    .icon-picker-option.is-selected { border-color: #22a7f0; background: #eaf7ff; color: #22a7f0; }
    .icon-picker-textarea { font-family: monospace; font-size: .8rem; }
</style>

<script>
    (function () {
        var wrap = document.getElementById(@json($fieldId));
        if (!wrap || wrap.dataset.bound) return;
        wrap.dataset.bound = '1';

        var preview = wrap.querySelector('[data-role="preview"]');
        var textarea = wrap.querySelector('[data-role="textarea"]');

        function markSelected(svg) {
            wrap.querySelectorAll('.icon-picker-option').forEach(function (btn) {
                btn.classList.toggle('is-selected', btn.dataset.svg === svg);
            });
        }

        wrap.querySelectorAll('.icon-picker-option').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var svg = btn.dataset.svg;
                textarea.value = svg;
                preview.innerHTML = svg;
                markSelected(svg);
            });
        });

        textarea.addEventListener('input', function () {
            preview.innerHTML = textarea.value;
            markSelected(textarea.value.trim());
        });

        markSelected(textarea.value.trim());
    })();
</script>
