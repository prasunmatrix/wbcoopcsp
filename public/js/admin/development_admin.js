var site_url = $("#website_admin_link").val()+'/securepanel';
var ajax_check = false;

$.validator.addMethod("valid_email", function(value, element) {
    if (/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(value)) {
        return true;
    } else {
        return false;
    }
}, "Please enter a valid email");

//Phone number eg. (+91)9876543210
$.validator.addMethod("valid_number", function(value, element) {
    if (/^(?=.*[0-9])[- +()0-9]+$/.test(value)) {
        return true;
    } else {
        return false;
    }
}, "Please enter a valid phone number");

//minimum 8 digit,small+capital letter,number,specialcharacter
$.validator.addMethod("valid_password", function(value, element) {
    if (/^(?=.*?[a-z])(?=.*?[A-Z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/.test(value)) {
        return true;
    } else {
        return false;
    }
});

//Integer and decimal
$.validator.addMethod("valid_amount", function(value, element) {
    if (/^[1-9]\d*(\.\d+)?$/.test(value)) {
        return true;
    } else {
        return false;
    }
});

// Show edit sub admin
var show_edit_subAdmin  = '/user/subAdmin/edit/';
$(".open_edit_subAdmin").on('click', function(){
    
    var editSubAdminUrl = site_url + show_edit_subAdmin + $(this).val();
    //alert(editSubAdminUrl);
    $('#whole-area').show(); //Showing loader
    if (ajax_check) {
        return;
    }
    ajax_check = true;

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: editSubAdminUrl,
        method: 'GET',
        data: {
            
        },
        success: function (detailsResponse) {
            $('#whole-area').hide(); //Showing loader
            ajax_check = false;
            $('.modal-body').html(detailsResponse);
            $('#edit-sub-admin').modal('show');
        }
    });
});
// Show edit sub admin

// Show edit member
var show_edit_member  = '/user/member/edit/';
$(".open_edit_member").on('click', function(){
    
    var editMemberUrl = site_url + show_edit_member + $(this).val();
    //alert(editSubAdminUrl);
    $('#whole-area').show(); //Showing loader
    if (ajax_check) {
        return;
    }
    ajax_check = true;

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: editMemberUrl,
        method: 'GET',
        data: {
            
        },
        success: function (detailsResponse) {
            $('#whole-area').hide(); //Showing loader
            ajax_check = false;
            $('.modal-body').html(detailsResponse);
            $('#edit-member-user').modal('show');
        }
    });
});
// Show edit member

// Show edit guest
var show_edit_guest  = '/user/guest/edit/';
$(".open_edit_guest").on('click', function(){
    
    var editGuestUrl = site_url + show_edit_guest + $(this).val();
    //alert(editSubAdminUrl);
    $('#whole-area').show(); //Showing loader
    if (ajax_check) {
        return;
    }
    ajax_check = true;

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: editGuestUrl,
        method: 'GET',
        data: {
            
        },
        success: function (detailsResponse) {
            $('#whole-area').hide(); //Showing loader
            ajax_check = false;
            $('.modal-body').html(detailsResponse);
            $('#edit-guest-user').modal('show');
        }
    });
});
// Show edit guest

// Show edit guest
var show_edit_change  = '/user/guest/change-user/';
$(".open_edit_user").on('click', function(){
    
    
    var editUrl = site_url + show_edit_change + $(this).val();
    //alert(editSubAdminUrl);
    $('#whole-area').show(); //Showing loader
    if (ajax_check) {
        return;
    }
    ajax_check = true;

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: editUrl,
        method: 'GET',
        data: {
            
        },
        success: function (detailsResponse) {
            $('#whole-area').hide(); //Showing loader
            ajax_check = false;
            $('.modal-body').html(detailsResponse);
            $('#edit-guest-change').modal('show');
        }
    });
});
// Show edit guest



