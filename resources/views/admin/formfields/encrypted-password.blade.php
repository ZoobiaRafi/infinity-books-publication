@if (isset($dataTypeContent->{$row->field}) && filled($dataTypeContent->{$row->field}))
  <br>
  <small>Leave blank to keep the current password.</small>
@endif
<input type="password"
       autocomplete="new-password"
       @if ($row->required == 1 && !isset($dataTypeContent->{$row->field})) required @endif
       class="form-control"
       name="{{ $row->field }}"
       value="">
