<div class="modal fade bd-example-modal-md" id="consigner-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Consigner Add</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
      </button>
  </div>
  <div class="modal-body">
    <div class="form-row">
            <div class="form-group col-md-6">
             <label for="c_name">Company Name<span style="color:red">*</span></label>
             <input type="text" name="c_name" class="form-control modal-input" id="c_name" placeholder="Company Name" required>
             @error('c_name')
             <label class="invalid-feedback" style="display: block;">{{ $message }}</label>
             @enderror
         </div>
         <div class="form-group col-md-6">
            <label for="g_no">GST No<span style="color:red">*</span></label>
            <input type="text" name="g_no" class="form-control" id="g_no" placeholder="GST No" required>
            @error('g_no')
            <label class="invalid-feedback" style="display: block;">{{ $message }}</label>
            @enderror
        </div>
        <div class="form-group col-md-12">
            <label> Address<span style="color:red">*</span></label>
            <textarea  name="consigner_address" class="form-control" id="consigner_address" placeholder="Please provide Address"></textarea>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-primary" onclick="addConsignerPopup()">Save </button>
</div>
</div>
</div>
</div>
 