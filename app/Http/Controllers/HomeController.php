<?php

namespace App\Http\Controllers;

use Img;
use Validator;
use App\Models\Page;
use Inertia\Inertia;

use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Student;

/**
 *
 */
class HomeController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth')->except('login');
    }

    function dashboard()
    {
        $students = [];
        foreach (Student::selectRaw('gender, count(*) as count')
            ->groupBy('gender')
            ->get()->map(fn ($item) => [
                ($item->gender ? 'female' : 'male') => $item->count
            ]) as $item) {

            if (isset($item['female'])) {
                $students['female'] = $item['female'];
            }
            if (isset($item['male'])) {
                $students['male'] = $item['male'];
            }
        }
        $students['total'] = $students['male'] + $students['female'];

        return Inertia::render('Dashboard', ['students' => $students]);
    }

    public function getDashboard()
    {
        return view('eschool::dashboard');
    }

    public function postImageUpload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|image'
        ]);

        if ($validator->fails()) {
            return response('Validation Error', 400);
        }
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            if ($file->isValid()) {
                $dir = public_path('images/upload/');
                if (!file_exists($dir)) {
                    mkdir($dir, 0755, TRUE);
                }
                $image = Img::make($file->getRealPath());
                $image->save($dir . $file->hashName());
                $photo = $image->encode('data-url')->encoded;
                $image->destroy();

                return response(json_encode(['location' => $photo]))->header('Content-Type', 'application/json');
            }
        }
        return response(json_encode(['location' => url('photo')]))->header('Content-Type', 'application/json');
    }
}
