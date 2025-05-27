<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShipDistrict;
use App\Models\ShipDivision;
use App\Models\ShipState;

class ShippingAreaController extends Controller
{
    /**
     * 
     * ShippingDivision Methods
     * 
     */
    public function AllDivision(){
        $division = ShipDivision::latest()->get();
        return view('admin.ship.division.division_all',compact('division'));
    } 

    public function AddDivision(){
        return view('admin.ship.division.division_add');
    }// End Method 

    public function StoreDivision(Request $request){ 

        ShipDivision::insert([ 
            'division_name' => $request->division_name, 
        ]);

        return redirect()->route('ship_division.all')->with('success','ShipDivision Added Successfully'); 

    }// End Method 


    public function EditDivision($id){

        $division = ShipDivision::findOrFail($id);
        return view('admin.ship.division.division_edit',compact('division'));

    }// End Method
    

    public function UpdateDivision(Request $request){

        $division_id = $request->id;

         ShipDivision::findOrFail($division_id)->update([
            'division_name' => $request->division_name,
        ]);

        return redirect()->route('ship_division.all')->with('success','ShipDivision Updated Successfully');



    }// End Method 


    public function DeleteDivision($id){

        ShipDivision::findOrFail($id)->delete();

        return redirect()->route('ship_division.all')->with('success','ShipDivision Deleted Successfully');


    }// End Method  



















    /**
     * 
     * ShippingDistrict Methods
     * 
     */




    public function AllDistrict(){
        $district = ShipDistrict::with('division')->latest()->get();
        return view('admin.ship.district.district_all',compact('district'));
    } // End Method 

    public function AddDistrict(){
        $division = ShipDivision::orderBy('division_name','ASC')->get();
        return view('admin.ship.district.district_add',compact('division'));
    }// End Method  


    public function StoreDistrict(Request $request){ 

        ShipDistrict::insert([ 
            'ship_division_id' => $request->division_id, 
            'district_name' => $request->district_name,
        ]);


        return redirect()->route('ship_district.all')->with('success','ShipDistrict Added Successfully');

    }// End Method 


    public function EditDistrict($id){
        $division = ShipDivision::orderBy('division_name','ASC')->get();
        $district = ShipDistrict::findOrFail($id);
        return view('admin.ship.district.district_edit',compact('district','division'));

    }// End Method 

    public function UpdateDistrict(Request $request){

        $district_id = $request->id;

         ShipDistrict::findOrFail($district_id)->update([
             'ship_division_id' => $request->division_id, 
            'district_name' => $request->district_name,
        ]);


        return redirect()->route('ship_district.all')->with('success','ShipDistrict Updated Successfully');


    }// End Method 


    public function DeleteDistrict($id){

        ShipDistrict::findOrFail($id)->delete();


        return redirect()->route('ship_district.all')->with('success','ShipDistrict Deleted Successfully');


    }// End Method 
    











    /**
     * 
     * ShippingState Methods
     * 
     */

    public function AllState(){
        $state = ShipState::latest()->get();
        return view('admin.ship.state.state_all',compact('state'));
    } // End Method 

    public function AddState(){
        $division = ShipDivision::orderBy('division_name','ASC')->get();
        $district = ShipDistrict::orderBy('district_name','ASC')->get();
         return view('admin.ship.state.state_add',compact('division','district'));
    }// End Method 

//???????????????????????????????????


    // public function GetDistrict($division_id){
    //     $dist = ShipDistrict::where('division_id',$division_id)->orderBy('district_name','ASC')->get();
    //         return json_encode($dist);

    // }// End Method 


    public function StoreState(Request $request){ 

        ShipState::insert([ 
            'ship_division_id' => $request->division_id, 
            'ship_district_id' => $request->district_id, 
            'state_name' => $request->state_name,
        ]);


        return redirect()->route('ship_state.all')->with('success','ShipState Added Successfully');


    }// End Method 


    public function EditState($id){
        $division = ShipDivision::orderBy('division_name','ASC')->get();
        $district = ShipDistrict::orderBy('district_name','ASC')->get();
        $state = ShipState::findOrFail($id);
         return view('admin.ship.state.edit_state',compact('division','district','state'));
    }// End Method 


    public function UpdateState(Request $request){

        $state_id = $request->id;

         ShipState::findOrFail($state_id)->update([
            'ship_division_id' => $request->division_id, 
            'ship_district_id' => $request->district_id, 
            'state_name' => $request->state_name,
        ]);


        return redirect()->route('ship_state.all')->with('success','ShipState Updated Successfully');


    }// End Method  


    public function DeleteState($id){

        ShipState::findOrFail($id)->delete();

        return redirect()->route('ship_state.all')->with('success','ShipState Deleted Successfully');


    }// End Method 

}
