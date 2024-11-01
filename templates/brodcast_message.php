<div id="wfpn" class="wrap nosubsub">
    <h1 class="wp-heading-inline" style="margin-bottom: 20px; padding-left: 50px">Send Brodcast Notification</h1>
    <hr class="wp-header-end">
    <div id="ajax-response"></div>
    <div id="col-container" class="wp-clearfix">
        <div class="col-wrap" style="margin: 0 50px;">
            <?php 
            if(!empty($_POST)){
                echo "<div class='message'>Notification sent successfully.</div>";
            }
            ?>
            <form id="brodcast_notification" method="post" action="">
                <div id="titlediv">
                    <div id="titlewrap">
                        <input type="text" required  placeholder="Add title"  name="brodcast_notification_title" size="30" value="" id="title" spellcheck="true" autocomplete="off" style="width: 80% !important;">
                        <input type="hidden" name="url" >
                    </div>
                    <div class="inside">
                    </div>

                    <div class="form-field term-description-wrap" style="padding-top: 20px;">
                     <textarea class=""  placeholder="Type your notification message here" id="brodcast_notification_message" name="brodcast_notification_message" rows="10" cols="40" style="padding: 10px;width: 80% !important;"></textarea>
                 </div>
                 <div class="form-field term-description-wrap" style="padding-top: 10px;">
                     <input type="submit" name="submit" value="Send" class="ajax-link button button-primary button-large">
                 </div>   
             </form>
         </div>
     </div>
 </div>