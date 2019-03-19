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

export default {
    hasClass: function (elem, cls) {
        cls = cls || '';
        if (cls.replace(/\s/g, '').length == 0) return false;
        return new RegExp(' ' + cls + ' ').test(' ' + elem.className + ' ');
    },
    removeClass: function (elem, cls) {
        if (hasClass(elem, cls)) {
            var newClass = ' ' + elem.className.replace(/[\t\r\n]/g, '') + ' ';
            while (newClass.indexOf(' ' + cls + ' ') >= 0) {
                newClass = newClass.replace(' ' + cls + ' ', ' ');
            }
            elem.className = newClass.replace(/^\s+|\s+$/g, '');
        }
    },
    addClass: function (elem, cls) {
        if (!hasClass(elem, cls)) {
            elem.className = elem.className == '' ? cls : elem.className + ' ' + cls;
        }
    },
    setCookie, getCookie, delCookie, deepClone,
    updateListByIndex: function(list, index, updates) {
        if (index >= 0 && index < list.length) {
            Object.assign(list[index], updates);
            return true;
        }

        return false;
    },
    updateListByKey: function(list, key, key_val, updates) {
        let index = list.findIndex(x=>x[key] === key_val);
        if(index >= 0 && index < list.length) {
            Object.assign(list[index], updates);
            return true;
        }

        return false;
    },
    removeListByKey: function(list, key, key_val) {
        let index = list.findIndex(x=>x[key] === key_val);
        list.splice(index, 1);
    },
    transfer_img_src: function(img_src) {
        if (Array.isArray(img_src)) {
            img_src = img_src.map(x=>{
                return window.img_url + (x[0] === '/' ? '': '/') + x;
            })
        }
        else if (img_src) img_src = window.img_url + (img_src[0] === '/' ? '': '/') + img_src;

        return img_src;
    },
    handle_laravel_errors: function(errors) {
        if (errors) {
            let all_errors = [];
            for(let attr in errors) {
                let error_message = errors[attr];
                all_errors.push(attr + ": " + error_message);
            }

            return all_errors.join("<br><br>");
        }
    },
    sorted_table_data: function(data, prop, order) {
        if (data.length === 0 || !data[0].hasOwnProperty(prop)) {
            return data;
        }

        return data.sort((x, y)=>{
            console.log(x, y);
            if ((x[prop] > y[prop] && order === 'ascending') || (x[prop] < y[prop] && order !== 'ascending')) {
                return 1;
            }
            else if((x[prop] < y[prop] && order === 'ascending') || (x[prop] > y[prop] && order !== 'ascending')) {
                return -1;
            }

            return 0;
        })
    },
    search_table_data: function(data, search_key) {
        let result = [];
        data.forEach(x=>{
            if (Object.values(x).join("***").indexOf(search_key) >= 0) {
                result.push(x);
            }
        });

        return result;
    }
}