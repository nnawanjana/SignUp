<?php if ($step1['plan_type'] == 'Elec' || $step1['plan_type'] == 'Dual'):?>
    <?php if (empty($plan['Plan']['elec_rate'])):?>
        <p style="color:#ff9302;">No Electricity Rates</p>
    <?php else:?>
        <?php
        $include_gst = true;
        if ($plan['Plan']['elec_rate']['gst_rates'] == 'No') {
            $gst = 1.1;
        } else {
            $gst = 1;
        }
        $ratio = 1;
        switch ($plan['Plan']['elec_rate']['rate_tier_period']) {
            case '2':
                //$period = 'every 2 month';
                $period = '/qtr';
                $ratio = 1.5;
                break;
            case 'D':
                //$period = 'per day';
                $period = '/qtr';
                $ratio = 91.25;
                break;
            case 'M':
                //$period = 'per month';
                $period = '/qtr';
                $ratio = 3;
                break;
            case 'Q':
                $period = '/qtr';
                break;
            case 'Y':
                //$period = 'per year';
                $period = '/qtr';
                $ratio = 0.25;
                break;
        }
        $stp_ratio = 1;
        switch ($plan['Plan']['elec_rate']['stp_period']) {
            case '2':
                $stp_period = 'every 2 month';
                break;
            case 'D':
                $stp_period = 'per day';
                break;
            case 'M':
                $stp_period = 'per month';
                break;
            case 'Q':
                $stp_period = 'per quarter';
                break;
            case 'Y':
                //$stp_period = 'per year';
                $stp_period = 'per day';
                $stp_ratio = 365;
                break;
        }
        $summer_winnter_rates = false;
        $tier_rates = array();
        if ($plan['Plan']['elec_rate']['peak_rate_1']) {
            if (strpos($plan['Plan']['elec_rate']['peak_rate_1'], '/') !== false) {
                $plan['Plan']['elec_rate']['peak_rate_1'] = explode('/', $plan['Plan']['elec_rate']['peak_rate_1']);
                $summer_winnter_rates = true;
            }
            $tier_rates[] = array('tier' => $plan['Plan']['elec_rate']['peak_tier_1'] * $ratio, 'rate' => $plan['Plan']['elec_rate']['peak_rate_1']);
        }
        if ($plan['Plan']['elec_rate']['peak_rate_2']) {
            if (strpos($plan['Plan']['elec_rate']['peak_rate_2'], '/') !== false) {
                $plan['Plan']['elec_rate']['peak_rate_2'] = explode('/', $plan['Plan']['elec_rate']['peak_rate_2']);
            }
            $tier_rates[] = array('tier' => $plan['Plan']['elec_rate']['peak_tier_2'] * $ratio, 'rate' => $plan['Plan']['elec_rate']['peak_rate_2']);
        }
        if ($plan['Plan']['elec_rate']['peak_rate_3']) {
            if (strpos($plan['Plan']['elec_rate']['peak_rate_3'], '/') !== false) {
                $plan['Plan']['elec_rate']['peak_rate_3'] = explode('/', $plan['Plan']['elec_rate']['peak_rate_3']);
            }
            $tier_rates[] = array('tier' => $plan['Plan']['elec_rate']['peak_tier_3'] * $ratio, 'rate' => $plan['Plan']['elec_rate']['peak_rate_3']);
        }
        if ($plan['Plan']['elec_rate']['peak_rate_4']) {
            if (strpos($plan['Plan']['elec_rate']['peak_rate_4'], '/') !== false) {
                $plan['Plan']['elec_rate']['peak_rate_4'] = explode('/', $plan['Plan']['elec_rate']['peak_rate_4']);
            }
            $tier_rates[] = array('tier' => $plan['Plan']['elec_rate']['peak_tier_4'] * $ratio, 'rate' => $plan['Plan']['elec_rate']['peak_rate_4']);
        }
        if ($plan['Plan']['elec_rate']['peak_rate_5']) {
            if (strpos($plan['Plan']['elec_rate']['peak_rate_5'], '/') !== false) {
                $plan['Plan']['elec_rate']['peak_rate_5'] = explode('/', $plan['Plan']['elec_rate']['peak_rate_5']);
            }
            $tier_rates[] = array('tier' => 0, 'rate' => $plan['Plan']['elec_rate']['peak_rate_5']);
        }
        ?>
        <?php if ($summer_winnter_rates):?>
            <h4>Summer Electricity Rates</h4>
            <table class="table table-striped">
                <tr>
                    <th>Rate</th><th>GST <?php if ($include_gst):?>inclusive<?php else:?>exclusive<?php endif;?></th><th></th>
                </tr>
                <tr>
                    <td>Daily Supply Charge</td>
                    <td><?php echo ($plan['Plan']['retailer'] != 'Powershop') ? round($plan['Plan']['elec_rate']['stp'] / $stp_ratio * 100 * $gst, 3) : round($plan['Plan']['elec_rate']['stp'] / $stp_ratio * 100 * $gst * (1 - $plan['Plan']['discount_guaranteed_elec'] / 100), 3);?>c</td>
                    <td>per day</td>
                </tr>
                <?php if (count($tier_rates) == 1):?>
                    <tr>
                        <td>Charge per kWh<?php echo $period;?></td>
                        <td><?php echo ($plan['Plan']['retailer'] != 'Powershop') ? round($tier_rates[0]['rate'][0] * 100 * $gst, 3) : round($tier_rates[0]['rate'][0] * 100 * $gst * (1 - $plan['Plan']['discount_guaranteed_elec'] / 100), 3);?>c</td>
                        <td>per kWh</td>
                    </tr>
                <?php endif;?>
                <?php if (count($tier_rates) == 2):?>
                    <tr>
                        <td>First <?php echo round($tier_rates[0]['tier']);?> kWh<?php echo $period;?></td>
                        <td><?php echo ($plan['Plan']['retailer'] != 'Powershop') ? round($tier_rates[0]['rate'][0] * 100 * $gst, 3) : round($tier_rates[0]['rate'][0] * 100 * $gst * (1 - $plan['Plan']['discount_guaranteed_elec'] / 100), 3);?>c</td>
                    </tr>
                    <tr>
                        <td>Remainder kWh<?php echo $period;?></td>
                        <td><?php echo ($plan['Plan']['retailer'] != 'Powershop') ? round($tier_rates[1]['rate'][0] * 100 * $gst, 3) : round($tier_rates[1]['rate'][0] * 100 * $gst * (1 - $plan['Plan']['discount_guaranteed_elec'] / 100), 3);?>c</td>
                        <td>per kWh</td>
                    </tr>
                <?php endif;?>
                <?php if (count($tier_rates) == 3):?>
                    <tr>
                        <td>First <?php echo round($tier_rates[0]['tier']);?> kWh<?php echo $period;?></td>
                        <td><?php echo ($plan['Plan']['retailer'] != 'Powershop') ? round($tier_rates[0]['rate'][0] * 100 * $gst, 3) : round($tier_rates[0]['rate'][0] * 100 * $gst * (1 - $plan['Plan']['discount_guaranteed_elec'] / 100), 3);?>c</td>
                        <td>per kWh</td>
                    </tr>
                    <tr>
                        <td>Next <?php echo round($tier_rates[1]['tier']);?> kWh<?php echo $period;?></td>
                        <td><?php echo ($plan['Plan']['retailer'] != 'Powershop') ? round($tier_rates[1]['rate'][0] * 100 * $gst, 3) : round($tier_rates[1]['rate'][0] * 100 * $gst * (1 - $plan['Plan']['discount_guaranteed_elec'] / 100), 3);?>c</td>
                        <td>per kWh</td>
                    </tr>
                    <tr>
                        <td>Remainder kWh<?php echo $period;?></td>
                        <td><?php echo ($plan['Plan']['retailer'] != 'Powershop') ? round($tier_rates[2]['rate'][0] * 100 * $gst, 3) : round($tier_rates[2]['rate'][0] * 100 * $gst * (1 - $plan['Plan']['discount_guaranteed_elec'] / 100), 3);?>c</td>
                        <td>per kWh</td>
                    </tr>
                <?php endif;?>
                <?php if (count($tier_rates) == 4):?>
                    <tr>
                        <td>First <?php echo round($tier_rates[0]['tier']);?> kWh<?php echo $period;?></td>
                        <td><?php echo ($plan['Plan']['retailer'] != 'Powershop') ? round($tier_rates[0]['rate'][0] * 100 * $gst, 3) : round($tier_rates[0]['rate'][0] * 100 * $gst * (1 - $plan['Plan']['discount_guaranteed_elec'] / 100), 3);?>c</td>
                        <td>per kWh</td>
                    </tr>
                    <tr>
                        <td>Next <?php echo round($tier_rates[1]['tier']);?> kWh<?php echo $period;?></td>
                        <td><?php echo ($plan['Plan']['retailer'] != 'Powershop') ? round($tier_rates[1]['rate'][0] * 100 * $gst, 3) : round($tier_rates[1]['rate'][0] * 100 * $gst * (1 - $plan['Plan']['discount_guaranteed_elec'] / 100), 3);?>c</td>
                        <td>per kWh</td>
                    </tr>
                    <tr>
                        <td>Next <?php echo round($tier_rates[2]['tier']);?> kWh<?php echo $period;?></td>
                        <td><?php echo ($plan['Plan']['retailer'] != 'Powershop') ? round($tier_rates[2]['rate'][0] * 100 * $gst, 3) : round($tier_rates[2]['rate'][0] * 100 * $gst * (1 - $plan['Plan']['discount_guaranteed_elec'] / 100), 3);?>c</td>
                        <td>per kWh</td>
                    </tr>
                    <tr>
                        <td>Remainder kWh<?php echo $period;?></td>
                        <td><?php echo ($plan['Plan']['retailer'] != 'Powershop') ? round($tier_rates[3]['rate'][0] * 100 * $gst, 3) : round($tier_rates[3]['rate'][0] * 100 * $gst * (1 - $plan['Plan']['discount_guaranteed_elec'] / 100), 3);?>c</td>
                        <td>per kWh</td>
                    </tr>
                <?php endif;?>
                <?php if (count($tier_rates) == 5):?>
                    <tr>
                        <td>First <?php echo round($tier_rates[0]['tier']);?> kWh<?php echo $period;?></td>
                        <td><?php echo ($plan['Plan']['retailer'] != 'Powershop') ? round($tier_rates[0]['rate'][0] * 100 * $gst, 3) : round($tier_rates[0]['rate'][0] * 100 * $gst * (1 - $plan['Plan']['discount_guaranteed_elec'] / 100), 3);?>c</td>
                        <td>per kWh</td>
                    </tr>
                    <tr>
                        <td>Next <?php echo round($tier_rates[1]['tier']);?> kWh<?php echo $period;?></td>
                        <td><?php echo ($plan['Plan']['retailer'] != 'Powershop') ? round($tier_rates[1]['rate'][0] * 100 * $gst, 3) : round($tier_rates[1]['rate'][0] * 100 * $gst * (1 - $plan['Plan']['discount_guaranteed_elec'] / 100), 3);?>c</td>
                        <td>per kWh</td>
                    </tr>
                    <tr>
                        <td>Next <?php echo round($tier_rates[2]['tier']);?> kWh<?php echo $period;?></td>
                        <td><?php echo ($plan['Plan']['retailer'] != 'Powershop') ? round($tier_rates[2]['rate'][0] * 100 * $gst, 3) : round($tier_rates[2]['rate'][0] * 100 * $gst * (1 - $plan['Plan']['discount_guaranteed_elec'] / 100), 3);?>c</td>
                        <td>per kWh</td>
                    </tr>
                    <tr>
                        <td>Next <?php echo round($tier_rates[3]['tier']);?> kWh<?php echo $period;?></td>
                        <td><?php echo ($plan['Plan']['retailer'] != 'Powershop') ? round($tier_rates[3]['rate'][0] * 100 * $gst, 3) : round($tier_rates[3]['rate'][0] * 100 * $gst * (1 - $plan['Plan']['discount_guaranteed_elec'] / 100), 3);?>c</td>
                        <td>per kWh</td>
                    </tr>
                    <tr>
                        <td>Remainder kWh<?php echo $period;?></td>
                        <td><?php echo ($plan['Plan']['retailer'] != 'Powershop') ? round($tier_rates[4]['rate'][0] * 100 * $gst, 3) : round($tier_rates[4]['rate'][0] * 100 * $gst * (1 - $plan['Plan']['discount_guaranteed_elec'] / 100), 3);?>c</td>
                        <td>per kWh</td>
                    </tr>
                <?php endif;?>
            </table>
            <h4>Winter Electricity Rates</h4>
            <table class="table table-striped">
                <tr>
                    <th>Rate</th><th>GST <?php if ($include_gst):?>inclusive<?php else:?>exclusive<?php endif;?></th><th></th>
                </tr>
                <tr>
                    <td>Daily Supply Charge</td>
                    <td><?php echo ($plan['Plan']['retailer'] != 'Powershop') ? round($plan['Plan']['elec_rate']['stp'] / $stp_ratio * 100 * $gst, 3) : round($plan['Plan']['elec_rate']['stp'] / $stp_ratio * 100 * $gst * (1 - $plan['Plan']['discount_guaranteed_elec'] / 100), 3);?>c</td>
                    <td>per day</td>
                </tr>
                <?php if (count($tier_rates) == 1):?>
                    <tr>
                        <td>Charge per kWh<?php echo $period;?></td>
                        <td><?php echo ($plan['Plan']['retailer'] != 'Powershop') ? round($tier_rates[0]['rate'][1] * 100 * $gst, 3) : round($tier_rates[0]['rate'][1] * 100 * $gst * (1 - $plan['Plan']['discount_guaranteed_elec'] / 100), 3);?>c</td>
                        <td>per kWh</td>
                    </tr>
                <?php endif;?>
                <?php if (count($tier_rates) == 2):?>
                    <tr>
                        <td>First <?php echo round($tier_rates[0]['tier']);?> kWh<?php echo $period;?></td>
                        <td><?php echo ($plan['Plan']['retailer'] != 'Powershop') ? round($tier_rates[0]['rate'][1] * 100 * $gst, 3) : round($tier_rates[0]['rate'][1] * 100 * $gst * (1 - $plan['Plan']['discount_guaranteed_elec'] / 100), 3);?>c</td>
                        <td>per kWh</td>
                    </tr>
                    <tr>
                        <td>Remainder kWh<?php echo $period;?></td>
                        <td><?php echo ($plan['Plan']['retailer'] != 'Powershop') ? round($tier_rates[1]['rate'][1] * 100 * $gst, 3) : round($tier_rates[1]['rate'][1] * 100 * $gst * (1 - $plan['Plan']['discount_guaranteed_elec'] / 100), 3);?>c</td>
                        <td>per kWh</td>
                    </tr>
                <?php endif;?>
                <?php if (count($tier_rates) == 3):?>
                    <tr>
                        <td>First <?php echo round($tier_rates[0]['tier']);?> kWh<?php echo $period;?></td>
                        <td><?php echo ($plan['Plan']['retailer'] != 'Powershop') ? round($tier_rates[0]['rate'][1] * 100 * $gst, 3) : round($tier_rates[0]['rate'][1] * 100 * $gst * (1 - $plan['Plan']['discount_guaranteed_elec'] / 100), 3);?>c</td>
                        <td>per kWh</td>
                    </tr>
                    <tr>
                        <td>Next <?php echo round($tier_rates[1]['tier']);?> kWh<?php echo $period;?></td>
                        <td><?php echo ($plan['Plan']['retailer'] != 'Powershop') ? round($tier_rates[1]['rate'][1] * 100 * $gst, 3) : round($tier_rates[1]['rate'][1] * 100 * $gst * (1 - $plan['Plan']['discount_guaranteed_elec'] / 100), 3);?>c</td>
                        <td>per kWh</td>
                    </tr>
                    <tr>
                        <td>Remainder kWh<?php echo $period;?></td>
                        <td><?php echo ($plan['Plan']['retailer'] != 'Powershop') ? round($tier_rates[2]['rate'][1] * 100 * $gst, 3) : round($tier_rates[2]['rate'][1] * 100 * $gst * (1 - $plan['Plan']['discount_guaranteed_elec'] / 100), 3);?>c</td>
                        <td>per kWh</td>
                    </tr>
                <?php endif;?>
                <?php if (count($tier_rates) == 4):?>
                    <tr>
                        <td>First <?php echo round($tier_rates[0]['tier']);?> kWh<?php echo $period;?></td>
                        <td><?php echo ($plan['Plan']['retailer'] != 'Powershop') ? round($tier_rates[0]['rate'][1] * 100 * $gst, 3) : round($tier_rates[0]['rate'][1] * 100 * $gst * (1 - $plan['Plan']['discount_guaranteed_elec'] / 100), 3);?>c</td>
                        <td>per kWh</td>
                    </tr>
                    <tr>
                        <td>Next <?php echo round($tier_rates[1]['tier']);?> kWh<?php echo $period;?></td>
                        <td><?php echo ($plan['Plan']['retailer'] != 'Powershop') ? round($tier_rates[1]['rate'][1] * 100 * $gst, 3) : round($tier_rates[1]['rate'][1] * 100 * $gst * (1 - $plan['Plan']['discount_guaranteed_elec'] / 100), 3);?>c</td>
                        <td>per kWh</td>
                    </tr>
                    <tr>
                        <td>Next <?php echo round($tier_rates[2]['tier']);?> kWh<?php echo $period;?></td>
                        <td><?php echo ($plan['Plan']['retailer'] != 'Powershop') ? round($tier_rates[2]['rate'][1] * 100 * $gst, 3) : round($tier_rates[2]['rate'][1] * 100 * $gst * (1 - $plan['Plan']['discount_guaranteed_elec'] / 100), 3);?>c</td>
                        <td>per kWh</td>
                    </tr>
                    <tr>
                        <td>Remainder kWh<?php echo $period;?></td>
                        <td><?php echo ($plan['Plan']['retailer'] != 'Powershop') ? round($tier_rates[3]['rate'][1] * 100 * $gst, 3) : round($tier_rates[3]['rate'][1] * 100 * $gst * (1 - $plan['Plan']['discount_guaranteed_elec'] / 100), 3);?>c</td>
                        <td>per kWh</td>
                    </tr>
                <?php endif;?>
                <?php if (count($tier_rates) == 5):?>
                    <tr>
                        <td>First <?php echo round($tier_rates[0]['tier']);?> kWh<?php echo $period;?></td>
                        <td><?php echo ($plan['Plan']['retailer'] != 'Powershop') ? round($tier_rates[0]['rate'][1] * 100 * $gst, 3) : round($tier_rates[0]['rate'][1] * 100 * $gst * (1 - $plan['Plan']['discount_guaranteed_elec'] / 100), 3);?>c</td>
                        <td>per kWh</td>
                    </tr>
                    <tr>
                        <td>Next <?php echo round($tier_rates[1]['tier']);?> kWh<?php echo $period;?></td>
                        <td><?php echo ($plan['Plan']['retailer'] != 'Powershop') ? round($tier_rates[1]['rate'][1] * 100 * $gst, 3) : round($tier_rates[1]['rate'][1] * 100 * $gst * (1 - $plan['Plan']['discount_guaranteed_elec'] / 100), 3);?>c</td>
                        <td>per kWh</td>
                    </tr>
                    <tr>
                        <td>Next <?php echo round($tier_rates[2]['tier']);?> kWh<?php echo $period;?></td>
                        <td><?php echo ($plan['Plan']['retailer'] != 'Powershop') ? round($tier_rates[2]['rate'][1] * 100 * $gst, 3) : round($tier_rates[2]['rate'][1] * 100 * $gst * (1 - $plan['Plan']['discount_guaranteed_elec'] / 100), 3);?>c</td>
                        <td>per kWh</td>
                    </tr>
                    <tr>
                        <td>Next <?php echo round($tier_rates[3]['tier']);?> kWh<?php echo $period;?></td>
                        <td><?php echo ($plan['Plan']['retailer'] != 'Powershop') ? round($tier_rates[3]['rate'][1] * 100 * $gst, 3) : round($tier_rates[3]['rate'][1] * 100 * $gst * (1 - $plan['Plan']['discount_guaranteed_elec'] / 100), 3);?>c</td>
                        <td>per kWh</td>
                    </tr>
                    <tr>
                        <td>Remainder kWh<?php echo $period;?></td>
                        <td><?php echo ($plan['Plan']['retailer'] != 'Powershop') ? round($tier_rates[4]['rate'][1] * 100 * $gst, 3) : round($tier_rates[4]['rate'][1] * 100 * $gst * (1 - $plan['Plan']['discount_guaranteed_elec'] / 100), 3);?>c</td>
                        <td>per kWh</td>
                    </tr>
                <?php endif;?>
            </table>
        <?php else:?>
            <?php if ($step1['elec_meter_type'] == 'Single Rate' || $step1['elec_meter_type2'] == 'Single Rate'):?>
                <h4>General Usage Electricity Rates</h4>
            <?php else:?>
                <h4>Peak Electricity Usage Rates</h4>
            <?php endif;?>
            <table class="table table-striped">
                <tr>
                    <th>Rate</th><th>GST <?php if ($include_gst):?>inclusive<?php else:?>exclusive<?php endif;?></th><th></th>
                </tr>
                <tr>
                    <td>Daily Supply Charge</td>
                    <td><?php echo ($plan['Plan']['retailer'] != 'Powershop') ? round($plan['Plan']['elec_rate']['stp'] / $stp_ratio * 100 * $gst, 3) : round($plan['Plan']['elec_rate']['stp'] / $stp_ratio * 100 * $gst * (1 - $plan['Plan']['discount_guaranteed_elec'] / 100), 3);?>c</td>
                    <td>per day</td>
                </tr>
                <?php if (count($tier_rates) == 1):?>
                    <tr>
                        <td>Charge per kWh<?php echo $period;?></td>
                        <td><?php echo ($plan['Plan']['retailer'] != 'Powershop') ? round($tier_rates[0]['rate'] * 100 * $gst, 3) : round($tier_rates[0]['rate'] * 100 * $gst * (1 - $plan['Plan']['discount_guaranteed_elec'] / 100), 3);?>c</td>
                        <td>per kWh</td>
                    </tr>
                <?php endif;?>
                <?php if (count($tier_rates) == 2):?>
                    <tr>
                        <td>First <?php echo round($tier_rates[0]['tier']);?> kWh<?php echo $period;?></td>
                        <td><?php echo ($plan['Plan']['retailer'] != 'Powershop') ? round($tier_rates[0]['rate'] * 100 * $gst, 3) : round($tier_rates[0]['rate'] * 100 * $gst * (1 - $plan['Plan']['discount_guaranteed_elec'] / 100), 3);?>c</td>
                        <td>per kWh</td>
                    </tr>
                    <tr>
                        <td>Remainder kWh<?php echo $period;?></td>
                        <td><?php echo ($plan['Plan']['retailer'] != 'Powershop') ? round($tier_rates[1]['rate'] * 100 * $gst, 3) : round($tier_rates[1]['rate'] * 100 * $gst * (1 - $plan['Plan']['discount_guaranteed_elec'] / 100), 3);?>c</td>
                        <td>per kWh</td>
                    </tr>
                <?php endif;?>
                <?php if (count($tier_rates) == 3):?>
                    <tr>
                        <td>First <?php echo round($tier_rates[0]['tier']);?> kWh<?php echo $period;?></td>
                        <td><?php echo ($plan['Plan']['retailer'] != 'Powershop') ? round($tier_rates[0]['rate'] * 100 * $gst, 3) : round($tier_rates[0]['rate'] * 100 * $gst * (1 - $plan['Plan']['discount_guaranteed_elec'] / 100), 3);?>c</td>
                        <td>per kWh</td>
                    </tr>
                    <tr>
                        <td>Next <?php echo round($tier_rates[1]['tier']);?> kWh<?php echo $period;?></td>
                        <td><?php echo ($plan['Plan']['retailer'] != 'Powershop') ? round($tier_rates[1]['rate'] * 100 * $gst, 3) : round($tier_rates[1]['rate'] * 100 * $gst * (1 - $plan['Plan']['discount_guaranteed_elec'] / 100), 3);?>c</td>
                        <td>per kWh</td>
                    </tr>
                    <tr>
                        <td>Remainder kWh<?php echo $period;?></td>
                        <td><?php echo ($plan['Plan']['retailer'] != 'Powershop') ? round($tier_rates[2]['rate'] * 100 * $gst, 3) : round($tier_rates[2]['rate'] * 100 * $gst * (1 - $plan['Plan']['discount_guaranteed_elec'] / 100), 3);?>c</td>
                        <td>per kWh</td>
                    </tr>
                <?php endif;?>
                <?php if (count($tier_rates) == 4):?>
                    <tr>
                        <td>First <?php echo round($tier_rates[0]['tier']);?> kWh<?php echo $period;?></td>
                        <td><?php echo ($plan['Plan']['retailer'] != 'Powershop') ? round($tier_rates[0]['rate'] * 100 * $gst, 3) : round($tier_rates[0]['rate'] * 100 * $gst * (1 - $plan['Plan']['discount_guaranteed_elec'] / 100), 3);?>c</td>
                        <td>per kWh</td>
                    </tr>
                    <tr>
                        <td>Next <?php echo round($tier_rates[1]['tier']);?> kWh<?php echo $period;?></td>
                        <td><?php echo ($plan['Plan']['retailer'] != 'Powershop') ? round($tier_rates[1]['rate'] * 100 * $gst, 3) : round($tier_rates[1]['rate'] * 100 * $gst * (1 - $plan['Plan']['discount_guaranteed_elec'] / 100), 3);?>c</td>
                        <td>per kWh</td>
                    </tr>
                    <tr>
                        <td>Next <?php echo round($tier_rates[2]['tier']);?> kWh<?php echo $period;?></td>
                        <td><?php echo ($plan['Plan']['retailer'] != 'Powershop') ? round($tier_rates[2]['rate'] * 100 * $gst, 3) : round($tier_rates[2]['rate'] * 100 * $gst * (1 - $plan['Plan']['discount_guaranteed_elec'] / 100), 3);?>c</td>
                        <td>per kWh</td>
                    </tr>
                    <tr>
                        <td>Remainder kWh<?php echo $period;?></td>
                        <td><?php echo ($plan['Plan']['retailer'] != 'Powershop') ? round($tier_rates[3]['rate'] * 100 * $gst, 3) : round($tier_rates[3]['rate'] * 100 * $gst * (1 - $plan['Plan']['discount_guaranteed_elec'] / 100), 3);?>c</td>
                        <td>per kWh</td>
                    </tr>
                <?php endif;?>
                <?php if (count($tier_rates) == 5):?>
                    <tr>
                        <td>First <?php echo round($tier_rates[0]['tier']);?> kWh<?php echo $period;?></td>
                        <td><?php echo ($plan['Plan']['retailer'] != 'Powershop') ? round($tier_rates[0]['rate'] * 100 * $gst, 3) : round($tier_rates[0]['rate'] * 100 * $gst * (1 - $plan['Plan']['discount_guaranteed_elec'] / 100), 3);?>c</td>
                        <td>per kWh</td>
                    </tr>
                    <tr>
                        <td>Next <?php echo round($tier_rates[1]['tier']);?> kWh<?php echo $period;?></td>
                        <td><?php echo ($plan['Plan']['retailer'] != 'Powershop') ? round($tier_rates[1]['rate'] * 100 * $gst, 3) : round($tier_rates[1]['rate'] * 100 * $gst * (1 - $plan['Plan']['discount_guaranteed_elec'] / 100), 3);?>c</td>
                        <td>per kWh</td>
                    </tr>
                    <tr>
                        <td>Next <?php echo round($tier_rates[2]['tier']);?> kWh<?php echo $period;?></td>
                        <td><?php echo ($plan['Plan']['retailer'] != 'Powershop') ? round($tier_rates[2]['rate'] * 100 * $gst, 3) : round($tier_rates[2]['rate'] * 100 * $gst * (1 - $plan['Plan']['discount_guaranteed_elec'] / 100), 3);?>c</td>
                        <td>per kWh</td>
                    </tr>
                    <tr>
                        <td>Next <?php echo round($tier_rates[3]['tier']);?> kWh<?php echo $period;?></td>
                        <td><?php echo ($plan['Plan']['retailer'] != 'Powershop') ? round($tier_rates[3]['rate'] * 100 * $gst, 3) : round($tier_rates[3]['rate'] * 100 * $gst * (1 - $plan['Plan']['discount_guaranteed_elec'] / 100), 3);?>c</td>
                        <td>per kWh</td>
                    </tr>
                    <tr>
                        <td>Remainder kWh<?php echo $period;?></td>
                        <td><?php echo ($plan['Plan']['retailer'] != 'Powershop') ? round($tier_rates[4]['rate'] * 100 * $gst, 3) : round($tier_rates[4]['rate'] * 100 * $gst * (1 - $plan['Plan']['discount_guaranteed_elec'] / 100), 3);?>c</td>
                        <td>per kWh</td>
                    </tr>
                <?php endif;?>
            </table>
        <?php endif;?>
        <?php if ($plan['Plan']['elec_rate']['controlled_load_1_rate_1'] || $plan['Plan']['elec_rate']['controlled_load_2_rate']):?>
            <h4>Controlled Load Electricity Rates</h4>
            <table class="table table-striped">
                <tr>
                    <th>Rate</th><th>GST <?php if ($include_gst):?>inclusive<?php else:?>exclusive<?php endif;?></th><th></th>
                </tr>
                <?php if ($plan['Plan']['elec_rate']['controlled_load_1_rate_1']):?>
                    <?php if (!$plan['Plan']['elec_rate']['controlled_load_tier_1']):?>
                        <tr>
                            <td>Charge per kWh<?php echo $period;?></td>
                            <td><?php echo ($plan['Plan']['retailer'] != 'Powershop') ? round($plan['Plan']['elec_rate']['controlled_load_1_rate_1'] * 100 * $gst, 3) : round($plan['Plan']['elec_rate']['controlled_load_1_rate_1'] * 100 * $gst * (1 - $plan['Plan']['discount_guaranteed_elec'] / 100), 3);?>c</td>
                            <td>per kWh</td>
                        </tr>
                    <?php else:?>
                        <tr>
                            <td>First <?php echo round($plan['Plan']['elec_rate']['controlled_load_tier_1'] * $ratio);?> kWh<?php echo $period;?></td>
                            <td><?php echo ($plan['Plan']['retailer'] != 'Powershop') ? round($plan['Plan']['elec_rate']['controlled_load_1_rate_1'] * 100 * $gst, 3) : round($plan['Plan']['elec_rate']['controlled_load_1_rate_1'] * 100 * $gst * (1 - $plan['Plan']['discount_guaranteed_elec'] / 100), 3);?>c</td>
                            <td>per kWh</td>
                        </tr>
                        <tr>
                            <td>Remainder kWh<?php echo $period;?></td>
                            <td><?php echo ($plan['Plan']['retailer'] != 'Powershop') ? round($plan['Plan']['elec_rate']['controlled_load_1_rate_2'] * 100 * $gst, 3) : round($plan['Plan']['elec_rate']['controlled_load_1_rate_2'] * 100 * $gst * (1 - $plan['Plan']['discount_guaranteed_elec'] / 100), 3);?>c</td>
                            <td>per kWh</td>
                        </tr>
                    <?php endif;?>
                <?php endif;?>
                <?php if ($plan['Plan']['elec_rate']['controlled_load_2_rate']):?>
                    <tr>
                        <td>Charge per kWh<?php echo $period;?></td>
                        <td><?php echo ($plan['Plan']['retailer'] != 'Powershop') ? round($plan['Plan']['elec_rate']['controlled_load_2_rate'] * 100 * $gst, 3) : round($plan['Plan']['elec_rate']['controlled_load_2_rate'] * 100 * $gst * (1 - $plan['Plan']['discount_guaranteed_elec'] / 100), 3);?>c</td>
                        <td>per kWh</td>
                    </tr>
                <?php endif;?>
            </table>
        <?php endif;?>
        <?php if ($plan['Plan']['elec_rate']['shoulder_rate']):?>
            <strong>Shoulder Electricity Rates</strong>
            <table class="table table-striped">
                <tr>
                    <td>Charge per kWh<?php echo $period;?></td>
                    <td><?php echo ($plan['Plan']['retailer'] != 'Powershop') ? round($plan['Plan']['elec_rate']['shoulder_rate'] * 100 * $gst, 3) : round($plan['Plan']['elec_rate']['shoulder_rate'] * 100 * $gst * (1 - $plan['Plan']['discount_guaranteed_elec'] / 100), 3);?>c</td>
                    <td>per kWh</td>
                </tr>
            </table>
        <?php endif;?>
        <?php if ($plan['Plan']['elec_rate']['off_peak_rate']):?>
            <strong>Off Peak Electricity Rates</strong>
            <table class="table table-striped">
                <tr>
                    <td>Charge per kWh<?php echo $period;?></td>
                    <td><?php echo ($plan['Plan']['retailer'] != 'Powershop') ? round($plan['Plan']['elec_rate']['off_peak_rate'] * 100 * $gst, 3) : round($plan['Plan']['elec_rate']['off_peak_rate'] * 100 * $gst * (1 - $plan['Plan']['discount_guaranteed_elec'] / 100), 3);?>c</td>
                    <td>per kWh</td>
                </tr>
            </table>
        <?php endif;?>
        <?php if ($plan['Plan']['elec_rate']['climate_saver_rate']):?>
            <?php $climate_saver_rate_arr = explode('/', $plan['Plan']['elec_rate']['climate_saver_rate']);?>
            <h4>Climate Saver Electricity Rates</h4>
            <table class="table table-striped">
                <tr>
                    <th>Rate</th><th>GST <?php if ($include_gst):?>inclusive<?php else:?>exclusive<?php endif;?></th><th></th>
                </tr>
                <tr>
                    <td>Charge per kWh<?php echo $period;?></td>
                    <td><?php echo ($plan['Plan']['retailer'] != 'Powershop') ? round($climate_saver_rate_arr[0] * 100 * $gst, 3) : round($climate_saver_rate_arr[0] * 100 * $gst * (1 - $plan['Plan']['discount_guaranteed_elec'] / 100), 3);?>c/<?php echo ($plan['Plan']['retailer'] != 'Powershop') ? round($climate_saver_rate_arr[1] * 100 * $gst, 3) : round($climate_saver_rate_arr[1] * 100 * $gst * (1 - $plan['Plan']['discount_guaranteed_elec'] / 100), 3);?>c</td>
                    <td>per kWh</td>
                </tr>
            </table>
        <?php endif;?>
    <?php endif;?>
