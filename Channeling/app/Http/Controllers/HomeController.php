<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use App\Models\User;
use App\Models\Doctor;
use App\Models\Appoinment;

class HomeController extends Controller
{
    public function redirect()
    {
        if(Auth::id())
        {
            if(Auth::user()->usertype=='0'){
                $doctor = doctor::all();
                return view('user.home',compact('doctor'));
            }
            else
            {
                $doctors = doctor::all();
                $doctors_count=$doctors->count();
                $user=user::all();
                $users_count=$user->count();
                // dd($doctors_count);
                return view('admin.home',compact('doctors_count','users_count'));
            }

        }
        else 
        {
            return redirect()->back();
        }
    }


    public function index()
    {
        if(Auth::id())
        {
            return redirect('home');
        }
        else
        {
        $doctor = doctor::all();

       return view('user.home',compact('doctor')) ;
        }
    }


    public function appointment(Request $request)
    {
        $data = new Appoinment;

        $data->name=$request->name;

        $data->email=$request->email;

        $data->date=$request->date;

        $data->phone=$request->number;

        $data->message=$request->message;

        $data->doctor=$request->doctor;

        $data->status='In progress';

        if(Auth::id())
        {
        $data->user_id=Auth::user()->id;
        }

        $data->save();

        return redirect()->back()->with('message','Appointment Request Successful. We will contact with you soon');
    }

    public function myappointment()
    {
        if(Auth::id())
        {
            if(Auth::user()->usertype==0)
            {

                $userid=Auth::user()->id;
                $appoint=appoinment::where('user_id',$userid)->get();
                return view('user.my_appointment',compact('appoint'));
            }

        }

       else
       {
           return redirect()->back;
       } 
       
    }

    public function cancel_appoint($id)
    {
        $data=appoinment::find($id);
        
        $data->delete();

        return redirect()->back();
    }
}
