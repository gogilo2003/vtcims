<div class="card">
    <form id="examination_groupForm" method="post" action="{{ route('admin-eschool-examinations-groups-add-post') }}" role="form" accept-charset="UTF-8" enctype="multipart/form-data">
        <div class="card-body">
            <div class="form-group{!! $errors->has('term') ? ' has-error':'' !!}">
                <label for="term" class="bmd-label-static">Term</label>
                <select data-live-search="true" data-style="btn-link" class="form-control selectpicker" id="term" name="term">
                    @foreach (\Ogilo\Eschool\Models\Term::all() as $term)
                        <option value="{{ $term->id }}">{{ $term->year_name }}</option>
                    @endforeach
                </select>
                {!! $errors->has('term') ? '<span class="text-danger">'.$errors->first('term').'</span>' : '' !!}
            </div>
            <div class="form-group{!! $errors->has('intake') ? ' has-error':'' !!}">
                <label for="intake" class="bmd-label-statick">Intake</label>
                <select multiple data-live-search="true" class="form-control selectpicker" id="intake" name="intake[]" data-style="btn btn-link">
                    @foreach (\Ogilo\Eschool\Models\Intake::orderBy('name','ASC')->get() as $intake)
                        <option value="{{ $intake->id }}">{{ $intake->name }}</option>
                    @endforeach
                </select>
                {!! $errors->has('intake') ? '<span class="text-danger">'.$errors->first('intake').'</span>' : '' !!}
            </div>
            <br>
            <div class="form-group{!! $errors->has('title') ? ' has-error':'' !!}">
                <label class="bmd-label-static" for="title">Title</label>
                <input type="text" class="form-control" id="title" name="title" value="{!! ((old('title')) ? old('title') : '') !!}">
                {!! $errors->has('title') ? '<span class="text-danger">'.$errors->first('title').'</span>' : '' !!}
            </div>
            <div class="form-group{!! $errors->has('notes') ? ' has-error':'' !!}">
                <label class="bmd-label-static" for="name">Notes</label>
                {!! $errors->has('notes') ? '<span class="text-danger">'.$errors->first('notes').'</span>' : '' !!}
                <textarea class="form-control" id="notes" name="notes"> {!! ((old('notes')) ? old('notes') : '') !!}</textarea>
            </div>
            <div class="form-group{!! $errors->has('id') ? ' has-error':'' !!}">
                {!! $errors->has('id') ? '<span class="text-danger">'.$errors->first('id').'</span>' : '' !!}
            </div>

            <input type="hidden" name="_token" value="{{csrf_token()}}">
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary btn-round"><span class="material-icons">save</span> Save</button>
            <a style="display:none" href="JavaScript:" class="btn btn-danger btn-round" id="cancelExaminationButton"><span class="material-icons">cancel</span> Cancel</a>
        </div>
    </form>
</div>

@push('scripts_bottom') 
    <script type="text/javascript">
        $(document).ready(function(){
            // alert(1)
        })
    </script>
@endpush