<?php endif;?>
<?php if ($step1['plan_type'] == 'Gas' || $step1['plan_type'] == 'Dual'):?>
    <?php if (empty($plan['Plan']['gas_rate'])):?>
        <p style="color:#ff9302;">No Gas Rates</p>
    <?php else:?>
        <?php
        $include_gst = true;
        if ($plan['Plan']['gas_rate']['gst_rates'] == 'No') {
            $gst = 1.1;
        } else {
            $gst = 1;
        }
        $ratio = 1;
        switch ($plan['Plan']['gas_rate']['rate_tier_period']) {
            case '2':
                if ($user['state'] == 'VIC') {
                    $period = '/2 mth';
                }
                else {
                    $period = '/qtr';
                    $ratio = 1.5;
                }
                break;
            case 'D':
                if ($user['state'] == 'VIC') {
                    $period = '/2 mth';
                    $ratio = 60.83;
                }
                else {
                    //$period = 'per day';
                    $period = '/qtr';
                    $ratio = 91.25;
                }
                break;
            case 'M':
                if ($user['state'] == 'VIC') {
                    $period = '/2 mth';
                    $ratio = 2;
                }
                else {
                    //$period = 'per month';
                    $period = '/qtr';
                    $ratio = 3;
                }
                break;
            case 'Q':
                if ($user['state'] == 'VIC') {
                    $period = '/2 mth';
                    $ratio = 0.67;
                }
                else {
                    $period = '/qtr';
                }
                break;
            case 'Y':
                if ($user['state'] == 'VIC') {
                    $period = '/2 mth';
                    $ratio = 0.17;
                }
                else {
                    //$period = 'per year';
                    $period = '/qtr';
                    $ratio = 0.25;
                }
                break;
        }
        $stp_ratio = 1;
        switch ($plan['Plan']['gas_rate']['stp_period']) {
            case '2':
                $stp_period = 'every 2 month';
                break;
            case 'D':
                $stp_period = 'per day';
                break;
            case 'M':
                $stp_period = 'per month';
                break;
            case 'Q':
                $stp_period = 'per quarter';
                break;
            case 'Y':
                //$stp_period = 'per year';
                $stp_period = 'per day';
                $stp_ratio = 365;
                break;
        }
        $peak_tier_rates = array();
        if ($plan['Plan']['gas_rate']['peak_rate_1']) {
            $peak_tier_rates[] = array('tier' => $plan['Plan']['gas_rate']['peak_tier_1'] * $ratio, 'rate' => $plan['Plan']['gas_rate']['peak_rate_1'] / 100);
        }
        if ($plan['Plan']['gas_rate']['peak_rate_2']) {
            $peak_tier_rates[] = array('tier' => $plan['Plan']['gas_rate']['peak_tier_2'] * $ratio, 'rate' => $plan['Plan']['gas_rate']['peak_rate_2'] / 100);
        }
        if ($plan['Plan']['gas_rate']['peak_rate_3']) {
            $peak_tier_rates[] = array('tier' => $plan['Plan']['gas_rate']['peak_tier_3'] * $ratio, 'rate' => $plan['Plan']['gas_rate']['peak_rate_3'] / 100);
        }
        if ($plan['Plan']['gas_rate']['peak_rate_4']) {
            $peak_tier_rates[] = array('tier' => $plan['Plan']['gas_rate']['peak_tier_4'] * $ratio, 'rate' => $plan['Plan']['gas_rate']['peak_rate_4'] / 100);
        }
        if ($plan['Plan']['gas_rate']['peak_rate_5']) {
            $peak_tier_rates[] = array('tier' => $plan['Plan']['gas_rate']['peak_tier_5'] * $ratio, 'rate' => $plan['Plan']['gas_rate']['peak_rate_5'] / 100);
        }
        if ($plan['Plan']['gas_rate']['peak_rate_6']) {
            $peak_tier_rates[] = array('tier' => 0, 'rate' => $plan['Plan']['gas_rate']['peak_rate_6'] / 100);
        }
        $off_peak_tier_rates = array();
        if ($plan['Plan']['gas_rate']['off_peak_rate_1']) {
            $off_peak_tier_rates[] = array('tier' => $plan['Plan']['gas_rate']['off_peak_tier_1'] * $ratio, 'rate' => $plan['Plan']['gas_rate']['off_peak_rate_1'] / 100);
        }
        if ($plan['Plan']['gas_rate']['off_peak_rate_2']) {
            $off_peak_tier_rates[] = array('tier' => $plan['Plan']['gas_rate']['off_peak_tier_2'] * $ratio, 'rate' => $plan['Plan']['gas_rate']['off_peak_rate_2'] / 100);
        }
        if ($plan['Plan']['gas_rate']['off_peak_rate_3']) {
            $off_peak_tier_rates[] = array('tier' => $plan['Plan']['gas_rate']['off_peak_tier_3'] * $ratio, 'rate' => $plan['Plan']['gas_rate']['off_peak_rate_3'] / 100);
        }
        if ($plan['Plan']['gas_rate']['off_peak_rate_4']) {
            $off_peak_tier_rates[] = array('tier' => $plan['Plan']['gas_rate']['off_peak_tier_4'] * $ratio, 'rate' => $plan['Plan']['gas_rate']['off_peak_rate_4'] / 100);
        }
        if ($plan['Plan']['gas_rate']['off_peak_rate_5']) {
            $off_peak_tier_rates[] = array('tier' => $plan['Plan']['gas_rate']['off_peak_tier_5'] * $ratio, 'rate' => $plan['Plan']['gas_rate']['off_peak_rate_5'] / 100);
        }
        ?>
        <h4>Peak Gas Rates</h4>
        <table class="table table-striped">
            <tr>
                <th>Rate</th><th>GST <?php if ($include_gst):?>inclusive<?php else:?>exclusive<?php endif;?></th><th></th>
            </tr>
            <tr>
                <td>Daily Supply Charge</td>
                <td><?php echo round($plan['Plan']['gas_rate']['stp'] / $stp_ratio * 100 * $gst, 3);?>c</td>
                <td>per day</td>
            </tr>
            <?php if (count($peak_tier_rates) == 1):?>
                <tr>
                    <td>Charge per MJ<?php echo $period;?></td>
                    <td><?php echo round($peak_tier_rates[0]['rate'] * 100 * $gst, 3);?>c</td>
                    <td>per Mj</td>
                </tr>
            <?php endif;?>
            <?php if (count($peak_tier_rates) == 2):?>
                <tr>
                    <td>First <?php echo round($peak_tier_rates[0]['tier']);?> MJ<?php echo $period;?></td>
                    <td><?php echo round($peak_tier_rates[0]['rate'] * 100 * $gst, 3);?>c</td>
                    <td>per Mj</td>
                </tr>
                <tr>
                    <td>Remainder MJ<?php echo $period;?></td>
                    <td><?php echo round($peak_tier_rates[1]['rate'] * 100 * $gst, 3);?>c</td>
                    <td>per Mj</td>
                </tr>
            <?php endif;?>
            <?php if (count($peak_tier_rates) == 3):?>
                <tr>
                    <td>First <?php echo round($peak_tier_rates[0]['tier']);?> MJ<?php echo $period;?></td>
                    <td><?php echo round($peak_tier_rates[0]['rate'] * 100 * $gst, 3);?>c</td>
                    <td>per Mj</td>
                </tr>
                <tr>
                    <td>Next <?php echo round($peak_tier_rates[1]['tier']);?> MJ<?php echo $period;?></td>
                    <td><?php echo round($peak_tier_rates[1]['rate'] * 100 * $gst, 3);?>c</td>
                    <td>per Mj</td>
                </tr>
                <tr>
                    <td>Remainder MJ<?php echo $period;?></td>
                    <td><?php echo round($peak_tier_rates[2]['rate'] * 100 * $gst, 3);?>c</td>
                    <td>per Mj</td>
                </tr>
            <?php endif;?>
            <?php if (count($peak_tier_rates) == 4):?>
                <tr>
                    <td>First <?php echo round($peak_tier_rates[0]['tier']);?> MJ<?php echo $period;?></td>
                    <td><?php echo round($peak_tier_rates[0]['rate'] * 100 * $gst, 3);?>c</td>
                    <td>per Mj</td>
                </tr>
                <tr>
                    <td>Next <?php echo round($peak_tier_rates[1]['tier']);?> MJ<?php echo $period;?></td>
                    <td><?php echo round($peak_tier_rates[1]['rate'] * 100 * $gst, 3);?>c</td>
                    <td>per Mj</td>
                </tr>
                <tr>
                    <td>Next <?php echo round($peak_tier_rates[2]['tier']);?> MJ<?php echo $period;?></td>
                    <td><?php echo round($peak_tier_rates[2]['rate'] * 100 * $gst, 3);?>c</td>
                    <td>per Mj</td>
                </tr>
                <tr>
                    <td>Remainder MJ<?php echo $period;?></td>
                    <td><?php echo round($peak_tier_rates[3]['rate'] * 100 * $gst, 3);?>c</td>
                    <td>per Mj</td>
                </tr>
            <?php endif;?>
            <?php if (count($peak_tier_rates) == 5):?>
                <tr>
                    <td>First <?php echo round($peak_tier_rates[0]['tier']);?> MJ<?php echo $period;?></td>
                    <td><?php echo round($peak_tier_rates[0]['rate'] * 100 * $gst, 3);?>c</td>
                    <td>per Mj</td>
                </tr>
                <tr>
                    <td>Next <?php echo round($peak_tier_rates[1]['tier']);?> MJ<?php echo $period;?></td>
                    <td><?php echo round($peak_tier_rates[1]['rate'] * 100 * $gst, 3);?>c</td>
                    <td>per Mj</td>
                </tr>
                <tr>
                    <td>Next <?php echo round($peak_tier_rates[2]['tier']);?> MJ<?php echo $period;?></td>
                    <td><?php echo round($peak_tier_rates[2]['rate'] * 100 * $gst, 3);?>c</td>
                    <td>per Mj</td>
                </tr>
                <tr>
                    <td>Next <?php echo round($peak_tier_rates[3]['tier']);?> MJ<?php echo $period;?></td>
                    <td><?php echo round($peak_tier_rates[3]['rate'] * 100 * $gst, 3);?>c</td>
                    <td>per Mj</td>
                </tr>
                <tr>
                    <td>Remainder MJ<?php echo $period;?></td>
                    <td><?php echo round($peak_tier_rates[4]['rate'] * 100 * $gst, 3);?>c</td>
                    <td>per Mj</td>
                </tr>
            <?php endif;?>
            <?php if (count($peak_tier_rates) == 6):?>
                <tr>
                    <td>First <?php echo round($peak_tier_rates[0]['tier']);?> MJ<?php echo $period;?></td>
                    <td><?php echo round($peak_tier_rates[0]['rate'] * 100 * $gst, 3);?>c</td>
                    <td>per Mj</td>
                </tr>
                <tr>
                    <td>Next <?php echo round($peak_tier_rates[1]['tier']);?> MJ<?php echo $period;?></td>
                    <td><?php echo round($peak_tier_rates[1]['rate'] * 100 * $gst, 3);?>c</td>
                    <td>per Mj</td>
                </tr>
                <tr>
                    <td>Next <?php echo round($peak_tier_rates[2]['tier']);?> MJ<?php echo $period;?></td>
                    <td><?php echo round($peak_tier_rates[2]['rate'] * 100 * $gst, 3);?>c</td>
                    <td>per Mj</td>
                </tr>
                <tr>
                    <td>Next <?php echo round($peak_tier_rates[3]['tier']);?> MJ<?php echo $period;?></td>
                    <td><?php echo round($peak_tier_rates[3]['rate'] * 100 * $gst, 3);?>c</td>
                    <td>per Mj</td>
                </tr>
                <tr>
                    <td>Next <?php echo round($peak_tier_rates[4]['tier']);?> MJ<?php echo $period;?></td>
                    <td><?php echo round($peak_tier_rates[4]['rate'] * 100 * $gst, 3);?>c</td>
                    <td>per Mj</td>
                </tr>
                <tr>
                    <td>Remainder MJ<?php echo $period;?></td>
                    <td><?php echo round($peak_tier_rates[5]['rate'] * 100 * $gst, 3);?>c</td>
                    <td>per Mj</td>
                </tr>
            <?php endif;?>
        </table>
        <?php if ($off_peak_tier_rates):?>
            <h4>Off Peak Gas Rates</h4>
            <table class="table table-striped">
                <tr>
                    <th>Rate</th><th>GST <?php if ($include_gst):?>inclusive<?php else:?>exclusive<?php endif;?></th><td></td>
                </tr>
                <?php if (count($off_peak_tier_rates) == 1):?>
                    <tr>
                        <td>Charge per MJ<?php echo $period;?></td>
                        <td><?php echo round($off_peak_tier_rates[0]['rate'] * 100 * $gst, 3);?>c</td>
                        <td>per Mj</td>
                    </tr>
                <?php endif;?>
                <?php if (count($off_peak_tier_rates) == 2):?>
                    <tr>
                        <td>First <?php echo round($off_peak_tier_rates[0]['tier']);?> MJ<?php echo $period;?></td>
                        <td><?php echo round($off_peak_tier_rates[0]['rate'] * 100 * $gst, 3);?>c</td>
                        <td>per Mj</td>
                    </tr>
                    <tr>
                        <td>Remainder MJ<?php echo $period;?></td>
                        <td><?php echo round($off_peak_tier_rates[1]['rate'] * 100 * $gst, 3);?>c</td>
                        <td>per Mj</td>
                    </tr>
                <?php endif;?>
                <?php if (count($off_peak_tier_rates) == 3):?>
                    <tr>
                        <td>First <?php echo round($off_peak_tier_rates[0]['tier']);?> MJ<?php echo $period;?></td>
                        <td><?php echo round($off_peak_tier_rates[0]['rate'] * 100 * $gst, 3);?>c</td>
                        <td>per Mj</td>
                    </tr>
                    <tr>
                        <td>Next <?php echo round($off_peak_tier_rates[1]['tier']);?> MJ<?php echo $period;?></td>
                        <td><?php echo round($off_peak_tier_rates[1]['rate'] * 100 * $gst, 3);?>c</td>
                        <td>per Mj</td>
                    </tr>
                    <tr>
                        <td>Remainder MJ<?php echo $period;?></td>
                        <td><?php echo round($off_peak_tier_rates[2]['rate'] * 100 * $gst, 3);?>c</td>
                        <td>per Mj</td>
                    </tr>
                <?php endif;?>
                <?php if (count($off_peak_tier_rates) == 4):?>
                    <tr>
                        <td>First <?php echo round($off_peak_tier_rates[0]['tier']);?> MJ<?php echo $period;?></td>
                        <td><?php echo round($off_peak_tier_rates[0]['rate'] * 100 * $gst, 3);?>c</td>
                        <td>per Mj</td>
                    </tr>
                    <tr>
                        <td>Next <?php echo round($off_peak_tier_rates[1]['tier']);?> MJ<?php echo $period;?></td>
                        <td><?php echo round($off_peak_tier_rates[1]['rate'] * 100 * $gst, 3);?>c</td>
                        <td>per Mj</td>
                    </tr>
                    <tr>
                        <td>Next <?php echo round($off_peak_tier_rates[2]['tier']);?> MJ<?php echo $period;?></td>
                        <td><?php echo round($off_peak_tier_rates[2]['rate'] * 100 * $gst, 3);?>c</td>
                        <td>per Mj</td>
                    </tr>
                    <tr>
                        <td>Remainder MJ<?php echo $period;?></td>
                        <td><?php echo round($off_peak_tier_rates[3]['rate'] * 100 * $gst, 3);?>c</td>
                        <td>per Mj</td>
                    </tr>
                <?php endif;?>
                <?php if (count($off_peak_tier_rates) == 5):?>
                    <tr>
                        <td>First <?php echo round($off_peak_tier_rates[0]['tier']);?> MJ<?php echo $period;?></td>
                        <td><?php echo round($off_peak_tier_rates[0]['rate'] * 100 * $gst, 3);?>c</td>
                        <td>per Mj</td>
                    </tr>
                    <tr>
                        <td>Next <?php echo round($off_peak_tier_rates[1]['tier']);?> MJ<?php echo $period;?></td>
                        <td><?php echo round($off_peak_tier_rates[1]['rate'] * 100 * $gst, 3);?>c</td>
                        <td>per Mj</td>
                    </tr>
                    <tr>
                        <td>Next <?php echo round($off_peak_tier_rates[2]['tier']);?> MJ<?php echo $period;?></td>
                        <td><?php echo round($off_peak_tier_rates[2]['rate'] * 100 * $gst, 3);?>c</td>
                        <td>per Mj</td>
                    </tr>
                    <tr>
                        <td>Next <?php echo round($off_peak_tier_rates[3]['tier']);?> MJ<?php echo $period;?></td>
                        <td><?php echo round($off_peak_tier_rates[3]['rate'] * 100 * $gst, 3);?>c</td>
                        <td>per Mj</td>
                    </tr>
                    <tr>
                        <td>Remainder MJ<?php echo $period;?></td>
                        <td><?php echo round($off_peak_tier_rates[4]['rate'] * 100 * $gst, 3);?>c</td>
                        <td>per Mj</td>
                    </tr>
                <?php endif;?>
            </table>
        <?php endif;?>
    <?php endif;?>
<?php endif;?>