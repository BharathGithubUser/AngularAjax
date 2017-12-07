<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\formModel;

class AngularController extends Controller
{
    public function index(){
    	return view('form');
    }

    public function postFormData(Request $form_data){
    	//Storing Post_data to DB
    	$formModel=new formModel;
    	$formModel->name=$form_data->name;
    	$formModel->email=$form_data->email;
    	$formModel->password=$form_data->password;
    	$formModel->save();
    	//Retriving Post_data from DB
    	$formModel=new formModel;
    	$formModel=formModel::all();
    	//Result of total DB data after Insertion
    	return response()->json($formModel);
    }

    public function display(){
    	$formModel=new formModel;
    	$display_records=formModel::all();
    	return response()->json($display_records);
    }

    public function preview_edit(Request $post_data){
    	$formModel=formModel::find($post_data->id);
    	return response()->json($formModel);
    }
    public function edit(Request $post_data){
    	$formModel=formModel::find($post_data->id);
    	$formModel->name=$post_data->name;
    	$formModel->email=$post_data->email;
    	$formModel->password=$post_data->password;
    	$formModel->save();
    	return response("Successfully Updated!");
    }

    public function delete(Request $post_data){
    	$formModel=formModel::find($post_data->id);
    	$formModel->delete();
    	return response("Deleted Successfully");
    }
}
