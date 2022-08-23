<?php
/**
 * Created by PhpStorm.
 * User: roninwp
 * Date: 3/4/2019
 * Time: 3:54 PM
 */
?>
<div class="fat-sb-header">
    <img src="<?php echo esc_url(REVY_ASSET_URL.'/images/plugin_logo.png');?>">
    <div class="fat-sb-header-title"><?php echo esc_html__('Settings','revy');?></div>
</div>
<div class="fat-sb-settings-container fat-semantic-container fat-min-height-300 fat-pd-right-15">
    <div class="ui card full-width">
        <div class="content">
            <div class="ui grid">
                <div class="five wide column">
                    <div class="ui items">
                        <div class="item" data-onClick="RevySetting.itemOnClick" data-template="fat-sb-setting-general-template">
                            <div class="image">
                                <i class="cogs icon"></i>
                            </div>
                            <div class="content">
                                <a class="header"><?php echo esc_html__('General Settings','revy');?></a>
                                <div class="meta">
                                    <span><?php echo esc_html__('Use this setting to set up day limit, tax default, booking default status, item per page','revy');?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="five wide column">
                    <div class="ui items">
                        <div class="item" data-onClick="RevySetting.itemOnClick" data-template="fat-sb-setting-company-template">
                            <div class="image">
                                <i class="building icon"></i>
                            </div>
                            <div class="content">
                                <a class="header"><?php echo esc_html__('Company','revy');?></a>
                                <div class="meta">
                                    <span><?php echo esc_html__('Use this setting to set up your company logo, name, address, phone','revy');?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="five wide column">
                    <div class="ui items">
                        <div class="item" data-onClick="RevySetting.itemOnClick" data-template="fat-sb-setting-notification-template">
                            <div class="image">
                                <i class="envelope outline icon"></i>
                            </div>
                            <div class="content">
                                <a class="header"><?php echo esc_html__('Email notification','revy');?></a>
                                <div class="meta">
                                    <span><?php echo esc_html__('Use this setting to set up send mail and action after booking','revy');?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="five wide column">
                    <div class="ui items">
                        <div class="item" data-onClick="RevySetting.itemOnClick" data-template="fat-sb-setting-delivery-template">
                            <div class="image">
                                <i class="shipping fast icon"></i>
                            </div>
                            <div class="content">
                                <a class="header"><?php echo esc_html__('Delivery method','revy');?></a>
                                <div class="meta">
                                    <span><?php echo esc_html__('Use this setting to set up delivery method in booking form','revy');?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="five wide column">
                    <div class="ui items">
                        <div class="item" data-onClick="RevySetting.itemOnClick" data-template="fat-sb-setting-payment-template">
                            <div class="image">
                                <i class="dollar sign icon"></i>
                            </div>
                            <div class="content">
                                <a class="header"><?php echo esc_html__('Payments','revy');?></a>
                                <div class="meta">
                                    <span><?php echo esc_html__('Use this setting to set up currency, paypal, stripe and onsite payment','revy');?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="five wide column">
                    <div class="ui items">
                        <div class="item" data-onClick="RevySetting.itemOnClick" data-template="fat-sb-setting-working-hour-template">
                            <div class="image">
                                <i class="clock outline icon"></i>
                            </div>
                            <div class="content">
                                <a class="header"><?php echo esc_html__('Working hours','revy');?></a>
                                <div class="meta">
                                    <span><?php echo esc_html__('Use this setting to set up working hour and day off for your company','revy');?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>