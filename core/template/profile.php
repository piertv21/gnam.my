<?php

$user_id = filter_input(INPUT_GET, 'user', FILTER_SANITIZE_NUMBER_INT);

if($user_id === null || $user_id <= 0 || !userExists($user_id) || !isset($user_id)) {
    $_GET['user'] = $_SESSION['id'];
}

$user = getUser($_GET['user']);
$followers = getUserFollowers($user['id']);
$followed = getUserFollowed($user['id']);
$userGnams = getUserGnams($user['id']);
$userLikedGnams = getUserLikedGnams($user['id']);

?>
<div class="container text-center mt-3 text-black">
    <div class="row">
        <div class="col-4 align-self-center">
            <img class="border border-2 border-dark rounded-circle w-75" alt="Foto profilo di <?php echo $user['name'] ?>" id="profileImage" src="assets/profile_pictures/<?php echo $user['id'] ?>.jpg" />
        </div>
        <div class="col-8 align-self-center">
            <div class="row">
                <div class="h4 mt-2 ps-0"><?php echo $user['name'] ?></div>
            </div>
            <div class="row">
                <a id="followerButton" href="#" class="col p-0 text-link">
                    <p class="fw-bold p-0 mb-0">Follower</p>
                    <p class="fw-normal " id="followersCount"><?php echo count($followers); ?></p>
                </a>
                <a id="followedButton" href="#" class="col p-0 text-link">
                    <div class="col p-0">
                        <p class="fw-bold mb-0">Seguiti</p>
                        <p class="fw-normal "><?php echo count($followed); ?></p>
                    </div>
                </a>
                <div class="col p-0 text-link">
                    <p class="fw-bold mb-0">Gnam</p>
                    <p class="fw-normal "><?php echo count($userGnams); ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container text-center mt-3 text-black">    
    <div class="row justify-content-center">
        <?php if($user['id'] != $_SESSION['id']) { ?>
            <div class="col-md-4 col-6">
                <button type="button" class="btn btn-bounce rounded-pill bg-secondary fw-bold text-white w-100" id="followButton"><?php echo isCurrentUserFollowing($user['id']) ? "Seguito" : "Segui" ?></button>
            </div>
        <?php } ?>
        <div class="col-md-4 col-6">
            <button id="shareButton" type="button" class="btn btn-bounce rounded-pill bg-secondary fw-bold text-white w-100">Condividi</button>
        </div>
        <?php if($user['id'] == $_SESSION['id']) { ?>
            <div class="col-md-4 col-6">
                <button id="settingsButton" type="button" class="btn btn-bounce rounded-pill bg-secondary fw-bold text-white w-100">Impostazioni</button>
            </div>
        <?php } ?>
    </div>
</div>

<div class="container text-center mt-3 text-black">
    <div class="row align-items-center text-center mt-2">
        <div class="col-1"></div>
        <div class="col-3 cursor-pointer">
            <p class="mb-0 fw-bold" id="allPostsButton">Post</p>
        </div>
        <div class="col-2"></div>
        <div class="col-5 cursor-pointer">
            <p class="mb-0" id="likedPostsButton">Gnam Piaciuti</p>
        </div>
        <div class="col-1"></div>
    </div>
    <div class="row row justify-content-center">
        <hr class="w-75" id="horizontalLine" />
    </div>
</div>

