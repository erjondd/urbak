<?php
/**
 * Created by PhpStorm.
 * User: RoninWP
 * Date: 5/29/2019
 * Time: 10:11 AM
 */
?>
<script type="text/html" id="tmpl-fat-sb-test-email-template">
    <div class="ui modal tiny fat-semantic-container fat-test-email-template-modal">
        <div class="header fat-sb-popup-title"><?php echo esc_html__('Send Test Email','revy'); ?></div>
        <div class="scrolling content">
            <div class="ui form">
                <div class="one fields">
                    <div class="field ">
                        <label for="name"><?php echo esc_html__('Recipient Email','revy'); ?><span
                                class="required"> *</span></label>
                        <div class="ui left icon input ">
                            <input type="email" name="send_to" id="send_to" autocomplete="nope"
                                   placeholder="<?php echo esc_attr__('Recipient Email','revy'); ?>" required>
                            <i class="envelope outline icon"></i>
                        </div>
                        <div class="field-error-message">
                            <?php echo esc_html__('Please enter recipient email','revy'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="actions">
            <button class="ui basic button fat-close-modal">
                <i class="times circle outline icon"></i>
                <?php echo esc_html__('Cancel','revy'); ?>
            </button>

            <button class="ui blue button fat-submit-modal"  data-invalid-message="<?php echo esc_attr__('Please input valid email','revy'); ?>"
                    data-success-message="<?php echo esc_attr__('Email test has been send, please check mailbox','revy'); ?>"
                    data-onClick="RevyEmailTemplate.sendTestMailTemplate">
                <i class="paper plane outline icon"></i>
                <?php echo esc_html__('Send','revy'); ?>
            </button>

        </div>
    </div>
</script>
