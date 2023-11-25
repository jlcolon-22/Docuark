<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Task;
use App\Models\User;
use App\Mail\Deadline;
use App\Models\Comment;
use App\Models\Deleted;
use App\Models\Project;
use App\Mail\FileUpload;
use App\Models\FileType;
use App\Models\TaskFile;
use App\Models\Department;
use App\Models\Activity_log;
use Illuminate\Http\Request;
use App\Models\ProjectDepartment;
use App\Models\SubTask;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class TaskerController extends Controller
{
    public function home()
    {
        $projects = DB::table('users')
                        ->join('project_departments','users.department_id','=','project_departments.department_id')
                        ->join('projects','project_departments.project_id','=','projects.id')
                        ->where('users.id', Auth::id())
                        ->where('projects.status_id', '!=', 0)
                        ->select('projects.id','projects.title','projects.description','projects.status_id','projects.created_at')
                        ->get();

        $department_name = Department::find(Auth::user()->department_id);

        return view('tasker.home',compact('projects','department_name'));
    }

    public function task_list($id)
    {
        // $tasks = DB::table('tasks')
        //         ->join('project_departments','tasks.project_id','=','project_departments.project_id')
        //         ->where('tasks.project_id', $id)
        //         ->where('project_departments.department_id', Auth::user()->department_id)
        //         ->select('tasks.id','tasks.title','tasks.description','tasks.created_at','tasks.status_id')
        //         ->get();
         $find_project = Project::find($id);

        if(!$find_project)
        {
            return abort(404);
        }

        $tasks = Task::where('project_id', $id)->get();
        $find_assign_project = ProjectDepartment::where('project_id',$id)->first();
        $department_name = Department::find(Auth::user()->department_id);
          if(@$_GET['arrange_by'] == 'Normal')
        {
            return view('tasker.task',compact('tasks','department_name','find_assign_project'));

        }else if(@$_GET['arrange_by'] == 'Sub-Task')
        {
            return view('tasker.sub_task',compact('tasks','department_name','find_assign_project'));


        }
        return view('tasker.task',compact('tasks','department_name','find_assign_project'));

        // $find_project = Project::find($id);

        // if(!$find_project)
        // {
        //     return abort(404);
        // }

        // $tasks = Task::where('project_id', $id)->get();
        // $departments = Department::all();
        // $find_assign_project = ProjectDepartment::where('project_id',$id)->first();
        // $file_types = FileType::all();

        // if(@$_GET['arrange_by'] == 'Normal')
        // {
        //     return view('admin.tasks',compact('find_project','tasks','departments','find_assign_project','file_types'));

        // }else if(@$_GET['arrange_by'] == 'Sub-Task')
        // {
        //     return view('admin.tasks_sub',compact('find_project','tasks','departments','find_assign_project','file_types'));

        // }

        // return view('admin.tasks',compact('find_project','tasks','departments','find_assign_project','file_types'));





        // return view('tasker.task',compact('tasks','department_name'));
    }

    public function update_sub_task($id)
    {
        $sub = SubTask::where('id',$id)->update([
            'status_id'=>2
        ]);
        return back();
    }

    public function update_task($id)
    {
        $find_task = Task::find($id);

        if(!$find_task)
        {
            return back()->with('error','Task Not Found');
        }

        $find_task->update(['status_id'=> 2]);

        return back()->with('success','Task is Completed.');
    }

    public function view_task($task_id,$project_id)
    {
        $find_project = Project::find($project_id);
        $find_task = Task::find($task_id);
        $file_types = FileType::all();

        $comments = Comment::where('task_id', $task_id)->orderBy('id','desc')->get();
        $task_files = TaskFile::where('task_id', $task_id)->where('file_name','!=','null')->get();


        return view('tasker.view_task',compact('find_project','find_task','comments','task_files','file_types'));
    }

    public function task_comment(Request $request)
    {
        $comment = trim($request->comment);
        $comment = htmlentities($comment);

        Comment::create(['task_id'=> $request->task_id, 'comment'=> $comment, 'user_id' => Auth::id()]);

        $task = Task::where('id', $request->task_id)->first();
        $task->update(['updated_by'=> Auth::id()]);

        $project = Project::query()->select('department_id','title')->where('id',$task->project_id)->first();
        $data = [
            'title'=>$project->title.' ('.$task->title.')',
            'comment'=>$comment,
            'user'=>Auth::user()->email
        ];
        $admins = User::whereHas('roles', function($q) {
            $q->where('roles.name','admin');
        })->get('email');
        foreach($admins as $v)

        {

            Mail::to($v['email'])->send(new \App\Mail\Comment($data));
        }
        $departmens = User::whereHas('roles', function($q) {
            $q->where('roles.name','!=','admin');
        })->where('department_id',$project->department_id)->get('email');
        foreach ($departmens as $v) {
            Mail::to($v->email)->send(new \App\Mail\Comment($data));
        }

        return back()->with('success','Comment Successfully.');
    }

    public function download_task(Request $request)
    {
        return Storage::download($request->url);
    }

    public function upload_task(Request $request)
    {
        $cover = $request->file('task_file')->getClientOriginalName();
        $file_size = $request->file('task_file')->getSize();

        $url = Storage::putFileAs('public', $request->file('task_file'),$cover);


        $task_file = new TaskFile;
        $task_file->task_id     = $request->task_id;
        $task_file->user_id     = Auth::id();
        $task_file->file_name   = $url;
        $task_file->type   = $request->file_type;
        $task_file->size   = $file_size;
        $task_file->save();

        $task = Task::where('id', $request->task_id)->first();
        $task->update(['updated_by'=> Auth::id()]);
        $project = Project::query()->select('department_id','title','id')->where('id',$task->project_id)->first();
        $data = [
            'filename'=>explode('/',$url)[1],
            'user'=>Auth::user()->email,
            'type'=>$request->file_type,
            'task'=>$project->title.'('.$task->title.')'
        ];

        $admins = User::whereHas('roles', function($q) {
            $q->where('roles.name','admin');
        })->get('email');
        Activity_log::query()
                    ->create([
                        'type_id'=>$task->id,

                        'project_id'=>$project->id,
                        'user_id'=>Auth::id(),
                        'message'=> ucfirst(Auth::user()->first_name).' '.ucfirst(Auth::user()->last_name). ' uploaded a file to Task '.'"'.$task->title.'"'
                    ]);
        foreach($admins as $v)

        {

            Mail::to($v['email'])->send(new FileUpload($data));
        }
        $departmens = User::whereHas('roles', function($q) {
            $q->where('roles.name','!=','admin');
        })->where('department_id',$project->department_id)->get('email');
        foreach ($departmens as $v) {
            Mail::to($v->email)->send(new FileUpload($data));
        }




        return back()->with('success','Task Files Uploaded Successfully');
    }

    public function update_upload_task(Request $request)
    {

        $check_task = TaskFile::where('task_id', $request->task_id)->where('id',$request->file_id)->first();
        if($check_task)
        {
            $cover = $request->file('task_file')->getClientOriginalName();;

            $url = Storage::putFileAs('public', $request->file('task_file'),$cover);

            $task = Task::where('id', $request->task_id)->update(['updated_by'=> Auth::id()]);
            $tasks = Task::find($request->task_id);

            $project = Project::find($tasks->project_id);
            Activity_log::query()
            ->create([
                'type_id'=>$tasks->id,
                'project_id'=>$project->id,
                'user_id'=>Auth::id(),
                'message'=> ucfirst(Auth::user()->first_name).' '.ucfirst(Auth::user()->last_name). ' Updated File "'.explode('/',$check_task->file_name)[1].'" from task "'.$tasks->title.'"'
            ]);
            $check_task->update(['user_id'=> Auth::id(),'file_name' => $url]);
            return back()->with('success','Task Files Uploaded Successfully');

        }




    }

    public function delete_comment($id)
    {
        $check_comment = Comment::where('id',$id)->where('user_id', Auth::id())->first();
        $check_comment->delete();
        return back()->with('success','Comment Remove Successfully');
    }

    public function delete_files(Request $request)
    {

        $check_comment = TaskFile::where('id',$request->id)->first();
        $task = Task::query()->where('id',$check_comment->task_id)->first();
        $project = Project::where('id',$task->project_id)->first();
        Deleted::query()->create([
            'name'=>$check_comment->file_name,
            'user_id'=>Auth::user()->id,
            'compalation_id'=>$task->project_id,
        ]);
        Activity_log::query()
                    ->create([
                        'type_id'=>$check_comment->id,
                        'type'=>1,
                        'category'=>'delete_file',
                        'project_id'=>$project->id,
                        'user_id'=>Auth::id(),
                        'message'=> ucfirst(Auth::user()->first_name).' '.ucfirst(Auth::user()->last_name). ' Deleted File '.'"'.explode('/',$check_comment->file_name)[1].'" from task "'.$task->title.'"'
                    ]);
        $check_comment->delete();
        return back()->with('success','File Remove Successfully');
    }
}
