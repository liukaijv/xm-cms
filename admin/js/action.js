//后台js操作
url_php = location.href.lastIndexOf("?") == -1 ? location.href.substring((location.href.lastIndexOf("/")) + 1) : location.href.substring((location.href.lastIndexOf("/")) + 1, location.href.lastIndexOf("?"));
url = location.href.lastIndexOf("?") == -1 ? location.href.substring((location.href.lastIndexOf("/")) + 1) : location.href.substring((location.href.lastIndexOf("/")) + 1/*, location.href.lastIndexOf("?")*/);
url += "?ajax=1";
//验证浮点数
function is_float(num) {
    var reg = /^[1-9]{1}[0-9]{0,}[\.]?[0-9]{1,}$/;
    if (reg.test(num)) {
        return true;
    } else {
        return false;
    }
}
//验证手机号
function is_mobile(num) {
    var reg = /^(((13[0-9]{1})|(15[0-9]{1})|(18[0-9]{1}))+[0-9]{8})$/;
    if (reg.test(num)) {
        return true;
    } else {
        return false;
    }
}
function is_url(text) {
    var reg = /^([a-z]+:\/\/)?([a-z]([a-z0-9\-]*\.)+([a-z]{2}|aero|arpa|biz|com|coop|edu|gov|info|int|jobs|mil|museum|name|nato|net|org|pro|travel)|(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}[0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])(:[0-9]{1,5})?(\/[a-z0-9_\-\.~]+)*(\/([a-z0-9_\-\.]*)(\?[a-z0-9+_\-\.%=&amp;]*)?)?(#[a-z][a-z0-9_]*)?$/;
    if (reg.test(text)) {
        return true;
    } else {
        return false;
    }
}
function is_username(name) {
    var reg = /^[a-zA-Z]{1}[0-9a-zA-Z]{4,19}$/;
    if (reg.test(name)) {
        return true;
    } else {
        return false;
    }
}
//以下验证
function trim(text) {
    if (typeof(text) == "string") {
        return text.replace(/^\s*|\s*$/g, "");
    } else {
        return text;
    }
}
function is_empty(val) {
    switch (typeof(val)) {
        case 'string':
            return trim(val).length == 0 ? true : false;
            break;
        case 'number':
            return val == 0;
            break;
        case 'object':
            return val == null;
            break;
        case 'array':
            return val.length == 0;
            break;
        default:
            return true;
    }
}
function is_number(val) {
    var reg = /^[\d|\.|,]+$/;
    return reg.test(val);
}
function is_int(val) {
    if (val == "") {
        return false;
    }
    var reg = /\D+/;
    return !reg.test(val);
}
function is_email(email) {
    var reg = /([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)/;
    return reg.test(email);
}
function is_tel(tel) {
    var reg = /^[\d|\-|\s|\_]+$/; //只允许使用数字-空格等
    return reg.test(tel);
}
function is_time(val) {
    var reg = /^\d{4}-\d{2}-\d{2}\s\d{2}:\d{2}$/;
    return reg.test(val);
}
function fixEvent(e) {
    var evt = (typeof e == "undefined") ? window.event : e;
    return evt;
}
function srcElement(e) {
    if (typeof e == "undefined") e = window.event;
    var src = document.all ? e.srcElement : e.target;
    return src;
}
$(function () {
    $("#check_all").click(function () {
        if ($(this).is(":checked")) {
            $("input[name='cd_id[]']").each(function () {
                $(this).attr("checked", true);
            })
        } else {
            $("input[name='cd_id[]']").each(function () {
                $(this).attr("checked", false);
            })
        }
    })
    $("#select_all").click(function () {
        $("input[name='cd_id[]']").each(function () {
            $(this).attr("checked", true);
        })
    })
    $("#select_none").click(function () {
        $("input[name='cd_id[]']").each(function () {
            $(this).attr("checked", false);
        })
    })
    $("#uncheck").click(function () {
        $("input[name='cd_id[]']").each(function () {
            if ($(this).attr("checked")) {
                $(this).attr("checked", false);
            } else {
                $(this).attr("checked", true);
            }
        })
    })

    $("#todo").click(function () {
        var act = $("#action").val();
        if (act != "") {
            operation(act);
        } else {
            alert("请选择操作！");
            return false;
        }
    })
    function operation(act) {
        var ids = '';
        $("[name='cd_id\[\]']:checked").each(function () {
            ids += $(this).val() + ',';
        });
        if (ids != '') {
            if (confirm('真的要执行吗？')) {
                $.ajax({
                    type: "GET",
                    data: "ids=" + ids + "&act=" + act + "&r=" + Math.random(),
                    url: url,
                    dataType: "html",
                    success: function (msg) {
                        if (msg) {
                            alert("操作成功！");
                            window.location.reload();
                        } else {
                            alert("操作失败！");
                        }
                    }
                })
            } else {
                return false;
            }
        } else {
            alert('你还没有选择项目!');
        }
    }
});

function operation_one(id_act) {
    var id_act = id_act.split("-");
    var ids = id_act[0] + ',';
    var act = id_act[1];
    notice = "可能是该分类下还有信息，不能删除，请先删除分类下信息！";

    if (confirm("真的要删除吗？")) {
        $.ajax({
            type: "GET",
            data: "ids=" + ids + "&act=" + act + "&r=" + Math.random(),
            url: url,
            dataType: "html",
            success: function (msg) {
                if (msg) {
                    alert("删除成功！");
                    window.location.reload();
                } else {
                    alert("操作失败！" + notice);
                }
            }
        });
    } else {
        return false;
    }
}
function change(id) {
    if (id) {
        var ids = id.split("-");
        $.ajax({
            type: "GET",
            data: "id=" + ids[0] + "&act=" + ids[1] + "&rd=" + Math.random(),
            url: url,
            datatype: "html",
            success: function (msg) {
                if (msg == 1) {
                    if ($("#" + id).hasClass('icon-ok')) {
                        $("#" + id).removeClass().addClass('icon-remove');
                    } else {
                        $("#" + id).removeClass().addClass('icon-ok');
                    }
                } else {
                    alert('更新失败！');
                }
            }
        })
    }
}
function edit(obj, field, id, type) {
    //此为防止二次点击，在此执行edit方法的bug
    var tag = obj.firstChild.tagName;
    if (typeof(tag) != "undefined" && tag.toLowerCase() == "input") {
        return;
    }
    /* 保存原始的内容 */
    var org = obj.innerHTML;
    /* 创建一个输入框 */
    var txt = document.createElement("INPUT");
    txt.value = (org.toUpperCase() == 'N/A') ? '' : org;
    txt.style.width = (obj.offsetWidth + 12) + "px";
    //txt.style.backgroundColor = '#00f';
    /* 隐藏对象中的内容，并将输入框加入到对象中 */
    obj.innerHTML = "";
    obj.appendChild(txt);
    txt.focus();
    txt.onkeypress = function (e) {
        var evt = fixEvent(e);
        var obj = srcElement(e);
        if (evt.keyCode == 13) {
            obj.blur();
            return false;
        }
        if (evt.keyCode == 27) {
            obj.parentNode.innerHTML = org;
        }
    }
    txt.onblur = function (e) {
        /*检查数据类型*/
        if (type == 'int' && !is_int(trim(txt.value))) {
            alert("只能是整数!");
            obj.innerHTML = org;
            return false;
        }
        /*检查邮箱*/
        if (type == 'email' && !is_email(trim(txt.value))) {
            alert("邮箱格式不正确!");
            obj.innerHTML = org;
            return false;
        }
        if (type == 'url' && !is_url(trim(txt.value))) {
            alert("网址格式不正确!");
            obj.innerHTML = org;
            return false;
        }
        /*检查联系电话*/
        if (type == 'tel' && !is_tel(trim(txt.value))) {
            alert("联系电话不正确!");
            obj.innerHTML = org;
            return false;
        }
        /*检查手机*/
        if (type == 'mobile' && !is_mobile(trim(txt.value))) {
            alert("不是正确的手机号码!");
            obj.innerHTML = org;
            return false;
        }
        //不为空和不与原值相同
        if (trim(txt.value).length > 0 && trim(txt.value) != org) {
            var val = trim(txt.value);
            $.ajax({
                type: "GET",
                data: "id=" + id + "&act=ajax_edit&field=" + field + "&val=" + encodeURI(val) + "&rd=" + Math.random(),
                url: url,
                datatype: "html",
                success: function (msg) {
                    if (msg == 1) {
                        //新值
                        obj.innerHTML = val;
                    } else {
                        alert('编辑失败！');
                        //原值
                        obj.innerHTML = org;
                    }
                }
            });
        } else {
            obj.innerHTML = org;
        }
    }
}
