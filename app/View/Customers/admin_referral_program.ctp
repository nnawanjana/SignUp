<div class="container-fluid">
    <?php echo $this->element('referral_program_header'); ?>
    <section class="contact-deatils">
        <div class="contact-form" style="max-width:1024px;">
            <h3 class="form-hdg">
                <div class="fh-inr">Referral Program</div>
            </h3>
            <div class="form clearfix" id="referral_form">
                <form action="#" id="referral" method="post">
                    <div class="form-data">
                        <div id="processing" style="display:none;"></div>
                        <table class="table">
                            <thead>
                            <tr>
                                <th width="25%">Referrer Name</th>
                                <th width="25%">Lead ID</th>
                                <th width="25%">Phone</th>
                                <th width="25%">Email</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td><div class="v2_field"><input class="required form-control" name="submitted[referrer_name]" type="text"/></div></td>
                                <td><div class="v2_field"><input class="required form-control" name="submitted[referrer_lead_id]" type="text"/></div></td>
                                <td><div class="v2_field"><input name="submitted[referrer_phone]" type="text" class="form-control"/></div></td>
                                <td><div class="v2_field"><input class="email form-control" name="submitted[referrer_email]" type="text"/></div></td>
                            </tr>
                            </tbody>
                        </table>
                        <table class="table referrals">
                            <thead>
                            <tr>
                                <th style="vertical-align:middle !important;">#</th>
                                <th width="19%">Referral Name(s)</th>
                                <th width="19%">Phone 1</th>
                                <th width="19%">Phone 2</th>
                                <th width="19%">Relationship</th>
                                <th width="19%">Time To Call</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td style="vertical-align:middle !important;"><span class="nr">1</span></td>
                                <td><div class="v2_field"><input class="form-control name-input required" name="submitted[referral_1_name]"
                                           rel="submitted[name]" type="text" value=""/></div></td>
                                <td><div class="v2_field"><input class="form-control phone-input required" name="submitted[referral_1_phone]"
                                           rel="submitted[phone]" type="text" value=""/></div></td>
                                <td><div class="v2_field"><input class="form-control phone-input" name="submitted[referral_1_secondaryphone]" type="text"
                                           value=""/></div></td>
                                <td><div class="v2_field"><input name="submitted[referral_1_relationship]" type="text" value="" class="form-control"/></div></td>
                                <td><div class="v2_field"><select name="submitted[referral_1_time_to_call]" class="form-control">
                                        <option value="morning">Morning</option>
                                        <option value="afternoon">Afternoon</option>
                                        <option value="evening">Evening</option>
                                    </select></div></td>
                            </tr>
                            <tr>
                                <td style="vertical-align:middle !important;"><span class="nr">2</span></td>
                                <td><div class="v2_field"><input class="form-control name-input" name="submitted[referral_2_name]" rel="submitted[name]"
                                           type="text" value=""/></div></td>
                                <td><div class="v2_field"><input class="form-control phone-input" name="submitted[referral_2_phone]" rel="submitted[phone]"
                                           type="text" value=""/></div></td>
                                <td><div class="v2_field"><input class="form-control phone-input" name="submitted[referral_2_secondaryphone]" type="text"
                                           value=""/></div></td>
                                <td><div class="v2_field"><input name="submitted[referral_2_relationship]" type="text" value="" class="form-control"/></div></td>
                                <td><div class="v2_field"><select name="submitted[referral_2_time_to_call]" class="form-control">
                                        <option value="morning">Morning</option>
                                        <option value="afternoon">Afternoon</option>
                                        <option value="evening">Evening</option>
                                    </select></div></td>
                            </tr>
                            <tr>
                                <td style="vertical-align:middle !important;"><span class="nr">3</span></td>
                                <td><div class="v2_field"><input class="form-control name-input" name="submitted[referral_3_name]" rel="submitted[name]"
                                           type="text" value=""/></div></td>
                                <td><div class="v2_field"><input class="form-control phone-input" name="submitted[referral_3_phone]" rel="submitted[phone]"
                                           type="text" value=""/></div></td>
                                <td><div class="v2_field"><input class="form-control phone-input" name="submitted[referral_3_secondaryphone]" type="text"
                                           value=""/></div></td>
                                <td><div class="v2_field"><input name="submitted[referral_3_relationship]" type="text" value="" class="form-control"/></div></td>
                                <td><div class="v2_field"><select name="submitted[referral_3_time_to_call]" class="form-control">
                                        <option value="morning">Morning</option>
                                        <option value="afternoon">Afternoon</option>
                                        <option value="evening">Evening</option>
                                    </select></div></td>
                            </tr>
                            <tr>
                                <td style="vertical-align:middle !important;"><span class="nr">4</span></td>
                                <td><div class="v2_field"><input class="form-control name-input" name="submitted[referral_4_name]" rel="submitted[name]"
                                           type="text" value=""/></div></td>
                                <td><div class="v2_field"><input class="form-control phone-input" name="submitted[referral_4_phone]" rel="submitted[phone]"
                                           type="text" value=""/></div></td>
                                <td><div class="v2_field"><input class="form-control phone-input" name="submitted[referral_4_secondaryphone]" type="text"
                                           value=""/></div></td>
                                <td><div class="v2_field"><input name="submitted[referral_4_relationship]" type="text" value="" class="form-control"/></div></td>
                                <td><div class="v2_field"><select name="submitted[referral_4_time_to_call]" class="form-control">
                                        <option value="morning">Morning</option>
                                        <option value="afternoon">Afternoon</option>
                                        <option value="evening">Evening</option>
                                    </select></div></td>
                            </tr>
                            <tr>
                                <td style="vertical-align:middle !important;"><span class="nr">5</span></td>
                                <td><div class="v2_field"><input class="form-control name-input" name="submitted[referral_5_name]" rel="submitted[name]"
                                           type="text" value=""/></div></td>
                                <td><div class="v2_field"><input class="form-control phone-input" name="submitted[referral_5_phone]" rel="submitted[phone]"
                                           type="text" value=""/></div></td>
                                <td><div class="v2_field"><input class="form-control phone-input" name="submitted[referral_5_secondaryphone]" type="text" value=""/></div></td>
                                <td><div class="v2_field"><input name="submitted[referral_5_relationship]" type="text" value="" class="form-control"/></div></td>
                                <td><div class="v2_field"><select name="submitted[referral_5_time_to_call]" class="form-control">
                                        <option value="morning">Morning</option>
                                        <option value="afternoon">Afternoon</option>
                                        <option value="evening">Evening</option>
                                    </select></div></td>
                            </tr>
                            <tr>
                                <td style="vertical-align:middle !important;"><span class="nr">6</span></td>
                                <td><div class="v2_field"><input class="form-control name-input" name="submitted[referral_6_name]" rel="submitted[name]"
                                           type="text" value=""/></div></td>
                                <td><div class="v2_field"><input class="form-control phone-input" name="submitted[referral_6_phone]" rel="submitted[phone]"
                                           type="text" value=""/></div></td>
                                <td><div class="v2_field"><input class="form-control phone-input" name="submitted[referral_6_secondaryphone]" type="text"
                                           value=""/></div></td>
                                <td><div class="v2_field"><input name="submitted[referral_6_relationship]" type="text" value="" class="form-control"/></div></td>
                                <td><div class="v2_field"><select name="submitted[referral_6_time_to_call]" class="form-control">
                                        <option value="morning">Morning</option>
                                        <option value="afternoon">Afternoon</option>
                                        <option value="evening">Evening</option>
                                    </select></div></td>
                            </tr>
                            <tr>
                                <td style="vertical-align:middle !important;"><span class="nr">7</span></td>
                                <td><div class="v2_field"><input class="form-control name-input" name="submitted[referral_7_name]" rel="submitted[name]"
                                           type="text" value=""/></div></td>
                                <td><div class="v2_field"><input class="form-control phone-input" name="submitted[referral_7_phone]" rel="submitted[phone]"
                                           type="text" value=""/></div></td>
                                <td><div class="v2_field"><input class="form-control phone-input" name="submitted[referral_7_secondaryphone]" type="text"
                                           value=""/></div></td>
                                <td><div class="v2_field"><input name="submitted[referral_7_relationship]" type="text" value="" class="form-control"/></div></td>
                                <td><div class="v2_field"><select name="submitted[referral_7_time_to_call]" class="form-control">
                                        <option value="morning">Morning</option>
                                        <option value="afternoon">Afternoon</option>
                                        <option value="evening">Evening</option>
                                    </select></div></td>
                            </tr>
                            <tr>
                                <td style="vertical-align:middle !important;"><span class="nr">8</span></td>
                                <td><div class="v2_field"><input class="form-control name-input" name="submitted[referral_8_name]" rel="submitted[name]"
                                           type="text" value=""/></div></td>
                                <td><div class="v2_field"><input class="form-control phone-input" name="submitted[referral_8_phone]" rel="submitted[phone]"
                                           type="text" value=""/></div></td>
                                <td><div class="v2_field"><input class="form-control phone-input" name="submitted[referral_8_secondaryphone]" type="text"
                                           value=""/></div></td>
                                <td><div class="v2_field"><input name="submitted[referral_8_relationship]" type="text" value="" class="form-control"/></div></td>
                                <td><div class="v2_field"><select name="submitted[referral_8_time_to_call]" class="form-control">
                                        <option value="morning">Morning</option>
                                        <option value="afternoon">Afternoon</option>
                                        <option value="evening">Evening</option>
                                    </select></div></td>
                            </tr>
                            <tr>
                                <td style="vertical-align:middle !important;"><span class="nr">9</span></td>
                                <td><div class="v2_field"><input class="form-control name-input" name="submitted[referral_9_name]" rel="submitted[name]"
                                           type="text" value=""/></div></td>
                                <td><div class="v2_field"><input class="form-control phone-input" name="submitted[referral_9_phone]" rel="submitted[phone]"
                                           type="text" value=""/></div></td>
                                <td><div class="v2_field"><input class="form-control phone-input" name="submitted[referral_9_secondaryphone]" type="text"
                                           value=""/></div></td>
                                <td><div class="v2_field"><input name="submitted[referral_9_relationship]" type="text" value="" class="form-control"/></div></td>
                                <td><div class="v2_field"><select name="submitted[referral_9_time_to_call]" class="form-control">
                                        <option value="morning">Morning</option>
                                        <option value="afternoon">Afternoon</option>
                                        <option value="evening">Evening</option>
                                    </select></div></td>
                            </tr>
                            <tr>
                                <td style="vertical-align:middle !important;"><span class="nr">10</span></td>
                                <td><div class="v2_field"><input class="form-control name-input" name="submitted[referral_10_name]" rel="submitted[name]"
                                           type="text" value=""/></div></td>
                                <td><div class="v2_field"><input class="form-control phone-input" name="submitted[referral_10_phone]"
                                           rel="submitted[phone]" type="text" value=""/></div></td>
                                <td><div class="v2_field"><input class="form-control phone-input" name="submitted[referral_10_secondaryphone]" type="text"
                                           value=""/></div></td>
                                <td><div class="v2_field"><input name="submitted[referral_10_relationship]" type="text" value="" class="form-control"/></div></td>
                                <td><div class="v2_field"><select name="submitted[referral_10_time_to_call]" class="form-control">
                                        <option value="morning">Morning</option>
                                        <option value="afternoon">Afternoon</option>
                                        <option value="evening">Evening</option>
                                    </select></div></td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="form-group row">
                            <div class="col-xs-6 field-col">
                                <div class="form-group">
                            	<label class="v2_label">Sales Rep Name</label>
                                <div class="v2_field"><input type="text" value="" name="submitted[user_name]" id="sales_rep_name" class="form-control"></div>
                                <input type="hidden" name="submitted[user]" id="sales_rep_email" value="">
								</div>	
                            </div>
                        </div>
                        <div class="mb50 mr50 mt50 clearfix">
                            <input id="referral_submit" type="submit" value="Submit Now" class="submit-form"/>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
<script type="text/javascript">
    $(window).bind('beforeunload', function () {
        return "You haven't finished the form yet. Do you want to leave without finishing?";
    });
    $(document).ready(function () {
        $.validator.addMethod("phone-input", function (value, element) {
            var result = this.optional(element) || value.length == 10 && /^(02|03|04|07|08)\d{8}$/.test(value.replace(/\s/g, ''));
            return result;
        }, "Please specify a valid phone number");
        $('#referral').validate({
            submitHandler: function () {
                $("#processing").show();
                //alert("I'm processing your referrals... Please wait");
                $.ajax({
                    type: 'POST',
                    async: false,
                    url: '/admin/customers/referral_program',
                    data: $('#referral').serialize(),
                    dataType: 'json',
                    success: function (response) {
                        $("#processing").hide();
                        if (response.success == 0) {
                            $.each(response.error, function (key, value) {
                                $("input[name='submitted[" + key + "]']").addClass('error');
                                alert(value);
                                $("input[name='submitted[" + key + "]']").focus();
                            });
                        } else {
                            $('#referral_form').html(response.html);
                        }
                    },
                });
            }
        });
    });
</script>