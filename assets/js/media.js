$(document).ready(function() {
    function copyToClipboard(text) {
        var $temp = $('<input>');
        $('body').append($temp);
        $temp.val(text).select();
        document.execCommand('copy');
        $temp.remove();
    }
    $('.btn-link').on('click', function() {
        copyToClipboard($(this).attr('data'));
        $('.div-rs').html('Coppied Link!').css('display', 'block');
        $('.div-rs').fadeOut(2000); return false;
    });
    var resizeCheck = "small";
    var playersize = { width: 0, height: 0 }
    var playerWrapper = $(".video-play");
    var wrapper = $('.bodyall').width();
    playersize.width = playerWrapper.width();
    playersize.height = playerWrapper.height();
    $("#explayer").click(function() {
        var container = $('.bodyall').width();
        if (container < 970) { return; }
        if (resizeCheck == "small") { playerWrapper.animate({ width: wrapper - 20, height: (wrapper - 20) / 1.78 });
            $(".columright").animate({ marginTop: (wrapper - 30) / 1.78 + 40 });
            $("#explayer").html("Zoom-");
            resizeCheck = "large"; } else if (resizeCheck == "large") { playerWrapper.animate({ width: playersize.width, height: playersize.height });
            $(".columright").animate({ marginTop: 0 });
            $("#explayer").html("Zoom+");
            resizeCheck = "small"; }
        $("html, body").animate({ scrollTop: $(playerWrapper).offset().top - 10 }, 1500);
    });
    function setVoteCountAfterButton(buttonEl, countText) {
        if (!buttonEl) return;
        // if (buttonEl.nextSibling && buttonEl.nextSibling.nodeType === Node.TEXT_NODE) {
        //     buttonEl.nextSibling.data = String(countText);
        // } else {
        //     buttonEl.insertAdjacentText('afterend', String(countText));
        // }

        buttonEl.innerHTML = countText;
    }
    function voteMovie(ratingValue) {
        var id = $(this).data('id');
        if (!id) return;

        $.post(ajaxUrl, {
            action: 'ratemovie',
            rating: ratingValue,
            postid: id
        }, function(resp) {
            try {
                if (!resp || resp.status !== 'success') return;

                var likeEl = document.getElementsByClassName('rate-like')[0];
                var dislikeEl = document.getElementsByClassName('rate-dislike')[0];

                setVoteCountAfterButton(likeEl, resp.like_count);
                setVoteCountAfterButton(dislikeEl, resp.dislike_count);
            } catch (e) {}
        }, 'json');
    }

    $('.btn-like').on('click', function(e) {
        e.preventDefault();
        voteMovie.call(this, 1);
    });

    $('.btn-dislike').on('click', function(e) {
        e.preventDefault();
        voteMovie.call(this, 0);
    });

    $("#server1").click(function() {
        $('span.default-srv').removeClass('default-srv');
        $('#server1').addClass('default-srv');
        $('#video').attr('data-sv', '1');
    });
    $("#server2").click(function() {
        $('span.default-srv').removeClass('default-srv');
        $('#server2').addClass('default-srv');
        $('#video').attr('data-sv', '2');
    });
     $("#server3").click(function() {
        $('span.default-srv').removeClass('default-srv');
        $('#server3').addClass('default-srv');
        $('#video').attr('data-sv', '3');
    });
});

var reloadedCount = {};
function reloadCurrentserver() {
    var data_id = $('#video').attr('data-id');
    var server_id = $('#video').attr('data-sv');
    if (data_id) {
        server(server_id, data_id);
    }
}

function del_cache() {
    var data_id = $('#video').attr('data-id');
    var server_id = $('#video').attr('data-sv');
    $.post(ajaxUrl, {
        delcache: 1,
        server: server_id,
        videoid: data_id
    });
}

