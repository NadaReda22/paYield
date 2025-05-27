<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DateTime;
use App\Models\Order;
use App\Models\User;


class ReportController extends Controller
{

    public function ReportView(){
        return view('admin.report.report_view');
    } 

    public function searchByDate(Request $request)
    {
           $date=new DateTime($request->date);
           $formatDate = $date->format('d F Y');

           $orders=Order::where('order_date',$formatDate)->latest()->get();
           return view('admin.report.report_by_date',compact(['formatDate','orders']));
    }

    public function searchByYear(Request $request)
    {
         $year=$request->year;
        
        $orders=Order::where('order_year',$year)->latest()->get();

           return view('admin.report.report_by_year',compact(['year','orders']));
    }


    public function searchByMonth(Request $request)
    {
           $year=$request->year_name;
           $month=$request->month;

           $orders=Order::where('order_year',$year)->where('order_month',$month)->latest()->get();
           return view('admin.report.report_by_month',compact(['month','year','orders']));
    }

    
    public function OrderByUser(){
        $users = User::where('role','user')->latest()->get();
        return view('admin.report.report_by_user',compact('users'));

    }// End Method 

    public function SearchByUser(Request $request){
        $users = $request->user;
        // dd($users);
        $user=User::where('id',$users)->first();
        $orders = Order::where('user_id',$users)->latest()->get();
        return view('admin.report.report_by_user_show',compact('orders','users','user'));
    }// End Method 


}
