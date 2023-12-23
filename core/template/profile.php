<div class="container text-center mt-3" id="headerDiv">
    <div class="row">
        <div class="col-4">
            <img class="border border-2 border-dark rounded-circle w-100" alt="Filippo Champagne" src="assets/profile_pictures/prova.png" />
        </div>
        <div class="col-8">
            <div class="row">
                <div class="h4 mt-2 ps-0">GiorgioneErRomano</div>
            </div>
            <div class="row">

                <a id="followerButton" href="#" class="col p-0 text-link">
                    <p class="fw-bold p-0 mb-0">Follower</p>
                    <p class="text-normal-black">0</p>
                </a>

                <a id="followedButton" href="#" class="col p-0 text-link">
                    <div class="col p-0">
                        <p class="fw-bold mb-0">Seguiti</p>
                        <p class="text-normal-black">0</p>
                    </div>
                </a>
                <div class="col p-0 text-link">
                    <p class="fw-bold mb-0">Gnam</p>
                    <p class="text-normal-black">0</p>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-4">
            <button type="button" class="btn btn-bounce rounded-pill bg-primary fw-bold text-black w-100">Segui</button>
        </div>
        <div class="col-4 px-0">
            <button id="shareButton" type="button" class="btn btn-bounce rounded-pill bg-primary fw-bold text-black w-100">Condividi</button>
        </div>
        <div class="col-2">
            <button type="button" class="btn btn-bounce rounded-pill bg-primary fw-bold text-black" id="settingsButton">
                <i class="fa-solid fa-gear fa-l"></i>
            </button>
        </div>
    </div>
    <div class="row align-items-center text-center mt-2">
        <div class="col-1"></div>
        <div class="col-3 fw-bold" id="allPostsButton">
            <p class="mb-0">Post</p>
        </div>
        <div class="col-2"></div>
        <div class="col-5" id="likedPostsButton">
            <p class="mb-0">Gnam Piaciuti</p>
        </div>
        <div class="col-1"></div>
    </div>
    <div class="row row justify-content-center">
        <hr class="w-75" id="horizontalLine" />
    </div>
</div>

<div class="container overflow-y-scroll" id="pageContentDiv">
    <div class="row">
        <img class="img-grid col" alt="Filippo Champagne" src="assets/gnams_thumbnails/prova.png" />
        <img class="img-grid col" alt="Filippo Champagne" src="assets/gnams_thumbnails/prova.png" />
        <img class="img-grid col" alt="Filippo Champagne" src="assets/gnams_thumbnails/prova.png" />
    </div>
    <div class="row my-3">
        <img class="img-grid col" alt="Filippo Champagne" src="assets/gnams_thumbnails/prova.png" />
        <img class="img-grid col" alt="Filippo Champagne" src="assets/gnams_thumbnails/prova.png" />
        <img class="img-grid col" alt="Filippo Champagne" src="assets/gnams_thumbnails/prova.png" />
    </div>
    <div class="row my-3">
        <img class="img-grid col" alt="Filippo Champagne" src="assets/gnams_thumbnails/prova.png" />
        <img class="img-grid col" alt="Filippo Champagne" src="assets/gnams_thumbnails/prova.png" />
        <img class="img-grid col" alt="Filippo Champagne" src="assets/gnams_thumbnails/prova.png" />
    </div>
    <div class="row my-3">
        <img class="img-grid col" alt="Filippo Champagne" src="assets/gnams_thumbnails/prova.png" />
        <img class="img-grid col" alt="Filippo Champagne" src="assets/gnams_thumbnails/prova.png" />
        <img class="img-grid col" alt="Filippo Champagne" src="assets/gnams_thumbnails/prova.png" />
    </div>
</div>

