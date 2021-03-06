@extends('admins.layouts.app')

@section('content')

<link rel="stylesheet" href="{{asset('public/admin_assets/plugins/dataTables/jquery.dataTables.min.css')}}">

<!-- Content Wrapper. Contains page content -->

  <div class="content-wrapper">

    <!-- Content Header (Page header) -->

    <section class="content-header">

    @if(@$approval!=1)
     <h1>
         Vendor List
      </h1>

    @else
     <h1>
        Vendor List For Approval
       
      </h1>

    @endif
     

      <ol class="breadcrumb">
        <li><a href="{{ url('employees/dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      </ol>

    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
          <div class="box">
            <!-- <div class="box-header">
              <h3 class="box-title"><a class="btn btn-info" href='{{ url("mastertables/projects/add") }}'>Add</a></h3>
            </div> -->
           <div class="box-header">

            @include('admins.validation_errors')

            @if(@$approval!=1)

           

            <form id="project_status_filter" method="GET">

              <div class="row select-detail-below">        
       
        
                  <div class="col-md-2 attendance-column4">
                    
                    <label>Vendor Status</label>

                    <select class="form-control input-sm basic-detail-input-style" name="project_status" id="project_status">
                        
                        <option value="all" selected>All</option>
                        <option value="0">Pending</option>
                        <option value="1">Approved</option>
                        <option value="2">Rejected</option>                              

                    </select>

                  </div>

                  <div class="col-md-2 attendance-column3">

                      <div class="form-group">

                          <button type="submit" class="btn searchbtn-attendance">Search <i class="fa fa-search"></i></button>

                      </div>

                  </div>

              </div>

              <br>

            </form>
             @endif
          </div>

            <!-- /.box-header -->

            <div class="box-body">

              <table id="listProjects" class="table table-bordered table-striped text-center">

                <thead class="table-heading-style">

                <tr>

                  <th>S.No.</th>
          
                  <th>Name Of Firm</th>

                  <th>Type Of Firm</th>

                  <th>Status Of Company</th>
          
                  <th>Email</th>
                  
                  <th>Status</th>                  

                  @if(auth()->user()->can('edit-vendor') || auth()->user()->can('vendor-approval'))

                  <th>Actions</th>

                  @endif                 

                </tr>

                </thead>

                <tbody>

                <?php $counter = 0; 
        
      /* echo"<PRE>";
        print_r($vendors);
        exit;*/
              
        
        ?>  
        @if(isset($vendors) AND ($vendors!=""))

                @foreach($vendors as $key =>$value)  
      
                <tr>

        <td>{{@$loop->iteration}}</td>

        <td>{{@$value['name_of_firm']}}</td>

        <td>{{@$value['type_of_firm']}}</td>

        <td>{{@$value['status_of_company']}}</td>

        <td>{{@$value['email']}}</td>          

        <td>@if($value['vendor_status'] == '0')

        <span class="label label-danger">Pending</span>

        @elseif($value['vendor_status'] == '1')

        <span class="label label-success">Approved</span>

        @elseif($value['vendor_status'] == '2')

        <span class="label label-primary">Rejected</span>

        @endif

        </td>                 

        @if(auth()->user()->can('edit-vendor') || auth()->user()->can('vendor-approval'))

        <td>

        @if((auth()->user()->can('edit-vendor'))  AND ($value['vendor_status'] == '0') AND (auth()->user()->id==$value['supervisor_id']))

            <a class="btn btn-xs bg-purple" href='{{ url("vendor/edit/")."/".$value['id']}}' title="edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>

                @elseif((auth()->user()->can('edit-vendor'))  AND ($value['vendor_status'] == '0') AND (auth()->user()->id!=$value['supervisor_id']))
        
                    <a class="btn btn-xs bg-purple" href='{{ url("vendor/edit/")."/".$value['id']}}' title="edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>&nbsp;

        @endif

        @if(auth()->user()->can('vendor-approval') && $value)
          @if($value['vendor_status'] == '0')
          <a class="btn btn-xs bg-blue approveBtn" href='{{ url("vendor/approve/")."/".$value['id']}}' title="approve"><i class="fa fa-check" aria-hidden="true"></i></a>
          <a class="btn btn-xs bg-red rejectBtn" href='{{ url("vendor/reject/")."/".$value['id']}}' title="reject"><i class="fa fa-close" style="color:black" aria-hidden="true"></i></a>
          <!-- <a class="btn btn-xs bg-red rejectBtn" href='#' title="Send Back" data-toggle="modal" data-target="#project_InfoModal"><i class="fa fa-reply" aria-hidden="true"></i></a> -->

          <a class="btn btn-xs bg-orange" href='{{ url("vendor/show_vendor_detail/".$value['id'])}}' title="show"><i class="fa fa-eye" aria-hidden="true"></i></a>
          @endif
        @endif
        
        </td>

        @endif                 

                </tr>

                @endforeach
        @endif
                </tbody>

                <tfoot class="table-heading-style">

                <tr>

                  <th>S.No.</th>
          
          <th>Name Of Firm</th>

                  <th>Type Of Firm</th>

                  <th>Status Of Company</th>
          
          <th>Email</th>
          
          <td>Status</td>

                   @if(auth()->user()->can('edit-vendor') || auth()->user()->can('vendor-approval'))

                  <th>Actions</th>

                  @endif

                </tr>

                </tfoot>

              </table>

            </div>

            <!-- /.box-body -->

          </div>

          <!-- /.box -->

      </div>

      <!-- /.row -->

      <!-- Main row -->

      <div class="row">

        <!-- Left col -->

        

      </div>

      <!-- /.row (main row) -->



    </section>

    <!-- /.content -->


    <div class="modal fade" id="project_InfoModal">

      <div class="modal-dialog">

        <div class="modal-content">

          <div class="modal-header">

            <button type="button" class="close" data-dismiss="modal" aria-label="Close">

              <span aria-hidden="true">&times;</span></button>

            <h4 class="modal-title">Additional Information</h4>

          </div>

          <!-- <div class="modal-body projectInfoModalBody">
            <form action="{{ url('mastertables/projects/reject/')."/".@$value['id'] }}" type="post" name="send_back">
              {{ csrf_field() }}
              <div class="row">
                <div class="col-sm-12">
                  <label>Detailed Reason:</label><br>
                  <textarea rows="5" style="width: 100%;" name="reason"></textarea>
                </div>
              </div>
              <hr>
              <div class="create-footer text-center">
                <input type="submit" class="btn btn-primary" id="reson_id" value="Send" name="reaon_submit">
              </div>
            </form>
          </div> -->

        </div>
        <!-- /.modal-content -->
      </div>

    <!-- /.modal-dialog -->

    </div>

      <!-- /.modal -->

  </div>

  <!-- /.content-wrapper -->



  <script src="{{asset('public/admin_assets/plugins/dataTables/jquery.dataTables.min.js')}}"></script>

  <script type="text/javascript">

      $(".approveBtn").on('click',function(){
        if (!confirm("Are you sure you want to approve this vendor?")) {
            return false; 
        }else{

        }
      });

       $(".rejectBtn").on('click',function(){
        if (!confirm("Are you sure you want to reject this vendor?")) {
            return false; 
        }
      });

    /*  $(".additionalProjectInfo").on('click',function(){
        var projectId = $(this).data('projectid');

        $.ajax({
          type: "POST",
          url: "{{ url('mastertables/additional-project-info') }}",
          data: {project_id: projectId},
          success: function (result){
            $(".projectInfoModalBody").html(result);
            $('#projectInfoModal').modal('show');
          }
        });
      });*/

      $(document).ready(function() {
          $('#listProjects').DataTable({
            scrollX: true,
            responsive: true
          });
      });

  </script>

  @endsection