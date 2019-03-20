import 'bootstrap';
function getValFromObjectOrDefault(obj, key, defaultVal) {
    if(obj && obj.hasOwnProperty(key)) {
        return obj[key];
    }

    return defaultVal;
}

function addError(error, attr, errMsg) {
    if (!error || ! error.hasOwnProperty(attr)) {
        error[attr] = [];
    }

    error[attr].push(errMsg);
}

function errorToString(error) {
    let message = [];
    for (let err of Object.keys(error)) {
        message = message.concat(error[err]);
    }

    return message.join("\n");
}

function validate(rules, values, messages=null, toString=false) {
    let error = {};
    for (let attr of Object.keys(rules)) {
        let rls = rules[attr].split('|');
        for(let rule of rls) {
            let [ruleKey, ruleVal] = rule.split(':'), value = values[attr];
            switch (ruleKey) {
                case 'required':
                    if (!value) {
                        addError(error, attr, getValFromObjectOrDefault(messages, attr, attr + '不能为空!'));
                    }
                    break;
                case 'min':
                    if (value && value.length < parseInt(ruleVal)) {
                        addError(error, attr, getValFromObjectOrDefault(messages, attr, attr + '长度应大于' + ruleVal + '!'));
                    }
                    break;
                case 'max':
                    if (value && value.length > parseInt(ruleVal)) {
                        addError(error, attr, getValFromObjectOrDefault(messages, attr, attr + '长度应小于' + ruleVal + '!'));
                    }
                    break;
                case 'between':
                    let [minL, maxL] = ruleVal.split(',');
                    if (value && value.length > parseInt(maxL) || value.length < parseInt(minL)) {
                        addError(error, attr, getValFromObjectOrDefault(messages, attr, attr + '长度应介于' + minL + '和' + maxL + '之间!'));
                    }
                    break;
            }
        }
    }

    return toString ? errorToString(error) : error;
}

function deepClone(obj) {
    let result = Array.isArray(obj) ? [] : {};
    for (let key in obj) {
        if (typeof obj[key] === 'object') {
            result[key] = deepClone(obj[key]);
        }
        else {
            result[key] = obj[key];
        }
    }

    return result;
}

function setCookie(c_name,value,expire) {
    const date = new Date();
    date.setSeconds(date.getSeconds() + expire);
    document.cookie = c_name + "="+escape(value) + "; expires=" + date.toGMTString()
}

function getCookie(c_name){
    if (document.cookie.length > 0){
        let c_start = document.cookie.indexOf(c_name + "=");
        if (c_start !== -1){
            c_start=c_start + c_name.length + 1;
            let c_end = document.cookie.indexOf(";", c_start);
            if (c_end === -1) c_end = document.cookie.length;
            return unescape(document.cookie.substring(c_start, c_end))
        }
    }

    return ""
}
function delCookie(c_name){
    setCookie(c_name, "", -1)
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
const tool = {
    fixLocaltime: function() {
        const dtd = $.Deferred();
        $.get('/timeDiff?localtime=' + (new Date()).getTime(), function(result) {
            setCookie('timediff', result.timediff);
            dtd.resolve(result.timediff);
        });

        return dtd;
    },
    count_dead_time: function(begin_time, end_time) {
        const begin = (new Date(begin_time.replace("-", "/"))).getTime(),
            end = (new Date(end_time.replace("-", "/"))).getTime(),
            now = (new Date()).getTime() + (parseInt(getCookie('timediff')) || 0);

        let time = 0;
        if (now > begin && now < end) {
            time = {"time": now - end, "timeStr": seconds_format((end - now) / 1000)};
        }
        else if(now < begin) {
            time = {"time": begin - now, "timeStr": seconds_format((begin - now) / 1000)};
        }

        return time;
    },
    validate, setCookie, getCookie, delCookie, seconds_format
};

window.tool = tool;
$.ajaxSetup({
    headers: {  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')    }
});