function errorHandler() {
    var time = 2;
    var data_id = $('#video').attr('data-id');
    var server_id = $('#video').attr('data-sv');
    if (typeof reloadedCount[server_id] == "undefined") {
        reloadedCount[server_id] = 1;
    }
    if (reloadedCount[server_id] < time) {
        setTimeout(function() {
            reloadCurrentserver();
        }, 100);
        reloadedCount[server_id] ++;
    } else {
        $.post(ajaxUrl, {
            reloadError: 1,
            server: server_id,
            videoid: data_id
        });
        $('#video .video-player').html("<p style='background:#333; color: #fff; text-align:center; padding: 10px;'>Server này là lỗi và tự động tải lại trong " + time + " lần." + "<br />Vui lòng chọn một server #2 để xem. </p>");
    }
}
var cookie_notice = !1,
    error_thispage = false;

function server(server, id) {
    var server = parseInt(server);
    $('#video').html('<svg class="circular" viewBox="25 25 50 50"><circle class="path" cx="50" cy="50" r="15" fill="none" stroke-width="2" stroke-miterlimit="10" /></svg><iframe src="/embed/index.php?id=' + encodeURIComponent(id) + '&server=' + server + '" scrolling="no" frameborder="0" width="100%" height="100%" allowfullscreen="true" webkitallowfullscreen="true" mozallowfullscreen="true" style="position: absolute;top: 0;"></iframe>')
    if (server == 1) {
        $('#server1').addClass('default-srv');
    }
    return false;
}


var _xvideos = '.videos .dept-ct';
$(_xvideos).addClass("show-less");
$('.show-less').css({ 'height': '66px', 'overflow': 'hidden' });
$(_xvideos).after("<span class='btn-show'>đọc thêm &rarr;</span>");
$(".btn-show").click(function() { if ($(_xvideos).hasClass("show-less")) { $(_xvideos).css({ 'height': 'auto', 'overflow': 'none' });
        $(_xvideos).removeClass("show-less");
        $(_xvideos).addClass("show-more");
        $(this).html("&larr; rút gọn"); } else { $(_xvideos).css({ 'height': '66px', 'overflow': 'hidden' });
        $(_xvideos).addClass("show-less");
        $(_xvideos).removeClass("show-more");
        $(this).html("đọc thêm &rarr;"); } });
$('.btn-show').css({ 'cursor': 'pointer' });


function removeAllMessageBoxes() {
    document.querySelectorAll(".message-wrapper").forEach(e => {
        e.remove()
    })
}
window.addEventListener("click", e => {
    !e.target.closest(".message-wrapper") && !e.target.closest(".page-menu-item") && removeAllMessageBoxes()
});

function noLoginMessage(e, t, n) {
    if (n.querySelector(".message-wrapper")) {
        removeAllMessageBoxes();
        return
    }
    const a = n.getBoundingClientRect().right,
        r = window.innerWidth - n.getBoundingClientRect().left;
    removeAllMessageBoxes();
    const s = document.createElement("div");
    if (s.className = "message-wrapper", s.dataset.open = "true", r > 260) s.style.left = "0";
    else if (a > 260) s.style.right = "0";
    else {
        const e = window.innerWidth - n.getBoundingClientRect().left - 300;
        s.style.left = e + "px"
    }
    const o = document.createElement("div");
    o.className = "message-box", o.innerHTML = '<div class="message-title">' + e + "</div>", o.innerHTML += '<div class="message-body">' + t + "</div>";
    const i = document.createElement("div");
    i.className = "signin-btn", i.innerHTML = '<a href="/user/login" class="signin-button no-login-btn">Sign In</a>', i.innerHTML += '<a href="/user/signup" class="register-button no-login-btn">Sign Up</a>', s.appendChild(o), s.appendChild(i), n.appendChild(s)
}

