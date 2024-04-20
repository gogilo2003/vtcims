@extends('admin::layout.main')

@section('title')
    E-School::Sponsors
@endsection

@section('page_title')
    <i class="fa fa-list-alt"></i> Sponsors
@endsection

@section('sidebar')
    @parent
    <hr>
    @include('sidebar')
    @include('sponsors.sidebar')
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <form id="sponsorForm" method="post"
                        action="{{ $errors->count() && old('id') ? route('admin-eschool-sponsors-edit-post') : route('admin-eschool-sponsors-add-post') }}"
                        role="form" accept-charset="UTF-8" enctype="multipart/form-data">
                        <div class="form-group{!! $errors->has('sponsor_name') ? ' has-error' : '' !!}">
                            <label class="bmd-label-floating" for="sponsor_name">Sponsor name</label>
                            <input type="text" class="form-control" id="sponsor_name"
                                name="sponsor_name"{!! old('sponsor_name') ? ' value="' . old('sponsor_name') . '"' : '' !!}>
                            {!! $errors->has('sponsor_name')
                                ? '<span class="text-danger">' . $errors->first('sponsor_name') . '</span>'
                                : '' !!}
                        </div>
                        <div class="form-group{!! $errors->has('contact_person') ? ' has-error' : '' !!}">
                            <label class="bmd-label-floating" for="contact_person">Contact Person</label>
                            <input type="text" class="form-control" id="contact_person" name="contact_person"
                                {!! old('contact_person') ? ' value="' . old('contact_person') . '"' : '' !!}>
                            {!! $errors->has('contact_person')
                                ? '<span class="text-danger">' . $errors->first('contact_person') . '</span>'
                                : '' !!}
                        </div>
                        <div class="form-group{!! $errors->has('email') ? ' has-error' : '' !!}">
                            <label class="bmd-label-floating" for="email">Email</label>
                            <input type="text" class="form-control" id="email" name="email"
                                {!! old('email') ? ' value="' . old('email') . '"' : '' !!}>
                            {!! $errors->has('email') ? '<span class="text-danger">' . $errors->first('email') . '</span>' : '' !!}
                        </div>
                        <div class="form-group{!! $errors->has('phone_number') ? ' has-error' : '' !!}">
                            <label class="bmd-label-floating" for="phone_number">Phone Number</label>
                            <input type="text" class="form-control" id="phone_number" name="phone_number"
                                {!! old('phone_number') ? ' value="' . old('phone_number') . '"' : '' !!}>
                            {!! $errors->has('phone_number')
                                ? '<span class="text-danger">' . $errors->first('phone_number') . '</span>'
                                : '' !!}
                        </div>
                        <div class="form-group{!! $errors->has('box_number') ? ' has-error' : '' !!}">
                            <label class="bmd-label-floating" for="box_number">Box Number</label>
                            <input type="text" class="form-control" id="box_number" name="box_number"
                                {!! old('box_number') ? ' value="' . old('box_number') . '"' : '' !!}>
                            {!! $errors->has('box_number') ? '<span class="text-danger">' . $errors->first('box_number') . '</span>' : '' !!}
                        </div>
                        <div class="form-group{!! $errors->has('post_code') ? ' has-error' : '' !!}">
                            <label class="bmd-label-floating" for="post_code">Post Code</label>
                            <input type="text" class="form-control" id="post_code" name="post_code"
                                {!! old('post_code') ? ' value="' . old('post_code') . '"' : '' !!}>
                            {!! $errors->has('post_code') ? '<span class="text-danger">' . $errors->first('post_code') . '</span>' : '' !!}
                        </div>
                        <div class="form-group{!! $errors->has('town') ? ' has-error' : '' !!}">
                            <label class="bmd-label-floating" for="town">Town</label>
                            <input type="text" class="form-control" id="town" name="town"
                                {!! old('town') ? ' value="' . old('town') . '"' : '' !!}>
                            {!! $errors->has('town') ? '<span class="text-danger">' . $errors->first('town') . '</span>' : '' !!}
                        </div>
                        <div class="form-group{!! $errors->has('physical_address') ? ' has-error' : '' !!}">
                            <label class="bmd-label-floating" for="physical_address">Physical Address</label>
                            <textarea class="form-control" id="physical_address" name="physical_address"> {!! old('physical_address') ? ' value="' . old('physical_address') . '"' : '' !!}</textarea>
                            {!! $errors->has('physical_address')
                                ? '<span class="text-danger">' . $errors->first('physical_address') . '</span>'
                                : '' !!}
                        </div>
                        <input type="hidden" name="id" value="" id="sponsorId">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <button type="submit" class="btn btn-primary btn-round"><span class="material-icons">save</span>
                            Save</button>
                        <a href="JavaScript:" id="cancelSponsorEdit" class="btn btn-danger btn-round"
                            style="display: none"><i class="material-icons">cancel</i> Cancel</a>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Sponsor Name</th>
                                    <th>Contact Person</th>
                                    <th>Phone Number</th>
                                    <th>Email</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sponsors as $sponsor)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $sponsor->name }}</td>
                                        <td>{{ $sponsor->contact_person }}</td>
                                        <td>{{ $sponsor->phone }}</td>
                                        <td>{{ $sponsor->email }}</td>
                                        <td>
                                            <a href="JavaScript:"
                                                class="btn btn-primary btn-fab btn-round btn-sm editSponsorButton"
                                                data-sponsor="{{ $sponsor->toJson() }}"><i
                                                    class="material-icons">edit</i></a>
                                            <a href="JavaScript:"
                                                class="btn btn-fab btn-round btn-danger btn-sm deleteSponsorButton"
                                                data-id="{{ $sponsor->id }}" data-name="{{ $sponsor->name }}"><i
                                                    class="material-icons">delete</i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <form id="deleteSponsorForm" method="post" action="{{ route('admin-eschool-sponsors-delete') }}"
                            role="form" accept-charset="UTF-8" enctype="multipart/form-data">
                            <input type="hidden" name="id" id="deleteSponsorId">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts_bottom')
    <script type="text/javascript">
        $(document).ready(function() {
            $('.editSponsorButton').click(function() {
                url = '{{ route('admin-eschool-sponsors-edit-post') }}'
                $('#sponsorForm').attr('action', url)

                $('#sponsor_name').val($(this).data('sponsor').name)
                $('#contact_person').val($(this).data('sponsor').contact_person)
                $('#email').val($(this).data('sponsor').email)
                $('#phone_number').val($(this).data('sponsor').phone)
                $('#box_number').val($(this).data('sponsor').box_no)
                $('#post_code').val($(this).data('sponsor').post_code)
                $('#town').val($(this).data('sponsor').town)
                $('#physical_address').val($(this).data('sponsor').address)
                $('#sponsorId').val($(this).data('sponsor').id)

                $('#cancelSponsorEdit').attr('style', 'display: inline-block')

                $('#sponsor_name').focus()
            })

            $('#cancelSponsorEdit').click(function() {
                url = '{{ route('admin-eschool-sponsors-add-post') }}'
                $('#sponsorForm').attr('action', url)

                $('#sponsor_name').val(null)
                $('#contact_person').val(null)
                $('#email').val(null)
                $('#phone_number').val(null)
                $('#box_number').val(null)
                $('#post_code').val(null)
                $('#town').val(null)
                $('#physical_address').val(null)
                $('#sponsorId').val(null)

                $('#cancelSponsorEdit').attr('style', 'display: none')
                $('#sponsor_name').focus()
            })

            $('.deleteSponsorButton').click(function() {
                $('#deleteSponsorId').val($(this).data('id'))
                name = $(this).data('name')
                if (confirm("Do you want delete " + name)) {
                    $('#deleteSponsorForm').submit()
                }

            })
        })
    </script>
@endsection
