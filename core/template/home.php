<div class="swiper">
    <div id="gnamSlider" class="swiper-wrapper"></div>
</div>
<script>
    let isDescriptionShort = true;
    let selectedPortions = 1;
    let commentToReplyID = null;
    let currentGnamID = null;
    let gnamsQueue = null;
    let firstSlideIndex = 0;
    let threshold = 5;
    let gnamsLeft = threshold;
    let wentUp = false;
    let swiper;

    const initializeSwiper = () => {
        document.querySelectorAll('[id^="gnam-"]').forEach(element => {
            element.classList.remove('d-none');
        });

        swiper = new Swiper('.swiper', {
            initialSlide: firstSlideIndex,
            direction: 'vertical',
            loop: false,
            keyboard: {
                enabled: true
            },
            on: {
                slideChangeTransitionEnd: function () {
                    stopCurrentVideo();
                    $("#gnamPlayer-" + currentGnamID)[0].currentTime = 0;
                    currentGnamID = $(".swiper-slide-active").attr('id').split('-')[1];
                    playCurrentVideo();
                },
                slideNextTransitionEnd: function () {
                    const currentIndex = gnamsQueue.findIndex(item => item[0] === parseInt(currentGnamID));
                    let newIndex = currentIndex + Math.floor(threshold / 2);
                    if (newIndex < gnamsQueue.length && !gnamsQueue[newIndex][1]) {
                        drawGnam(newIndex);
                    }
                },
                slidePrevTransitionEnd: function () {
                    const currentIndex = gnamsQueue.findIndex(item => item[0] === parseInt(currentGnamID));
                    let newIndex = Math.max(0, currentIndex - Math.floor(threshold / 2));
                    if (newIndex >= 0 && !gnamsQueue[newIndex][1]) {
                        wentUp = true;
                        drawGnam(newIndex);
                    }
                }
            }
        });
    }

    $(window).on("load", function () {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('gnam')) {
            gnamsQueue = [[(urlParams.get('gnam')), false]];
            currentGnamID = urlParams.get('gnam');
            drawFirstGnams();
        } else {
            gnamsInCookies = JSON.parse(readAndDeleteCookie('gnamsToWatch'));
            if (gnamsInCookies == null) {
                $.get("api/search.php", {
                    api_key: "<?php echo $_SESSION['api_key']; ?>",
                    action: 'random'
                }, function (data) {
                    gnamsQueue = JSON.parse(data);
                    currentGnamID = gnamsQueue[0];
                    for (let i = 0; i < gnamsQueue.length; i++) {
                        const element = gnamsQueue[i];
                        newArray = [element, false];
                        gnamsQueue[i] = newArray;
                    }
                    drawFirstGnams();
                });
            } else {
                gnamsQueue = [];
                currentGnamID = gnamsInCookies['startFrom'];
                let firstGnamIndex = 0;
                for (let i = 0; i < gnamsInCookies['list'].length; i++) {
                    const element = gnamsInCookies['list'][i];
                    newArray = [element, false];
                    gnamsQueue[i] = newArray;
                    if (element == gnamsInCookies['startFrom']) {
                        firstGnamIndex = i
                    }
                }
                firstSlideIndex = Math.min(firstGnamIndex, Math.floor(threshold / 2));
                drawFirstGnams();
            }
        }

        $("#gnamSlider").on('click', function () {
            let gnamPlayer = $("#gnamPlayer-" + currentGnamID)[0];

            if (gnamPlayer.paused) {
                gnamPlayer.play();
            } else {
                gnamPlayer.pause();
            }
        });
    });

    const drawFirstGnams = () => {
        const firstIndex = gnamsQueue.findIndex(item => item[0] == parseInt(currentGnamID));
        let newIndex = firstIndex + Math.ceil(threshold / 2) - gnamsLeft;
        if (newIndex < 0) {
            gnamsLeft--;
            drawFirstGnams();
        } else {
            const id = gnamsQueue[newIndex][0];
            $.get("api/gnams.php", {
                api_key: "<?php echo $_SESSION['api_key']; ?>",
                gnam: id
            }, function (gnamsData) {
                addGnamSlide(JSON.parse(gnamsData));
                $.get("api/comments.php", {
                    api_key: "<?php echo $_SESSION['api_key']; ?>",
                    gnam_id: id
                }, function (commentsData) {
                    setComments(JSON.parse(commentsData), id);
                    gnamsLeft--;
                    if (gnamsLeft > 0 && newIndex != gnamsQueue.length - 1) {
                        drawFirstGnams();
                    } else {
                        gnamsLeft = 0;
                        initializeSwiper();
                    }
                });
            });
        }
    }

    const drawGnam = (index) => {
        const id = gnamsQueue[index][0];
        $.get("api/gnams.php", {
            api_key: "<?php echo $_SESSION['api_key']; ?>",
            gnam: id
        }, function (gnamsData) {
            addGnamSlide(JSON.parse(gnamsData));
            $.get("api/comments.php", {
                api_key: "<?php echo $_SESSION['api_key']; ?>",
                gnam_id: id
            }, function (commentsData) {
                setComments(JSON.parse(commentsData), id);
            });
            document.querySelector("#gnam-" + id).classList.remove('d-none');
            if (wentUp) {
                swiper.destroy();
                firstSlideIndex = Math.floor(threshold/2);
                initializeSwiper();
                wentUp = false;
            } else {
                swiper.update();
            }
        });
    }

    const addGnamSlide = (gnamsInfo) => {
        let gnamHtml = `
            <video id="gnamPlayer-${gnamsInfo['id']}" class="w-100 h-100 object-fit-fill p-0" loop playsinline preload="auto" poster="assets/gnams_thumbnails/${gnamsInfo['id']}.jpg" src="assets/gnams/${gnamsInfo['id']}.mp4"></video>
            <div  id="videoOverlay-${gnamsInfo['id']}" class="video-overlay">
                <div class="container">
                    <div class="row mb-3">
                        <div id="descriptionBox-${gnamsInfo['id']}" class="col-10 align-self-end">
                            <div class="row text-link">
                                <div class="col-3">
                                    <img id="userImage-${gnamsInfo['id']}" class="border border-2 border-dark rounded-circle w-100" alt="${gnamsInfo['user_name']}" src="assets/profile_pictures/${gnamsInfo['user_id']}.jpg" />
                                </div>
                                <div class="col-9 d-flex align-items-center p-0">
                                    <p id="userName-${gnamsInfo['id']}" class="fs-6 fw-bold m-0">${gnamsInfo['user_name']}</p>
                                </div>
                            </div>
                            <div class="row" id="videoDescription">
                                <span id="videoDescriptionShort-${gnamsInfo['id']}" class="fs-7 m-0">${gnamsInfo['short_description']}
                                    <span class="fs-7 m-0 color-accent">Leggi di piú...</span>
                                </span>
                                <p id="videoDescriptionLong-${gnamsInfo['id']}" class="fs-7 m-0 d-none">${gnamsInfo['description']}</p>
                            </div>
                            <div class="row" id="videoTags-${gnamsInfo['id']}">
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="container p-0">
                                <div class="col">
                                    <div class="row pb-4 text-center" id="recipeButton-${gnamsInfo['id']}">
                                        <span><i class="fa-solid fa-utensils fa-2xl fa-fw color-secondary"></i></span>
                                    </div>
                                    <div class="row text-center" id="likeButton-${gnamsInfo['id']}">
                                        <span><i class="fa-solid fa-heart fa-2xl fa-fw color-secondary"></i></span>
                                    </div>
                                    <div class="row pt-2 color-accent fw-bold text-center">
                                        <span id="likesCounter-${gnamsInfo['id']}">${gnamsInfo['likes_count']}</span>
                                    </div>
                                    <div class="row pt-2 text-center" id="commentsButton-${gnamsInfo['id']}">
                                        <span><i class="fa-solid fa-comment-dots fa-2xl fa-fw color-secondary"></i></span>
                                    </div>
                                    <div class="row pt-2 color-accent fw-bold text-center">
                                        <span id="commentsCounter-${gnamsInfo['id']}">0</span>
                                    </div>
                                    <div class="row pt-2 text-center" id="shareButton-${gnamsInfo['id']}">
                                        <span><i class="fa-solid fa-share-nodes fa-2xl fa-fw color-secondary"></i></span>
                                    </div>
                                    <div class="row pt-2 color-accent fw-bold text-center">
                                        <span id="shareCounter-${gnamsInfo['id']}">${gnamsInfo['shares_count']}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `
        const slideElement = document.createElement('div');
        slideElement.classList.add("swiper-slide");
        slideElement.classList.add("d-none");
        slideElement.id = "gnam-" + gnamsInfo['id'];
        slideElement.innerHTML = gnamHtml.trim();

        let count = 0;
        let tagHTML = '';
        gnamsInfo['tags'].forEach(tag => {
            let tagText = tag['text'];

            if (count < 2) {
                tagHTML += `
                    <div class="col-4 videoTag">
                        <span class="badge rounded-pill bg-primary fw-light text-black">
                            #${tagText}
                        </span>
                    </div>`;
            } else {
                tagHTML += `
                    <div class="col-4 d-none videoTag">
                        <span class="badge rounded-pill bg-primary fw-light text-black">
                            #${tagText}
                        </span>
                    </div>`;
            }
            count++;
        });

        if (gnamsInfo['tags'].length > 2) {
            tagHTML += `
                <div class="col-2 pe-0" id="moreTagsButton-${gnamsInfo['id']}">
                    <span class="badge rounded-pill bg-primary fw-light text-black">
                        <i class="fa-solid fa-ellipsis"></i>
                    </span>
                </div>`;
        }

        slideElement.querySelector('#videoTags-' + gnamsInfo['id']).innerHTML = tagHTML;
        let indexOfId = gnamsQueue.findIndex(item => item[0] == gnamsInfo['id']);
        if (currentGnamID == gnamsInfo['id']) {
            slideElement.querySelector("#gnamPlayer-" + currentGnamID).setAttribute("autoplay", "");
        }
        if ($(".swiper-slide").length == 0) {
            $("#gnamSlider").append(slideElement);
        } else if (indexOfId - 1 >= 0 && gnamsQueue[indexOfId - 1][1] == true) {
            let lastGnamChild = $("#gnamSlider").children("[id^='gnam-']").last();
            $(slideElement).insertAfter(lastGnamChild);
        } else if (indexOfId + 1 < gnamsQueue.length && gnamsQueue[indexOfId + 1][1] == true) {
            let firstGnamChild = $("#gnamSlider").children("[id^='gnam-']").first();
            $(slideElement).insertBefore(firstGnamChild);
        }
        gnamsQueue[indexOfId][1] = true;

        $.get('api/likes.php', {
            "api_key": '<?php echo $_SESSION["api_key"] ?>',
            "gnam_id": gnamsInfo['id']
        }, function (data) {
            let children = $("#likeButton-" + gnamsInfo['id']).children().children();
            if (JSON.parse(data) && children.hasClass("color-secondary")) {
                children.removeClass("color-secondary").addClass("color-alert");
            }
        });

        $("#likeButton-" + gnamsInfo['id']).each(function () {
            let likeButton = $(this);
            let children = likeButton.children().children();
            let likesCounter = $("#likesCounter-" + gnamsInfo['id']);
            likeButton.on("click", function (e) {
                if (children.hasClass("color-secondary")) {
                    children.removeClass("color-secondary").addClass("color-alert");
                    likesCounter.text(parseInt(likesCounter.text()) + 1);
                } else {
                    children.addClass("color-secondary").removeClass("color-alert");
                    likesCounter.text(parseInt(likesCounter.text()) - 1);
                }
                $.post('api/likes.php', {
                    "api_key": '<?php echo $_SESSION["api_key"] ?>',
                    "gnam_id": gnamsInfo['id'],
                    "action": "TOGGLE_LIKE"
                });
                e.stopPropagation();
            });
        });

        isDescriptionShort = true;
        $("#descriptionBox-" + gnamsInfo['id']).on("click", showFullDescription);
        $("#videoOverlay-" + gnamsInfo['id']).on("click", showShortDescription);

        $("#shareButton-" + gnamsInfo['id']).on("click", function (e) {
            let swalContent = `
            <div class='row-md-2 py-2 text-center text-black'>
                <div class='container'>
                    <div class='col'>
                        <div class='row-9 py-4'><i class='fa-solid fa-share-nodes fa-2xl'></i></div>
                        <div class='row-3 pt-3'><button type='button' class='btn btn-bounce rounded-pill bg-accent fw-bold
                                text-white' id="copyGnamLinkButton">Copia link</button></div>
                    </div>
                </div>
            </div>`;
            showSwalSmall('<p class="fs-5">Condividi Gnam</p>', swalContent);
            $("#copyGnamLinkButton").on("click", function () {
                $("#shareCounter-" + currentGnamID).text(parseInt($("#shareCounter-" + currentGnamID).text()) + 1);
                let gnamLink = buildURL("home", "gnam=" + currentGnamID);
                navigator.clipboard.writeText(gnamLink)
                    .then(function () {
                        showToast("success", "Link copiato nella clipboard");
                        $.post('api/gnams.php', {
                            "api_key": '<?php echo $_SESSION["api_key"] ?>',
                            "gnam_id": currentGnamID,
                            "action": "INCREMENT_SHARE"
                        });
                    })
                    .catch(function (err) {
                        console.error("Impossibile copiare il link nella clipboard: ", err);
                    });
            });
            e.stopPropagation();
        });

        $("#recipeButton-" + gnamsInfo['id']).on("click", function (e) {
            let html = `
                <div class="d-flex align-items-center justify-content-center mb-2">
                    <p class="m-0 me-2 fs-6">Numero di porzioni:</p>
                    <input type="number" value="1" min="1" max="100" class="form-control bg-primary rounded shadow-sm fs-6 fw-bold text-center" id="portionsInput" />
                </div>
                <div class="row mx-0 my-2">
                    <div class="col-6 d-flex align-items-center justify-content-start">
                        <p class="m-0 p-0 fs-6 fw-bold">Nome:</p>
                    </div>
                    <div class="col-6 d-flex align-items-center justify-content-end">
                        <p class="m-0 p-0 fs-6 fw-bold">Quantità</p>
                    </div>
                </div>
                <hr class="my-2" />
                <div class="text-center" id="ingredients-${gnamsInfo['id']}">
                </div>
                <hr class="m-0 mt-2" />
                </div>
            `;
            stopCurrentVideo();
            showSwal('Ricetta', html, playCurrentVideo);
            $('#portionsInput').val(selectedPortions);
            $("#portionsInput").on("change", function (e) {
                if ((this).value > 0) {
                    selectedPortions = (this).value;
                    drawAllIngredients(gnamsInfo['recipe']);
                } else {
                    (this).value = selectedPortions;
                }
            });
            drawAllIngredients(gnamsInfo['recipe']);
            e.stopPropagation();
        });

        $("#userImage-" + gnamsInfo['id']).on("click", function (e) {
            redirectToGnamUserPage(gnamsInfo['user_id']);
            e.stopPropagation();
        });

        $("#userName-" + gnamsInfo['id']).on("click", function (e) {
            redirectToGnamUserPage(gnamsInfo['user_id']);
            e.stopPropagation();
        });

        $("#videoTags-" + gnamsInfo['id'] + " .videoTag span").on("click", function (e) {
            window.location = "search.php?q=" + encodeURIComponent($(this).html().trim());
        });
    }

    const showFullDescription = (e) => {
        if (isDescriptionShort) {
            isDescriptionShort = false;
            $("#videoDescriptionShort-" + currentGnamID).addClass("d-none");
            $("#videoDescriptionLong-" + currentGnamID).removeClass("d-none");
            $("#moreTagsButton-" + currentGnamID).addClass("d-none");
            let videoTags = $("#videoTags-" + currentGnamID + " .videoTag");
            for (let i = 0; i < videoTags.length; i++) {
                $(videoTags[i]).removeClass("d-none");
            }
            $("#videoOverlay-" + currentGnamID).css("background-image", "linear-gradient(0deg, var(--background), rgba(248, 215, 165, 0) 40%)");
            e.stopPropagation();
        }
    }

    const showShortDescription = (e) => {
        if (!isDescriptionShort) {
            isDescriptionShort = true;
            $("#videoDescriptionLong-" + currentGnamID).addClass("d-none");
            $("#videoDescriptionShort-" + currentGnamID).removeClass("d-none");
            $("#moreTagsButton-" + currentGnamID).removeClass("d-none");
            let videoTags = $("#videoTags-" + currentGnamID + " .videoTag");
            for (let i = 2; i < videoTags.length; i++) {
                $(videoTags[i]).addClass("d-none");
            }
            $("#videoOverlay-" + currentGnamID).css("background-image", "linear-gradient(0deg, var(--background), rgba(248, 215, 165, 0) 30%)");
            e.stopPropagation();
        }
    }

    const drawAllIngredients = (recipe) => {
        $("#ingredients-" + currentGnamID).empty();
        let ingredientsHTML = "";
        recipe.forEach(ingredient => {
            ingredientsHTML += `
            <div class="row m-0 p-0 align-items-center">
                <div class="col-8 m-0 p-1 d-flex align-items-center justify-content-start">
                    <p class="m-0 fs-6">${ingredient["name"]}</p>
                </div>
                <div class="col-4 m-0 p-1 d-flex align-items-center justify-content-end">
                    <p class="m-0 fs-6 fw-bold ">${ingredient["quantity"] > 0 ? ingredient["quantity"] * selectedPortions : ""} ${ingredient["measurement_unit"]}</p>
                </div>
            </div>`;
        });
        $("#ingredients-" + currentGnamID).append(ingredientsHTML);
    }

    const buildURL = (siteSection, query) => {
        return window.location.href.split("home")[0] + siteSection + ".php?" + query;
    }

    const redirectToGnamUserPage = (userId) => {
        let redirectPath = buildURL("profile", "user=" + userId);
        window.location.href = redirectPath;
    }

    const getCommentsHTML = (comments) => {
        if (comments.length == 0) {
            let firstCommentHTML = `
                <div class="row-8 modal-content-lg">
                    <div class="container">
                        <div class="col">
                            <div class="row p-1 pb-3">
                                <span>Sii il primo a commentare!</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row-1 bg-primary rounded">
                    <div class="input-group rounded">
                        <input id="commentField-${currentGnamID}" type="text" class="fs-7 form-control bg-primary shadow-sm" placeholder="Insercisci commento..." />
                        <span id="commentButton-${currentGnamID}" class="input-group-text bg-primary border-0 fs-7 fw-bold cursor-pointer">Commenta</span>
                    </div>
                </div>`;

            let commentsContainerElement = document.createElement('div');
            commentsContainerElement.classList.add("container");
            commentsContainerElement.id = "commentsBoxContainer-" + currentGnamID;
            commentsContainerElement.innerHTML = firstCommentHTML.trim();
            return commentsContainerElement.outerHTML;
        } else {
            let commentContainerHTML = `
                <div class="row-8 modal-content-lg">
                    <div class="container">
                        <div class="col">
                            <div id="commentsContainer" class="row">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row-1 bg-primary rounded">
                    <div class="rounded bg-primary p-1 d-none" id="replyToDiv-${currentGnamID}">
                        <div class="rounded bg container">
                            <div class="row">
                                <div class="col-11 align-items-center">
                                    <span class="border-0 fs-7">Stai rispondendo a: <span id="replyToName-${currentGnamID}" class="text-link"></span></span>
                                </div>
                                <div class="col-1 d-flex align-items-center p-0">
                                    <i id="closeReplyTo-${currentGnamID}" class="fa-solid fa-xmark color-accent"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="input-group rounded">
                        <input id="commentField-${currentGnamID}" type="text" class="fs-7 form-control bg-primary shadow-sm" placeholder="Insercisci commento..." />
                        <span id="commentButton-${currentGnamID}" class="input-group-text bg-primary border-0 fs-7 fw-bold cursor-pointer">Commenta</span>
                    </div>
                </div>`;

            let commentsContainerElement = document.createElement('div');
            commentsContainerElement.classList.add("container");
            commentsContainerElement.id = "commentsBoxContainer-" + currentGnamID;
            commentsContainerElement.innerHTML = commentContainerHTML.trim();

            comments.sort((a, b) => {
                if (a.parent_comment_id === null && b.parent_comment_id !== null) {
                    return -1;
                } else if (a.parent_comment_id !== null && b.parent_comment_id === null) {
                    return 1;
                } else {
                    return a.timestamp - b.timestamp;
                }
            });

            comments.forEach(comment => {
                if (comment['parent_comment_id'] == null) {
                    let commentHTML = `
                    <div id="comment-${comment['id']}" class="container comment py-1">
                        <div class="row">
                            <div class="col-2 p-0">
                                <img id="commenterImg-${comment['id']}" class="border border-2 border-dark rounded-circle w-100" alt="${comment['user_name']}"
                                    src="assets/profile_pictures/${comment['user_id']}.jpg"/>
                            </div>
                            <div class="col">
                                <div class="row-md-1 text-start">
                                    <span id="commenterUserName-${comment['id']}" class="text-link">${comment['user_name']}</span>
                                </div>
                                <div class="row-md text-normal-black fs-7 text-start">
                                    <p class="m-0">${comment['text']}</p>
                                </div>
                                <div class="row-md-1 text-start">
                                    <span id="replyButton-${comment['id']}" class="text-button fw-bold color-accent fs-7 ">Rispondi</span>
                                </div>
                            </div>
                        </div>
                        <div id="subcommentsContainer-${comment['id']}" class="row d-none">
                        </div>
                    </div>`;

                    commentsContainerElement.querySelector('#commentsContainer').innerHTML += commentHTML;
                } else {
                    let commentHTML = `
                    <div class="row">
                        <div class="col-2"></div>
                        <div class="col">
                            <div id="comment-${comment['id']}" class="container subcomment py-1">
                                <div class="row">
                                    <div class="col-2 p-0">
                                        <img class="border border-2 border-dark rounded-circle w-100" alt="${comment['user_name']}"
                                            src="assets/profile_pictures/${comment['user_id']}.jpg"/>
                                    </div>
                                    <div class="col">
                                        <div class="row-md-1 text-start">
                                            <span id="commenterUserName-${comment['id']}" class="text-link">${comment['user_name']}</span>
                                        </div>
                                        <div class="row-md text-normal-black fs-7 text-start">
                                            <p class="m-0">${comment['text']}</p>
                                        </div>
                                        <div class="row-md-1 text-start">
                                            <span id="replyButton-${comment['id']}" class="replyTo-${comment['parent_comment_id']} text-button fw-bold color-accent fs-7 ">Rispondi</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>`;
                    commentsContainerElement.querySelector('#subcommentsContainer-' + comment['parent_comment_id']).classList.remove('d-none');
                    commentsContainerElement.querySelector('#subcommentsContainer-' + comment['parent_comment_id']).innerHTML += commentHTML;
                }
            });
            return commentsContainerElement.outerHTML;
        }
    }

    const setHandlersForCommentsContainer = (comments) => {
        comments.forEach(comment => {
            $("#replyButton-" + comment['id']).on("click", function (e) {
                const id = $(e.target).attr('id').split('-')[1];
                const parent = $("#comment-" + id);
                let commenterName = "";
                if (parent.hasClass('subcomment')) {
                    commentToReplyID = $(e.target).attr('class').split(' ')[0].split('-')[1];
                } else {
                    commentToReplyID = id;
                }

                commenterName = parent.find("#commenterUserName-" + id).text();
                $("#replyToDiv-" + currentGnamID).removeClass("d-none");
                $("#replyToName-" + currentGnamID).text(commenterName);

            });
            $("#commenterUserName-" + comment['id']).on("click", function () {
                redirectToGnamUserPage(comment['user_id']);
            });
            $("#commenterImg-" + comment['id']).on("click", function () {
                redirectToGnamUserPage(comment['user_id']);
            });
        });
        $("#commentButton-" + currentGnamID).on("click", function () {
            const commentText = $("#commentField-" + currentGnamID).val();
            if (commentText.length === 0) return;
            $.post("api/comments.php", {
                api_key: "<?php echo $_SESSION['api_key']; ?>",
                "gnam_id": currentGnamID,
                "text": commentText,
                "parent_comment_id": commentToReplyID
            }, (result) => {
                $.get("api/comments.php", {
                    api_key: "<?php echo $_SESSION['api_key']; ?>",
                    gnam_id: currentGnamID
                }, function (commentsData) {
                    let comments = JSON.parse(commentsData);
                    $("#commentsBoxContainer-" + currentGnamID).parent().html(getCommentsHTML(comments));
                    setComments(comments, currentGnamID);
                    setHandlersForCommentsContainer(comments);
                });
            });
        });
        $("#closeReplyTo-" + currentGnamID).on("click", function () {
            $("#replyToDiv-" + currentGnamID).addClass("d-none");
            commentToReplyID = null;
        });
    }

    const setComments = (comments, gnam_id) => {
        $("#commentsCounter-" + gnam_id).text(comments.length);
        $("#commentsButton-" + gnam_id).on('click', function (e) {
            stopCurrentVideo();
            showSwal('Commenti', getCommentsHTML(comments), playCurrentVideo);
            setHandlersForCommentsContainer(comments);
            e.stopPropagation();
        });
    }

    const stopCurrentVideo = () => {
        $("#gnamPlayer-" + currentGnamID)[0].pause();
    }

    const playCurrentVideo = () => {
        $("#gnamPlayer-" + currentGnamID)[0].play();
    }

</script>