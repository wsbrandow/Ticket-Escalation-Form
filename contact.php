<?php
// Begin Script-----------------------------------------------------------------------------------------------------//

// Import PHPMailer classes into the global namespace
// These must be at the top of the script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$errorMSG = "";


if (array_key_exists('file', $_FILES)) {
    $Attachment = tempnam(sys_get_temp_dir(), hash('sha256', $_FILES['file']['name']));
    if (move_uploaded_file($_FILES['file']['tmp_name'], $Attachment)) {  //Repository for .tmp files (necessary for attachments) is C:/inetpub/temp. This may need to be cleaned out periodically

        //Load Composer's autoloader - This is Essential
        require 'vendor/autoload.php';

        //Email Subject Header Variables-----------------------------------------------------------------------//
        $TicketDate = $_POST["TicketDate"];
        $ImpactedProduct = $_POST["ImpactedProduct"];
        $ShortDescription = $_POST["ShortDescription"];

        //Email Body Variables-------------------------------------------------------------------------------//
        $FirstCallTime = $_POST["FirstCallTime"];
        $WhoNotified = $_POST["WhoNotified"];
        $TimeEscalated = $_POST["TimeEscalated"];
        $TicketNumber = $_POST["TicketNumber"];
        $TicketLink = $_POST["TicketLink"];
        $Impact = $_POST["Impact"];
        $Urgency = $_POST["Urgency"];
        $SevLvl = $_POST["SevLvl"];
        $WhoSpeakTo = $_POST["WhoSpeakTo"];
        $ArmIVR = $_POST["ArmIVR"];
        $AdditionalNotes = $_POST["AdditionalNotes"];

        //Technician Validation Variables---------------------------------------------------------------------//
        $memail = $_POST["memail"]."@companydomain.com";
        $musername = $_POST["musername"];
        $mpassword = $_POST["mpassword"];

        //Email Subject Content-------------------------------------------------------------------------------//
        $MsgSubject = "NOC Escalation - ".$TicketDate." - ".$ImpactedProduct." - ".$ShortDescription;
        
        //Email Body Content---------------------------------------------------------------------------------//
        $MsgContent = "
        <html>
            <head>
                <style> 
                        #question {font-style: bold;} 
                        h1 {font-size: 150%; font-weight: bold; text-align: center; color: red;}
                        #altquestion {color: red; font-weight: bold;}
                        #no-bullets {list-style-type: none;}
                </style> 
                <title style=\"color: red; font-size: 200%; text-align: center;\">NOC Escalation</title>
            </head>
            <body style=\"font-family: Calibri, sans-serif\">
                <h1>NOC Escalation</h1>
                <ol>
                    <li><strong>What time was the first call that relates to the issue escalated?</strong> {$FirstCallTime}</li>
                    <li><strong>Who notified the ESD Team about this issue?</strong> {$WhoNotified}</li>
                    <li><strong>Time Escalated to NOC?</strong> {$TimeEscalated}</li>
                    <li><strong>Ticket number and ticket link used for escalation:</strong> {$TicketNumber} ({$TicketLink})</li>
                    <li><strong><span id=\"altquestion\">Impact:</span></strong> {$Impact}<br/>
                        <strong><span id=\"altquestion\">Urgency:</span></strong> {$Urgency}<br/>
                        <strong><span id=\"altquestion\">Priority Calculation:</span></strong> {$SevLvl}
                    </li>
                    <li><strong>Who did you speak to at NOC and what method of communication was used?</strong> {$WhoSpeakTo}</li>
                    <li><strong>If applicable to the situation, who armed the IVR and at what time?</strong> {$ArmIVR}</li>
                </ol>
                <p><strong>Additional Notes:</strong><br/>{$AdditionalNotes}</p>     
            </body>
            
        </html>
        ";

        //Mail Configuration------------------------------------------------------------------------------//
        $mail = new PHPMailer();  // Passing `true` in parenthesis enables exceptions for error checking


        //SMTP Server Settings-----------------------------------------------------------//
        $mail->isSMTP(true);                        // Set mailer to use SMTP
        //Enable SMTP debugging
        // 0 = off (for production use)
        // 1 = client messages
        // 2 = client and server messages
        //$mail->SMTPDebug = 4;                            
        //$mail->Debugoutput = 'html';              // Specifies HTML debug output                                    
        $mail->Host = 'XXXXXX.companydomain.com';   // Specify SMTP server
        $mail->Mailer = 'smtp';
        $mail->SMTPAuth = true;                     // Enable SMTP authentication
        $mail->Username = $musername;               // Username (passing variable from form)
        $mail->Password = $mpassword;               // Password (passing variable from form)
        $mail->SMTPSecure = 'tls';                  // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        //Recipients
        $mail->setFrom($memail, 'Enterprise Service Desk');
        $mail->addAddress($memail);                                         // Add a recipient
        $mail->addReplyTo($memail, 'ESD');
        $mail->addCC($memail);
        //$mail->addBCC("email@domain.com");

        //Attachment
        $mail->addAttachment($Attachment, $_FILES['file']['name']);         // Add attachments

        //Content
        $mail->isHTML(true);                                                // Set email format to HTML
        $mail->Subject = $MsgSubject;
        $mail->Body    = $MsgContent;
        $mail->AltBody = 'HTML Mail Client Required';

        //Send Email-------------------------------------------------------------------------------->
        $mail->send();

    } else {
        sendMailNoAttachment();
    }
} else {
    sendMailNoAttachment();
}
        
