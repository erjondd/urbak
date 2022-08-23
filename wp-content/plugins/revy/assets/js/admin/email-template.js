"use strict";
var RevyEmailTemplate = {};
(function ($) {
    RevyEmailTemplate.init = function () {
        RevyMain.registerEventProcess($('.fat-sb-email-template-container'));
        RevyMain.initPopupToolTip();
    };

    RevyEmailTemplate.menuOnClick = function(self){
        if(self.hasClass('active')){
            return;
        }
        $('a.active','.fat-sb-email-template-container .ui.menu').removeClass('active');
        self.addClass('active');
        if(self.hasClass('fat-sb-customer-code')){
            $('.fat-sb-get-customer-code-template .customer-template').show();
            $('.fat-sb-get-customer-code-template').removeClass('fat-hidden');
            $('.fat-sb-pending-template').hide();

            RevyEmailTemplate.initGetCodeTemplate(self.attr('data-template'));
        }else{
            $('.fat-sb-get-customer-code-template').addClass('fat-hidden');
            $('.fat-sb-pending-template').show();

            $('.fat-sb-fixit-home-label').html(self.attr('data-title-fixit-home'));
            $('.fat-sb-carry-in-label').html(self.attr('data-title-carry-in'));
            $('.fat-sb-mail-in-label').html(self.attr('data-title-mail-in'));
            RevyEmailTemplate.initTemplate(self.attr('data-template'));
        }

    };

    RevyEmailTemplate.dependFieldOnChange = function(self){
        var id = self.attr('id'),
            value = self.val();
        $('[data-depend="' + id + '"]', '.fat-sb-email-template-container').each(function () {
            var elm = $(this);
            if (self.is(':checked')) {
                elm.slideDown();
            } else {
                elm.slideUp();
            }
        });
    };

    RevyEmailTemplate.submitTemplate = function(self){
        RevyMain.showProcess(self);
        var template =  $('a.active','.fat-sb-email-template-container .ui.menu').attr('data-template'),
            fixit_home_template_enable = $('#fixit_home_template_enable').is(':checked') ? 1 : 0,
            fixit_home_subject = $('#fixit_home_subject').val(),
            fixit_home_message = tinymce.editors['fixit_home_template'].getContent(),
            carry_in_template_enable = $('#carry_in_template_enable').is(':checked') ? 1 : 0,
            carry_in_subject = $('#carry_in_subject').val(),
            carry_in_message = tinymce.editors['carry_in_template'].getContent(),
            mail_in_template_enable = $('#mail_in_template_enable').is(':checked') ? 1 : 0,
            mail_in_subject = $('#mail_in_subject').val(),
            mail_in_message = tinymce.editors['mail_in_template'].getContent(),
            customer_code_subject = $('#customer_code_subject').val(),
            customer_code_message = tinymce.editors['customer_code_template'].getContent();

        $.ajax({
            url: RevyMain.data.ajax_url,
            type: 'POST',
            data: ({
                action: 'save_email_template',
                data: {
                    'template' : template,
                    'fixit_home_enable': fixit_home_template_enable,
                    'fixit_home_subject' : fixit_home_subject,
                    'fixit_home_message' : he.encode(fixit_home_message),
                    'carry_in_enable': carry_in_template_enable,
                    'carry_in_subject': carry_in_subject,
                    'carry_in_message': he.encode(carry_in_message),
                    'mail_in_enable': mail_in_template_enable,
                    'mail_in_subject': mail_in_subject,
                    'mail_in_message': he.encode(mail_in_message),
                    'customer_code_subject': customer_code_subject,
                    'customer_code_message': he.encode(customer_code_message)
                }
            }),
            success: function (response) {
                RevyMain.closeProcess(self);
                response = $.parseJSON(response);
                if (response.result > 0) {
                    RevyMain.showMessage(self.attr('data-success-message'));

                    for(var $i=0; $i< revy_email_data.length; $i++){
                        if(revy_email_data[$i]['template'] == template){
                            if(template=='get_customer_code'){
                                revy_email_data[$i]['customer_code_subject'] = customer_code_subject;
                                revy_email_data[$i]['customer_code_message'] = customer_code_message;
                            }else{
                                revy_email_data[$i]['fixit_home_enable'] = fixit_home_template_enable;
                                revy_email_data[$i]['fixit_home_subject'] = fixit_home_message;
                                revy_email_data[$i]['fixit_home_message'] = fixit_home_message;

                                revy_email_data[$i]['carry_in_enable'] = carry_in_template_enable;
                                revy_email_data[$i]['carry_in_subject'] = carry_in_subject;
                                revy_email_data[$i]['carry_in_message'] = carry_in_message;

                                revy_email_data[$i]['mail_in_enable'] = mail_in_template_enable;
                                revy_email_data[$i]['mail_in_subject'] = mail_in_subject;
                                revy_email_data[$i]['mail_in_message'] = mail_in_message;
                            }
                        }
                    }

                } else {
                    if(typeof response.message!='undefined'){
                        RevyMain.showMessage(response.message, 3);
                    }else{
                        RevyMain.showMessage(RevyMain.data.error_message, 2);
                    }
                }
            },
            error: function () {
                RevyMain.closeProcess(self);
                RevyMain.showMessage(RevyMain.data.error_message, 2);
            }
        });


    };

    RevyEmailTemplate.initTemplate = function(template){
        var fixit_home_enable = 0,
            fixit_home_subject = '',
            fixit_home_message = '',
            carry_in_enable = 0,
            carry_in_subject = '',
            carry_in_message = '',
            mail_in_enable = 0,
            mail_in_subject = '',
            mail_in_message = '';

        $('#fixit_home_template-tmce').trigger('click');
        $('#carry_in_template-tmce').trigger('click');
        $('#mail_in_template-tmce').trigger('click');

        try{
            for(var $i=0; $i< revy_email_data.length; $i++){
                if(revy_email_data[$i]['template'] == template ){
                    var data = revy_email_data[$i];
                    fixit_home_enable = data['fixit_home_enable'];
                    fixit_home_subject = data['fixit_home_subject'];
                    fixit_home_message = he.decode(data['fixit_home_message']);

                    carry_in_enable = data['carry_in_enable'];
                    carry_in_subject = data['carry_in_subject'];
                    carry_in_message = he.decode(data['carry_in_message']);

                    mail_in_enable = data['mail_in_enable'];
                    mail_in_subject = data['mail_in_subject'];
                    mail_in_message = he.decode(data['mail_in_message']);
                }
            }

            $('#fixit_home_template_enable').prop("checked", fixit_home_enable==1);
            $('#fixit_home_subject').val(fixit_home_subject);
            if(typeof tinymce.editors['fixit_home_template']!='undefined' ){
                tinymce.editors['fixit_home_template'].setContent(fixit_home_message);
            }
            if ($('#fixit_home_template_enable').is(':checked')) {
                $('.fields.fixit_home_template').slideDown();
            } else {
                $('.fields.fixit_home_template').hide();
            }

            $('#carry_in_template_enable').prop("checked", carry_in_enable==1);
            $('#carry_in_subject').val(carry_in_subject);
            if(typeof tinymce.editors['carry_in_template']!='undefined' ){
                tinymce.editors['carry_in_template'].setContent(carry_in_message);
            }
            if ($('#carry_in_template_enable').is(':checked')) {
                $('.fields.carry_in_template').slideDown();
            } else {
                $('.fields.carry_in_template').hide();
            }

            $('#mail_in_template_enable').prop("checked", mail_in_enable==1);
            $('#mail_in_subject').val(mail_in_subject);
            if(typeof tinymce.editors['mail_in_template']!='undefined' ){
                tinymce.editors['mail_in_template'].setContent(mail_in_message);
            }
            if ($('#mail_in_template_enable').is(':checked')) {
                $('.fields.mail_in_template').slideDown();
            } else {
                $('.fields.mail_in_template').hide();
            }

        }catch ($err){

        }

    };

    RevyEmailTemplate.initGetCodeTemplate = function(template){
        if(typeof tinymce.editors['customer_code_template']!='undefined'){
            tinymce.editors['customer_code_template'].theme.resizeTo(null, 200);
        }

        for(var $i=0; $i< revy_email_data.length; $i++){
            if(revy_email_data[$i]['template'] == template){
                var data = revy_email_data[$i];
                $('#customer_code_subject').val(data['customer_code_subject']);
                if(typeof tinymce.editors['customer_code_template']!='undefined'){
                    tinymce.editors['customer_code_template'].setContent(data['customer_code_message']);
                }
            }
        }
    };

    RevyEmailTemplate.sendTestMailTemplateOnClick = function(self){
        RevyMain.showPopup('fat-sb-test-email-template', '', [], function () {
            RevyMain.registerEventProcess($('.fat-test-email-template-modal'));
        })
    };

    RevyEmailTemplate.sendTestMailTemplate = function(self){
        var send_to = $('#send_to').val(),
            pattern = new RegExp(/^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/);
        if (send_to != '' && pattern.test(send_to)) {
            self.addClass('loading');
            $.ajax({
                url: RevyMain.data.ajax_url,
                type: 'POST',
                data: ({
                    action: 'test_send_email_template',
                    template: $('a.item.active ','.fat-sb-email-template-container .fat-sb-template-tab').attr('data-template'),
                    send_to: send_to
                }),
                success: function (response) {
                    self.removeClass('loading');
                    response = $.parseJSON(response);
                    if (response.result_fixit_home > 0 ) {
                        RevyMain.showMessage(response.message_fixit_home);
                        $('.fat-test-email-template-modal .fat-close-modal').trigger('click');
                    }else{
                        RevyMain.showMessage(RevyMain.data.error_message, 2);
                    }
                    if(response.result_carry_in > 0 ){
                        RevyMain.showMessage(response.message_carry_in);
                        $('.fat-test-email-template-modal .fat-close-modal').trigger('click');
                    }else{
                        RevyMain.showMessage(RevyMain.data.error_message, 2);
                    }
                    if(response.result_mail_in > 0 ){
                        RevyMain.showMessage(response.message_mail_in);
                        $('.fat-test-email-template-modal .fat-close-modal').trigger('click');
                    }else{
                        RevyMain.showMessage(RevyMain.data.error_message, 2);
                    }
                },
                error: function () {
                    self.removeClass('loading');
                }
            })
        } else {
            RevyMain.showMessage(self.attr('data-invalid-message'), 2);
        }
    };

    $(document).ready(function () {
        RevyEmailTemplate.init();
    });
    $(window).load(function(){
        RevyMain.showLoading();
        setTimeout(function(){
            var template = $('a.active','.fat-sb-email-template-container .ui.menu').attr('data-template');
            RevyEmailTemplate.initTemplate(template);
            RevyMain.closeLoading();
        },3000);
    });
})(jQuery);