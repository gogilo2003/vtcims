<!-- Attendance Preferences Dialog -->
<div class="modal fade" data-backdrop="static" id="attendanceDialog" tabindex="-1" role="dialog"
    aria-labelledby="StduentDetails">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 560px" role="document">
        <div class="modal-content">
            <div class="card">
                <form id="attendanceDetailsForm" method="post"
                    action="{{ route('admin-eschool-students-attendance') }}" role="form" accept-charset="UTF-8"
                    enctype="multipart/form-data">
                    <div class="card-header card-header-primary">
                        <h4>Select Class Attendance Details</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group bmd-form-group is-filled is-focused{!! $errors->has('class') ? ' has-error' : '' !!}">
                                    <label for="class" class="static-label-static">Select Class</label>
                                    <select multiple data-live-search="true" class="form-control selectpicker"
                                        name="class[]" id="class" data-style="btn btn-link">
                                        @foreach (\Ogilo\Eschool\Models\Intake::all() as $intake)
                                            <option value="{{ $intake->id }}">{{ $intake->name }}</option>
                                        @endforeach
                                    </select>
                                    {!! $errors->has('class') ? '<span class="text-danger">' . $errors->first('class') . '</span>' : '' !!}
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group bmd-form-group is-filled is-focused{!! $errors->has('subject') ? ' has-error' : '' !!}">
                                    <label for="subject" static="bmd-label-static">Select Subject</label>
                                    <select data-live-search="true" class="form-control selectpicker" name="subject"
                                        id="subject" data-style="btn btn-link"></select>
                                    {!! $errors->has('subject') ? '<span class="text-danger">' . $errors->first('subject') . '</span>' : '' !!}
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group{!! $errors->has('staff') ? ' has-error' : '' !!}">
                                    <label class="bmd-label-static" for="staff">Instractor</label>
                                    <select data-live-search="true" data-style="btn btn-link"
                                        class="form-control selectpicker" id="staff" name="staff">
                                        @foreach (\Ogilo\Eschool\Models\Staff::orderBy('surname')->orderBy('first_name')->orderBy('middle_name')->get()
    as $staff)
                                            <option value="{{ $staff->id }}">{{ $staff->min_name }}</option>
                                        @endforeach
                                    </select>
                                    {!! $errors->has('staff') ? '<span class="text-danger">' . $errors->first('staff') . '</span>' : '' !!}
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-check form-check-radio">
                                                    <label class="form-check-label">
                                                        <input class="form-check-input" type="radio" name="type"
                                                            id="weekly" value="weekly"
                                                            {{ old('type') == 'weekly' ? 'checked' : (old('type') != 'monthly' || old('type') != 'termly' ? 'checked' : '') }}>
                                                        Weekly
                                                        <span class="circle">
                                                            <span class="check"></span>
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-check form-check-radio">
                                                    <label class="form-check-label">
                                                        <input class="form-check-input" type="radio" name="type"
                                                            id="monthly" value="monthly"
                                                            {{ old('type') == 'monthly' ? 'checked' : '' }}>
                                                        Monthly
                                                        <span class="circle">
                                                            <span class="check"></span>
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-check form-check-radio">
                                                    <label class="form-check-label">
                                                        <input class="form-check-input" type="radio" name="type"
                                                            id="termly" value="termly"
                                                            {{ old('type') == 'termly' ? 'checked' : '' }}>
                                                        Termly
                                                        <span class="circle">
                                                            <span class="check"></span>
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div style="display: block" id="weekly-wrap"
                                    class="attendance-type form-group bmd-form-group{!! $errors->has('date') ? ' has-error' : '' !!}">
                                    <label class="bmd-label-static" for="date">Date</label>
                                    <input type="text" class="form-control datepicker" id="date" name="date"
                                        {!! old('date') ? ' value="' . old('date') . '"' : '' !!}>
                                    {!! $errors->has('date') ? '<span class="text-danger">' . $errors->first('date') . '</span>' : '' !!}
                                </div>
                                <div style="display: none" id="monthly-wrap"
                                    class="attendance-type form-group bmd-form-group" {!! $errors->has('month') ? ' has-error' : '' !!}>
                                    <label class="bmd-label-static" for="month">Month</label>
                                    <select name="month" id="month" class="form-control selectpicker"
                                        data-style="btn btn-link">
                                        @foreach (range(1, 12) as $item)
                                            @php
                                                $d = date('Y') . '-' . str_pad($item, 2, '0', STR_PAD_LEFT) . '-01';
                                                $date = date_create($d);
                                            @endphp
                                            <option value="{{ $date->format('m') }}">{{ $date->format('F') }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div style="display: none" id="termly-wrap"
                                    class="attendance-type form-group bmd-form-group" {!! $errors->has('termly') ? ' has-error' : '' !!}>
                                    <label class="bmd-label-static" for="termly">Term</label>
                                    <select name="term" id="termly" class="form-control selectpicker"
                                        data-style="btn btn-link">
                                        @foreach (\Ogilo\Eschool\Models\Term::orderBy('year', 'DESC')->orderBy('name', 'DESC')->get()
    as $term)
                                            <option value="{{ $term->id }}"
                                                {{ old('term') == $term->id ? 'selected' : ($term->start_date < now() && $term->end_date > now() ? 'selected' : '') }}>
                                                {{ $term->year }}-{{ $term->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="card-title">Lessons</div>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-check form-check-inline">
                                            <label class="form-check-label">
                                                <input class="form-check-input" type="checkbox" name="days[1]"
                                                    value="1"> Monday
                                                <span class="form-check-sign">
                                                    <span class="check"></span>
                                                </span>
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <label class="form-check-label">
                                                <input class="form-check-input" type="checkbox" name="days[2]"
                                                    value="2"> Tuesday
                                                <span class="form-check-sign">
                                                    <span class="check"></span>
                                                </span>
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <label class="form-check-label">
                                                <input class="form-check-input" type="checkbox" name="days[3]"
                                                    value="3"> Wednesday
                                                <span class="form-check-sign">
                                                    <span class="check"></span>
                                                </span>
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <label class="form-check-label">
                                                <input class="form-check-input" type="checkbox" name="days[4]"
                                                    value="4"> Thursday
                                                <span class="form-check-sign">
                                                    <span class="check"></span>
                                                </span>
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <label class="form-check-label">
                                                <input class="form-check-input" type="checkbox" name="days[5]"
                                                    value="5"> Friday
                                                <span class="form-check-sign">
                                                    <span class="check"></span>
                                                </span>
                                            </label>
                                        </div>
                                        {!! $errors->has('days') ? '<span class="text-danger">' . $errors->first('days') . '</span>' : '' !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        {{ csrf_field() }}
                        <button type="submit" class="btn btn-primary btn-round"><i
                                class="material-icons">cloud_download</i> Get Register</button>
                        <button type="reset" data-dismiss="modal" class="btn btn-danger btn-round"><i
                                class="material-icons">cancel</i> Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts_bottom')
    <script>
        let changeType = function(value) {
            document.querySelectorAll('div.attendance-type').forEach(item => {
                item.style.display = 'none'
            })
            document.getElementById(`${value}-wrap`).style.display = 'block'

        }
        $('input[name=type]').change(e => {
            changeType(e.target.value)
        })

        let checkedType = "{{ old('type') ? old('type') : null }}"
        // console.log('checkedType1: ', checkedType)
        checkedType = (checkedType == '') ? document.querySelector('input[name="type"]:checked').value : checkedType
        // console.log('checkedType2: ', checkedType)
        changeType(checkedType)

        $('select#month').val({{ old('month') ?? date('m') }})
        $('select#month').selectpicker('refresh')
    </script>
@endpush