function sendMailNoAttachment() {
    //Load Composer's autoloader - This is Essential
    require 'vendor/autoload.php';

    //Email Subject Header Variables-----------------------------------------------------------------------//
    $TicketDate = $_POST["TicketDate"];
    $ImpactedProduct = $_POST["ImpactedProduct"];
    $ShortDescription = $_POST["ShortDescription"];

    //Email Body Variables-------------------------------------------------------------------------------//
    $FirstCallTime = $_POST["FirstCallTime"];
    $WhoNotified = $_POST["WhoNotified"];
    $TimeEscalated = $_POST["TimeEscalated"];
    $TicketNumber = $_POST["TicketNumber"];
    $TicketLink = $_POST["TicketLink"];
    $Impact = $_POST["Impact"];
    $Urgency = $_POST["Urgency"];
    $SevLvl = $_POST["SevLvl"];
    $WhoSpeakTo = $_POST["WhoSpeakTo"];
    $ArmIVR = $_POST["ArmIVR"];
    $AdditionalNotes = $_POST["AdditionalNotes"];

    //Technician Validation Variables---------------------------------------------------------------------//
    $memail = $_POST["memail"]."@companydomain.com";
    $musername = $_POST["musername"];
    $mpassword = $_POST["mpassword"];

    //Email Subject Content-------------------------------------------------------------------------------//
    $MsgSubject = "NOC Escalation - ".$TicketDate." - ".$ImpactedProduct." - ".$ShortDescription;

    //Email Body Content---------------------------------------------------------------------------------//
    $MsgContent = "
    <html>
        <head>
            <style> 
                    #question {font-style: bold;} 
                    h1 {font-size: 150%; font-weight: bold; text-align: center; color: red;}
                    #altquestion {color: red; font-weight: bold;}
                    #no-bullets {list-style-type: none;}
            </style> 
            <title style=\"color: red; font-size: 200%; text-align: center;\">NOC Escalation</title>
        </head>
        <body style=\"font-family: Calibri, sans-serif\">
            <h1>NOC Escalation</h1>
            <ol>
                <li><strong>What time was the first call that relates to the issue escalated?</strong> {$FirstCallTime}</li>
                <li><strong>Who notified the ESD Team about this issue?</strong> {$WhoNotified}</li>
                <li><strong>Time Escalated to NOC?</strong> {$TimeEscalated}</li>
                <li><strong>Ticket number and ticket link used for escalation:</strong> {$TicketNumber} ({$TicketLink})</li>
                <li><strong><span id=\"altquestion\">Impact:</span></strong> {$Impact}<br/>
                    <strong><span id=\"altquestion\">Urgency:</span></strong> {$Urgency}<br/>
                    <strong><span id=\"altquestion\">Priority Calculation:</span></strong> {$SevLvl}
                </li>
                <li><strong>Who did you speak to at NOC and what method of communication was used?</strong> {$WhoSpeakTo}</li>
                <li><strong>If applicable to the situation, who armed the IVR and at what time?</strong> {$ArmIVR}</li>
            </ol>
            <p><strong>Additional Notes:</strong><br/>{$AdditionalNotes}</p>     
        </body>
        
    </html>
    ";

    //Mail Configuration------------------------------------------------------------------------------//
    $mail = new PHPMailer();  // Passing `true` in parenthesis enables exceptions for error checking

    //SMTP Server Settings-----------------------------------------------------------//
    $mail->isSMTP(true);                        // Set mailer to use SMTP
    //Enable SMTP debugging
    // 0 = off (for production use)
    // 1 = client messages
    // 2 = client and server messages
    //$mail->SMTPDebug = 4;                            
    //$mail->Debugoutput = 'html';              // Specifies HTML debug output                                    
    $mail->Host = 'XXXXXXX.companydomain.com';  // Specify SMTP server
    $mail->Mailer = 'smtp';
    $mail->SMTPAuth = true;                     // Enable SMTP authentication
    $mail->Username = $musername;               // Username (passing variable from form)
    $mail->Password = $mpassword;               // Password (passing variable from form)
    $mail->SMTPSecure = 'tls';                  // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587;
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );

    //Recipients and Senders                                // Add recipients
    $mail->setFrom($memail, 'Enterprise Service Desk');
    $mail->addAddress($memail, 'ESD');                      
    $mail->addReplyTo($memail, 'ESD');
    //$mail->addCC('cc@example.com');
    $mail->addBCC($memail);

    //Content
    $mail->isHTML(true);                                    // Set email format to HTML
    $mail->Subject = $MsgSubject;
    $mail->Body    = $MsgContent;
    $mail->AltBody = 'HTML Mail Client Required';

    //Send Email-------------------------------------------------------------------------------->
    $mail->send();

}

/*$success = $mail->Send();                   //The following lines of code are for server-side form field validation. Was unable to debug this fully. 

if ($success && $errorMSG == ""){
    echo "success";
 }else{
     if($errorMSG == ""){
         echo "Error: Please ensure all form fields are filled out correctly.";
     } else {
         echo $errorMSG."<br> Mailer Error: ".$mail->ErrorInfo;
     }
 }*/
?>