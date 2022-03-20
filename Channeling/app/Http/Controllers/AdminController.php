<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Doctor;

use Illuminate\Support\Facades\Auth;

use App\Models\Appoinment;

use Notification;

use App\Notifications\SendEmailNotification;

class AdminController extends Controller
{
    public function index(){
        $doctors=doctor::all();
        // dd($doctors);
        $doctors_count=$doctors->name;
        return view('dashboard',$doctors);
    }

    public function addview()
    {
        if(Auth::id())
        
        {
            if(Auth::user()->usertype==1)
            {
                return view('admin.add_doctor');
            }

            else 
                {
                    return redirect()->back();
                }

               
        }
        else
        {
            return redirect('login');
        }
        
    }

    public function upload(request $request)
    {
        $doctor=new doctor;

        $image=$request->file;

        $imagename=time().'.'.$image->getClientoriginalExtension();

        $request->file->move('doctorimage',$imagename);

        $doctor->image=$imagename;

        $doctor->name=$request->name;
        $doctor->phone=$request->number;
        $doctor->speciality=$request->speciality;
        $doctor->room =$request->room ;
       
        $doctor->save();

        return redirect()->back()->with('message','Doctor Added Successfully');

    }

    public function showappointment()
    {
        if(Auth::id())
        
        {
            if(Auth::user()->usertype==1)
            {

                 $data=appoinment::all();
                return view('admin.showappointment', compact('data'));
            }

            else{
                return redirect()->back();
            }
        }
        else{
            return redirect('login');
        }
    }

    public function approved($id)
    {
        $data=appoinment::find($id);

        $data->status='approved';

        $data->save();

        return redirect()->back();

    }

    public function canceled($id)
    {
        $data=appoinment::find($id);

        $data->status='canceled';

        $data->save();

        return redirect()->back();

    }

    public function showdoctor()
    {
        $data = doctor::all();

        return view('admin.showdoctor',compact('data'));

    }

    public function deletedoctor($id)
{
    $data=doctor::find($id);

    $data->delete();

    return redirect()->back();
}
    public function updatedoctor($id)
    {
        $data = doctor::find($id);

        return view('admin.update_doctor',compact('data'));
    }

    public function editdoctor(Request $request , $id)
    {
        $doctor = doctor::find($id);

        $doctor->name=$request->name;

        $doctor->phone=$request->phone;

        $doctor->speciality=$request->speciality;

        $doctor->room=$request->room;

        $image=$request->file;

        if($image)
        {

        $imagename=time().'.'.$image->getClientOriginalExtension();

        $request->file->move('doctorimage',$imagename);

        $doctor->image=$imagename;

        }

        $doctor->save();

        return redirect()->back()->with('message','Doctor Details Updated Successfully');

    } 

    public function emailview($id)
    {
        $data=Appoinment::find($id);
        return view('admin.email_view',compact('data'));
    }

    public function sendemail(Request $request,$id)
    {
        $data = Appoinment::find($id);

        $details=[

            'greeting' => $request->greeting,

            'body' => $request->body,

            'actiontext' => $request->actiontext,

            'actionurl' => $request->actionurl,

            'endpart' => $request->endpart



        ];

        Notification::send($data,new SendEmailNotification($details));
        return redirect()->back();
   
    }



}
