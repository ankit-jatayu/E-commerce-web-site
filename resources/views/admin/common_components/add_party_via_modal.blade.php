<div class="modal fade bd-example-modal-md" id="party-modal" tabindex="-1" role="dialog" 
      aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="partyModalTitle">Consignor Add</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
      </button>
  </div>
  <div class="modal-body">
    <div class="form-row">
        <div class="form-group col-md-12">
           <label for="name">Name<span style="color:red">*</span></label>
           <input type="text" name="name" class="form-control modal-input" id="name" placeholder="Name" required>
           <input type="hidden" id="curnt_dropdown_id" value="">
           <span class="error" id="name_error" style="color:red"></span>
        </div>

        <div class="form-group col-md-12">
            <label>Party Type <span  style="color:red">*</span></label>
            <select name="party_type_id[]" id="party_type_id" 
              class="form-control select2 modal-input-select" style="width: 100%;" 
              required multiple data-placeholder="Choose Party Type"
              >
              @if(!empty($partyTypes))
                @foreach($partyTypes as $k =>$row)   
                  <option value="{{$row->id}}">
                   {{$row->name}}
                  </option>
                @endforeach
              @endif
            </select>
           <span class="error" id="party_type_error" style="color:red"></span>
        </div>
  </div>

<div class="modal-footer">
    <button type="submit" class="btn btn-primary" onclick="addPartyViaPopup()">Save </button>
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
</div>
</div>
</div>
</div>
</div>
