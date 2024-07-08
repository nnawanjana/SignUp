<div class="container-fluid">
    <?php echo $this->element('signup_view_header'); ?>
    <section class="contact-deatils">
        <div class="contact-form"><div class="container"><div class="row"><div class="col-sm-12">
        <?php echo $this->Session->flash(); ?>
			<div class="form-wrapper">
            <a class="form-hdg" data-toggle="collapse" href="#form-collapse1" role="button">View Lead<i class="fa fa-angle-up" aria-hidden="true"></i><i class="fa fa-angle-down" aria-hidden="true"></i></a>
            <div class="form clearfix collapse in" id="form-collapse1">
                <form action="/admin/customers/form" method="post" name="view_lead" id="view_lead" target="_blank">
                    <input type="hidden" name="action" value="view">
                    <div class="form-data">
                        <div class="form-group">
                           	<label class="v2_label">Velocify Lead ID</label>
                           	<div class="v2_field"><input type="text" name="lead_id" id="view_lead_id" class="form-control" value=""/></div>
                        </div>
                        <div class="form-group">
                            <label class="v2_label">Phone Number</label>
                            <div class="v2_field"><input type="text" name="mobile" id="view_lead_mobile" class="form-control" value="<?php echo (isset($mobile)) ? $mobile : '';?>"/></div>
                        </div>
						<div class="form-group text-right">
							<button type="submit" class="submit-form"><i class="fa fa-search" aria-hidden="true"></i>Search</button>
						</div>
                    </div>
                    <?php if (isset($submissions) && $submissions):?>
                    <div class="table-wrapper">
                        <table class="table table-striped">
                            <thead>
                                <th>Lead ID</th>
                                <th>Mobile</th>
                                <th>Email</th>
                                <th>Submitted</th>
                                <th>Source</th>
                                <th></th>
                            </thead>
                            <tbody>
                            <?php foreach ($submissions as $submission):?>
                            <tr>
                                <td><?php echo $submission['Submission']['leadid'];?></td>
                                <td><?php echo $submission['Submission']['mobile'];?></td>
                                <td><?php echo $submission['Submission']['email'];?></td>
                                <td><?php echo $submission['Submission']['submitted'];?></td>
                                <td><?php echo $submission['Submission']['source'];?></td>
                                <td><a class="btn btn-primary" href="/admin/customers/view/<?php echo $submission['Submission']['leadid'];?>" target="_blank">View</a></td>
                            </tr>
                            <?php endforeach;?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif;?>
                </form>
            </div>
			</div>	
			<div class="form-wrapper">
            <a class="form-hdg collapsed" data-toggle="collapse" href="#form-collapse2" role="button">Fix Invalid Email<i class="fa fa-angle-up" aria-hidden="true"></i><i class="fa fa-angle-down" aria-hidden="true"></i></a>
            <div class="form clearfix collapse" id="form-collapse2">
                <form action="/admin/customers/form" method="post" name="update_lead" id="update_lead">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" id="campaign_id" name="campaign_id" value="1">
                    <div class="form-data">
                        <div class="form-group">
                            <p>This function is to be used to fix invalid emails in Velocify. Please post the Lead ID and the correct email into the below fields and click update.</p>
                        </div>
                        <div class="form-group">
                            <label class="v2_label">Velocify Lead ID</label>
                            <div class="v2_field"><input type="text" name="lead_id" id="update_lead_id" class="form-control" value=""/></div>
                        </div>
                        <div class="form-group">
                            <label class="v2_label">Email</label>
                            <div class="v2_field"><input type="text" name="email" id="email" class="form-control" value=""/></div>
                        </div>
						<div class="form-group text-right">
							<button type="submit" class="submit-form"><i class="fa fa-refresh" aria-hidden="true"></i>Update</button>
						</div>	
                    </div>
                </form>
            </div>
			</div>		
        </div></div></div></div>
    </section>
</div>
