<div class="card mb-3" style="border: 1px solid #cbd5e1;">
    <div class="card-body">
        <h5 class="mb-4" style="color: #1d4ed8">Search to filter content</h5>
        <div class="row mb-4">
            <div class="col-md-2">
                <label for="" class="form-label">From financial year</label>
                <select class="form-select" id="from">
                    <option>Select...</option>  
                    @foreach ($fYears as $item)
                        <option value="{{$item->id}}">{{$item->year}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label for="" class="form-label">To Financial Year</label>
                <select class="form-select" id="to">
                    <option>Select...</option> 
                    @foreach ($fYears as $item)
                        <option value="{{$item->id}}">{{$item->year}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label for="" class="form-label">Sub-County</label>
                <select class="form-select subCounty" id="subCounty">
                    <option>Select...</option>  
                    @foreach ($subCounties as $item)
                        <option value="{{$item->id}}">{{$item->state}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label for="" class="form-label">Ward</label>
                <select class="form-select ward" id="ward">
                    <option>Select...</option>  
                   
                </select>
            </div>
        </div>
        <div class="row mb-4">
            
            
        </div>
        <div>
            <div class="row">
                <div class="col-md-12 text-center">
                    <button class="btn btn-warning">Reset</button>
                    <button class="btn btn-success" id="filter-btn">Filter</button>
                </div>
            </div>
        </div>
    </div>
</div>

