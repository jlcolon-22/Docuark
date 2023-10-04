<?php

namespace App\Http\Controllers;

use App\Mail\Deadline;
use App\Mail\FileUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Project;
use App\Models\Task;
use App\Models\Department;
use App\Models\Comment;
use App\Models\Deleted;
use Illuminate\Support\Facades\Storage;
use App\Models\TaskFile;
use App\Models\FileType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

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
        $tasks = DB::table('tasks')
                ->join('project_departments','tasks.project_id','=','project_departments.project_id')
                ->where('tasks.project_id', $id)
                ->where('project_departments.department_id', Auth::user()->department_id)
                ->select('tasks.id','tasks.title','tasks.description','tasks.created_at','tasks.status_id')
                ->get();
        $department_name = Department::find(Auth::user()->department_id);

        return view('tasker.task',compact('tasks','department_name'));
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
            Mail::to('jlcolon368@gmail.com')->send(new \App\Mail\Comment($data));
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
        $project = Project::query()->select('department_id','title')->where('id',$task->project_id)->first();
        $data = [
            'filename'=>explode('/',$url)[1],
            'user'=>Auth::user()->email,
            'type'=>$request->file_type,
            'task'=>$project->title.'('.$task->title.')'
        ];

        $admins = User::whereHas('roles', function($q) {
            $q->where('roles.name','admin');
        })->get('email');
        foreach($admins as $v)

        {

            Mail::to($v['email'])->send(new FileUpload($data));
        }
        $departmens = User::whereHas('roles', function($q) {
            $q->where('roles.name','!=','admin');
        })->where('department_id',$project->department_id)->get('email');
        foreach ($departmens as $v) {
            Mail::to('jlcolon368@gmail.com')->send(new FileUpload($data));
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

            Task::where('id', $request->task_id)->update(['updated_by'=> Auth::id()]);

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

    public function delete_files($id)
    {

        $check_comment = TaskFile::where('id',$id)->where('user_id', Auth::id())->first();
        $task = Task::query()->where('id',$check_comment->task_id)->first();
        Deleted::query()->create([
            'name'=>$check_comment->file_name,
            'user_id'=>Auth::user()->id,
            'compalation_id'=>$task->project_id,
        ]);
        $check_comment->delete();
        return back()->with('success','File Remove Successfully');
    }
}
