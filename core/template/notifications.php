<?php require_once("core/functions.php"); ?>

<div class="container text-center font-text">
    <div id="headerDiv" class="row-2 py-2">
        <h1 class="fw-bold">Notifiche</h1>
    </div>
    <div id="pageContentDiv" class="row-md-8 overflow-auto align-content-center">
        <div class="container h-auto" id="notificationsContainer">
            <?php foreach (getNotifications($_SESSION["api_key"]) as $notification) { ?>
                <div class="row m-1 p-0">
                    <a role="button" href="home.php?q=<?php echo $notification['gnam_id'] ?>" class="btn btn-bounce rounded-pill bg-primary p-0 notification-pill-text notification-btn">
                        <div class="container">
                            <div class="row">
                                <div class="p-1 col-2 d-flex flex-wrap align-items-center">
                                    <img class="border border-1 border-dark rounded-circle w-100 align-middle" alt="<?php echo $notification['source_user_name'] ?>" src="assets/prova-profile.png"/>
                                </div>
                                <div class="col align-self-center fs-7">
                                    <div class="m-0 text-link d-inline"><?php echo $notification["source_user_name"];?></div><span class="m-0 text-normal-black"><?php echo $notification["template_text"];?></span>
                                </div>
                                <div class="col-1 m-0 ps-3 pe-0 pt-2 pb-2 d-flex">
                                    <div class="vr"></div>
                                </div>
                                <div class="col-2 align-self-center">
                                    <span class="m-0 text-normal-black"><?php echo $notification["timestamp"];?></span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            <?php } ?>
        </div>
    </div>
    <div class="row-md-4 pt-3 pb-4" id="footerDiv">
        <button type="button" class="btn btn-bounce rounded-pill bg-secondary fw-bold text-white" id="clearNotificationsButton">Segna come lette</button>
    </div>
</div>

<script>
    $("#clearNotificationsButton").on("click", function() {
        $("#notificationsContainer").empty();
        $("#clearNotificationsButton").addClass("d-none");
        $("#notificationsContainer").append('<p class="fs-6" id="emptyNotificationsText">Non hai nuove notifiche.</p>');
    });
</script>