function showNoLoginMessagePopup(e, t) {
    const r = document.createElement("div");
    r.className = "no-login-backdrop";
    const s = document.createElement("div");
    s.className = "no-login-popup-wrapper";
    const n = document.createElement("div");
    n.className = "no-login-dialog", n.dataset.open = "true";
    const o = document.createElement("div");
    o.className = "message-box", o.innerHTML = '<div class="message-title">' + e + "</div>", o.innerHTML += '<div class="message-body">' + t + "</div>";
    const i = document.createElement("div");
    i.className = "signin-btn", i.innerHTML = '<a href="/user/login" class="signin-button no-login-btn">Sign In</a>', i.innerHTML += '<a href="/user/signup" class="register-button no-login-btn">Sign Up</a>', n.appendChild(o), n.appendChild(i), s.appendChild(n);
    const a = document.createElement("div");
    a.className = "no-login-box", a.appendChild(r), a.appendChild(s), document.body.appendChild(a), s.addEventListener("click", e => {
        e.target.classList.contains("no-login-popup-wrapper") && hideNoLoginMessagePopup()
    })
}

function hideNoLoginMessagePopup() {
    document.querySelector(".no-login-box").remove()
}
const isLogin = false,
    mobile = false,
    addToListBtn = document.querySelector(".add-to-list"),
    likeBtn = document.querySelector(".like-btn"),
    dislikeBtn = document.querySelector(".dislike-btn"),
    shareMenuBtn = document.querySelector(".share-video"),
    reportMenuBtn = document.querySelector(".report-video");
 shareMenuBtn.addEventListener("click", e => {
    const t = e.currentTarget.closest(".share-video"),
        n = parseVideoInfo(t);
    showShareBox(n)
}), reportMenuBtn.addEventListener("click", e => {
    const t = e.target.closest(".report-video");
    showReportBox(t)
});

function checkVideoLiked() {
    if (isLogin) {
        const t = document.querySelector(".share-video").dataset.id;
        var e = new Headers;
        e.append("Content-Type", "application/json"), fetch(`/video/check/liked/${t}`, {
            method: "GET",
            headers: e
        }).then(e => e.json()).then(e => {
            if (e.check) {
                document.querySelector(".page-menu-item.like").classList.add("selected");
                const e = parseInt(likeBtn.querySelector(".menu-text").textContent);
                likeBtn.querySelector(".menu-text").textContent = e + 1
            }
        })
    }
}
checkVideoLiked();

function addToLikes(e) {
    return new Promise((t) => {
        var s = new Headers;
        s.append("Content-Type", "application/json"), fetch("/add/video/like", {
            method: "POST",
            headers: s,
            body: JSON.stringify(e)
        }).then(e => e.json()).then(e => t(e))
    })
}

function removeLike(e) {
    return new Promise((t) => {
        var s = new Headers;
        s.append("Content-Type", "application/json"), fetch(`/remove/video/like/${e}`, {
            method: "GET",
            headers: s
        }).then(e => e.json()).then(e => t(e))
    })
}
// likeBtn.addEventListener("click", e => {
//     const t = e.target.closest(".page-menu-item");
//     if (isLogin) {
//         const e = document.querySelector(".share-video").dataset.id;
//         if (document.querySelector(".page-menu-item.like").classList.contains("selected")) {
//             document.querySelector(".page-menu-item.like").classList.remove("selected");
//             const t = parseInt(likeBtn.querySelector(".menu-text").textContent);
//             likeBtn.querySelector(".menu-text").textContent = t - 1, removeLike(e).then(e => {})
//         } else {
//             document.querySelector(".page-menu-item.like").classList.add("selected");
//             const e = parseInt(likeBtn.querySelector(".menu-text").textContent);
//             likeBtn.querySelector(".menu-text").textContent = e + 1;
//             const t = parseVideoInfo(document.querySelector(".page-menu-item > .add-to-list"));
//             addToLikes(t).then(e => {})
//         }
//     } else mobile ? showNoLoginMessagePopup("please login", "if you want to like this video") : noLoginMessage("please login", "if you want to like this video", t)
// }), dislikeBtn.addEventListener("click", e => {
//     const t = e.target.closest(".page-menu-item");
//     isLogin || (mobile ? showNoLoginMessagePopup("please login", "if you want to dislike this video") : noLoginMessage("please login", "if you want to dislike this video", t))
// })