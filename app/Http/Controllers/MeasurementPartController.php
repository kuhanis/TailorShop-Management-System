<?php

namespace App\Http\Controllers;

use App\Models\ClothType;
use Illuminate\Http\Request;
use App\Models\BodyMeasurement;
use App\Models\MeasurementPart;

class MeasurementPartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = "Body Measurements";
        $measurements = BodyMeasurement::with('customer')
            ->get()
            ->groupBy('customer_id');
        return view('measurement-parts', compact('title', 'measurements'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            // 'cloth_type'=>'required',
            'name'=>'required|max:200',
        ]);
        
        MeasurementPart::create([
            'cloth_type_id'=>$request->cloth_type,
            'name'=>$request->name,
        ]);

        $notification = array(
            'message'=>"Measurement part added successfully!!",
            'alert-type'=>'success'
        );
        return back()->with($notification);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'body_name' => 'required',
            'shoulder' => 'required|numeric',
            'chest' => 'required|numeric',
            'waist' => 'required|numeric',
            'hips' => 'required|numeric',
            'dress_length' => 'required|numeric',
            'wrist' => 'required|numeric',
            'skirt_length' => 'required|numeric',
            'armpit' => 'required|numeric',
        ]);

        $measurement = BodyMeasurement::findOrFail($request->id);
        $measurement->update($request->all());

        return response()->json(['success' => true]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $measurement = BodyMeasurement::findOrFail($request->id);
        $measurement->delete();
        
        return response()->json(['success' => true]);
    }
}
