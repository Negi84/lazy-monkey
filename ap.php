<?php
include_once 'api/inc/config.php';
include_once 'classes/CurlClass.php';
$CurlClass = new CurlClass();

$tokenVal = base64_decode($_GET['pt']);
$arrToken = explode('|', $tokenVal);

$feedback_quick_id = $arrToken[0];
$user_id = $arrToken[1];
$feedback_type = $arrToken[2];
                                    
$probArray = $CurlClass->getCurlResult('getProblemDetailByObjectId/' . $feedback_quick_id . '/' . $feedback_type)['data'];

if(!isset($probArray['floor_title'])){
    $probArray['floor_title'] = '';
}

?>
<!doctype html>
<html class="no-js" lang="">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>Issue Template</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <style>
            body {
                font-family: Arial, Helvetica, sans-serif;
                font-size: 14px;
                margin: 0;
                padding: 0;
                line-height: 1.5;
            }
            .issueBox {
                text-align: center;
                padding: 10px;
            }
            a {
                text-align: center;
                text-decoration: none;
                background: #000;
                color: #fff;
                padding: 10px 20px;
                border-radius: 5px;
                display: inline-block;
            }
            a.done {
                background: #093;
            }
            .completed {
                text-align: center;
                text-decoration: none;
                background: #F90;
                color: #fff;
                padding: 10px 20px;
                border-radius: 5px;
                display: inline-block;
            }
        </style>
        <script>
            var API_URL = '<?php echo API_URL ?>';
            var USER_ID = '<?php echo $user_id ?>';
            var PROB_ID = '<?php echo $feedback_quick_id ?>';
        </script>
    </head>

    <body>
        <div class="issueBox">
            <p>Issue received from <strong><?php echo $probArray['brand_name'] . ' ' . $probArray['feedback_for']; ?></strong> on<strong> <?php echo $probArray['floor_title']; ?></strong></p>
            <p><strong>Location:</strong> <?php echo $probArray['qr_location']; ?></p>
            <p><strong>Rating:</strong> <?php echo $probArray['rating']; ?></p>
            <p><strong>Issue:</strong> <?php if($probArray['problem_string']==''){ echo 'Not Given'; }else{ echo $probArray['problem_string']; } ?></p>
            <p><strong>Name:</strong> <?php if($probArray['name']==''){ echo 'Not Shared'; }else{ echo $probArray['name']; } ?></p>
            <p><strong>Mobile Number:</strong> <?php if($probArray['phone']==''){ echo 'Not Shared'; }else{ echo $probArray['phone']; } ?></p>
            <p><strong>Comment:</strong> <?php if($probArray['comment']==null){ echo 'N/A'; }else{ echo $probArray['comment']; } ?></p>
            <p><strong>Created:</strong> <?php echo $probArray['created_at']; ?></p>
            <?php if ($probArray['status'] == 0) { ?>
                <a class="action accept" href="javascript:void();">Accept</a>
            <?php } else if ($probArray['status'] == 1) { ?>
                <p><strong>Resolution Comment:<br><br></strong><textarea style="width: 100%; min-height: 100px;padding: 10px;box-sizing: border-box;resize: auto;font-size: 17px;line-height: 26px;" name="resolution_comment" placeholder="Resolution Comment..." id="resolution_comment"></textarea></p>
                <a class="action done" href="javascript:void();" class="done">Done</a> 
            <?php } else { ?>
                <p><strong>Resolution Comment:<br><br></strong><textarea style="width: 100%; min-height: 100px;padding: 10px;box-sizing: border-box;resize: auto;font-size: 17px;line-height: 26px;" name="resolution_comment" id="resolution_comment" disabled="true"><?php echo $probArray['resolution_comment']; ?></textarea></p>
                <span class="completed">Completed</span> 
            <?php } ?>
        </div>
    </body>
</html>
<script type="text/javascript" src="js/jquery.min.js"></script>
<script>
            $(document).ready(function () {
                $('.action').on('click', function () {
                    var url = '';
                    var sendInfo = {
                        user_id: USER_ID,
                        feedback_quick_id: PROB_ID,
                        type:'',
                        resolution_comment: $("#resolution_comment").val()
                    }
                    if ($(this).hasClass('accept')) {
                        url = API_URL + 'acceptQuickFeedbackIsuue'
                    } else {
                        url = API_URL + 'doneQuickFeedbackIsuue'
                    }
                    $.ajax({
                        type: "POST",
                        url: url,
                        dataType: 'json',
                        data: JSON.stringify(sendInfo),
                        contentType: "application/json",
                        success: function (result) {
                            console.log(result);
                            if (result.code == 200) {
                                window.location.reload();
                            } else {
                                alert('Server error occured!');
                            }
                        },
                        error: function (error) {
                            alert('Something went wrong!');
                        }
                    });
                });
            }); //end of ready function
</script>