<div class="container text-black">
    <div id="postedGnams">
        <?php
            $gnamPerRow = 3;
            if(count($userGnams) > 0) {
                echo '<div class="row">';
                for($i = 0; $i < count($userGnams); $i++) {
                    echo '<img class="img-grid px-2 col-4 btn-bounce cursor-pointer" id="postedGnam-' . $userGnams[$i]['id'] . '" alt="Copertina Gnam di ' . $user['name'] . '" src="assets/gnams_thumbnails/' . $userGnams[$i]['id'] . '.jpg" />';
                    $gnamPerRow--;
                    if($i == count($userGnams) - 1) {
                        echo '</div>';
                    } else if($gnamPerRow == 0) {
                        echo '</div><div class="row my-3">';
                        $gnamPerRow = 3;
                    }
                }
            } else {
                echo '
                <div class="row text-center mt-3">
                    <div class="fs-6">Nessuno Gnam pubblicato.</div>
                </div>
                ';
            }
        ?>
    </div>
    <div id="likedGnams">
        <?php
            $gnamPerRow = 3;
            if(count($userLikedGnams) > 0) {
                echo '<div class="row">';
                for($i = 0; $i < count($userLikedGnams); $i++) {
                    echo '<img class="img-grid px-2 col-4 btn-bounce cursor-pointer" id="likedGnam-' . $userLikedGnams[$i]['gnam_id'] . '" alt="Copertina Gnam di ' . $user['name'] . '" src="assets/gnams_thumbnails/' . $userLikedGnams[$i]['gnam_id'] . '.jpg" />';
                    $gnamPerRow--;
                    if($i == count($userLikedGnams) - 1) {
                        echo '</div>';
                    } else if($gnamPerRow == 0) {
                        echo '</div><div class="row my-3">';
                        $gnamPerRow = 3;
                    }
                }
            } else {
                echo '
                <div class="row text-center mt-3">
                    <div class="fs-6">Nessuno Gnam fra i preferiti.</div>
                </div>
                ';
            }
        ?>
    </div>
</div>

