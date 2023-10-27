<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Task;

use App\Models\User;
use App\Models\Project;
use App\Models\SubTask;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class Deadline extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deadline:starts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $projects = Project::get();
        foreach($projects as $v)
        {


            $deadline = Carbon::parse($v->deadline);
            $deadline->toDateString();
            $diff = $deadline->diffInDays(Carbon::now()->toDateString());
            if($diff == 7)
            {

                $data = [
                    'type'=>'Project',
                    'title'=>$v->title,
                    'deadline'=>'1 Week before the deadline'
                ];
                $departmens = User::whereHas('roles', function($q) {
                    $q->where('roles.name','!=','admin');
                })->where('department_id',$v->department_id)->get('email');
                foreach ($departmens as $value) {
                    Mail::to('jlcolon368@gmail.com')->send(new \App\Mail\Deadline($data));
                }

                $admins = User::whereHas('roles', function($q) {
                    $q->where('roles.name','admin');
                })->get('email');

                foreach($admins as $admin)
                {

                    Mail::to($admin['email'])->send(new \App\Mail\Deadline($data));
                }



            }
            if($diff == 3)
            {
                $data = [
                    'type'=>'Project',
                    'title'=>$v->title,
                    'deadline'=>'3 days before the deadline'
                ];
                $admins = User::whereHas('roles', function($q) {
                    $q->where('roles.name','admin');
                })->get('email');
                foreach($admins as $v)
                {

                    Mail::to($v['email'])->send(new \App\Mail\Deadline($data));
                }
                $departmens = User::whereHas('roles', function($q) {
                    $q->where('roles.name','!=','admin');
                })->where('department_id',$v->department_id)->get('email');
                foreach ($departmens as $v) {
                    Mail::to('jlcolon368@gmail.com')->send(new \App\Mail\Deadline($data));
                }
            }
            if($diff == 1)
            {
                $data = [
                    'type'=>'Project',
                    'title'=>$v->title,
                    'deadline'=>'1 day before the deadline'
                ];
                $admins = User::whereHas('roles', function($q) {
                    $q->where('roles.name','admin');
                })->get('email');
                foreach($admins as $v)
                {

                    Mail::to($v['email'])->send(new \App\Mail\Deadline($data));
                }
                $departmens = User::whereHas('roles', function($q) {
                    $q->where('roles.name','!=','admin');
                })->where('department_id',$v->department_id)->get('email');
                foreach ($departmens as $v) {
                    Mail::to('jlcolon368@gmail.com')->send(new \App\Mail\Deadline($data));
                }
            }

        }
        $tasks = Task::get();
        foreach($tasks as $v)
        {
            $deadline = Carbon::parse($v->deadline);
            $deadline->toDateString();
            $diff = $deadline->diffInDays(Carbon::now()->toDateString());
            $project = Project::select('title','department_id')->where('id',$v->project_id)->first();
            if($diff == 7)
            {
                $data = [
                    'type'=>'TASK',
                    'title'=>$project->title.'('.$v->title.')',
                    'deadline'=>'1 Week before the deadline'
                ];
                $admins = User::whereHas('roles', function($q) {
                    $q->where('roles.name','admin');
                })->get('email');
                foreach($admins as $admin)
                {

                    Mail::to($admin['email'])->send(new \App\Mail\Deadline($data));
                }
                $departmens = User::whereHas('roles', function($q) {
                    $q->where('roles.name','!=','admin');
                })->where('department_id',$project->department_id)->get('email');
                foreach ($departmens as $value) {
                    Mail::to('jlcolon368@gmail.com')->send(new \App\Mail\Deadline($data));
                }
            }
            if($diff == 3)
            {
                $data = [
                    'type'=>'TASK',
                    'title'=>$project->title.'('.$v->title.')',
                    'deadline'=>'3 days before the deadline'
                ];
                Mail::to('jlcolon368@gmail.com')->send(new \App\Mail\Deadline($data));
            }
            if($diff == 1)
            {
                $data = [
                    'type'=>'TASK',
                    'title'=>$project->title.'('.$v->title.')',
                    'deadline'=>'1 day before the deadline'
                ];
                Mail::to('jlcolon368@gmail.com')->send(new \App\Mail\Deadline($data));
            }

        }
        $sub_tasks = SubTask::get();
        foreach($sub_tasks as $sub)
        {
            $deadline = Carbon::parse($sub->deadline);
            $deadline->toDateString();
            $diff = $deadline->diffInDays(Carbon::now()->toDateString());
            $task = Task::where('id',$sub->task_id)->first();
            $project = Project::select('title','department_id')->where('id',$task->project_id)->first();
            if($diff == 7)
            {
                $data = [
                    'type'=>'SUB TASK',
                    'title'=>$project->title.'('.$task->title.') ('.$sub->title.')',
                    'deadline'=>'1 Week before the deadline'
                ];
                $admins = User::whereHas('roles', function($q) {
                    $q->where('roles.name','admin');
                })->get('email');
                foreach($admins as $admin)
                {

                    Mail::to($admin['email'])->send(new \App\Mail\Deadline($data));
                }
                $departmens = User::whereHas('roles', function($q) {
                    $q->where('roles.name','!=','admin');
                })->where('department_id',$project->department_id)->get('email');
                foreach ($departmens as $value) {
                    Mail::to('jlcolon368@gmail.com')->send(new \App\Mail\Deadline($data));
                }
            }
            if($diff == 3)
            {
                // $data = [
                //     'type'=>'TASK',
                //     'title'=>$project->title.'('.$v->title.')',
                //     'deadline'=>'3 days before the deadline'
                // ];
                // Mail::to('jlcolon368@gmail.com')->send(new \App\Mail\Deadline($data));
                $data = [
                    'type'=>'SUB TASK',
                    'title'=>$project->title.'('.$task->title.') ('.$sub->title.')',
                    'deadline'=>'3 days before the deadline'
                ];
                $admins = User::whereHas('roles', function($q) {
                    $q->where('roles.name','admin');
                })->get('email');
                foreach($admins as $admin)
                {

                    Mail::to($admin['email'])->send(new \App\Mail\Deadline($data));
                }
                $departmens = User::whereHas('roles', function($q) {
                    $q->where('roles.name','!=','admin');
                })->where('department_id',$project->department_id)->get('email');
                foreach ($departmens as $value) {
                    Mail::to('jlcolon368@gmail.com')->send(new \App\Mail\Deadline($data));
                }
            }
            if($diff == 1)
            {
                // $data = [
                //     'type'=>'TASK',
                //     'title'=>$project->title.'('.$v->title.')',
                //     'deadline'=>'1 day before the deadline'
                // ];
                // Mail::to('jlcolon368@gmail.com')->send(new \App\Mail\Deadline($data));
                $data = [
                    'type'=>'SUB TASK',
                    'title'=>$project->title.'('.$task->title.') ('.$sub->title.')',
                    'deadline'=>'1 day before the deadline'
                ];
                $admins = User::whereHas('roles', function($q) {
                    $q->where('roles.name','admin');
                })->get('email');
                foreach($admins as $admin)
                {

                    Mail::to($admin['email'])->send(new \App\Mail\Deadline($data));
                }
                $departmens = User::whereHas('roles', function($q) {
                    $q->where('roles.name','!=','admin');
                })->where('department_id',$project->department_id)->get('email');
                foreach ($departmens as $value) {
                    Mail::to('jlcolon368@gmail.com')->send(new \App\Mail\Deadline($data));
                }
            }

        }
        return 0;
    }
}
