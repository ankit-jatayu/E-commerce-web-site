<?php 

use Carbon\Carbon;

use App\Models\Vehicles;
use App\Models\Drivers;
use App\Models\States;
use App\Models\TransportTrips;


if(!function_exists('helperConvertYmdTodmY')){
	function helperConvertYmdTodmY($date){
		return Carbon::createFromFormat('Y-m-d',$date)->format('d/m/Y');
	}
}

if(!function_exists('helperConvertDateTimeYmdTodmY')){
	function helperConvertDateTimeYmdTodmY($datetime){
		return Carbon::createFromFormat('Y-m-d H:i:s',$datetime)->format('d/m/Y H:i A');
	}
}

// if(!function_exists('returnDataFormatWise')){
// 	function returnDataFormatWise($data,$dataType){
// 		if($dataType=='eloquent'){
// 			return $data;
// 		}if($dataType=='array'){
// 			return $data->toArray();
// 		}elseif($dataType=='json'){
// 			return json_encode($data);
// 		}
// 	} //func close
// }//if close

if(!function_exists('helperGetAllVehicles')){
	function helperGetAllVehicles(){
		$tempData=Vehicles::orderBy('id','DESC')->get(['id','registration_no']);
		return $tempData;
		// return returnDataFormatWise($tempData,$dataType);
	}//func close
}//if close

if(!function_exists('helperGetAllStates')){
	function helperGetAllStates(){
		$tempData=States::get(['id','name']);
		return $tempData;
		// return returnDataFormatWise($tempData,$dataType);
	}//func close
}//if close

if(!function_exists('helperGetTripUsedDrivers')){
	function helperGetTripUsedDrivers(){
		$driver_ids=TransportTrips::whereNotNull('driver_id')->groupBY('driver_id')
								  ->get(['driver_id'])->pluck('driver_id');
		$driver_ids=($driver_ids)?$driver_ids->toArray():[];
		if(count($driver_ids)>0){
			$tempData=Drivers::whereIn('id',$driver_ids)->get(['id','name','contact']);
		}else{
			$tempData=[];
		}
		return $tempData;
		// return returnDataFormatWise($tempData,$dataType);
	}//func close
}//if close

if(!function_exists('helperGetDriverDetailByID')){
	function helperGetDriverDetailByID($id=''){
		$data=TransportTrips::where('id',$id)->get();
		return $data;
	}//func close
}//if close
