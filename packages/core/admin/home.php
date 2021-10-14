<?php

isset($_ARCHON) or die();

if(!$_REQUEST['f'])
{
    home_ui_main();
}
else
{
    home_exec();
}


function home_ui_main()
{
    global $_ARCHON;
    

   $in_Message = CONFIG_CORE_ADMINISTRATIVE_WELCOME_MESSAGE;

    // code removed to prevent external update checks that fail - Issue #167

    if(file_exists("packages/core/install/install.php"))
    {
        $in_Message = "<span style='color:red'>NOTICE: Archon will not function until packages/core/install/install.php has been deleted.</span>";
        $in_Message = $_ARCHON->processPhrase($in_Message);
    }

    // code removed to prevent external update checks that fail - Issue #167
    
    $_ARCHON->AdministrativeInterface->getSection('browse')->disable();

    $_ARCHON->AdministrativeInterface->disableQuickSearch();

    $generalSection = $_ARCHON->AdministrativeInterface->getSection('general');
    
    $generalSection->insertRow()->insertHTML("<h2>".$in_Message."</h2>")->disableHelp();
   

    $_ARCHON->AdministrativeInterface->outputInterface();
}




function home_exec()
{
    global $_ARCHON;
    
    if(true)
    {
        $_ARCHON->declareError("Unknown Command: {$_REQUEST['f']}");
    }

    if($_ARCHON->Error)
    {
        $msg = "<font color=red>$_ARCHON->Error</font>";
    }
    else
    {
        $msg = "Database Updated Successfully.";
    }
    
    $_ARCHON->sendMessageAndRedirect($msg, $location);
}
?>
