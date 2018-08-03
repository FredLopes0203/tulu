/*
 * author : fang yongbao
 * data : 2014.12.24
 * model : 垂直多级导航
 * info ：知识在于积累，每天一小步，成功永远属于坚持的人。
 * blog : http://www.best-html5.net
 */

/*
 *
 * @param {type} option
 * {
 *   @param Speed: num,//动画收缩时间
 *   @param autostart: ture/false,//初次加载是否将菜单全部展开
 *   @param autohide: true/false,//同级菜单是否隐藏
 * }
 * return obj
 *   none
 *
 *
 */
(function($) {
    $.fn.vmenuModule = function(option) {
        var obj,
            item;
        var unitem;
        var options = $.extend({
                Speed: 220,
                autostart: true,
                autohide: 1
            },
            option);
        obj = $(this);

        item = obj.find("ul").parent("li").children("a");
        unitem = obj.find('a');

        item.attr("data-option", "off");

        item.unbind('click').on("click", function() {
            var a = $(this);
            if (options.autohide) {
                a.parent().parent().find("a[data-option='on']").parent("li").children("ul").slideUp(options.Speed / 1.2,
                    function() {
                        $(this).parent("li").children("a").attr("data-option", "off");
                    })
            }
            if (a.attr("data-option") == "off") {
                a.parent("li").children("ul").slideDown(options.Speed,
                    function() {
                        a.attr("data-option", "on");
                    });
            }
            if (a.attr("data-option") == "on") {
                a.attr("data-option", "off");
                a.parent("li").children("ul").slideUp(options.Speed)
            }
        });

        unitem.on("click", function(e) {
            e.preventDefault();
            var a = $(this);


            if (a.attr("data-option") == "unselected")
            {
                if(a.attr("data-parentcategory") > 0)
                {
                    a.attr("data-option", "selected");
                }
            }
            else if (a.attr("data-option") == "selected") {
                a.attr("data-option", "unselected");
            }
        });

        if (options.autostart) {
            obj.find("a").each(function() {
                if($(this).attr('data-option') != "selected")
                {
                    $(this).attr('data-option', 'unselected');
                }
                $(this).parent("li").parent("ul").slideDown(options.Speed,
                    function() {
                        $(this).parent("li").children("a").attr("data-option", "on");
                    })
            })
        }

    }
})(window.jQuery || window.Zepto);