$(document).ready(function() {
    /* Admin Login Form */
    $("#adminLoginForm").validate({
        rules: {
            email: {
                required: true,
                valid_email: true
            },
            password: {
                required: true
            }
        }
    });

    /* Admin Profile Update */
    $("#updateAdminProfile").validate({
        rules: {
            full_name: {
                required: true,
                minlength: 2,
                maxlength: 255
            },
            phone_no: {
                required: true,
            },
        },
        messages: {
            full_name: {
                required: "Please enter full name",
                minlength: "Full name should be atleast 2 characters",
                maxlength: "Full name must not be more than 255 characters"
            },
            phone_no: {
                required: "Please enter phone number",
            },
        },
        submitHandler: function(form) {
            form.submit();
        }
    });

    /* Admin Password Update */
    $("#updateAdminPassword").validate({
        rules: {
            current_password: {
                required: true,
                minlength: 8
            },
            password: {
                required: true,
                valid_password: true,
            },
            confirm_password: {
                required: true,
                valid_password: true,
                equalTo: "#password"
            }
        },
        messages: {
            current_password: {
                required: "Please enter current password",
                minlength: "Password should be atleast 8 characters"
            },
            password: {
                required: "Please enter new password",
                valid_password: "Password should be minimum 8 characters, at least one lowercase letter, at least one uppercase letter, one number and one special character"
            },
            confirm_password: {
                required: "Please enter confirm password",
                valid_password: "Password should be minimum 8 characters, at least one lowercase letter, at least one uppercase letter, one number and one special character",
                equalTo: "Password should be same as new password",
            }
        },
        submitHandler: function(form) {
            form.submit();
        }
    });

    /* User Profile Add*/
    $("#addUserProfile").validate({
        rules: {
            full_name: {
                required: true,
                minlength: 2,
                maxlength: 255
            },
            email: {
                required: true,
                required: '#phone_no:blank',
                valid_email: function() {
                    if ($("#email").val() != '') {
                        return true;
                    }
                }
            },
            phone_no: {
                required: true,
                required: '#email:blank',
                valid_number: function() {
                    if ($("#phone_no").val() != '') {
                        return true;
                    }
                }
            },
            password: {
                required: true,
                valid_password: true,
            },
            confirm_password: {
                required: true,
                valid_password: true,
                equalTo: "#password"
            },
            user_type: {
                required: true
            },
            back_role_id: {
                required: true
            },
            "front_role_id[]": {
                required: true
            }
        },
        messages: {
            full_name: {
                required: "Please enter name",
                minlength: "Name should be atleast 2 characters",
                maxlength: "Name must not be more than 255 characters"
            },

            email: {
                required: "Please enter email",
            },
            phone_no: {
                required: "Please enter phone number",
            },
            password: {
                required: "Please enter new password",
                valid_password: "Password should be minimum 8 characters, at least one lowercase letter, at least one uppercase letter, one number and one special character"
            },
            confirm_password: {
                required: "Please enter confirm password",
                valid_password: "Password should be minimum 8 characters, at least one lowercase letter, at least one uppercase letter, one number and one special character",
                equalTo: "Password should be same as new password",
            },
            back_role_id: {
                required: "Please select any role"
            },
            "front_role_id[]": {
                required: "Please select any role"
            }
        },
        errorPlacement: function(error, element) {
            if ($(element).attr('type') == 'checkbox') {
                error.insertAfter($(element).parents('div.form-control'));
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function(form) {
            form.submit();
        }
    });

    /* Sub-Admin Add*/
    $("#sub-admin-form").validate({
        rules: {
            full_name: {
                required: true,
                minlength: 2,
                maxlength: 255
            },
            email: {
                required: true,
                valid_email: true
            },
            phone_no: {
                required: true,
                valid_number: true
            },
            password: {
                required: true,
                valid_password: true,
            },
            confirm_password: {
                required: true,
                valid_password: true,
            },
            role_id: {
                required: true
            },
        },
        messages: {
            full_name: {
                required: "Please enter name",
                minlength: "Name should be atleast 2 characters",
                maxlength: "Name must not be more than 255 characters"
            },
            email: {
                required: "Please enter email",
            },
            phone_no: {
                required: "Please enter phone number",
            },
            password: {
                required: "Please enter new password",
                valid_password: "Password should be minimum 8 characters, at least one lowercase letter, at least one uppercase letter, one number and one special character"
            },
            confirm_password:{
                required: "Please enter new password",
                valid_password: "Password should be minimum 8 characters, at least one lowercase letter, at least one uppercase letter, one number and one special character"
            },
            role_id: {
                required: "Please select any role"
            },
        },
        errorPlacement: function(error, element) {
            if ($(element).attr('type') == 'checkbox') {
                error.insertAfter($(element).parents('div.form-control'));
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function(form) {
            form.submit();
        }
    });
    /* Sub-Admin Add*/

    /* Sub-Admin Edit*/
    $("#edit_sub_admin_details").validate({
        rules: {
            full_name: {
                required: true,
                minlength: 2,
                maxlength: 255
            },
            email: {
                required: true,
                valid_email: true
            },
            phone_no: {
                required: true,
                valid_number: true
            },
        },
        messages: {
            full_name: {
                required: "Please enter name",
                minlength: "Name should be atleast 2 characters",
                maxlength: "Name must not be more than 255 characters"
            },
            email: {
                required: "Please enter email",
            },
            phone_no: {
                required: "Please enter phone number",
            },
        },
        errorPlacement: function(error, element) {
            if ($(element).attr('type') == 'checkbox') {
                error.insertAfter($(element).parents('div.form-control'));
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function(form) {
            form.submit();
        }
    });
    /* Sub-Admin Add*/

    /* User Profile Update*/
    $("#updateUserProfile").validate({
        rules: {
            full_name: {
                required: true,
                minlength: 2,
                maxlength: 255
            },
            email: {
                required: true,
                required: '#phone_no:blank',
                valid_email: function() {
                    if ($("#email").val() != '') {
                        return true;
                    }
                }
            },
            phone_no: {
                required: true,
                required: '#email:blank',
                valid_number: function() {
                    if ($("#phone_no").val() != '') {
                        return true;
                    }
                }
            },
            password: {
                valid_password: function() {
                    if ($("#password").val() != '') {
                        return true;
                    }
                }
            },

            //wholesaler validation
            wholesaler_full_name :{
                required: true,
                minlength: 2,
                maxlength: 255
            },
            wholesaler_mobile_number:{
                required: true,
                valid_number: function() {
                    if ($("#phone_no").val() != '') {
                        return true;
                    }
                }
            },
            wholesaler_shop_name: {
                required: true,
                minlength: 2,
                maxlength: 255
            },
            wholesaler_shop_address: {
                required: true,
                minlength: 2,
                maxlength: 255
            },
            wholesaler_shop_mobile_number: {
                required: true,
                valid_number: function() {
                    if ($("#phone_no").val() != '') {
                        return true;
                    }
                }
            },
            //contractor validation
            contractor_full_name: {
                required: true,
                minlength: 2,
                maxlength: 255
            },
            contractor_mobile_number: {
                required: true,
                valid_number: function() {
                    if ($("#phone_no").val() != '') {
                        return true;
                    }
                }
            },
            
            back_role_id: {
                required: "Please select any role"
            }
        },
        messages: {
            full_name: {
                required: "Please enter full name",
                minlength: "Full name should be atleast 2 characters",
                maxlength: "Full name must not be more than 255 characters"
            },
            
            email: {
                required: "Please enter email",
            },
            phone_no: {
                required: "Please enter phone number",
            },
            password: {
                required: "Please enter new password",
                valid_password: "Password should be minimum 8 characters, at least one lowercase letter, at least one uppercase letter, one number and one special character"
            },

            //wholesaler validation
            wholesaler_full_name :{
                required: "Please enter wholesaler full name",
                minlength: "Wholesaler full name should be atleast 2 characters",
                maxlength: "Wholesaler full name must not be more than 255 characters"
            },
            wholesaler_mobile_number:{
                required: "Please enter valid wholesaler mobile name",
               
            },
            wholesaler_shop_name: {
                required: "Please enter wholesaler shop name",
                minlength: "Wholesaler shop name should be atleast 2 characters",
                maxlength: "Wholesaler shop name must not be more than 255 characters"
            },
            wholesaler_shop_address: {
                required: "Please enter wholesaler shop address",
                minlength: "Wholesaler shop address should be atleast 2 characters",
                maxlength: "Wholesaler shop address must not be more than 255 characters"
            },
            wholesaler_shop_mobile_number: {
                required: "Please enter valid shop mobile name",
            },

            //contractor validation
            contractor_full_name: {
                required: "Please enter contractor full name",
                minlength: "Contractor full name should be atleast 2 characters",
                maxlength: "Contractor full name must not be more than 255 characters"
            },
            contractor_mobile_number: {
                required: "Please enter valid contractor mobile name",
            }, 

            back_role_id: {
                required: "Please select any role"
            }
        },
        submitHandler: function(form) {
            form.submit();
        }
    });

    

    /* User Password Update */
    $("#updateUserPassword").validate({
        rules: {
            password: {
                required: true,
                valid_password: true,
            },
            confirm_password: {
                required: true,
                valid_password: true,
                equalTo: "#password"
            }
        },
        messages: {
            password: {
                required: "Please enter new password",
                valid_password: "Password should be minimum 8 characters, at least one lowercase letter, at least one uppercase letter, one number and one special character"
            },
            confirm_password: {
                required: "Please enter confirm password",
                valid_password: "Password should be minimum 8 characters, at least one lowercase letter, at least one uppercase letter, one number and one special character",
                equalTo: "Password should be same as new password",
            }
        },
        submitHandler: function(form) {
            form.submit();
        }
    });

    /* Site Settings */
    $("#updateSiteSettingsForm").validate({
        rules: {
            from_email: {
                required: true,
                valid_email: true
            },
            to_email: {
                required: true,
                valid_email: true
            },
            order_email: {
                required: true,
                valid_email: true
            },
            website_title: {
                required: true,
                minlength: 2,
                maxlength: 255
            },
            website_link: {
                required: true,
                minlength: 2,
                maxlength: 255
            },
            vat_amount: {
                required: true,
                valid_amount: true
            }
        },
        messages: {
            from_email: {
                required: "Please enter from email",
            },
            to_email: {
                required: "Please enter to email",
            },
            order_email: {
                required: "Please enter order email",
            },
            website_title: {
                required: "Please enter website title",
                minlength: "Website title should be atleast 2 characters",
                maxlength: "Website title must not be more than 255 characters"
            },
            website_link: {
                required: "Please enter website link",
                minlength: "Website link should be atleast 2 characters",
                maxlength: "Website link must not be more than 255 characters"
            },
            vat_amount: {
                required: "Please enter vat amount",
                valid_amount: "Please enter valid amount"
            }
        },
        errorPlacement: function(error, element) {
            if ($(element).attr('id') == 'vat_amount') {
                error.insertAfter($(element).parents('div#vatamount'));
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function(form) {
            form.submit();
        }
    });

    /* Add Faq */
    $("#addFaqForm").validate({
        ignore: [],
        debug: false,
        rules: {
            title: {
                required: true,
                minlength: 2,
                maxlength: 255
            },
            title_ar: {
                required: true,
                minlength: 2,
                maxlength: 255
            },
            description: {
                required: function() {
                    CKEDITOR.instances.description.updateElement();
                },
                minlength: 10
            },
            description_ar: {
                required: function() {
                    CKEDITOR.instances.description_ar.updateElement();
                },
                minlength: 10
            }
        },
        messages: {
            title: {
                required: "Please enter title",
                minlength: "Title should be atleast 2 characters",
                maxlength: "Title must not be more than 255 characters"
            },
            title_ar: {
                required: "Please enter title (arabic)",
                minlength: "Title (arabic) should be atleast 2 characters",
                maxlength: "Title (arabic) must not be more than 255 characters"
            },
            description: {
                required: "Please enter description",
                minlength: "Please enter 10 characters"
            },
            description_ar: {
                required: "Please enter description (arabic)",
                minlength: "Please enter 10 characters"
            }
        },
        submitHandler: function(form) {
            form.submit();
        }
    });

    /* Edit Faq */
    $("#updateFaqForm").validate({
        ignore: [],
        debug: false,
        rules: {
            title: {
                required: true,
                minlength: 2,
                maxlength: 255
            },
            title_ar: {
                required: true,
                minlength: 2,
                maxlength: 255
            },
            description: {
                required: function() {
                    CKEDITOR.instances.description.updateElement();
                },
                minlength: 10
            },
            description_ar: {
                required: function() {
                    CKEDITOR.instances.description_ar.updateElement();
                },
                minlength: 10
            }
        },
        messages: {
            title: {
                required: "Please enter title",
                minlength: "Title should be atleast 2 characters",
                maxlength: "Title must not be more than 255 characters"
            },
            name_ar: {
                required: "Please enter title (arabic)",
                minlength: "Title (arabic) should be atleast 2 characters",
                maxlength: "Title (arabic) must not be more than 255 characters"
            },
            description: {
                required: "Please enter description",
                minlength: "Please enter 10 characters"
            },
            description_ar: {
                required: "Please enter description (arabic)",
                minlength: "Please enter 10 characters"
            }
        },
        submitHandler: function(form) {
            form.submit();
        }
    });

    /* Edit Cms*/
    $("#updateCmsForm").validate({
        ignore: [],
        debug: false,
        rules: {
            title_en: {
                required: true,
                minlength: 2,
                maxlength: 255
            },
            title_ar: {
                required: true,
                minlength: 2,
                maxlength: 255
            },
            description_en: {
                required: function() {
                    CKEDITOR.instances.description_en.updateElement();
                },
                minlength: 10
            },
            description_ar: {
                required: function() {
                    CKEDITOR.instances.description_ar.updateElement();
                },
                minlength: 10
            }
        },
        messages: {
            title_en: {
                required: "Please enter page name",
                minlength: "Page name should be atleast 2 characters",
                maxlength: "Page name must not be more than 255 characters"
            },
            title_ar: {
                required: "Please enter title (arabic)",
                minlength: "Page name (arabic) should be atleast 2 characters",
                maxlength: "Page name (arabic) must not be more than 255 characters"
            },
            description_en: {
                required: "Please enter description (English)"
            },
            description_ar: {
                required: "Please enter description (Arabic)"
            },
        },
        submitHandler: function(form) {
            form.submit();
        }
    });

    /* Role Form */
    $("#role-manage-form").validate({
        rules: {
            role_name: {
                required: true,
                minlength: 2,
                maxlength: 255
            }
        },
        messages: {
            role_name: {
                required: "Please enter role name",
                minlength: "Page name should be atleast 2 characters",
                maxlength: "Page name must not be more than 255 characters"
            }
        },
        submitHandler: function(form) {
            form.submit();
        }
    });
    /* Add Faq */
    $("#addBannersForm").validate({
        
        debug: false,
        rules: {
            upload_web_banner: {
                required: true, 
                accept: "jpg|png|jpeg|svg", 
                filesize: 5048576 
            },
            upload_app_banner: {
                required: true, 
                accept: "jpg|png|jpeg|svg", 
                filesize: 5048576 
            },
            
        },
        messages: {
            upload_web_banner: {
                required: "Please select image",
                accept:   "File must be as jpg, jpeg, png or svg",
                filesize: "File size not more then 5mb"
            },
            upload_app_banner: {
                required: "Please select image",
                accept:   "File must be as jpg, jpeg, png or svg",
                filesize: "File size not more then 5mb"
            },
            
        },
        submitHandler: function(form) {
            form.submit();
        }
    });

    /* Member Profile Update */
    $("#edit_member_details").validate({
        ignore: [],
        debug: false,
        rules: {
            full_name: {
                required: true,
                minlength: 2,
                maxlength: 255
            },
            phone_no: {
                required: true,
            },
            email: {
                required:true,
            },
        },
        messages: {
            full_name: {
                required: "Please enter full name",
                minlength: "Full name should be atleast 2 characters",
                maxlength: "Full name must not be more than 255 characters"
            },
            phone_no: {
                required: "Please enter phone number",
            },
            email: {
                required: "Please enter email",
            }
        },
        submitHandler: function(form) {
            form.submit();
        }
    });
    /* Add Event  */
    $("#addEventForm").validate({
        ignore: [],
        debug: false,
        rules: {
            event_name: {
                required: true,
                minlength: 2,
                maxlength: 255
            },
            event_location: {
                required:true,
            },
            event_start_date: {
                required:true,
            },
            event_end_date: {
                required:true,
            },
            event_start_time: {
                required:true,
            },
            event_end_time: {
                required:true,
            },
            event_banner: {
                required:true,
            },
        },
        messages: {
            event_name: {
                required: "Please enter name",
                minlength: "Event name should be atleast 2 characters",
                maxlength: "Event name must not be more than 255 characters"
            },
            event_location: {
                required: "Please enter event location",
            },
            event_start_date: {
                required: "Please enter event start date",
            },
            event_end_date: {
                required: "Please enter event end date",
            },
            event_start_time: {
                required: "Please enter event start time",
            },
            event_end_time: {
                required: "Please enter event end time",
            },
            event_banner: {
                required: "Please enter event image",
            },

        },
        submitHandler: function(form) {
            form.submit();
        }
    });

    /* Edit Event  */
    $("#editEventForm").validate({
        ignore: [],
        debug: false,
        rules: {
            event_name: {
                required: true,
                minlength: 2,
                maxlength: 255
            },
            event_location: {
                required:true,
            },
            event_start_date: {
                required:true,
            },
            event_end_date: {
                required:true,
            },
            event_start_time: {
                required:true,
            },
            event_end_time: {
                required:true,
            },
        },
        messages: {
            event_name: {
                required: "Please enter name",
                minlength: "Event name should be atleast 2 characters",
                maxlength: "Event name must not be more than 255 characters"
            },
            event_location: {
                required: "Please enter event location",
            },
            event_start_date: {
                required: "Please enter event start date",
            },
            event_end_date: {
                required: "Please enter event end date",
            },
            event_start_time: {
                required: "Please enter event start time",
            },
            event_end_time: {
                required: "Please enter event end time",
            },

        },
        submitHandler: function(form) {
            form.submit();
        }
    });

    /* Add City */
    $("#addCityForm").validate({
        
        debug: false,
        rules: {
            name: {
                required: true,
            },
            state_id: {
                required: true,
            },
            
        },
        messages: {
            name: {
                required: "Please select name",
            },
            state_id: {
                required: "Please select state",
            },
            
        },
        submitHandler: function(form) {
            form.submit();
        }
    });

    /* Edit City */
    $("#editCityForm").validate({
        
        debug: false,
        rules: {
            name: {
                required: true,
            },
            state_id: {
                required: true,
            },
            
        },
        messages: {
            name: {
                required: "Please select name",
            },
            state_id: {
                required: "Please select state",
            },
            
        },
        submitHandler: function(form) {
            form.submit();
        }
    });

    /* Add Project type */
    $("#addProjectTypeForm").validate({
        
        debug: false,
        rules: {
            type_name: {
                required: true,
            },
            type_description: {
                required: true,
            },
            
        },
        messages: {
            type_name: {
                required: "Please select name",
            },
            type_description: {
                required: "Please select description",
            },
            
        },
        submitHandler: function(form) {
            form.submit();
        }
    });

    /* edit Project type */
    $("#editProjectTypeForm").validate({
        
        debug: false,
        rules: {
            type_name: {
                required: true,
            },
            type_description: {
                required: true,
            },
            
        },
        messages: {
            type_name: {
                required: "Please select name",
            },
            type_description: {
                required: "Please select description",
            },
            
        },
        submitHandler: function(form) {
            form.submit();
        }
    });

    /* Add Parent */
    $("#addParentForm").validate({
        
        debug: false,
        rules: {
            name: {
                required: true,
            },
            
        },
        messages: {
            name: {
                required: "Please select name",
            },
            
        },
        submitHandler: function(form) {
            form.submit();
        }
    });

    /* Edit Parent */
    $("#editParentForm").validate({
        
        debug: false,
        rules: {
            name: {
                required: true,
            },
            
        },
        messages: {
            name: {
                required: "Please select name",
            },
        },
        submitHandler: function(form) {
            form.submit();
        }
    });

     /* Add Child */
     $("#addChildForm").validate({
        
        debug: false,
        rules: {
            name: {
                required: true,
            },
            parent_id: {
                required: true,
            },
            
        },
        messages: {
            name: {
                required: "Please select name",
            },
            parent_id: {
                required: "Please select parent series",
            },
            
        },
        submitHandler: function(form) {
            form.submit();
        }
    });

    /* Edit Child */
    $("#editChildForm").validate({
        
        debug: false,
        rules: {
            name: {
                required: true,
            },
            parent_id: {
                required: true,
            },
            
        },
        messages: {
            name: {
                required: "Please select name",
            },
            parent_id: {
                required: "Please select parent series",
            },
            
        },
        submitHandler: function(form) {
            form.submit();
        }
    });

     /* Add Shade */
     $("#addShadeForm").validate({
        
        debug: false,
        rules: {
            name: {
                required: true,
            },
            code: {
                required: true,
            },
            
        },
        messages: {
            name: {
                required: "Please select name",
            },
            code: {
                required: "Please select code",
            },
            
        },
        submitHandler: function(form) {
            form.submit();
        }
    });

    /* Edit Shade */
    $("#editShadeForm").validate({
        
        debug: false,
        rules: {
            name: {
                required: true,
            },
            code: {
                required: true,
            },
            
        },
        messages: {
            name: {
                required: "Please select name",
            },
            code: {
                required: "Please select code",
            },
            
        },
        submitHandler: function(form) {
            form.submit();
        }
    });
    
    
    /* Add Material */
    $("#addMaterialForm").validate({
        
        debug: false,
        rules: {
            name: {
                required: true,
            },
            
        },
        messages: {
            name: {
                required: "Please select name",
            },
            
        },
        submitHandler: function(form) {
            form.submit();
        }
    });

    /* Add PointLog */
    $("#pointLog").validate({
        
        debug: false,
        rules: {
            parent_series_id: {
                required: true,
            },
            child_series_id: {
                required: true,
            },
            shade_id: {
                required: true,
            },
            material_grade_id: {
                required: true,
            },
            shade_code_id: {
                required: true,
            },
            fabstar_points: {
                required: true,
            },
            signstar_points: {
                required: true,
            },
            designstar_sp_points: {
                required: true,
            },
            designstar_cp_points: {
                required: true,
            },
            
        },
        messages: {
            parent_series_id: {
                required: "Please select parent series",
            },
            child_series_id: {
                required: "Please select child series",
            },
            shade_id: {
                required: "Please select shade",
            },
            material_grade_id: {
                required: "Please select material",
            },
            shade_code_id: {
                required: "Please select shade code",
            },
            fabstar_points: {
                required: "Please enter feb start point",
            },
            signstar_points: {
                required: "Please enter sign star point",
            },
            designstar_sp_points: {
                required: "Please enter design stat sp point",
            },
            designstar_cp_points: {
                required: "Please enter design stat cp point",
            },
        },
        submitHandler: function(form) {
            form.submit();
        }
    });

    /* Add message */
    $("#addMessageForm").validate({
        
        debug: false,
        rules: {
            text: {
                required: true,
            },
            body: {
                required: true,
            },
            user_type: {
                required: true,
            },
            
        },
        messages: {
            text: {
                required: "Please select text",
            },
            body: {
                required: "Please select body",
            },
            user_type: {
                required: "Please select usertype",
            },
            
        },
        submitHandler: function(form) {
            form.submit();
        }
    });

    /* Edit message */
    $("#editMessageForm").validate({
        
        debug: false,
        rules: {
            text: {
                required: true,
            },
            body: {
                required: true,
            },
            user_type: {
                required: true,
            },
            
        },
        messages: {
            text: {
                required: "Please select text",
            },
            body: {
                required: "Please select body",
            },
            user_type: {
                required: "Please select usertype",
            },
            
        },
        submitHandler: function(form) {
            form.submit();
        }
    });

     /* Add Faq */
     $("#addFaqForm").validate({
        
        debug: false,
        rules: {
            question: {
                required: true,
            },
            answer: {
                required: true,
            },
            
        },
        messages: {
            question: {
                required: "Please select question",
            },
            answer: {
                required: "Please select answer",
            },
            
        },
        submitHandler: function(form) {
            form.submit();
        }
    });

    /* Edit Faq */
    $("#editFaqForm").validate({
        
        debug: false,
        rules: {
            question: {
                required: true,
            },
            answer: {
                required: true,
            },
            
        },
        messages: {
            question: {
                required: "Please select question",
            },
            answer: {
                required: "Please select answer",
            },
            
        },
        submitHandler: function(form) {
            form.submit();
        }
    });

     /* Add Project type */
    

     /* Bank Add */
    $("#add_bank_details").validate({
        rules: {
            full_name: {
                required: true,
                minlength: 2,
                maxlength: 255
            },
            email: {
                required: true,
                valid_email: true
            },
            phone_no: {
                required: true,
                valid_number: true
            },
            ifsc_code: {
                required: true,
                minlength: 4,
                maxlength: 255
            },
            address: {
                required: true,
                minlength: 5,
                maxlength: 255
            },
            
        },
        messages: {
            full_name: {
                required: "Please enter name",
                minlength: "Name should be atleast 2 characters",
                maxlength: "Name must not be more than 255 characters"
            },
            email: {
                required: "Please enter email",
            },
            phone_no: {
                required: "Please enter phone number",
            },
            ifsc_code: {
                required: "Please enter IFSC Code",
                minlength: "IFSC Code should be atleast 4 characters",
                maxlength: "IFSC Code must not be more than 255 characters"
            },
            address:{
                required: "Please enter Address",
                minlength: "Address should be atleast 4 characters",
                maxlength: "Address must not be more than 255 characters"
            },
            
        },
        errorPlacement: function(error, element) {
            if ($(element).attr('type') == 'checkbox') {
                error.insertAfter($(element).parents('div.form-control'));
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function(form) {
            form.submit();
        }
    });
    /* Bank Add*/



    /* Bank Edit*/
    $("#edit_bank_details").validate({
        rules: {
            full_name: {
                required: true,
                minlength: 2,
                maxlength: 255
            },
            email: {
                required: true,
                valid_email: true
            },
            phone_no: {
                required: true,
                valid_number: true
            },
            ifsc_code: {
                required: true,
                minlength: 4,
                maxlength: 255
            },
            address: {
                required: true,
                minlength: 5,
                maxlength: 255
            },
            
        },
        messages: {
            full_name: {
                required: "Please enter name",
                minlength: "Name should be atleast 2 characters",
                maxlength: "Name must not be more than 255 characters"
            },
            email: {
                required: "Please enter email",
            },
            phone_no: {
                required: "Please enter phone number",
            },
            ifsc_code: {
                required: "Please enter IFSC Code",
                minlength: "IFSC Code should be atleast 4 characters",
                maxlength: "IFSC Code must not be more than 255 characters"
            },
            address:{
                required: "Please enter Address",
                minlength: "Address should be atleast 4 characters",
                maxlength: "Address must not be more than 255 characters"
            },
            
        },
        errorPlacement: function(error, element) {
            if ($(element).attr('type') == 'checkbox') {
                error.insertAfter($(element).parents('div.form-control'));
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function(form) {
            form.submit();
        }
    });
    /* Bank Edit*/


    
    /* Zone Add */
    $("#add_zone_details").validate({
        rules: {
            full_name: {
                required: true,
                minlength: 2,
                maxlength: 255
            },
            email: {
                required: true,
                valid_email: true
            },
            phone_no: {
                required: true,
                valid_number: true
            },
            bank_id: {
                required: true,

            },
            address: {
                required: true,
                minlength: 5,
                maxlength: 255
            },
            
        },
        messages: {
            full_name: {
                required: "Please enter name",
                minlength: "Name should be atleast 2 characters",
                maxlength: "Name must not be more than 255 characters"
            },
            email: {
                required: "Please enter email",
            },
            phone_no: {
                required: "Please enter phone number",
            },
            bank_id: {
                required: "Please select Bank",
            },
            address:{
                required: "Please enter Address",
                minlength: "Address should be atleast 4 characters",
                maxlength: "Address must not be more than 255 characters"
            },
            
        },
        errorPlacement: function(error, element) {
            if ($(element).attr('type') == 'checkbox') {
                error.insertAfter($(element).parents('div.form-control'));
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function(form) {
            form.submit();
        }
    });
    /* Zone Add*/

     /* Zone Edit */
    $("#edit_zone_details").validate({
        rules: {
            full_name: {
                required: true,
                minlength: 2,
                maxlength: 255
            },
            email: {
                required: true,
                valid_email: true
            },
            phone_no: {
                required: true,
                valid_number: true
            },
            bank_id: {
                required: true,

            },
            address: {
                required: true,
                minlength: 5,
                maxlength: 255
            },
            
        },
        messages: {
            full_name: {
                required: "Please enter name",
                minlength: "Name should be atleast 2 characters",
                maxlength: "Name must not be more than 255 characters"
            },
            email: {
                required: "Please enter email",
            },
            phone_no: {
                required: "Please enter phone number",
            },
            bank_id: {
                required: "Please select Bank",
            },
            address:{
                required: "Please enter Address",
                minlength: "Address should be atleast 4 characters",
                maxlength: "Address must not be more than 255 characters"
            },
            
        },
        errorPlacement: function(error, element) {
            if ($(element).attr('type') == 'checkbox') {
                error.insertAfter($(element).parents('div.form-control'));
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function(form) {
            form.submit();
        }
    });
    /* Zone Edit*/

    
     /* Range Add */
    $("#add_range_details").validate({
        rules: {
            full_name: {
                required: true,
                minlength: 2,
                maxlength: 255
            },
            email: {
                required: true,
                valid_email: true
            },
            phone_no: {
                required: true,
                valid_number: true
            },
            bank_id: {
                required: true,

            },
            zone_id: {
                required: true,

            },
            address: {
                required: true,
                minlength: 5,
                maxlength: 255
            },
            
        },
        messages: {
            full_name: {
                required: "Please enter name",
                minlength: "Name should be atleast 2 characters",
                maxlength: "Name must not be more than 255 characters"
            },
            email: {
                required: "Please enter email",
            },
            phone_no: {
                required: "Please enter phone number",
            },
            bank_id: {
                required: "Please select Bank",
            },
            zone_id: {
                required: "Please select Zone",
            },
            address:{
                required: "Please enter Address",
                minlength: "Address should be atleast 4 characters",
                maxlength: "Address must not be more than 255 characters"
            },
            
        },
        errorPlacement: function(error, element) {
            if ($(element).attr('type') == 'checkbox') {
                error.insertAfter($(element).parents('div.form-control'));
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function(form) {
            form.submit();
        }
    });
    /* Range Add*/

    /* Range Edit */
    $("#add_range_details").validate({
        rules: {
            full_name: {
                required: true,
                minlength: 2,
                maxlength: 255
            },
            email: {
                required: true,
                valid_email: true
            },
            phone_no: {
                required: true,
                valid_number: true
            },
            bank_id: {
                required: true,

            },
            zone_id: {
                required: true,

            },
            address: {
                required: true,
                minlength: 5,
                maxlength: 255
            },
            
        },
        messages: {
            full_name: {
                required: "Please enter name",
                minlength: "Name should be atleast 2 characters",
                maxlength: "Name must not be more than 255 characters"
            },
            email: {
                required: "Please enter email",
            },
            phone_no: {
                required: "Please enter phone number",
            },
            bank_id: {
                required: "Please select Bank",
            },
            zone_id: {
                required: "Please select Zone",
            },
            address:{
                required: "Please enter Address",
                minlength: "Address should be atleast 4 characters",
                maxlength: "Address must not be more than 255 characters"
            },
            
        },
        errorPlacement: function(error, element) {
            if ($(element).attr('type') == 'checkbox') {
                error.insertAfter($(element).parents('div.form-control'));
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function(form) {
            form.submit();
        }
    });
    /* Range Edit*/

    /* Edit Acknowledgement */
    $("#edit_acknowledgement_form").validate({
        
        debug: false,
        rules: {
            full_name: {
                required: true,
                minlength: 2,
                maxlength: 255
            },
            email: {
                required: true,
                required: '#phone_no:blank',
                valid_email: function() {
                    if ($("#email").val() != '') {
                        return true;
                    }
                }
            },
            phone_no: {
                required: true,
                required: '#email:blank',
                valid_number: function() {
                    if ($("#phone_no").val() != '') {
                        return true;
                    }
                }
            },
            
            bank_id: {
                required: true
            },
            zone_id: {
                required: true
            },
            range_id: {
                required: true
            },
            district_id: {
                required: true
            },

            block_id: {
                required: true
            },
            software_using: {
                required: true
            },
            address: {
                required: true,
                minlength: 5,
            },
            socity_type: {
                required: true
            },

            socity_registration_no: {
                required: true
            },
            district_registration_no: {
                required: true
            },
            information_correct_verified: {
                required: true
            },
            unique_id_noted: {
                required: true
            },
            pacs_using_software: {
                required: true
            },
            pacs_uploaded_format: {
                required: true
            },
        },
        messages: {
            full_name: {
                required: "Please enter name",
                minlength: "Name should be atleast 2 characters",
                maxlength: "Name must not be more than 255 characters"
            },

            email: {
                required: "Please enter email",
            },
            phone_no: {
                required: "Please enter phone number",
            },

            bank_id: {
                required: "Please select any Bank"
            },
            zone_id: {
                required: "Please select any Zone"
            },
            range_id: {
                required: "Please select any Range"
            },

            address: {
                required: "Please enter Address",
                minlength: "Address should be atleast 5 characters",
            },
            district_id: {
                required: "Please select any role"
            },
            block_id: {
                required: "Please select any role"
            },
            socity_type: {
                required: "Please select any role"
            },
            district_registration_no: {
                required: "Please select any role"
            },
            software_using: {
                required: "Please select any role"
            },
            
            
        },
        submitHandler: function(form) {
            form.submit();
        }
    });
    /* Edit Acknowledgement */

    /* Edit Acknowledgement */
    $("#replyComplainForm").validate({
        
        debug: false,
        rules: {
            disposing_note: {
                required: true,
                
            },
           
        },
        messages: {
            disposing_note: {
                required: "Please enter disposing note",
            },

           
            
            
        },
        submitHandler: function(form) {
            form.submit();
        }
    });
    /* Edit Acknowledgement */
});
