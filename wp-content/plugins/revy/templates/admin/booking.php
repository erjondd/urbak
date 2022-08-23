<?php
/**
 * Created by PhpStorm.
 * User: roninwp
 * Date: 11/20/2018
 * Time: 10:42 AM
 */
?>
<div class="fat-sb-header">
    <img src="<?php echo esc_url(REVY_ASSET_URL.'/images/plugin_logo.png');?>">
    <div class="fat-sb-header-title"><?php echo esc_html__('All Bookings','revy');?></div>
</div>
<div class="fat-sb-booking-container fat-semantic-container fat-min-height-300 fat-pd-right-15">
    <div class="ui card full-width">
        <div class="content has-button-group">
            <div class="toolbox-action-group">
                <div class="ui transparent left icon input ui-search fat-sb-search no-border-radius fat-mg-right-10 " >
                    <input type="text" id="b_customer_name" id="b_customer_name" data-onKeyUp="RevyBooking.searchNameKeyup" autocomplete="nope"
                           placeholder="<?php echo esc_attr__('Search name or email ...','revy');?>">
                    <i class="search icon"></i>
                    <a class="fat-close" data-onClick="RevyBooking.closeSearchOnClick">
                        <i class="times icon"></i>
                    </a>
                </div>

                <div class="ui transparent left date-input input no-border-radius">
                    <?php
                    $start_date = new DateTime();
                    $end_date = new DateTime();
                    $end_date->modify('+6 day');
                    $date_format = get_option('date_format');
                    $locale = get_locale();
                    $locale = explode('_',$locale)[0];
                    ?>
                    <input type="text"  class="date-range-picker"  name="date_of_book" id="date_of_book" data-auto-update="1" data-onChange="RevyBooking.searchDateOnChange"
                           data-start="<?php echo esc_attr($start_date->format('Y-m-d'));?>" data-end="<?php echo esc_attr($end_date->format('Y-m-d'));?>"
                           data-locale="<?php echo esc_attr($locale);?>"
                           date-time-picker="1"
                           data-start-init="<?php echo date_i18n($date_format,$start_date->format('U'));?>"
                           data-end-init="<?php echo date_i18n($date_format,$end_date->format('U'));?>" >
                </div>

                <div class="fat-checkbox-dropdown-wrap fat-mg-right-10 fat-sb-garage-dic">
                    <select multiple="multiple" name="garage" data-onChange="RevyBooking.sumoSearchOnChange"
                            data-prev-value=""
                            data-placeholder="<?php echo esc_attr__('Select garage'); ?>"
                            data-caption-format="<?php echo esc_attr__('Garage selected'); ?>"
                            data-search-text="<?php echo esc_attr__('Enter garage\'s name'); ?>"
                            id="garage" class="SumoUnder fat-sb-sumo-select" tabindex="-1">
                    </select>
                </div>

                <div class="fat-checkbox-dropdown-wrap fat-sb-services-dic fat-mg-right-10" >
                    <select multiple="multiple" name="b_service" id="b_service" data-onChange="RevyBooking.sumoSearchOnChange"
                            data-placeholder="<?php echo esc_attr__('Select services','revy'); ?>"
                            data-search-text="<?php echo esc_attr__('Enter service\'s name','revy'); ?>"
                            data-caption-format="<?php echo esc_attr__('Sevices selected','revy'); ?>"
                            id="services" class="SumoUnder fat-sb-sumo-select" tabindex="-1"
                            data-prev-value="">
                    </select>
                </div>

                <div class="fat-checkbox-dropdown-wrap fat-sb-customers-dic fat-mg-right-10">
                    <select multiple="multiple" name="b_customer" id="b_customer" data-onChange="RevyBooking.sumoSearchOnChange"
                            data-placeholder="<?php echo esc_attr__('Select customers','revy'); ?>"
                            data-search-text="<?php echo esc_attr__('Enter customer\'s name','revy'); ?>"
                            data-caption-format="<?php echo esc_attr__('Customers selected','revy'); ?>"
                            id="employees" class="SumoUnder fat-sb-sumo-select" tabindex="-1"
                            data-prev-value="">
                    </select>
                </div>

                <div class="ui floating dropdown labeled icon selection dropdown fat-mg-right-10">
                    <i class="dropdown icon"></i>
                    <input type="hidden" name="b_delivery_method" id="b_delivery_method" data-onChange="RevyBooking.searchBookingDeliveryChange">
                    <span class="text"><?php echo esc_html__('Select delivery method','revy');?></span>
                    <div class="menu">
                        <div class="item"  data-value="">
                            <?php echo esc_html__('All delivery method','revy');?>
                        </div>
                        <div class="item"  data-value="1">
                            <?php echo esc_html__('Fixit home','revy');?>
                        </div>
                        <div class="item"  data-value="2">
                            <?php echo esc_html__('Carry in','revy');?>
                        </div>
                        <div class="item"  data-value="3">
                            <?php echo esc_html__('Mail-in','revy');?>
                        </div>
                    </div>
                </div>

                <div class="ui floating dropdown labeled icon selection dropdown fat-mg-right-10">
                    <i class="dropdown icon"></i>
                    <input type="hidden" name="b_process_status" id="b_process_status" data-onChange="RevyBooking.searchStatusChange">
                    <span class="text"><?php echo esc_html__('Select status','revy');?></span>
                    <div class="menu">
                        <div class="item"  data-value="">
                            <div class="ui empty"></div>
                            <?php echo esc_html__('All status','revy');?>
                        </div>
                        <div class="item"  data-value="0">
                            <div class="ui yellow empty circular label"></div>
                            <?php echo esc_html__('Pending','revy');?>
                        </div>
                        <div class="item"  data-value="1">
                            <div class="ui green empty circular label"></div>
                            <?php echo esc_html__('Approved','revy');?>
                        </div>
                        <div class="item" data-value="2">
                            <div class="ui red empty circular label"></div>
                            <?php echo esc_html__('Cancel','revy');?>
                        </div>
                        <div class="item"  data-value="3">
                            <div class="ui empty empty circular label"></div>
                            <?php echo esc_html__('Reject','revy');?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="content">

            <div class="ui horizontal fat-booking-status-list list">
                <div class="item">
                    <div class="ui red empty circular label"></div>
                    <span id="total_canceled">0</span><?php echo esc_html__(' Canceled','revy');?>
                </div>
                <div class="item">
                    <div class="ui green empty circular label"></div>
                    <span id="total_approved">0</span><?php echo esc_html__(' Approved','revy');?>
                </div>
                <div class="item">
                    <div class="ui yellow empty circular label"></div>
                    <span id="total_pending">0</span><?php echo esc_html__(' Pending','revy');?>
                </div>
                <div class="item">
                    <div class="ui empty empty circular label"></div>
                    <span id="total_rejected">0</span><?php echo esc_html__(' Rejected','revy');?>
                </div>
                <div class="item fat-mg-left-60">

                    <button class="ui primary basic button no-border fat-bt-export disabled" data-onClick="RevyBooking.exportBooking">
                        <i class="share square icon"></i>
                        <?php echo esc_html__('Export booking','revy');?>
                    </button>

                    <button class="ui negative basic button no-border fat-bt-delete disabled" data-onClick="RevyBooking.processDeleteBooking">
                        <i class="trash alternate outline icon"></i>
                        <?php echo esc_html__('Delete','revy');?>
                    </button>
                </div>
            </div>
            <table class="ui single line table fat-sb-list-booking">
                <thead>
                <tr>
                    <th class="check-item">
                        <div class="ui checkbox">
                            <input type="checkbox" name="example" class="table-check-all">
                            <label></label>
                        </div>
                    </th>
                    <th><?php echo esc_html__('Appointment Date','revy');?>
                        <span class="fat-sb-order-wrap" data-order-by="b_date">
                            <i class="caret up icon asc" data-onClick="RevyBooking.processOrder" data-order="asc"></i>
                            <i class="caret up icon revert desc active"  data-onClick="RevyBooking.processOrder" data-order="desc"></i>
                        </span>
                    </th>
                    <th><?php echo esc_html__('Create Date','revy');?>
                    </th>
                    <th><?php echo esc_html__('Customer','revy');?>
                        <span class="fat-sb-order-wrap" data-order-by="c_first_name">
                            <i class="caret up icon asc" data-onClick="RevyBooking.processOrder" data-order="asc"></i>
                            <i class="caret up icon revert desc"  data-onClick="RevyBooking.processOrder" data-order="desc"></i>
                        </span>
                    </th>
                    <th><?php echo esc_html__('Models','revy');?></th>
                    <th><?php echo esc_html__('Delivery method','revy');?></th>
                    <th class="fat-sb-payment"><?php echo esc_html__('Payment','revy');?></th>
                    <th class="fat-sb-status"><?php echo esc_html__('Status','revy');?></th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <tr class="fat-tr-not-found">
                    <td colspan="10">
                        <div class="ui fluid placeholder">
                            <div class="line"></div>
                            <div class="line"></div>
                            <div class="line"></div>
                            <div class="line"></div>
                            <div class="line"></div>
                            <div class="line"></div>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>

            <div class="fat-sb-pagination" data-obj="RevyBooking" data-func="loadBooking">

            </div>
        </div>
    </div>
</div>