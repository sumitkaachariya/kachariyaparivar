
<!-- jQuery -->
<script src="<?php echo base_url();?>/assets/cm/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="<?php echo base_url();?>/assets/cm/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Toastr -->
<script src="<?php echo base_url();?>/assets/cm/plugins/toastr/toastr.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url();?>/assets/cm/dist/js/adminlte.min.js"></script>

<script>
  $("#loginbtn").on("click", function(){
    var mobile = $("#mobile").val();
    var password = $("#password").val();
    if(mobile == ''){
      $("#mobile").focus();
      toastr.error('Mobile no Field is required..')
      return false;
    }
    if(password == ''){
      $("#password").focus();
      toastr.error('Password Field is required..')
      return false;
    }
    $.ajax({
      url:'<?php echo base_url();?>index.php/check_auth',
      type:'post',
      dataType: "json",
      headers: {"token": "IAbza1VGScBEesSbcCzdnUoVuy2oQjYj8xFt8X3gOQN6IpBXYoUE5fGrTGRJeqn0OCtmZbF06fG5Z1N5"},
      data:{mobile:mobile,password:password},
      success:function(res){
        console.log(res);
        if(res.code == 200){
          toastr.success(res.message);     
          setTimeout( function(){
            window.location.href = "<?php echo base_url();?>index.php/member/dashboard";
          }, 1000);
        }
        if(res.code == 400){
          toastr.warning(res.message);     
        }
        if(res.code == 404){
          toastr.error(res.message);     
        }
      }
    })
  });
</script>
</body>
</html>
