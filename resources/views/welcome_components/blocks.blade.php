<div class="row">
    <div class="col-12 col-xl-12 stretch-card">
        <div class="row flex-grow-1">
          <div class="col-md-3 grid-margin stretch-card">
            <div class="card" style="background-color: #a5f3fc; border: 1px solid #cbd5e1;">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-baseline">
                  <h6 class="card-title mb-0">All Projects</h6>
                  <div class="dropdown mb-2">
                    <button class="btn btn-link p-0" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                    </button>
                    
                  </div>
                </div>
                <div class="row">
                  <div class="col-6 col-md-12 col-xl-5">
                    <h3 class="mb-2" id="numOfProjects">{{$numOfProjects}}</h3>
                    
                  </div>
                  <div class="col-6 col-md-12 col-xl-7">
                    <div id="customersChart" class="mt-md-3 mt-xl-0"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-3 grid-margin stretch-card">
            <div class="card" style="background-color: #c7d2fe; border: 1px solid #cbd5e1;">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-baseline">
                  <h6 class="card-title mb-0">Complete Projects</h6>
                  <div class="dropdown mb-2">
                    <button class="btn btn-link p-0" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                    </button>
                    
                  </div>
                </div>
                <div class="row">
                  <div class="col-6 col-md-12 col-xl-5">
                    <h3 class="mb-2" id="numOfCompleted">{{$numOfCompleted}}</h3>
                    
                  </div>
                  <div class="col-6 col-md-12 col-xl-7">
                    <div id="ordersChart" class="mt-md-3 mt-xl-0"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-3 grid-margin stretch-card">
            <div class="card" style="background-color: #e9d5ff; border: 1px solid #cbd5e1;">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-baseline">
                  <h6 class="card-title mb-0">On Track Projects</h6>
                  <div class="dropdown mb-2">
                    <button class="btn btn-link p-0" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                    </button>
                    
                  </div>
                </div>
                <div class="row">
                  <div class="col-6 col-md-12 col-xl-5">
                    <h3 class="mb-2" id="numOfOnTrack">{{$numOfOnTrack}}</h3>
                    
                  </div>
                  <div class="col-6 col-md-12 col-xl-7">
                    <div id="growthChart" class="mt-md-3 mt-xl-0"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-3 grid-margin stretch-card">
              <div class="card" style="background-color: #fecdd3; border: 1px solid #cbd5e1;">
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-baseline">
                    <h6 class="card-title mb-0">Pending Completion</h6>
                    <div class="dropdown mb-2">
                      <button class="btn btn-link p-0" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                      </button>
                      
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-6 col-md-12 col-xl-5">
                      <h3 class="mb-2" id="numOfPendingCompletion">{{$numOfPendingCompletion}}</h3>
                      
                    </div>
                    <div class="col-6 col-md-12 col-xl-7">
                      <div id="growthChart" class="mt-md-3 mt-xl-0"></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-xl-12 stretch-card">
      <div class="row flex-grow-1">
        <div class="col-md-3 grid-margin stretch-card">
          <div class="card" style="background-color: #fda4af; border: 1px solid #cbd5e1;">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-baseline">
                <h6 class="card-title mb-0">Behind Schedule</h6>
                <div class="dropdown mb-2">
                  <button class="btn btn-link p-0" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                  </button>
                  
                </div>
              </div>
              <div class="row">
                <div class="col-6 col-md-12 col-xl-5">
                  <h3 class="mb-2" id="numOfBehindSchedule">{{$numOfBehindSchedule}}</h3>
                  
                </div>
                <div class="col-6 col-md-12 col-xl-7">
                  <div id="customersChart" class="mt-md-3 mt-xl-0"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-3 grid-margin stretch-card">
          <div class="card" style="background-color: #f0abfc; border: 1px solid #cbd5e1;">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-baseline">
                <h6 class="card-title mb-0">Awaiting Procurement</h6>
                <div class="dropdown mb-2">
                  <button class="btn btn-link p-0" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                  </button>
                  
                </div>
              </div>
              <div class="row">
                <div class="col-6 col-md-12 col-xl-5">
                  <h3 class="mb-2" id="numOfAwaitingProcurement">{{$numOfAwaitingProcurement}}</h3>
                  
                </div>
                <div class="col-6 col-md-12 col-xl-7">
                  <div id="ordersChart" class="mt-md-3 mt-xl-0"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-3 grid-margin stretch-card">
          <div class="card" style="background-color: #fed7aa; border: 1px solid #cbd5e1;">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-baseline">
                <h6 class="card-title mb-0">On-Hold</h6>
                <div class="dropdown mb-2">
                  <button class="btn btn-link p-0" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                  </button>
                  
                </div>
              </div>
              <div class="row">
                <div class="col-6 col-md-12 col-xl-5">
                  <h3 class="mb-2" id="numOfOnHold">{{$numOfOnHold}}</h3>
                 
                </div>
                <div class="col-6 col-md-12 col-xl-7">
                  <div id="growthChart" class="mt-md-3 mt-xl-0"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-3 grid-margin stretch-card">
            <div class="card" style="background-color: #d9f99d; border: 1px solid #cbd5e1;">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-baseline">
                  <h6 class="card-title mb-0">Cancelled Projects</h6>
                  <div class="dropdown mb-2">
                    <button class="btn btn-link p-0" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                    </button>
                    
                  </div>
                </div>
                <div class="row">
                  <div class="col-6 col-md-12 col-xl-5">
                    <h3 class="mb-2" id="numOfCancelled">{{$numOfCancelled}}</h3>
                    
                  </div>
                  <div class="col-6 col-md-12 col-xl-7">
                    <div id="growthChart" class="mt-md-3 mt-xl-0"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
      </div>
    </div>
  </div> 


