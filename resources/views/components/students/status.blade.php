@foreach (['In session','On Attachment','Completed','Dropout'] as $status)
    <option value="{{ $status }}" {{ isset($selected) ? ($selected == $status ? 'selected="selected"' : '') : '' }}>{{ $status }}</option>
@endforeach
