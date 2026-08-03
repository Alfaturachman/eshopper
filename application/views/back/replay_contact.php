
<script type="text/javascript" src="<?php echo base_url();?>/assets/back/ckeditor/ckeditor.js"></script>
    <!--  page-wrapper -->
    <div id="page-wrapper">
        <div class="row">
           <!-- page header -->
           <div class="col-lg-12">
            <h1 class="page-header">Reply Contact Message</h1>
        </div>
        <!--end page header -->
    </div>
    <div class="row">
        <div class="col-lg-12">
            <!-- Form Elements -->
            <div class="panel panel-default">
              <?php echo $this->session->flashdata('flsh_msg'); ?>
                <div class="panel-heading">
                    Reply to Message #<?php echo html_escape($replay_message_by_id->contact_id ?? ''); ?>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="well">
                                <p><strong>From:</strong> <?php echo html_escape($replay_message_by_id->contact_name ?? ''); ?> (<?php echo html_escape($replay_message_by_id->contact_email ?? ''); ?>)</p>
                                <p><strong>Subject:</strong> <?php echo html_escape($replay_message_by_id->contact_subject ?? ''); ?></p>
                                <p><strong>Message:</strong></p>
                                <p><?php echo nl2br(html_escape($replay_message_by_id->contact_message ?? '')); ?></p>
                            </div>
                            <h5 style='color:red'><?php echo validation_errors();?></h5>
                            <?php echo form_open('Contact/send_reply', array('method' => 'post')); ?>
                            <?php echo csrf_field(); ?>
                                <input type="hidden" name="contact_id" value="<?php echo html_escape($replay_message_by_id->contact_id ?? ''); ?>">
                                <div class="form-group">
                                    <label>Recipient Email</label>
                                    <input type="email" class="form-control" name="reply_email" value="<?php echo html_escape($replay_message_by_id->contact_email ?? ''); ?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Reply Message</label>
                                    <textarea class="form-control" rows="5" name="reply_message" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Send Reply</button>
                                <a href="<?php echo base_url('contact-message-list'); ?>" class="btn btn-default">Back to List</a>
                            <?php echo form_close();?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Form Elements -->
        </div>
    </div>
</div>
<!-- end page-wrapper -->


