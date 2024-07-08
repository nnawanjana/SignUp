<?php
$retention = $acquisition = false;
if ($lead['Supply']['nmi_acq_ret'][0] == 'Retention') {
    $retention = true;
} else {
    $acquisition = true;
}
?>
<div class="container-fluid pdt100">
    <?php echo $this->element('signup_view_header'); ?>
    <section class="contact-deatils">
        <div class="contact-form">
        <?php echo $this->Session->flash(); ?>
            <div class=" clearfix" style="min-height: 600px">
                
            </div>
        </div>
    </section>
</div>
<div class="modal fade complete-modal" id="complete_modal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="complete_modalLabel" aria-hidden="true">
    <div class="modal-dialog" style="margin-top: 10%">
        <div class="modal-content">
            <div class="modal-body thanks-popup text-center">
                <h1>This sale has been posted to Velocify! Well done!</h1>

                <h2>Lead ID: <span id="complete_dialog_lead_id"><a href="https://lm.prod.velocify.com/Web/LeadAddEdit.aspx?LeadId=<?php echo $lead_id;?>" target="_blank"><?php echo $lead_id;?></a></span> <?php if ($cb_lead_id):?><span id="complete_dialog_cb_lead_id">CB Lead ID: <?php echo $cb_lead_id;?></span><?php endif;?></h2>
                <p><i>(Ensure customer has your name & number & advise to contact you or the retailer if they have any questions.)</i></p>
                <?php if ($lead['Contact']['email'] && $lead['Contact']['email'] != 'no@email.com.au'):?>
                <p class="receive_an_email">You will receive an email shortly from us. This is not your welcome pack. Your welcome pack will come from the retailer directly. </p>
                <?php else:?>
                <p class="receive_no_email">Your welcome pack will come direct from the retailer</p>
                <?php endif;?>
                <?php if ($user['step1']['looking_for'] == 'Move Properties'):?>
                <p class="popup_movein_text">If you experience any issues or concerns after our conversation today please don't hesitate to contact myself or the retailer directly. (Make sure Customer has the retailer contact number noted down)</p>
                <?php endif;?>
                <?php if ($acquisition && $user['step1']['looking_for'] == 'Transfer'):?>
                <p class="popup_transfer_acquisition_text">Your current retailer may attempt contact with you with a counter offer, if they do please give me a call so that I can check if it would be beneficial or not.<br>After your 10 Business day cooling off period you will receive one final bill from your current retailer & then you will switch to X retailer.</p>
                <?php endif;?>
                
                <p>
                    <input value='Sign up another property' class="btn btn-warning signup-another-property" type="button">
                    <input value='I am done' class="btn btn-warning no-thanks" type="button">
                </p>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function() {
    $("#complete_modal").modal('show');
});
</script>