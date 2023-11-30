<div class="container text-center font-text">
    <div class="row-2 py-2 h4" id="headerDiv">
        <h1 class="fw-bold">Pubblica Gnam</h1>
    </div>

    <!-- video chooser field -->
    <div class="row container overflow-auto p-0 m-0" id="pageContentDiv">
        <div class="col">
            <div class="row-md px-4 h4">
                <h2 class="fw-bold">Scegli video</h2>
                <input type="file" class="form-control bg-primary rounded shadow-sm" />
            </div>
            <!-- thumbnail chooser field -->
            <div class="row-md px-4 h4">
                <h2 class="fw-bold">Scegli copertina</h2>
                <input type="file" class="form-control bg-primary rounded shadow-sm" />
            </div>
            <!-- description field -->
            <div class="row-md-6 px-4 h4">
                <h2 class="fw-bold">Descrizione</h2>
                <textarea class="form-control bg-primary rounded shadow-sm" rows="3"></textarea>
            </div>
            <!-- ingredients -->
            <div class="row-sm pt-2 pb-0 ">
                <!-- Button con counter -->
                <button type="button" onclick="chooseIngredients();" class="btn btn-bounce rounded-pill bg-secondary fw-bold text-white">
                    Ingredienti <span class="badge rounded-pill bg-accent">0</span>
                </button>
            </div>
            <!-- tag -->
            <div class="row-sm pt-1 h-0">
                <!-- Button con counter -->
                <button type="button" class="btn btn-bounce rounded-pill bg-secondary fw-bold text-white" id="hashtagsButton" >
                    Hashtag <span class="badge rounded-pill bg-accent" id="hashtagsCount">0</span>
                </button>
            </div>
            <!-- read all notification button -->
            <div class="row-md-4 pt-4">
                <a href="#" role="button" class="btn btn-bounce rounded-pill bg-accent fw-bold text-white" id="publishBtn">Pubblica Gnam</a>
            </div>
        </div>
    </div>
</div>

<script>
    const chooseIngredients = () => {
        let html = '<div class="row-md-2 py-2"><div class="input-group rounded"><span class="input-group-text bg-primary border-0"><i class="fa-solid fa-magnifying-glass"></i></span><input type="text" class="form-control bg-primary shadow-sm" placeholder="Cerca Ingredienti"></div></div><hr><div class="text-center" id="searchedIngredients"><div class="row fw-bold m-0 p-0 fs-xs"><div class="col">Cannella</div><div class="col"><select class="form-select bg-primary rounded shadow-sm"><option>1</option><option>2</option><option>3</option></select></div><div class="col"><select class="form-select bg-primary rounded shadow-sm"><option>cucch.no</option><option>gr.</option><option>qb</option></select></div><div class="col"><button type="button" class="btn btn-bounce bg-primary text-black"><i class="fa-solid fa-trash-can"></i></button></div></div></div><hr><div class="row m-0 p-0"><div class="col-6"><button type="button" class="btn btn-bounce rounded-pill bg-alert fw-bold text-white w-100" id="reset">Reset</button></div><div class="col-6"><button type="button" class="btn btn-bounce rounded-pill bg-accent fw-bold text-white w-100" id="addElement">Ok</button></div></div>';
        showSwal('Scegli Ingredienti', html);
    }

    const publish = () => {
        // TO DO: Handling dati con PHP

        let html = '<div class="row-md-2 py-2 text-center text-black"><i class="fa-solid fa-check fa-2xl"></i></div>';
        showSwalSmall('Gnam pubblicato', html);
    }

    let hashtags = [];

    const openHashtags = () => {
        let modalContent = '';

        if (hashtags.length > 0) {
            modalContent = hashtags.map(hashtag => '<p class="fw-bold"><button type="button" class="btn btn-bounce bg-primary text-black" onclick="removeHashtag(this)"><i class="fa-solid fa-trash-can"></i></button>&nbsp#' + hashtag + '</p>').join('');
        }

        let html = `<div class="row-md-2 py-2">
                        <div class="input-group rounded">
                            <span class="input-group-text bg-primary border-0"><i class="fa-solid fa-magnifying-glass"></i></span>
                            <input type="text" id="hashtagInput" class="form-control bg-primary shadow-sm" placeholder="Cerca Hashtag">
                        </div>
                    </div>
                    <hr>
                    <div class="text-center" id="searchedHashtags">${modalContent}</div>
                    <hr>
                    <div class="row m-0 p-0">
                        <div class="col-6">
                            <button type="button" class="btn btn-bounce rounded-pill bg-alert fw-bold text-white w-100" onclick="resetHashtags()">Reset</button>
                        </div>
                        <div class="col-6">
                            <button type="button" id="okButton" class="btn btn-bounce rounded-pill bg-accent fw-bold text-white w-100">Ok</button>
                        </div>
                    </div>`;

        const modal = showSwal('Scegli hashtag', html);

        $('#hashtagInput').keypress(function(event) {
            if (event.which === 13) {
                addHashtag();
            }
        });

        $('#okButton').click(function() {
            closeSwal();
        });
    }

    const addHashtag = () => {
        let newHashtag = $('#hashtagInput').val().trim();
        while(newHashtag.startsWith('#')) {
            newHashtag = newHashtag.slice(1);
        }
        newHashtag = '#' + newHashtag;
        if (!newHashtag || hashtags.includes(newHashtag)) {
            return;
        }        
        hashtags.push(newHashtag);
        $("#searchedHashtags").append('<p class="fw-bold"><button type="button" class="btn btn-bounce bg-primary text-black" onclick="removeHashtag(this)"><i class="fa-solid fa-trash-can"></i></button>&nbsp' + newHashtag + '</p>');
        $('#hashtagInput').val('');
        $('#hashtagsCount').html(hashtags.length);
    }


    const removeHashtag = (element) => {
        let indexToRemove = $(element).parent().index();
        hashtags.splice(indexToRemove, 1);
        $(element).parent().remove();
        $('#hashtagsCount').html(hashtags.length);
    }

    const resetHashtags = () => {
        hashtags = [];
        $("#searchedHashtags").empty();
        $('#hashtagsCount').html(hashtags.length);
    }
    
    $("#publishBtn").on("click", publish);
    $("#hashtagsButton").on("click", openHashtags);
</script>