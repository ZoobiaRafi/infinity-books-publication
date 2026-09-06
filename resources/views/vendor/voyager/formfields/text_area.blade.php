{{-- Override of vendor/tcg/voyager/resources/views/formfields/text_area.blade.php —
     stock Voyager's text_area field ignores `details.placeholder` entirely
     (unlike its `text` field, which does support it). This adds that. --}}
<textarea @if($row->required == 1) required @endif class="form-control" name="{{ $row->field }}" rows="{{ $options->display->rows ?? 5 }}" placeholder="{{ $options->placeholder ?? '' }}">{{ old($row->field, $dataTypeContent->{$row->field} ?? $options->default ?? '') }}</textarea>