<script>
    const followUser = () => {
        $.post("api/users.php", {
            user_id: "<?php echo $user['id'] ?>",
            api_key: "<?php echo $_SESSION['api_key'] ?>",
            action: "toggleFollowState"
        }, (result) => {
            let decodedResult = JSON.parse(result);
            if (decodedResult.status === "success") {
                window.location.reload();
            } else showToast(decodedResult.status, "<p class='fs-6 text-center pt-3'>" + decodedResult.message + "</p>");
        });
    }

    const toggleGnamToVisualize = () => {
        if(event.target.id === 'allPostsButton') {
            $('#likedPostsButton').removeClass('fw-bold');
            $('#allPostsButton').addClass('fw-bold');
            $('#likedGnams').hide();
            $('#postedGnams').show();
        } else {
            $('#allPostsButton').removeClass('fw-bold');
            $('#likedPostsButton').addClass('fw-bold');
            $('#postedGnams').hide();
            $('#likedGnams').show();
        }
    }

    const showSwalShare = () => {
        let swalContent = `
            <div class='row-md-2 py-2 text-center text-black'>
                <div class='container'>
                    <div class='col'>
                        <div class='row-9 py-4'><em class='fa-solid fa-share-nodes fa-2xl' aria-hidden="true"></em></div>
                        <div class='row-3 pt-3'><button type='button' class='btn btn-bounce rounded-pill bg-accent fw-bold
                                text-white' id="copyLinkButton">Copia link</button></div>
                    </div>
                </div>
            </div>
        `;
        showSmallSwal('Condividi Profilo', swalContent);        
        $("#copyLinkButton").on("click", function() {
            copyToClipboard(window.location.href + "?user=<?php echo $user['id'] ?>");
        });
    }

    const showSwalFollower = () => {
        let swalContent = `
            <ul class="list-group modal-content-lg text-black">
                <?php
                    if(count($followers) > 0) {
                        foreach ($followers as $f) {
                            echo '
                                <li class="list-group-item bg border-0 btn-bounce"><a href="profile.php?user=' . $f['id'] . '" class="text-link">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-2 d-flex flex-wrap align-items-center p-0"><img class="border border-2 border-dark rounded-circle w-100 align-middle" alt="Foto profilo di ' . $f['name'] . '" src="assets/profile_pictures/' . $f['id'] . '.jpg" /></div>
                                            <div class="col-8 d-flex flex-wrap align-items-center">' . $f['name'] . '</div>
                                        </div>
                                    </div>
                                </a></li>';
                        }
                    } else {
                        echo 'Nessun follower.';
                    }
                ?>
            </ul>`;
        showSwal('Follower', swalContent);
    }

    const showSwalFollowed = () => {
        let swalContent = `
            <ul class="list-group modal-content-lg text-black">
                <?php
                    if(count($followed) > 0) {
                        foreach($followed as $f) {
                            echo '
                                <li class="list-group-item bg border-0 btn-bounce"><a href="profile.php?user=' . $f['id'] . '" class="text-link">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-2 d-flex flex-wrap align-items-center p-0"><img class="border border-2 border-dark rounded-circle w-100 align-middle" alt="Foto profilo di ' . $f['name'] . '" src="assets/profile_pictures/' . $f['id'] . '.jpg" /></div>
                                            <div class="col-8 d-flex flex-wrap align-items-center">' . $f['name'] . '</div>
                                        </div>
                                    </div>
                                </a></li>
                            ';
                        }
                    } else {
                        echo 'Nessun utente seguito.';
                    }
                ?>
            </ul>`;
        showSwal('Seguiti', swalContent);
    }

    const showSwalSettings = () => {
        let swalContent = `
            <div class='row-md-2 py-2 text-center overflow-hidden'>
                <div class='container px-0'>
                    <div class='row mb-3'>
                        <div class='col'>
                            <p class="fs-5 text-black">Cambia immagine profilo:</p>
                            <input type="file" class="form-control bg-primary rounded shadow-sm" id="newProfileImage" title="nuova immagine di profilo" aria-label="nuova immagine di profilo" />
                        </div>
                    </div>
                    <div class='row justify-content-center'>
                        <div class='col-4' id="saveButton">
                            <a role='button' class='btn btn-bounce rounded-pill bg-accent fw-bold text-white'>Salva</a>
                        </div>
                        <div class='col-5' id="logoutButton">
                            <a role='button' class='btn btn-bounce rounded-pill bg-alert fw-bold text-white'>Log out</a>
                        </div>
                    </div>
                </div>
            </div>
        `;
        showSwal('Impostazioni', swalContent);

        $(document).ready(function() {
            $('#saveButton').hide();
            $('input[type=file]').change(function() {
                $('#saveButton').show();
            });

            $('#saveButton').click(function() {
                let imageFile = $('#newProfileImage')[0].files[0];
                let formData = new FormData();
                formData.append('image', imageFile);
                formData.append("action", "updateProfileImage");
                formData.append("api_key", "<?php echo $_SESSION['api_key']; ?>");

                $.ajax({
                    url: 'api/users.php',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        let decodedResult = JSON.parse(response);
                        let html = `<div class="row-md-2 py-2 text-center text-black"><em class="fa-solid fa-check fa-2xl" aria-hidden="true"></em></div>`;
                        if (decodedResult.status === "success") {
                            showSmallSwal('Fatto', html, () => {
                                window.location.reload();
                            });
                        } else showToast(decodedResult.status, "<p class='fs-6 text-center pt-3'>" + decodedResult.message + "</p>");
                    }
                });
            });

            $('#logoutButton').click(function() {
                window.location.href = "logout.php";
            });
        });
    }

    const getIdsFromElementId = (prefix, clickedElement) => {
        let currentId = clickedElement.id.replace(`${prefix}-`, '');
        let ids = $(`#${prefix}s img`).map(function(index, element) {
            let idWithoutPrefix = element.id.replace(`${prefix}-`, '');
            return { id: idWithoutPrefix };
        }).get();
        return { currentId, ids };
    }

    $('[id^="postedGnam-"]').on('click', function() {
        let { currentId, ids } = getIdsFromElementId('postedGnam', this);
        setGnamsToWatchFrom(currentId, ids);
    });

    $('[id^="likedGnam-"]').on('click', function() {
        let { currentId, ids } = getIdsFromElementId('likedGnam', this);
        setGnamsToWatchFrom(currentId, ids);
    });

    $("#followerButton").on("click", showSwalFollower);
    $("#followedButton").on("click", showSwalFollowed);
    $("#shareButton").on("click", showSwalShare);
    $("#allPostsButton").on("click", toggleGnamToVisualize);
    $("#likedPostsButton").on("click", toggleGnamToVisualize);
    $("#followButton").on("click", followUser);
    $("#settingsButton").on("click", showSwalSettings);
    $("#likedGnams").hide();
</script>