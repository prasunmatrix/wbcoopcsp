<?php $getAllRoles = Helper::getRolePermissionPages(); ?>

<!-- sidebar: style can be found in sidebar.less -->
<section class="sidebar">
    <!-- Sidebar user panel -->
    <div class="user-panel">
        <div class="pull-left image">
            <img src="<?php echo e(asset('js/admin/dist/img/avatar5.png')); ?>" class="img-circle" alt="<?php echo e(Auth::guard('admin')->user()->full_name); ?>">
        </div>
        <div class="pull-left info">
            <p><?php echo e(Auth::guard('admin')->user()->full_name); ?></p>
            <?php if(isset(Auth::guard('admin')->user()->userProfile->unique_id)): ?>
                <p>Unique Id: <?php echo e(@Auth::guard('admin')->user()->userProfile->unique_id); ?></p>
            <?php endif; ?>
        </div>
    </div>


    <ul class="sidebar-menu" data-widget="tree">
        <li class="header"><strong>MAIN MENU</strong></li>
        <li <?php if(Route::current()->getName() == 'admin.dashboard'): ?>class="active" <?php elseif(session('password_changed') || session('pacs_acknowledge')): ?> class="disabled" <?php endif; ?>>
            <a href="<?php echo e(route('admin.dashboard')); ?>">
                <i class="fa fa-dashboard"></i><span>Dashboard</span>
            </a>
        </li>


    <?php if( (Auth::guard('admin')->user()->user_type==0) ): ?>
        <li class="treeview <?php if(Route::current()->getName() == 'admin.contactus.list' || Route::current()->getName() == 'admin.contactus.contact-us-details' || Route::current()->getName() == 'admin.CMS.list' || Route::current()->getName() == 'admin.CMS.edit' || Route::current()->getName() == 'admin.newsletter.list' || Route::current()->getName() == 'admin.site-settings' || Route::current()->getName() == 'admin.enquiry.list' || Route::current()->getName() == 'admin.enquiry.view' || Route::current()->getName() == 'admin.enquiry.delete' || Route::current()->getName() == 'admin.share.list' || Route::current()->getName() == 'admin.share.view' || Route::current()->getName() == 'admin.social.list' || Route::current()->getName() == 'admin.social.view' || Route::current()->getName() == 'admin.design.list' || Route::current()->getName() == 'admin.design.view' || Route::current()->getName() == 'admin.enquiryfabarc.list' || Route::current()->getName() == 'admin.enquiryfabarc.view' || Route::current()->getName() == 'admin.generalenquiry.list' || Route::current()->getName() == 'admin.generalenquiry.view' || Route::current()->getName() == 'admin.city.list' || Route::current()->getName() == 'admin.city.add' || Route::current()->getName() == 'admin.city.edit' || Route::current()->getName() == 'admin.state.add' || Route::current()->getName() == 'admin.state.list' || Route::current()->getName() == 'admin.state.edit' || Route::current()->getName() == 'admin.societie.list'  || Route::current()->getName() == 'admin.societie.edit' || Route::current()->getName() == 'admin.societie.add'): ?> menu-open <?php endif; ?>">
            <a href="#">
            <i class="fa fa-list-alt" aria-hidden="true"></i>
                <span>Website Management</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu" <?php if(Route::current()->getName() == 'admin.CMS.list' || Route::current()->getName() == 'admin.site-settings' || Route::current()->getName() == 'admin.district.list' || Route::current()->getName() == 'admin.block.list' || Route::current()->getName() == 'admin.serviceprovider.list' || Route::current()->getName() == 'admin.district.add' || Route::current()->getName() == 'admin.district.edit' || Route::current()->getName() == 'admin.block.add' || Route::current()->getName() == 'admin.block.edit' || Route::current()->getName() == 'admin.serviceprovider.add' || Route::current()->getName() == 'admin.serviceprovider.edit' || Route::current()->getName() == 'admin.societie.list'  || Route::current()->getName() == 'admin.societie.edit' || Route::current()->getName() == 'admin.societie.add'): ?> style="display: block;" <?php endif; ?>>

                <?php if( (Auth::guard('admin')->user()->user_type==0) || (in_array('admin.site-settings',$getAllRoles)) ): ?>
                
                <li <?php if(Route::current()->getName() == 'admin.site-settings'): ?>class="active" <?php endif; ?>>
                        <a href="<?php echo e(route('admin.site-settings')); ?>">
                            <i class="fa fa-cog" aria-hidden="true"></i><span>Site Settings</span>
                        </a>
                    </li>
                
                <!-- District management Start -->

             <li <?php if(Route::current()->getName() == 'admin.district.list' || Route::current()->getName() == 'admin.district.add' || Route::current()->getName() == 'admin.district.edit'): ?>class="active" <?php endif; ?>><a href="<?php echo e(route('admin.district.list')); ?>"><i class="fa fa-area-chart"></i>District</a></li>

            <!-- District management End -->

        <!-- Block management Start -->
            

        <!-- Block management End -->

        <!-- serviceprovider management Start -->
            <li <?php if(Route::current()->getName() == 'admin.serviceprovider.list'  || Route::current()->getName() == 'admin.serviceprovider.edit' || Route::current()->getName() == 'admin.serviceprovider.add'): ?>class="active" <?php endif; ?>><a href="<?php echo e(route('admin.serviceprovider.list')); ?>"><i class="fa fa-wrench"></i>Service Provider</a></li>

        <!-- serviceprovider management End -->
        <!-- societie management Start -->
        <li <?php if(Route::current()->getName() == 'admin.societie.list'  || Route::current()->getName() == 'admin.societie.edit' || Route::current()->getName() == 'admin.societie.add'): ?>class="active" <?php endif; ?>><a href="<?php echo e(route('admin.societie.list')); ?>"><i class="fa fa-home"></i>Society Type</a></li>

