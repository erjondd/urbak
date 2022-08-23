<?php
?>
<div class="appointment-wrap fat-section-shadow fat-mg-top-30">
    <h4 class="fat-mg-bottom-15"><?php echo esc_html__('Appointment options', 'revy'); ?></h4>
    <div class="fat-sb-calendar-wrap">
        <div class="calendar-filter fat-mg-bottom-15" data-week="">
            <span class="prev-week">
                <i class="angle left icon"></i>
            </span>
            <span class="current-week" >Nov 18- Nov 24,2019</span>
            <span class="next-week">
                <i class="angle right icon"></i>
            </span>
        </div>
        <div class="week-detail">
            <div class="week-header">
                <div class="week-day-header sun"><?php echo esc_html__('Sun', 'revy'); ?>
                    <div class="week-header-mobile"></div>
                    <div class="week-date"><span data-onClick="RevyBookingFlow.dateOnClick"></span></div>
                </div>

                <div class="week-day-header mon"><?php echo esc_html__('Mon', 'revy'); ?>
                    <div class="week-header-mobile"></div>
                    <div class="week-date"><span data-onClick="RevyBookingFlow.dateOnClick"></span></div>
                </div>
                <div class="week-day-header tue"><?php echo esc_html__('Tue', 'revy'); ?>
                    <div class="week-header-mobile"></div>
                    <div class="week-date"><span data-onClick="RevyBookingFlow.dateOnClick"></span></div>
                </div>
                <div class="week-day-header wed"><?php echo esc_html__('Wed', 'revy'); ?>
                    <div class="week-header-mobile"></div>
                    <div class="week-date"><span data-onClick="RevyBookingFlow.dateOnClick"></span></div>
                </div>
                <div class="week-day-header thu"><?php echo esc_html__('Thu', 'revy'); ?>
                    <div class="week-header-mobile"></div>
                    <div class="week-date"><span data-onClick="RevyBookingFlow.dateOnClick"></span></div>
                </div>
                <div class="week-day-header fri"><?php echo esc_html__('Fri', 'revy'); ?>
                    <div class="week-header-mobile"></div>
                    <div class="week-date disabled"><span data-onClick="RevyBookingFlow.dateOnClick"></span></div>
                </div>
                <div class="week-day-header sat"><?php echo esc_html__('Sat', 'revy'); ?>
                    <div class="week-header-mobile"></div>
                    <div class="week-date disabled"><span data-onClick="RevyBookingFlow.dateOnClick"></span></div>
                </div>
            </div>
        </div>
    </div>
    <div class="fat-sb-calendar-time fat-mg-top-15">
        <label class="fat-mg-bottom-15"><?php echo esc_html__('Appointment Time','revy');?></label>
        <div class="ui fluid search selection dropdown bottom left time-options" data-onChange="RevyBookingFlow.timeOnChange">
            <input type="hidden" name="b_time" id="b_time"
                   autocomplete="nope" value="">
            <i class="dropdown icon"></i>
            <div class="default text"><?php echo esc_html__('Select Time','revy');?></div>
            <div class="menu">

            </div>
        </div>
        <div class="fat-sb-time-notice">
            <?php echo esc_html__('The appointments are fully booked. Please check again later or browse other day!','revy');?>
        </div>
    </div>
</div>
