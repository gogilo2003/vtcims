<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Staff;
use App\Models\IntakeStaffSubject;
use Validator;
use Img;
use App;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getStaff()
    {
        $staff = Staff::all();
        return view('eschool::staff.index', compact('staff'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAdd()
    {
        return view('eschool::staff.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postAdd(Request $request)
    {
        // dd($request->all());

        $validator = Validator::make($request->all(), [
            'idno' => 'required|unique:staff',
            'pfno' => 'nullable|unique:staff',
            'manno' => 'nullable|unique:staff',
            'phone' => 'nullable|unique:staff',
            'email' => 'nullable|unique:staff|email',
            'photo' => 'nullable|image',
            'staff_role_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator)
                ->with('global-warning', 'Some fields failed validation. Please check and try again');
        }

        $staff = new Staff;

        $staff->idno = $request->idno;
        $staff->pfno = $request->pfno ? $request->pfno : null;
        $staff->manno = $request->manno ? $request->manno : null;
        $staff->surname = $request->surname;
        $staff->first_name = $request->first_name;
        $staff->middle_name = $request->middle_name;
        $staff->box_no = $request->box_no;
        $staff->post_code = $request->post_code;
        $staff->town = $request->town;
        $staff->email = $request->email;
        $staff->phone = clean_isdn($request->phone);
        $staff->employer = $request->employer;
        $staff->staff_role_id = $request->staff_role_id;
        $staff->gender = $request->gender;

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            if ($file->isValid()) {
                $image = Img::make($file->getRealPath());
                $image->fit(480, 640);
                $dir = public_path('images/staff/');
                if (!file_exists($dir)) {
                    mkdir($dir, true, 0755);
                }
                $filename = time() . '.' . $file->guessClientExtension();
                $staff->photo = $filename;

                $image->save($dir . $filename);
                $image->destroy();
            }
        }

        $staff->save();

        return redirect()
            ->route('admin-eschool-staff')
            ->with('global-success', 'Staff Added');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getView($id)
    {
        // dd($id);
        $validator = Validator::make(['id' => $id], ['id' => 'exists:staff']);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->with('global-warning', 'staff does not exist');
        }

        $staff = Staff::find($id);
        $intake_subjects = IntakeStaffSubject::where('staff_id', $id)->with('subject', 'intake')->orderBy('intake_id', 'DESC')->get();
        return view('eschool::staff.view', compact('staff', 'intake_subjects'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getDownload(Request $request, $id = null)
    {
        $pdf = App::make('snappy.pdf.wrapper');

        if ($id) {
            $staff = Staff::find($id);
            $intake_subjects = IntakeStaffSubject::where('staff_id', $id)->with('subject', 'intake')->orderBy('intake_id', 'DESC')->get();
            $pdf->loadView('eschool::staff.download.view', compact('staff', 'intake_subjects'));
            return $pdf->download(strtoupper('Staff#' . str_pad($staff->id, 4, '0', 0)) . '.pdf');
        } else {
            $staff = Staff::all();
            $pdf->loadView('eschool::staff.download.list', compact('staff'))
                ->setOrientation('landscape')
                ->setPaper('A4')
                ->setOption('footer-center', 'Page [page] of [toPage]')
                ->setOption('footer-font-size', 8);
            return $pdf->download(strtoupper('STAFF_LIST_' . date('d-M-Y')) . '.pdf');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function postAttendance(Request $request)
    {
        // dd($request->class);
        $validator = Validator::make($request->all(), [
            'date' => [
                'required',
                'date',
                function ($attribute, $value, $fail) {
                    $day = (int)date_create($value)->format('w');
                    if ($day !== 1) {
                        return $fail($attribute . ' must be a monday');
                    }
                }
            ]
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator)
                ->with('global-warning', 'Some fields failed validation. Please check and try again');
        }

        $staff = Staff::when($request->employer, function ($query) use ($request) {
            return $query->whereIn('employer', $request->employer);
        })->get();

        $date['mon'] = date_create($request->date);
        $date['tue'] = clone $date['mon'];
        $date['tue'] = date_add($date['tue'], date_interval_create_from_date_string('1 days'));
        $date['wed'] = clone $date['tue'];
        $date['wed'] = date_add($date['wed'], date_interval_create_from_date_string('1 days'));
        $date['thu'] = clone $date['wed'];
        $date['thu'] = date_add($date['thu'], date_interval_create_from_date_string('1 days'));
        $date['fri'] = clone $date['thu'];
        $date['fri'] = date_add($date['fri'], date_interval_create_from_date_string('1 days'));

        $pdf = App::make('snappy.pdf.wrapper')
            ->setOrientation('landscape')
            ->setPaper('A4')
            ->setOption('no-outline', true)
            ->setOption('footer-center', 'Page [page] of [toPage]')
            ->setOption('footer-font-size', 8);

        $employer = $request->employer ? (is_array($request->employer) ? implode(', ', $request->employer) : $request->employer) : 'All';

        $pdf->loadView('eschool::staff.download.attendance', compact('staff', 'employer'), $date);

        $filename = strtoupper(str_studle('attendance register ' . $date['mon']->format('j-M-Y'))) . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getEdit($id)
    {
        $validator = Validator::make(['id' => $id], ['id' => 'exists:staff']);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->with('global-warning', 'staff does not exist');
        }

        $staff = Staff::find($id);
        return view('eschool::staff.edit', compact('staff'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function postEdit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:staff',
            'idno' => 'required|unique:staff,idno,' . $request->id,
            'pfno' => 'nullable|unique:staff,pfno,' . $request->id,
            'manno' => 'nullable|unique:staff,manno,' . $request->id,
            'phone' => 'nullable|unique:staff,phone,' . $request->id,
            'email' => 'nullable|email|unique:staff,email,' . $request->id,
            'photo' => 'nullable|image',
            'staff_role_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator)
                ->with('global-warning', 'Some fields failed validation. Please check and try again');
        }

        $staff = Staff::find($request->id);

        $staff->idno = $request->idno;
        $staff->pfno = $request->pfno ? $request->pfno : null;
        $staff->manno = $request->manno ? $request->manno : null;
        $staff->surname = $request->surname;
        $staff->first_name = $request->first_name;
        $staff->middle_name = $request->middle_name;
        $staff->box_no = $request->box_no;
        $staff->post_code = $request->post_code;
        $staff->town = $request->town;
        $staff->email = $request->email;
        $staff->phone = clean_isdn($request->phone);
        $staff->employer = $request->employer;
        $staff->staff_role_id = $request->staff_role_id;
        $staff->gender = $request->gender;

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            if ($file->isValid()) {
                $image = Img::make($file->getRealPath());
                $image->fit(480, 640);

                $dir = public_path('images/staff/');
                if (!file_exists($dir)) {
                    mkdir($dir, true, 0755);
                }

                if ($staff->photo && file_exists($dir . $staff->photo)) {
                    unlink($dir . $staff->photo);
                }

                $filename = time() . '.' . $file->guessClientExtension();
                $staff->photo = $filename;

                $image->save($dir . $filename);
                $image->destroy();
            }
        }

        $staff->save();

        return redirect()
            ->route('admin-eschool-staff')
            ->with('global-success', 'Staff updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
