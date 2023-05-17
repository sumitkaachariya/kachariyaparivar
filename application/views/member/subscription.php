  

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Subscription</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Subscription</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <a href="<?php echo site_url('member/newsubscription');?>" class="btn btn-primary">New Subscription</a>
            </div>
        </div>

      <div class="row">
          <div class="col-12">

            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Users Subscription and Donation Information table</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="subscription-table" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>SrNo</th>
                    <th>Name</th>
                    <th>Gam</th>
                    <th>Date</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                
                  <?php if(isset($subscriptions)){
                    foreach ($subscriptions as $key => $subscription) {
                  ?>
                  <tr>
                    <td><?php echo $subscription->id; ?></td>
                    <td><?php echo $subscription->name; ?></td>
                    <td><?php echo $subscription->gam; ?></td>
                    <td><?php echo date('d-m-Y',strtotime($subscription->created_at)); ?></td>
                    <td>
                      <a href="<?php echo site_url('member/newsubscription');?>?mobileno=<?php echo $subscription->mobileno?>"><i class="fa fa-eye"></i></a>
                    </td>
                  </tr>
                  <?php } }?>
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>SrNo</th>
                    <th>Name</th>
                    <th>Gam</th>
                    <th>Date</th>
                    <th>Action</th>
                  </tr>
                  </tfoot>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->    
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->