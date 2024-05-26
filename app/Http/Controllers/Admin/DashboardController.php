<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Bills;
use App\Models\Parties;
use App\Models\TransportTrips;
use App\Models\CompanySettings;
use App\Models\Vehicles;
use App\Models\Routes;
use App\Models\Drivers;
use App\Models\DriverAllocateVehicles;

// use App\Models\Tickets;
// use App\Models\Customers;
// use App\Models\Sites;
// use App\Models\Workcategory;
// use App\Models\TicketProducts;
// use App\Models\Parts;



class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(){
        $viewData=[];
        // $vehicleData = helperGetAllVehicles('eloquent'); //return type can be eloquent|array|json
        
       
        // $viewData['total_vehicle'] = Vehicles::where('type','=','owner')->count();
        // $viewData['total_driver'] = Drivers::where('status',1)->count();
        // $viewData['total_trip'] = TransportTrips::count();
        // $viewData['market_trip'] = TransportTrips::where('is_market_lr',1)->count();
        // $viewData['bill_pending_trip'] = TransportTrips::where('bill_id',0)->count();
        // $viewData['bill_pending'] = Bills::where('remain_amount','>',0)->count();
        
        // $viewData['part_count'] = Parts::where('status',1)->count();
        
        // $viewData['pending_ticket'] = Tickets::where('ticket_status','Pending')->count();
        // $viewData['alloted_ticket'] = Tickets::where('ticket_status','Allotted')->count();
        // $viewData['partial_ticket'] = Tickets::where('ticket_status','Partial completed')->count();
        // $viewData['completed_ticket'] = Tickets::where('ticket_status','Completed')->count();

        return view('admin.dashboard.dashboard',$viewData);
    }

    function testFuelApi(){
        $url = "http://sidcdvmyretail.in.ril.com/StatusService/api/StatusService/ReverseAPIDetails";
        $data = array(
                        "Username"      => "e9S4fPHYusUgC6rDmzGOIg==",
                        "Password"      => "5kKbmFnvu+cNed6cBraaMw==",
                        "SFCustomerID"  => "NM00011679",
                        "RILCustomerID" => "646843",
                        "FromDate"      => "28/07/2021",
                        "ToDate"        => "30/07/2021",
                        "MobileNo"      => "",
                        "CardNumber"    => "",
                        "VehicleFormType"=> "M",
                    );
        $data=["ReverseAPIRequestDtls"=>$data];
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Enable verbose output
        curl_setopt($ch, CURLOPT_VERBOSE, true);

        // Capture the verbose output in a variable
        $verbose = fopen('php://temp', 'w+');
        curl_setopt($ch, CURLOPT_STDERR, $verbose);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'cURL error: ' . curl_error($ch);
        } else {
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($httpCode != 200) {
                echo 'HTTP status code: ' . $httpCode;
            }
            // print_r('<pre>');
            // print_r($response);
        }

        curl_close($ch);

        // Rewind the verbose output handle and output its contents
        rewind($verbose);
        $verboseLog = stream_get_contents($verbose);
        echo "Verbose information:\n<pre>", htmlspecialchars($verboseLog), "</pre>";
        fclose($verbose);

    }

}
