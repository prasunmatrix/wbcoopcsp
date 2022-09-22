<div class="whole-area" id="whole-area" style="display: none;">
	<div class="loader" id="loader-1"></div>
</div>

<input type="hidden" name="website_admin_link" id="website_admin_link" value="<?php echo e(url('/')); ?>" />

<div class="pull-right hidden-xs">
    <b><?php echo e(Helper::getAppName()); ?> Admin Panel</b>
</div>

<strong>Copyright &copy; <?php echo e(date('Y')); ?>.</strong> All rights reserved.

<?php $sessionData =  session('clicked') ?>

<div class="modal fade" <?php if($sessionData): ?> id="myModalRedirect" <?php else: ?> id="myModalRedirectDone" <?php endif; ?> tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header" style="background: orange">
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="ion-android-close"></span></button> -->
                <?php //echo $sessionData =  session('clicked') ?>
                <h4 class="modal-title" id="myModalLabel" style="color: whitesmoke;">Click on your desire location</h4>
            </div>            <!-- Modal Body -->
            <div class="modal-body clearfix" style="text-align: center;">
                <div class="modal-dashboard" id="modal-dashboard" style="display:inline-block;">
                	<!-- href="<?php echo e(route('admin.dashboard')); ?>" -->

                    <a id="btnDashboard" class="btn btn-default-border-blk" href="<?php echo e(route('admin.dashboard-destroy')); ?>" >Dashboard</a>
                </div>

                <div class="modal-complain" id="modal-complain" style="text-align: center; display:inline-block;">
                	<div><a id="btnComplain" class="btn btn-default-border-blk" href="<?php echo e(route('admin.complain.complain-destroy')); ?>">Complain Raised</a></div>
                </div>

            </div>
        </div>
    </div>

    <?php $__env->startPush('script'); ?>
    <script type="text/javascript">



        $(window).on('load',function(){

        		$('#myModalRedirect').modal('show');


        });
    </script>
    <?php $__env->stopPush(); ?>
<?php /**PATH C:\xampp\htdocs\matrixmedia\wbcoopcsp\resources\views/admin/elements/footer.blade.php ENDPATH**/ ?>