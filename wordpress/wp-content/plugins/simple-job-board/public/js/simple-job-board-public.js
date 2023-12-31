/**
 * Simple Job Board Core Front-end JS File - V 1.4.0
 * 
 * @author PressTigers <support@presstigers.com>, 2016
 *
 * Actions List
 * - Job Application Submission Callbacks
 * - Date Picker Initialization
 * - Validate Email 
 * - Initialize TelInput Plugin
 * - Validate Phone Number
 * - Allowable Uploaded File's Extensions
 * - Validate Required Inputs ( Attachment, Phone & Email )
 * - Checkbox Group Required Attribute Callbacks
 * - Custom Styling of File Upload Button 
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        $('[id*=jobapp_total_work_experience]').addClass("sjb-numbers-only");
        var jobpost_submit_button = $('.app-submit');

        $(".jobpost-form").on("submit", function(event) {

            var forminputvar = $(this);
            var forminputvar_id = $(this).attr('id');
            var idfordiv = '.jobpost_form_status';
            var jobpost_form_status = $('.jobpost_form_status');
            //var formelement = $("#" + forminputvar_id + "");

            var datastring = new FormData(document.getElementById(forminputvar_id));
            console.log(forminputvar_id);
            /** 
             * Application Form Submit -> Validate Email & Phone 
             * @since 2.2.0          
             */
            var is_valid_email = sjb_is_valid_input(event, "text", "email");
            var is_valid_phone = sjb_is_valid_input(event, "text", "phone");
            var is_attachment = sjb_is_attachment(event);

            /* Stop Form Submission on Invalid Phone, Email & File Attachement */
            if (!is_valid_email || !is_valid_phone) {
                return false;
                console.log('hello i reached here');
            }

            $.ajax({
                url: application_form.ajaxurl,
                type: 'POST',
                dataType: 'json',
                data: datastring,
                async: true,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#' + forminputvar_id).find('.jobpost_form_status').html('Submitting.....');
                    $('#' + forminputvar_id).find('.app-submit').attr('disabled', 'diabled');
                },
                success: function(response) {
                    if (response['success'] == true) {
                        $('#' + forminputvar_id).find('.job-post-slide').slideUp();

                        /* Translation Ready String Through Script Locaization */
                        $('#' + forminputvar_id).find('.jobpost_form_status').html(response['success_alert']);
                    }

                    if (response['success'] == false) {

                        /* Translation Ready String Through Script Locaization */
                        $('#' + forminputvar_id).find('.jobpost_form_status').html(response['error'] + ' ' + application_form.jquery_alerts['application_not_submitted'] + '</div>');
                        $('#' + forminputvar_id).find('.app-submit').removeAttr('disabled');
                    }

                }
            });

            return false;
        });

        /* Date Picker */
        $('.sjb-datepicker').datepicker({
            dateFormat: 'dd-mm-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: '-100:+50',
        });

        /** 
         * Application Form -> On Input Email Validation
         *  
         * @since   2.2.0          
         */
        /*$('.sjb-email-address').on('input', function() {
            var input = $(this);
            $(this).popover();
            var re = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
            var is_email = re.test(input.val());
            var error_element = $(this).next();
            if (is_email) {
                //input.popover('hide');
            } else {
                $(this).popover({ html: true, trigger: 'click', placement: 'top', content: 'smileyContent', title: 'Smilies' });
            }
        });*/




        /**
         * Initialize TelInput Plugin
         * 
         * @since   2.2.0
         */
        if ($('.sjb-phone-number').length) {
            var telInput_id = $('.sjb-phone-number').map(function() {
                return this.id;
            }).get();

            for (var input_ID in telInput_id) {
                var telInput = $('#' + telInput_id[input_ID]);
                telInput.intlTelInput({
                    initialCountry: "auto",
                    geoIpLookup: function(callback) {
                        $.get('https://ipinfo.io', function() {}, "jsonp").always(function(resp) {
                            var countryCode = (resp && resp.country) ? resp.country : "";
                            callback(countryCode);
                        });
                    },
                });
            }
        }

        /**
         * Application Form -> Phone Number Validation
         * 
         * @since 2.2.0
         */
        /*  $('.sjb-phone-number').on('input', function() {
            var telInput = $(this);
            var telInput_id = $(this).attr('id');
            var error_element = $("#" + telInput_id + "-invalid-phone");
            error_element.hide();

            // Validate Phone Number
            if ($.trim(telInput.val())) {
                if (telInput.intlTelInput("isValidNumber")) {
                    telInput.removeClass("invalid").addClass("valid");
                    error_element.hide();
                } else {
                    telInput.removeClass("valid").addClass("invalid");
                }
            }
        });
*/
        /** 
         * Check for Allowable Extensions of Uploaded File
         *  
         * @since   2.3.0          
         */
        $('.sjb-attachment').on('change', function() {
            var input = $(this);
            var file = $("#" + $(this).attr("id"));
            var error_element = file.parent().next("span");
            error_element.text('');
            error_element.hide();

            // Validate on File Attachment
            if (0 != file.get(0).files.length) {
                /**
                 *  Uploded File Extensions Checks
                 *  Get Uploded File Ext
                 */
                var file_ext = file.val().split('.').pop().toLowerCase();

                // All Allowed File Extensions
                var allowed_file_exts = application_form.allowed_extensions;

                // Settings File Extensions && Getting value From Script Localization
                var settings_file_exts = application_form.setting_extensions;
                var selected_file_exts = (('yes' === application_form.all_extensions_check) || null == settings_file_exts) ? allowed_file_exts : settings_file_exts;

                // File Extension Validation
                if ($.inArray(file_ext, selected_file_exts) > -1) {
                    jobpost_submit_button.attr('disabled', false);
                    input.removeClass("invalid").addClass("valid");
                } else {

                    /* Translation Ready String Through Script Locaization */
                    error_element.text(application_form.jquery_alerts['invalid_extension']);
                    error_element.show();
                    input.removeClass("valid").addClass("invalid");
                }
            }
        });

        /** 
         * Stop Form Submission -> On Required Attachments
         *  
         * @since 2.3.0          
         */
        function sjb_is_attachment(event) {
            var error_free = true;
            $(".sjb-attachment").each(function() {

                var element = $("#" + $(this).attr("id"));
                var valid = element.hasClass("valid");
                var is_required_class = element.hasClass("sjb-not-required");

                // Set Error Indicator on Invalid Attachment
                if (!valid) {
                    if (!(is_required_class && 0 === element.get(0).files.length)) {
                        error_free = false;
                    }
                }

                // Stop Form Submission
                if (!error_free) {
                    event.preventDefault();
                }
            });

            return error_free;
        }

        /** 
         * Stop Form Submission -> On Invalid Email/Phone
         *  
         * @since 2.2.0          
         */
        function sjb_is_valid_input(event, input_type, input_class) {
            var jobpost_form_inputs = $("." + input_class).serializeArray();
            var error_free = true;
            // var inputsvalid = formelements.find('.invalid');
            // console.log(inputsvalid);
            // if (!inputsvalid) {
            //     error_free = false;
            //     event.preventDefault();
            // }
            for (var i in jobpost_form_inputs) {
                //var element = $("#" + jobpost_form_inputs[i]['name']);
                var invalid = $("." + input_class).hasClass("invalid");
                //     var is_required_class = element.hasClass("sjb-not-required");
                //     if (!(is_required_class && "" === jobpost_form_inputs[i]['value'])) {
                //         if ("email" === input_type) {
                //             var error_element = $("span", element.parent());
                //         } else if ("phone" === input_type) {
                //             var error_element = $("#" + jobpost_form_inputs[i]['name'] + "-invalid-phone");
                //         }

                // Set Error Indicator on Invalid Input
                if (invalid) {
                    //            error_element.show();
                    error_free = false;
                } else {
                    //             error_element.hide();
                }

                // Stop Form Submission
                if (!error_free) {
                    event.preventDefault();
                }
            }
            //     console.log(error_element);
            // }
            return error_free;
        }

        /**
         * Remove Required Attribute from Checkbox Group -> When one of the option is selected.
         * 
         * Add Required Attribute from Checkboxes Group -> When none of the option is selected.
         * 
         * @since   2.3.0
         */
        var requiredCheckboxes = $(':checkbox[required]');
        requiredCheckboxes.on('change', function() {
            var checkboxGroup = requiredCheckboxes.filter('[name="' + $(this).attr('name') + '"]');
            var isChecked = checkboxGroup.is(':checked');
            checkboxGroup.prop('required', !isChecked);
        });

        // Accept Numbers Input Only
        $(".sjb-numbers-only").keypress(function(evt) {
            evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                return false;
            }
            return true;
        });


    });

    /* 
     * Custom Styling of Upload Field Button
     * 
     * @since   2.4.0
     */
    var file = {
        maxlength: 20, // maximum length of filename before it's trimmed

        convert: function() {
            // Convert all file type inputs.
            // $('input[type=file].sjb-attachment').each(function() {
            //     $(this).wrap('<div class="file" />');
            //     $(this).parent().prepend('<div>' + application_form.file['browse'] + '</div>');
            //     $(this).parent().prepend('<span>' + application_form.file['no_file_chosen'] + '</span>');
            //     $(this).fadeTo(0, 0);
            //     $(this).attr('size', '50'); // Use this to adjust width for FireFox.
            // });
        },
        update: function(x) {

            // Update the filename display.
            var filename = x.val().replace(/^.*\\/g, '');
            if (filename.length > $(this).maxlength) {
                trim_start = $(this).maxlength / 2 - 1;
                trim_end = trim_start + filename.length - $(this).maxlength + 1;
                filename = filename.substr(0, trim_start) + '&#8230;' + filename.substr(trim_end);
            }

            if (filename == '')
                filename = application_form.file['no_file_chosen'];
            x.siblings('span').html(filename);
        }
    }

    $(document).ready(function() {
        //file.convert();
        $('input[type=file].sjb-attachment').change(function() {
            file.update($(this));
        });
    });
})(jQuery);

