<div class="modal fade bd-example-modal-md" id="vehicle-add-modal" tabindex="-1" role="dialog" 
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
           <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Vehicle Add</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
           </div>
           <div class="modal-body">
                <form id="addVehicleFormViaModal" class="need-validation" method="post">
                    <div class="row">
                        <div class="form-group col-md-2">
                            <label for="registration_no" class="required">Registration No</label>
                            <input type="text" name="registration_no" class="form-control modal-input" id="registration_no" placeholder="vehicle No" required style="text-transform:uppercase;">
                            <span class="error" id="registration_no_error" style="color:red"></span>
                        </div>
                        <div class="form-group col-md-2">
                            <label class="required">Vehicle Owner</label>
                            <select name="party_id" id="party_id" class="form-control select2 modal-input" required style="width:100%">
                                <option value="">CHOOSE VEHICLE OWNER</option>       
                                @if(!empty($parties))
                                    @foreach($parties as $k =>$row)   
                                      <option value="{{$row['id']}}" >
                                       {{$row['name']}}
                                      </option>       
                                    @endforeach
                                @endif
                            </select>
                            <span class="error" id="party_id_error" style="color:red"></span>
                        </div>

                        <div class="form-group col-md-2">
                            <label for="type">Type</label>
                            <select name="type" id="type" class="form-control select2 modal-input" style="width:100%">
                                <option value="owner">Owner</option> 
                                <option value="group">Group</option> 
                                <option value="market">Market</option>  
                            </select>
                        </div>

                        <div class="form-group col-md-2">
                            <label for="model_code">Model Code</label>
                            <select name="model_code" id="model_code" class="form-control select2 modal-input"  style="width: 100%">
                           <option value="">CHOOSE MODEL CODE</option>
                                @if(!empty($VehicleModelCodes))
                                    @foreach($VehicleModelCodes as $k =>$singledata)
                                        <option value="{{$singledata->name}}">{{$singledata->name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="form-group col-md-2">
                            <label for="vehicle_alias">Vehicle alias</label>
                            <input type="text" name="vehicle_alias" class="form-control modal-input" id="vehicle_alias" placeholder="vehicle alias">
                        </div>

                        <div class="form-group col-md-2">
                            <label for="registration_date">Registration Date</label>
                            <input type="date" name="registration_date" class="form-control modal-input" id="registration_date">
                        </div>

                        <div class="form-group col-md-2">
                            <label for="rto_auth">RTO auth.</label>
                            <input type="text" name="rto_auth" class="form-control modal-input" id="rto_auth" placeholder="RTO">
                        </div>
                    
                        <div class="form-group col-md-2">
                            <label for="chassis_no">Chassis No.</label>
                            <input type="text" name="chassis_no" class="form-control modal-input" id="chassis_no" placeholder="Chassis no">
                        </div>

                        <div class="form-group col-md-2">
                            <label for="engine_no">Engine No</label>
                            <input type="text" name="engine_no" class="form-control modal-input" id="engine_no" placeholder="Engine no">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="manufacture_year">Manufacture Year</label>
                            <input type="text" name="manufacture_year" class="form-control modal-input" id="manufacture_year" placeholder="Manufacture year">
                        </div>

                        <div class="form-group col-md-2">
                            <label for="manufacture_month">Manufacture Month</label>
                            <input type="text" name="manufacture_month" class="form-control modal-input" id="manufacture_month" placeholder="Manufacture month">
                        </div>

                        <div class="form-group col-md-2">
                            <label for="purchase_date">Purchase Date</label>
                            <input type="date" name="purchase_date" class="form-control modal-input" id="purchase_date">
                        </div>

                        <div class="form-group col-md-2">
                            <label for="purchase_amount">Purchase Amount</label>
                            <input type="text" name="purchase_amount" class="form-control modal-input" id="purchase_amount" placeholder="Purchase amount">
                        </div>
                    
                        <div class="form-group col-md-2">
                            <label for="sale_date">Sale Date</label>
                            <input type="date" name="sale_date" class="form-control modal-input" id="sale_date">
                        </div>

                        <div class="form-group col-md-2">
                            <label for="sale_amount">Sale Amount</label>
                            <input type="text" name="sale_amount" class="form-control modal-input" id="sale_amount" placeholder="Sale amount">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="gvw_in_kg">GVW in kg/kl</label>
                            <input type="text" name="gvw_in_kg" class="form-control modal-input" id="gvw_in_kg" placeholder="gvw in kg">
                        </div>

                        <div class="form-group col-md-2">
                            <label for="ulw_in_kg">ULW in kg/kl</label>
                            <input type="text" name="ulw_in_kg" class="form-control modal-input" id="ulw_in_kg" placeholder="ulw in kg">
                        </div>

                        <div class="form-group col-md-2">
                            <label for="equipment_vehicle">Equipment Vehicle</label>
                            <select name="equipment_vehicle" id="equipment_vehicle" class="form-control modal-input select2" style="width:100%">
                                <option value="0" >No</option> 
                                <option value="1" >Yes</option>   
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="vehicle_type">Vehicle Type</label>
                            <select name="vehicle_type" id="vehicle_type" class="form-control modal-input select2 filter-input-select" style="width:100%">
                               <option value="">CHOOSE VEHICLE TYPE</option>       
                               @if(!empty($VehicleTypes))
                               @foreach($VehicleTypes as $k =>$row)   
                                    <option value="{{$row->name}}">{{$row->name}}</option>       
                               @endforeach
                               @endif
                           </select>
                        </div>
                    
                        <div class="form-group col-md-2">
                            <label for="stephanie">Stephanie</label>
                            <select name="stephanie" id="stephanie" class="form-control modal-input select2" style="width:100%">
                                <option value="No">No</option> 
                                <option value="Yes">Yes</option>   
                            </select>
                        </div>

                        <div class="form-group col-md-2">
                            <label for="fuel">Fuel</label>
                            <select name="fuel" id="fuel" class="form-control modal-input select2" style="width:100%">
                                <option value="petrol">Petrol</option>   
                                <option value="diesel">Diesel</option>   
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="f_t_type">Front Tyre Type</label>
                            <input type="text" name="f_t_type" class="form-control modal-input" id="f_t_type" placeholder="Front Tyre Type" >
                        </div>

                        <div class="form-group col-md-2">
                            <label for="f_size">Front Tyre Size</label>
                            <input type="text" name="f_size" class="form-control modal-input" id="f_size" placeholder="Front Tyre Size" >
                        </div>

                        <div class="form-group col-md-2">
                            <label for="f_total_tyre">Front Total Tyre</label>
                            <input type="text" name="f_total_tyre" class="form-control modal-input" id="f_total_tyre" placeholder="Front Total Tyre" >
                        </div>

                        <div class="form-group col-md-2">
                            <label for="b_t_type">Back Tyre Type</label>
                            <input type="text" name="b_t_type" class="form-control modal-input" id="b_t_type" placeholder="Back Tyre Type" >
                        </div>

                        <div class="form-group col-md-2">
                            <label for="b_size">Back Tyre Size</label>
                            <input type="text" name="b_size" class="form-control modal-input" id="b_size" placeholder="Back Tyre Size" >
                        </div>

                        <div class="form-group col-md-2">
                            <label for="b_total_tyre">Back Total Tyre</label>
                            <input type="text" name="b_total_tyre" class="form-control modal-input" id="b_total_tyre" placeholder="Back Total Tyre" >
                        </div>
                        <div class="form-group col-md-6">
                            <label for="remarks">Remarks</label>
                            <input type="text" name="remarks" class="form-control modal-input" id="remarks" placeholder="Remarks" >
                        </div>
                    </div>
                    <div class="row">
                         <div class="col-md-12 text-right">
                             <button type="submit" class="btn btn-primary" onclick="addVehiclePopup('event')">Save </button>
                             <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                         </div>
                    </div>   
                </form>
           </div>
        </div>
    </div>
</div>