<!-- societie management End -->
                <?php endif; ?>
                       
            </ul>
        </li>
    

        <!-- User management Start -->
    
        <li class="treeview <?php if(Route::current()->getName() == 'admin.bank.list' || Route::current()->getName() == 'admin.bank.edit' || Route::current()->getName() == 'admin.bank.add' || Route::current()->getName() == 'admin.zone.list' || Route::current()->getName() == 'admin.zone.edit' || Route::current()->getName() == 'admin.zone.add' || Route::current()->getName() == 'admin.range.list' || Route::current()->getName() == 'admin.range.edit' || Route::current()->getName() == 'admin.range.add' || Route::current()->getName() == 'admin.pacs.list'  || Route::current()->getName() == 'admin.pacs.edit' || Route::current()->getName() == 'admin.pacs.add'): ?> menu-open <?php endif; ?>">
            <a href="#">
            <i class="fa fa-user" aria-hidden="true"></i>
                <span>User Management</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu" <?php if(Route::current()->getName() == 'admin.bank.list' || Route::current()->getName() == 'admin.bank.edit' || Route::current()->getName() == 'admin.bank.add' || Route::current()->getName() == 'admin.zone.list' || Route::current()->getName() == 'admin.zone.edit' || Route::current()->getName() == 'admin.zone.add' || Route::current()->getName() == 'admin.range.list' || Route::current()->getName() == 'admin.range.edit' || Route::current()->getName() == 'admin.range.add' || Route::current()->getName() == 'admin.pacs.list'  || Route::current()->getName() == 'admin.pacs.edit' || Route::current()->getName() == 'admin.pacs.add'): ?> style="display: block;" <?php endif; ?>>

            <li <?php if(Route::current()->getName() == 'admin.bank.list' || Route::current()->getName() == 'admin.bank.edit' || Route::current()->getName() == 'admin.bank.add'): ?>class="active" <?php endif; ?>><a href="<?php echo e(route('admin.bank.list')); ?>"><i class="fa fa-university"></i>Bank</a></li>
            <li <?php if(Route::current()->getName() == 'admin.zone.list' || Route::current()->getName() == 'admin.zone.edit' || Route::current()->getName() == 'admin.zone.add'): ?>class="active" <?php endif; ?>><a href="<?php echo e(route('admin.zone.list')); ?>"><i class="fa fa-list"></i>Zone</a></li>
            <li <?php if(Route::current()->getName() == 'admin.range.list' || Route::current()->getName() == 'admin.range.edit' || Route::current()->getName() == 'admin.range.add'): ?>class="active" <?php endif; ?>><a href="<?php echo e(route('admin.range.list')); ?>"><i class="fa fa fa-map-marker"></i>Range</a></li>
            <li <?php if(Route::current()->getName() == 'admin.pacs.list'  || Route::current()->getName() == 'admin.pacs.edit'): ?>class="active" <?php endif; ?>><a href="<?php echo e(route('admin.pacs.list')); ?>"><i class="fa fa-wrench"></i>PACS</a></li>
                       
            </ul>
        </li>
    <!-- User management End -->

    
    
        <?php endif; ?>
        <?php if( (Auth::guard('admin')->user()->user_type==3) ): ?>
        <li <?php if(Route::current()->getName() == 'admin.range.pacs'  || Route::current()->getName() == 'admin.range.pacsedit' || Route::current()->getName() == 'admin.range.pacsadd'): ?>class="active"  <?php endif; ?>><a href="<?php echo e(route('admin.range.pacs')); ?>"><i class="fa fa-wrench"></i>PACS</a></li>
        <?php endif; ?>
        
        <?php if( (Auth::guard('admin')->user()->user_type==1) ): ?>
        <li <?php if(Route::current()->getName() == 'admin.bank.pacslist'): ?>class="active"  <?php endif; ?>><a href="<?php echo e(route('admin.bank.pacslist')); ?>"><i class="fa fa-wrench"></i>PACS</a></li>
        <?php endif; ?>

        <li <?php if(Route::current()->getName() == 'admin.complain.list'  || Route::current()->getName() == 'admin.complain.edit' || Route::current()->getName() == 'admin.complain.add'): ?>class="active" <?php elseif(session('password_changed') || session('pacs_acknowledge')): ?> class="disabled" <?php endif; ?>><a href="<?php echo e(route('admin.complain.list')); ?>"><i class="fa fa-cc"></i>Complain Raised</a></li>
        
        <?php if( (Auth::guard('admin')->user()->user_type!=0) ): ?>
        <li <?php if(Route::current()->getName() == 'admin.complain.alllreply'): ?>class="active" <?php elseif(session('password_changed') || session('pacs_acknowledge')): ?> class="disabled" <?php endif; ?>><a href="<?php echo e(route('admin.complain.alllreply')); ?>"><i class="fa fa-list"></i>Complain Received</a></li>
        <?php endif; ?>
        
        <?php if( (Auth::guard('admin')->user()->user_type==0) ): ?>
        <li <?php if(Route::current()->getName() == 'admin.complain.alllist'): ?>class="active" <?php elseif(session('password_changed') || session('pacs_acknowledge')): ?> class="disabled" <?php endif; ?>><a href="<?php echo e(route('admin.complain.alllist')); ?>"><i class="fa fa-list"></i>All Complain</a></li>
        <?php endif; ?>
    <!-- complain management End -->
    
    <?php if( (Auth::guard('admin')->user()->user_type==4) ): ?>
    <li <?php if(Route::current()->getName() == 'admin.pacs.pacsAcknowledement'): ?>class="active" <?php endif; ?>><a href="<?php echo e(route('admin.pacs.pacsAcknowledement')); ?>"><i class="fa fa-cc"></i>Acknowledement</a></li>
    <?php endif; ?>
</ul>

</section>

<style type="text/css">
    .disabled {
    pointer-events:none; //This makes it not clickable
    opacity:2;         //This grays it out to look disabled
}
</style>
<!-- /.sidebar --><?php /**PATH C:\xampp\htdocs\matrixmedia\wbcoopcsp\resources\views/admin/elements/sidebar.blade.php ENDPATH**/ ?>