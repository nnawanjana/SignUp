<div class="container-fluid pdt100">
    <?php echo $this->element('signup_view_header'); ?>
    <section class="contact-deatils">
        <div class="contact-form">
        <?php echo $this->Session->flash(); ?>
            <h3 class="form-hdg">
                <div class="fh-inr">Telco Lead</div>
            </h3>
            <div class="form clearfix">
                <form action="/admin/customers/form" method="post" name="update_telco_lead" id="update_telco_lead" target="_blank">
                    <input type="hidden" name="action" value="update_telco">
                    <div class="form-data">
                        <div class="form-group row">
                            <div class="col-xs-12 field-col">
                                <label>Telco Lead ID</label>
                                <input type="text" name="lead_id" id="telco_lead_id" value=""/>
                            </div>
                        </div>
                    </div>
                    <div class="mb50 mr50 clearfix">
                        <button type="submit" class="submit-form">Search</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
