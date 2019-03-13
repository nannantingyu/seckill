function fixLocaltime() {
    const dtd = $.Deferred();
    $.get('/timediff?localtime=' + (new Date()).getTime(), function(result) {
        cookie_tool.setCookie('timediff', result.timediff);
        dtd.resolve(result.timediff);
    });

    return dtd;
}

function count_dead_time(begin_time, end_time) {
    const begin = (new Date(begin_time.replace("-", "/"))).getTime(),
        end = (new Date(end_time.replace("-", "/"))).getTime(),
        now = (new Date()).getTime() + (parseInt(cookie_tool.getCookie('timediff')) || 0);

    let time = 0;
    if (now > begin && now < end) {
        time = {"time": now - end, "timeStr": seconds_format((end - now) / 1000)};
    }
    else if(now < begin) {
        time = {"time": begin - now, "timeStr": seconds_format((begin - now) / 1000)};
    }

    return time;
}

function seconds_format(second) {
    if(second <= 60) {
        return Math.floor(second) + "秒";
    }
    else if (second <= 3600) {
        const minutes = Math.floor(second / 60);
        return minutes + "分" + seconds_format(second - minutes * 60);
    }
    else if(second <= 24 * 3600) {
        const hours = Math.floor(second / 3600);
        return hours + "时" + seconds_format(second - hours * 3600);
    }
}