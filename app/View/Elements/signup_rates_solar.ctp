<?php if ($plan['Plan']['solar_rate']):?>
    <h4>Solar Rates</h4>
    <table class="table table-striped">
        <?php if ($step1['looking_for'] != 'Move Properties' || !in_array($user['state'], array('NSW', 'QLD'))):?>
            <tr>
                <td>Govt contribution per kWh</td>
                <td><?php echo ($plan['Plan']['solar_rate']['government'] == '1 for 1') ? round($tier_rates[0]['rate'] * 100, 3) : $plan['Plan']['solar_rate']['government'];?>c</td>
            </tr>
        <?php endif;?>
        <?php if ($plan['Plan']['solar_boost_fit']):?>
        <tr>
            <td>Retailer contribution per kWh</td>
            <td><?php echo $plan['Plan']['solar_boost_fit'];?>c</td>
        </tr>
        <?php else:?>
        <tr>
            <td>Retailer contribution per kWh</td>
            <td><?php echo ($plan['Plan']['solar_rate']['retailer'] == '1 for 1') ? round($tier_rates[0]['rate'] * 100, 3) : $plan['Plan']['solar_rate']['retailer'];?>c</td>
        </tr>
        <?php endif;?>
        <tr>
            <td>Total contribution per kWh</td>
            <td>
                <?php
                $govt_solar_rate = ($plan['Plan']['solar_rate']['government'] == '1 for 1') ? round($tier_rates[0]['rate'] * 100, 3) : $plan['Plan']['solar_rate']['government'];
                if ($step1['looking_for'] == 'Move Properties' && in_array($user['state'], array('NSW', 'QLD'))) {
                    $govt_solar_rate = 0;
                }
                $retailer_solar_rate = ($plan['Plan']['solar_rate']['retailer'] == '1 for 1') ? round($tier_rates[0]['rate'] * 100, 3) : $plan['Plan']['solar_rate']['retailer'];
                if ($plan['Plan']['solar_boost_fit']) {
                    $retailer_solar_rate = $plan['Plan']['solar_boost_fit'];
                }
                echo ($govt_solar_rate + $retailer_solar_rate);
                ?>c
            </td>
        </tr>
    </table>
<?php else:?>
    No Solar Rates
<?php endif;?>