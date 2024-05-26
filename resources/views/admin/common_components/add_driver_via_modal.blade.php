<div class="modal fade bd-example-modal-md" id="driver-modal" tabindex="-1" role="dialog" 
     aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title" id="exampleModalLabel">Driver Add</h5>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                 <span aria-hidden="true">&times;</span>
               </button>
            </div>
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group col-md-4">
                         <label class="required" for="driver_name">Name</label>
                         <input type="text" name="driver_name" class="form-control modal-input" id="driver_name" placeholder="Name" required>
                         
                         <span class="error" id="driver_name_error" style="color:red"></span>
                    </div>
                    <div class="form-group col-md-4">
                         <label class="required" for="contact">Contact</label>
                         <input type="text" name="contact" class="form-control modal-input integers-only" id="contact" 
                                placeholder="Contact" required maxlength="10">
                         
                         <span class="error" id="contact_error" style="color:red"></span>
                    </div>
                    <div class="form-group col-md-4">
                         <label class="required" for="home_contact">Home Contact</label>
                         <input type="text" name="home_contact" class="form-control modal-input integers-only" id="home_contact" placeholder="Home Contact" required maxlength="10">
                         
                         <span class="error" id="home_contact_error" style="color:red"></span>
                    </div>
                    <div class="form-group col-md-12">
                         <label class="required" for="local_address">Local Address</label>
                         <textarea name="local_address" class="form-control modal-input" id="local_address" placeholder="Local Address" required></textarea>
                         
                         <span class="error" id="local_address_error" style="color:red"></span>
                    </div>
                    <div class="form-group col-md-12">
                         <label class="required" for="permanent_address">Permanent Address</label>
                         <textarea name="permanent_address" class="form-control modal-input" id="permanent_address" placeholder="Permanent Address" required></textarea>
                         <span class="error" id="permanent_address_error" style="color:red"></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" onclick="addDriverPopup()">Save </button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