var $ = jQuery.noConflict();

$.fn.goValidate = function() {
    var $form = this,
        $inputs = $form.find('input:text');
    var validators = {
        email: {
            regex: /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/
        },
        phone: {
            regex: /^\d{10}$/
        }
    };
    var validate = function(klass, value) {
        var isValid = true,
            error = '';
        if (!value && /required/.test(klass)) {
            error = 'This field is required';
            isValid = false;
        } else {
            klass = klass.split(/\s/);
            $.each(klass, function(i, k) {
                if (validators[k]) {
                    if (value && !validators[k].regex.test(value)) {
                        isValid = false;
                        error
                            = validators[k].error;
                    }
                }
            });
        }
        return {
            isValid: isValid,
            error: error
        }
    };
    var showError = function($e) {
        var klass = $e.attr('class'),
            value = $e.val(),
            test = validate(klass, value);
        $e.removeClass('invalid');
        $('#form-error').addClass('hide');
        if (!test.isValid) {
            $e.addClass('invalid');
            if (typeof $e.data("shown") == "undefined" || $e.data("shown") == false) {
                $e.popover('show');
            }
        } else {
            $e.popover('hide');
        }
    };
    $inputs.keyup(function() {
        showError($(this));
    });

    $inputs.on('shown.bs.popover', function() {
        $(this).data("shown", true);
    });
    $inputs.on('hidden.bs.popover', function() {
        $(this).data("shown", false);
    });
    $form.submit(function(e) {
        $inputs.each(function() { /* test each input */
            if ($(this).is('.required') || $(this).hasClass('invalid')) {
                showError($(this));
            }
        });

        if ($form.find('input.invalid').length) { /* form is not valid */
            e.preventDefault();
            $('#form-error').toggleClass('show');
        }
    });
    return this;
};
$('form').goValidate();



$(function() {
    $('[id*=jobapp_total_work_experience]')
        .popover({ title: '', content: "Please Enter You Work Experience in total number of months" })
        .blur(function() {
            $(this).popover('hide');
        });
});