
<?php  $this->load->view('layout/header'); ?>
<div class="container">
    <h2>User Listing</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>User Name</th>
                <th>Name</th>
                <th>Email</th>
                <th>status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if(sizeof($user_data) > 0){
                foreach ($user_data as $key => $value) {
                    ?>
                    <tr>
                        <td><?php echo $value['user_name'] ?></td>
                        <td><?php echo $value['name'] ?></td>
                        <td><?php echo $value['email'] ?></td>
                        <td><?php echo $value['status'] ?></td>
                        <td>
                            <a href="javascript:void(0)" onclick="mychat(<?php echo $value['user_id'] ?>)" title="chat" alt="chat"> <i class="fa fa-comments-o"></i> </a>
                        </td>
                    </tr>
                    <?php
                }
            } ?>
        </tbody>
    </table>
</div>
<div class="modal" id="mychat" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cummuniction</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="messageContainer"></div>
            </div>
            <div class="modal-body">
                <textarea style="text-align:left; width: 100%;" id="messageInput" placeholder="Type your message..."></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button  onclick="sendMessage()" class="btn btn-primary">Send</button>
            </div>
        </div>
    </div>
</div>
<!-- <div id="mychat" style="display: none;">
    <div id="messageContainer"></div>
    <button onclick="sendMessage()">Send</button>
</div> -->

<script>
    var userId = null;

    function mychat(user_id) {
        userId = user_id;
        $('#mychat').modal();

        fetchMessages();
    }

    function fetchMessages() {
        $.ajax({
            url: '<?php echo base_url('dashbord/fetch_messages') ?>',
            type: 'POST',
            data: { user_id: userId },
            success: function(response) {
                var jsonResponse = JSON.parse(response);
                if (jsonResponse.status === 'success') {
                    $('#messageContainer').html(jsonResponse.message);
                } else {
                    console.error('Error: ' + jsonResponse.message);
                }
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    }

    function sendMessage() {
        var message = $('#messageInput').val().trim();
        if (message !== '') {
            $.ajax({
                url: '<?php echo base_url('dashbord/send_message') ?>',
                type: 'POST',
                data: { user_id: userId, message: message },
                success: function(response) {
                    $('#messageInput').val('');
                    fetchMessages();
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }
    }

// Fetch messages every 5 seconds
setInterval(fetchMessages, 5000);
</script>
<?php $this->load->view('layout/footer'); ?>