/*侧拉导航*/
$(function () {
    //点击出现
    $('.cl_nav').click(function () {
        $('.bk_gray').fadeIn();
        $('.allnav_left').stop(true, false).animate({ 'right': 0 }, 300);
        return false;
    });
    //点击消失
    $('.theclose').click(function () {
        $('.bk_gray').fadeOut();
        $('.allnav_left').stop(true, false).animate({ 'right': -960 }, 300)
        return false;
    });
    $('.bk_gray').click(function () {
        $('.bk_gray').fadeOut();
        $('.allnav_left').stop(true, false).animate({ 'right': -960 }, 300)
        return false;
    });
    $('html,body').click(function () {
        $('.bk_gray').fadeOut();
        $('.allnav_left').stop(true, false).animate({ 'right': -960 }, 300);
        $('.wx_show').fadeOut();
    });
    //出现时不给滑动
    $('.bk_gray').on('touchmove', function (e) {
        e.stopPropagation();
        e.preventDefault()
    });
    $('.allnav_left').on('touchmove', function (e) {
        e.stopPropagation();
        e.preventDefault()
    });
});
/*温馨公告消失*/
$(function () {
    //点击消失
    $('.wx_show-close').click(function () {
        $('.bk_gray').fadeOut();
        $('.wx_show').fadeOut();
    });
});
