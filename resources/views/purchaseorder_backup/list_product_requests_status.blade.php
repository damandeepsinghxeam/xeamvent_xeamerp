@extends('admins.layouts.app')

@section('content')

<link rel="stylesheet" href="{{asset('public/admin_assets/plugins/dataTables/jquery.dataTables.min.css')}}">

<!-- Content Wrapper. Contains page content -->

  <div class="content-wrapper">

    <!-- Content Header (Page header) -->

    <section class="content-header">

    @if(@$approval!=1)
     <h1>
         Product Request Status
      </h1>

    @else
     <h1>
     Product Request Status
       
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
                    
                    <label>Product Request Status</label>

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
          
                  <th>Product Name</th>

                  <th>Product Name (Other)</th>

                  <th>Number Of Items Requested</th>
          
                  <th>Product Description</th>
                  
                  <th>Status</th>                                 

                </tr>

                </thead>

                <tbody>

                <?php $counter = 0; 
        
     /*  echo"<PRE>";
      print_r($requested_product_items);
        exit;*/
                
        ?>  
        @if(isset($requested_product_items) AND ($requested_product_items!=""))

                @foreach($requested_product_items as $key =>$value)  
      
      <tr>

        <td>{{@$loop->iteration}}</td>

        <td>{{@$value['product_name']}}</td>

        <td>{{@$value['others_product_name']}}</td>

        <td>{{@$value['no_of_items_requested']}}</td>

        <td>{{@$value['product_description']}}</td>          

        <td>@if($value['product_requested_status'] == '0')

        <span class="label label-danger">Pending</span>

        @elseif($value['product_requested_status'] == '1')

        <span class="label label-success">Approved</span>

        @elseif($value['product_requested_status'] == '2')

        <span class="label label-primary">Rejected</span>

        @endif
        

        </td>                                

    </tr>

                @endforeach
        @endif
                </tbody>

                <tfoot class="table-heading-style">

                <tr>

                  <th>S.No.</th>
          
                  <th>Product Name</th>

                  <th>Product Name (Other)</th>

                  <th>Number Of Items Requested</th>

                  <th>Product Description</th>               
          
                  <td>Status</td>

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

      $(document).ready(function() {
          $('#listProjects').DataTable({
            scrollX: true,
            responsive: true
          });
      });

  </script>

  @endsection