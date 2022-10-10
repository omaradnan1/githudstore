<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\projects;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProjectsController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $project = projects::all();

            return DataTables::of($project)
                ->addIndexColumn()
                ->rawColumns(['record_select', 'actions'])

            ->editColumn('image', function (project $project) {

                return '<img src="'.asset($project->image).'" class="img-fluid rounded-circle">';

            })
                ->make(true);
        }

        return view('front.projects.index', [
            'projects' => projects::get(),
        ]);
    }


    public function store(Request $request)
    {

        $validator = \Validator::make($request->all(), [
            "title" => 'required',
            "title_en" => 'required',
            "service_id" => 'required',
            "main_image" => 'required',
            "daraing_date" => 'required',
            "description" => 'required',
            "description_en" => 'required'

        ],
            [
                "title.required" => "هذا الحقل مطلوب"
            ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

            $img_path = null;
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $img = $request->file('image');
                $img_path = $img->store('/projects','main_image');
        }
        // if($request->id != null){
        //     Services::find($request->id)->update([
        //         "name"=>$request->name,
        //     "name_en" =>$request->name_en ,
        //     "description" =>$request->description,
        //     "description_en" =>$request->description_en
        //     ]);

        // }else{
        //     Services::create( [
        //         "name"=>$request->name,
        //         "name_en" =>$request->name_en ,
        //         "description" =>$request->description,
        //         "description_en" =>$request->description_en
        //     ]);
        // }
        projects::updateOrCreate([
            'id' => $request->id
        ], [
            "title" => $request->title,
            "title_en" => $request->title_en,
            "service_id" => $request->service_id,

            "image" =>"main_image/".strip_tags($img_path,'<img>'),
            "daraing_date" => $request->daraing_date,

            "description" => $request->description,
            "description_en" => $request->description_en
        ]);

        return response()->json([
            "message" => "success",
            "status" => 200
        ]);
    }

    public function edit(Request $request)
    {
        $project = projects::find($request->id);
        return Response()->json($project);
    }

    public function delete(Request $request)
    {
        $projects = projects::find($request->id);
        if (!$projects) {
            return response()->json([
                'erorr' => true,
                'message' => 'هذه الخدمة غير موجودة',
            ]);
        }
        $projects->delete();
        return response()->json([
            'success' => true,
            'message' => 'تم الحذف بنجاح',
        ]);
    }

}