<script>
    let isShowingAllPosts = true;

    const showAllPosts = () => {
        if (!isShowingAllPosts) {
            isShowingAllPosts = true;
            $("#allPostsButton").addClass("fw-bold");
            $("#likedPostsButton").removeClass("fw-bold");
        }
    }

    const showLikedPosts = () => {
        if (isShowingAllPosts) {
            isShowingAllPosts = false;
            $("#allPostsButton").removeClass("fw-bold");
            $("#likedPostsButton").addClass("fw-bold");
        }
    }

    const showSwalShare = () => {
        let swalContent = `
            <div class='row-md-2 py-2 text-center text-black'>
                <div class='container'>
                    <div class='col'>
                        <div class='row-9 py-4'><i class='fa-solid fa-share-nodes fa-2xl'></i></div>
                        <div class='row-3 pt-3'><button type='button' class='btn btn-bounce rounded-pill bg-accent fw-bold
                                text-white' id="copyLinkButton">Copia link</button></div>
                    </div>
                </div>
            </div>
        `;
        showSwalSmall('Condividi Profilo', swalContent);
        $("#copyLinkButton").on("click", copyCurrentPageLink);
    }

    const showSwalFollower = () => {
        let swalContent = `
            <ul class="list-group modal-content-lg">
                <li class="list-group-item bg border-0"><a href="#" class="text-link">
                        <div class="container">
                            <div class="row">
                                <div class="col-2 d-flex flex-wrap align-items-center p-0"><img class="border border-2 border-dark rounded-circle w-100 align-middle" alt="Filippo Champagne" src="assets/profile_pictures/prova.png"></div>
                                <div class="col-8 d-flex flex-wrap align-items-center">Nome utente</div>
                            </div>
                        </div>
                    </a></li>
                <li class="list-group-item bg border-0"><a href="#" class="text-link">
                        <div class="container">
                            <div class="row">
                                <div class="col-2 d-flex flex-wrap align-items-center p-0"><img class="border border-2 border-dark rounded-circle w-100 align-middle" alt="Filippo Champagne" src="assets/profile_pictures/prova.png"></div>
                                <div class="col-8 d-flex flex-wrap align-items-center">Nome utente</div>
                            </div>
                        </div>
                    </a></li>
                <li class="list-group-item bg border-0"><a href="#" class="text-link">
                        <div class="container">
                            <div class="row">
                                <div class="col-2 d-flex flex-wrap align-items-center p-0"><img class="border border-2 border-dark rounded-circle w-100 align-middle" alt="Filippo Champagne" src="assets/profile_pictures/prova.png"></div>
                                <div class="col-8 d-flex flex-wrap align-items-center">Nome utente</div>
                            </div>
                        </div>
                    </a></li>
                <li class="list-group-item bg border-0"><a href="#" class="text-link">
                        <div class="container">
                            <div class="row">
                                <div class="col-2 d-flex flex-wrap align-items-center p-0"><img class="border border-2 border-dark rounded-circle w-100 align-middle" alt="Filippo Champagne" src="assets/profile_pictures/prova.png"></div>
                                <div class="col-8 d-flex flex-wrap align-items-center">Nome utente</div>
                            </div>
                        </div>
                    </a></li>
                <li class="list-group-item bg border-0"><a href="#" class="text-link">
                        <div class="container">
                            <div class="row">
                                <div class="col-2 d-flex flex-wrap align-items-center p-0"><img class="border border-2 border-dark rounded-circle w-100 align-middle" alt="Filippo Champagne" src="assets/profile_pictures/prova.png"></div>
                                <div class="col-8 d-flex flex-wrap align-items-center">Nome utente</div>
                            </div>
                        </div>
                    </a></li>
                <li class="list-group-item bg border-0"><a href="#" class="text-link">
                        <div class="container">
                            <div class="row">
                                <div class="col-2 d-flex flex-wrap align-items-center p-0"><img class="border border-2 border-dark rounded-circle w-100 align-middle" alt="Filippo Champagne" src="assets/profile_pictures/prova.png"></div>
                                <div class="col-8 d-flex flex-wrap align-items-center">Nome utente</div>
                            </div>
                        </div>
                    </a></li>
                <li class="list-group-item bg border-0"><a href="#" class="text-link">
                        <div class="container">
                            <div class="row">
                                <div class="col-2 d-flex flex-wrap align-items-center p-0"><img class="border border-2 border-dark rounded-circle w-100 align-middle" alt="Filippo Champagne" src="assets/profile_pictures/prova.png"></div>
                                <div class="col-8 d-flex flex-wrap align-items-center">Nome utente</div>
                            </div>
                        </div>
                    </a></li>
                <li class="list-group-item bg border-0"><a href="#" class="text-link">
                        <div class="container">
                            <div class="row">
                                <div class="col-2 d-flex flex-wrap align-items-center p-0"><img class="border border-2 border-dark rounded-circle w-100 align-middle" alt="Filippo Champagne" src="assets/profile_pictures/prova.png"></div>
                                <div class="col-8 d-flex flex-wrap align-items-center">Nome utente</div>
                            </div>
                        </div>
                    </a></li>
                <li class="list-group-item bg border-0"><a href="#" class="text-link">
                        <div class="container">
                            <div class="row">
                                <div class="col-2 d-flex flex-wrap align-items-center p-0"><img class="border border-2 border-dark rounded-circle w-100 align-middle" alt="Filippo Champagne" src="assets/profile_pictures/prova.png"></div>
                                <div class="col-8 d-flex flex-wrap align-items-center">Nome utente</div>
                            </div>
                        </div>
                    </a></li>
                <li class="list-group-item bg border-0"><a href="#" class="text-link">
                        <div class="container">
                            <div class="row">
                                <div class="col-2 d-flex flex-wrap align-items-center p-0"><img class="border border-2 border-dark rounded-circle w-100 align-middle" alt="Filippo Champagne" src="assets/profile_pictures/prova.png"></div>
                                <div class="col-8 d-flex flex-wrap align-items-center">Nome utente</div>
                            </div>
                        </div>
                    </a></li>
                <li class="list-group-item bg border-0"><a href="#" class="text-link">
                        <div class="container">
                            <div class="row">
                                <div class="col-2 d-flex flex-wrap align-items-center p-0"><img class="border border-2 border-dark rounded-circle w-100 align-middle" alt="Filippo Champagne" src="assets/profile_pictures/prova.png"></div>
                                <div class="col-8 d-flex flex-wrap align-items-center">Nome utente</div>
                            </div>
                        </div>
                    </a></li>
                <li class="list-group-item bg border-0"><a href="#" class="text-link">
                        <div class="container">
                            <div class="row">
                                <div class="col-2 d-flex flex-wrap align-items-center p-0"><img class="border border-2 border-dark rounded-circle w-100 align-middle" alt="Filippo Champagne" src="assets/profile_pictures/prova.png"></div>
                                <div class="col-8 d-flex flex-wrap align-items-center">Nome utente</div>
                            </div>
                        </div>
                    </a></li>
                <li class="list-group-item bg border-0"><a href="#" class="text-link">
                        <div class="container">
                            <div class="row">
                                <div class="col-2 d-flex flex-wrap align-items-center p-0"><img class="border border-2 border-dark rounded-circle w-100 align-middle" alt="Filippo Champagne" src="assets/profile_pictures/prova.png"></div>
                                <div class="col-8 d-flex flex-wrap align-items-center">Nome utente</div>
                            </div>
                        </div>
                    </a></li>
                <li class="list-group-item bg border-0"><a href="#" class="text-link">
                        <div class="container">
                            <div class="row">
                                <div class="col-2 d-flex flex-wrap align-items-center p-0"><img class="border border-2 border-dark rounded-circle w-100 align-middle" alt="Filippo Champagne" src="assets/profile_pictures/prova.png"></div>
                                <div class="col-8 d-flex flex-wrap align-items-center">Nome utente</div>
                            </div>
                        </div>
                    </a></li>
                <li class="list-group-item bg border-0"><a href="#" class="text-link">
                        <div class="container">
                            <div class="row">
                                <div class="col-2 d-flex flex-wrap align-items-center p-0"><img class="border border-2 border-dark rounded-circle w-100 align-middle" alt="Filippo Champagne" src="assets/profile_pictures/prova.png"></div>
                                <div class="col-8 d-flex flex-wrap align-items-center">Nome utente</div>
                            </div>
                        </div>
                    </a></li>
                <li class="list-group-item bg border-0"><a href="#" class="text-link">
                        <div class="container">
                            <div class="row">
                                <div class="col-2 d-flex flex-wrap align-items-center p-0"><img class="border border-2 border-dark rounded-circle w-100 align-middle" alt="Filippo Champagne" src="assets/profile_pictures/prova.png"></div>
                                <div class="col-8 d-flex flex-wrap align-items-center">Nome utente</div>
                            </div>
                        </div>
                    </a></li>
                <li class="list-group-item bg border-0"><a href="#" class="text-link">
                        <div class="container">
                            <div class="row">
                                <div class="col-2 d-flex flex-wrap align-items-center p-0"><img class="border border-2 border-dark rounded-circle w-100 align-middle" alt="Filippo Champagne" src="assets/profile_pictures/prova.png"></div>
                                <div class="col-8 d-flex flex-wrap align-items-center">Nome utente</div>
                            </div>
                        </div>
                    </a></li>
            </ul>`;
        showSwal('Follower', swalContent);
    }

    const showSwalFollowed = () => {
        let swalContent = `
            <ul class="list-group modal-content-lg">
                <li class="list-group-item bg border-0"><a href="#" class="text-link">
                        <div class="container">
                            <div class="row">
                                <div class="col-2 d-flex flex-wrap align-items-center p-0"><img class="border border-2 border-dark rounded-circle w-100 align-middle" alt="Filippo Champagne" src="assets/profile_pictures/prova.png"></div>
                                <div class="col-8 d-flex flex-wrap align-items-center">Nome utente</div>
                            </div>
                        </div>
                    </a></li>
                <li class="list-group-item bg border-0"><a href="#" class="text-link">
                        <div class="container">
                            <div class="row">
                                <div class="col-2 d-flex flex-wrap align-items-center p-0"><img class="border border-2 border-dark rounded-circle w-100 align-middle" alt="Filippo Champagne" src="assets/profile_pictures/prova.png"></div>
                                <div class="col-8 d-flex flex-wrap align-items-center">Nome utente</div>
                            </div>
                        </div>
                    </a></li>
                <li class="list-group-item bg border-0"><a href="#" class="text-link">
                        <div class="container">
                            <div class="row">
                                <div class="col-2 d-flex flex-wrap align-items-center p-0"><img class="border border-2 border-dark rounded-circle w-100 align-middle" alt="Filippo Champagne" src="assets/profile_pictures/prova.png"></div>
                                <div class="col-8 d-flex flex-wrap align-items-center">Nome utente</div>
                            </div>
                        </div>
                    </a></li>
                <li class="list-group-item bg border-0"><a href="#" class="text-link">
                        <div class="container">
                            <div class="row">
                                <div class="col-2 d-flex flex-wrap align-items-center p-0"><img class="border border-2 border-dark rounded-circle w-100 align-middle" alt="Filippo Champagne" src="assets/profile_pictures/prova.png"></div>
                                <div class="col-8 d-flex flex-wrap align-items-center">Nome utente</div>
                            </div>
                        </div>
                    </a></li>
                <li class="list-group-item bg border-0"><a href="#" class="text-link">
                        <div class="container">
                            <div class="row">
                                <div class="col-2 d-flex flex-wrap align-items-center p-0"><img class="border border-2 border-dark rounded-circle w-100 align-middle" alt="Filippo Champagne" src="assets/profile_pictures/prova.png"></div>
                                <div class="col-8 d-flex flex-wrap align-items-center">Nome utente</div>
                            </div>
                        </div>
                    </a></li>
                <li class="list-group-item bg border-0"><a href="#" class="text-link">
                        <div class="container">
                            <div class="row">
                                <div class="col-2 d-flex flex-wrap align-items-center p-0"><img class="border border-2 border-dark rounded-circle w-100 align-middle" alt="Filippo Champagne" src="assets/profile_pictures/prova.png"></div>
                                <div class="col-8 d-flex flex-wrap align-items-center">Nome utente</div>
                            </div>
                        </div>
                    </a></li>
                <li class="list-group-item bg border-0"><a href="#" class="text-link">
                        <div class="container">
                            <div class="row">
                                <div class="col-2 d-flex flex-wrap align-items-center p-0"><img class="border border-2 border-dark rounded-circle w-100 align-middle" alt="Filippo Champagne" src="assets/profile_pictures/prova.png"></div>
                                <div class="col-8 d-flex flex-wrap align-items-center">Nome utente</div>
                            </div>
                        </div>
                    </a></li>
                <li class="list-group-item bg border-0"><a href="#" class="text-link">
                        <div class="container">
                            <div class="row">
                                <div class="col-2 d-flex flex-wrap align-items-center p-0"><img class="border border-2 border-dark rounded-circle w-100 align-middle" alt="Filippo Champagne" src="assets/profile_pictures/prova.png"></div>
                                <div class="col-8 d-flex flex-wrap align-items-center">Nome utente</div>
                            </div>
                        </div>
                    </a></li>
                <li class="list-group-item bg border-0"><a href="#" class="text-link">
                        <div class="container">
                            <div class="row">
                                <div class="col-2 d-flex flex-wrap align-items-center p-0"><img class="border border-2 border-dark rounded-circle w-100 align-middle" alt="Filippo Champagne" src="assets/profile_pictures/prova.png"></div>
                                <div class="col-8 d-flex flex-wrap align-items-center">Nome utente</div>
                            </div>
                        </div>
                    </a></li>
                <li class="list-group-item bg border-0"><a href="#" class="text-link">
                        <div class="container">
                            <div class="row">
                                <div class="col-2 d-flex flex-wrap align-items-center p-0"><img class="border border-2 border-dark rounded-circle w-100 align-middle" alt="Filippo Champagne" src="assets/profile_pictures/prova.png"></div>
                                <div class="col-8 d-flex flex-wrap align-items-center">Nome utente</div>
                            </div>
                        </div>
                    </a></li>
                <li class="list-group-item bg border-0"><a href="#" class="text-link">
                        <div class="container">
                            <div class="row">
                                <div class="col-2 d-flex flex-wrap align-items-center p-0"><img class="border border-2 border-dark rounded-circle w-100 align-middle" alt="Filippo Champagne" src="assets/profile_pictures/prova.png"></div>
                                <div class="col-8 d-flex flex-wrap align-items-center">Nome utente</div>
                            </div>
                        </div>
                    </a></li>
                <li class="list-group-item bg border-0"><a href="#" class="text-link">
                        <div class="container">
                            <div class="row">
                                <div class="col-2 d-flex flex-wrap align-items-center p-0"><img class="border border-2 border-dark rounded-circle w-100 align-middle" alt="Filippo Champagne" src="assets/profile_pictures/prova.png"></div>
                                <div class="col-8 d-flex flex-wrap align-items-center">Nome utente</div>
                            </div>
                        </div>
                    </a></li>
                <li class="list-group-item bg border-0"><a href="#" class="text-link">
                        <div class="container">
                            <div class="row">
                                <div class="col-2 d-flex flex-wrap align-items-center p-0"><img class="border border-2 border-dark rounded-circle w-100 align-middle" alt="Filippo Champagne" src="assets/profile_pictures/prova.png"></div>
                                <div class="col-8 d-flex flex-wrap align-items-center">Nome utente</div>
                            </div>
                        </div>
                    </a></li>
                <li class="list-group-item bg border-0"><a href="#" class="text-link">
                        <div class="container">
                            <div class="row">
                                <div class="col-2 d-flex flex-wrap align-items-center p-0"><img class="border border-2 border-dark rounded-circle w-100 align-middle" alt="Filippo Champagne" src="assets/profile_pictures/prova.png"></div>
                                <div class="col-8 d-flex flex-wrap align-items-center">Nome utente</div>
                            </div>
                        </div>
                    </a></li>
                <li class="list-group-item bg border-0"><a href="#" class="text-link">
                        <div class="container">
                            <div class="row">
                                <div class="col-2 d-flex flex-wrap align-items-center p-0"><img class="border border-2 border-dark rounded-circle w-100 align-middle" alt="Filippo Champagne" src="assets/profile_pictures/prova.png"></div>
                                <div class="col-8 d-flex flex-wrap align-items-center">Nome utente</div>
                            </div>
                        </div>
                    </a></li>
                <li class="list-group-item bg border-0"><a href="#" class="text-link">
                        <div class="container">
                            <div class="row">
                                <div class="col-2 d-flex flex-wrap align-items-center p-0"><img class="border border-2 border-dark rounded-circle w-100 align-middle" alt="Filippo Champagne" src="assets/profile_pictures/prova.png"></div>
                                <div class="col-8 d-flex flex-wrap align-items-center">Nome utente</div>
                            </div>
                        </div>
                    </a></li>
                <li class="list-group-item bg border-0"><a href="#" class="text-link">
                        <div class="container">
                            <div class="row">
                                <div class="col-2 d-flex flex-wrap align-items-center p-0"><img class="border border-2 border-dark rounded-circle w-100 align-middle" alt="Filippo Champagne" src="assets/profile_pictures/prova.png"></div>
                                <div class="col-8 d-flex flex-wrap align-items-center">Nome utente</div>
                            </div>
                        </div>
                    </a></li>
            </ul>`;
        showSwal('Seguiti', swalContent);
    }

    const showSwalSettings = () => {
        let swalContent = `
            <div class='row-md-2 py-2 text-center text-black overflow-hidden'>
                <div class='container px-0'>
                    <div class='row mb-3'>
                        <div class='col'>
                            <p class="fs-5">Cambia immagine profilo:</p>
                            <input type="file" class="form-control bg-primary rounded shadow-sm" />
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
                // TODO Upload foto

                closeSwal();
                showToast('success', '<p class="fs-5">Foto profilo cambiata con successo!</p>');
            });

            $('#logoutButton').click(function() {
                window.location.href = "logout.php";
            });
        });
    }

    $("#followerButton").on("click", showSwalFollower);
    $("#followedButton").on("click", showSwalFollowed);
    $("#shareButton").on("click", showSwalShare);
    $("#allPostsButton").on("click", showAllPosts);
    $("#likedPostsButton").on("click", showLikedPosts);
    $("#settingsButton").on("click", showSwalSettings);
    $("#logoutButton").on("click", logOut);
</script>