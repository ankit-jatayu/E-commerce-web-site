<div class="modal fade bd-example-modal-md" id="place-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="placeModalTitle">From Station Add</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="name" class="required">Name</label>
                        <input type="text" name="name" class="form-control modal-input" id="name" placeholder="Name" required>
                        <input type="hidden" id="crnt_dropdown_id" value="">
                        <span class="error" id="name_error" style="color:red"></span>
                    </div>
                    <div class="form-group col-md-12">
                        <label class="required">State</label>
                        <select name="place_type" id="place_type"
                                class="form-control modal-input-select select2"  required style="width:100%" 
                        >
                            <option value="">Choose State</option>
                            @if(helperGetAllStates())
                            @foreach(helperGetAllStates() as $k =>$row)
                                <option value="{{$row->name}}">{{$row->name}}</option>
                            @endforeach
                            @endif
                        </select>
                        <span class="error" id="place_type_error" style="color:red"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" onclick="addFromLocationPopup()">Save </button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>