var ls = ls ||
    {};

ls.attachtopics = ls.attachtopics ||
{};

ls.attachtopics = (function ($) {
    this.targetType = '';
    this.targetId = 0;
    this.wasSearch = false;

    this.options =
    {}

    this.getStickyTopicsList = function (titlePart) {
        aRes = new Array();
        $('.st_stickytopic').each(
            function (index, el) {
                aRes.push(el.id.substr(12));
            }
        );
        return aRes;
    }

    this.findTopics = function (titlePart, force) {
        if (!force && !this.wasSearch)
            return;

        console.log(titlePart);

        aList = this.getStickyTopicsList();
        ls.ajax(aRouter.ajax + 'attachtopics/find',
            {
                'excludeTopics': aList,
                'titlePart': titlePart
            }, function (response) {
                console.log(response);
                if (!response.bStateError) {
                    $('#stickytopics_find_list').html(response.topicData);
                }
                else {
                    ls.msg.error(response.sMsgTitle, response.sMsg);
                }
            });

        this.wasSearch = true;
    }

    this.addTopic = function (topicId) {
        var add_element = document.getElementById('stickytopic_' + topicId);
        var new_element = add_element.cloneNode(true);
        var children_length = document.getElementById('stickytopics_list_body').children.length;
        var list = document.getElementById('stickytopics_list_body');
        list.appendChild(new_element);

        var td = new_element.getElementsByTagName('td')[0];
        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'attach_topic[]';
        input.value = topicId;
        td.appendChild(input);
        var a_add = td.getElementsByClassName('st_add')[0];
        a_add.style.visibility = 'hidden';
        if (children_length != 0) {
            var a_up = td.getElementsByClassName('st_up')[0];
            a_up.removeAttribute('style');
        }
        if (children_length > 0) {
            var last = list.childNodes[children_length];
            var last_td = last.getElementsByTagName('td')[0];
            var last_a_down = last_td.getElementsByClassName('st_down')[0];
            last_a_down.removeAttribute('style');
        }
        var a_delete = td.getElementsByClassName('st_delete')[0];
        a_delete.removeAttribute('style');
        add_element.parentNode.removeChild(add_element);
    }

    this.moveTopic = function (topicId, dir) {
        var move_element = document.getElementById('stickytopic_' + topicId);
        for (var i = 0; i < move_element.parentNode.children.length; i++) {
            if (move_element.parentNode.children[i] == move_element)
                var id = i;
        }
        var new_element = move_element.cloneNode(true);

        if (dir == 1) {
            var after = move_element.parentNode.children[id + 1];
            insertAfter(after, new_element);

            af_up = after.getElementsByTagName('td')[0].getElementsByClassName('st_up')[0].style.visibility;
            af_down = after.getElementsByTagName('td')[0].getElementsByClassName('st_down')[0].style.visibility;

            new_up = new_element.getElementsByTagName('td')[0].getElementsByClassName('st_up')[0].style.visibility;
            new_down = new_element.getElementsByTagName('td')[0].getElementsByClassName('st_down')[0].style.visibility;

            if (af_up == 'hidden')
                new_element.getElementsByTagName('td')[0].getElementsByClassName('st_up')[0].style.visibility = 'hidden';
            else
                new_element.getElementsByTagName('td')[0].getElementsByClassName('st_up')[0].removeAttribute('style');

            if (af_down == 'hidden')
                new_element.getElementsByTagName('td')[0].getElementsByClassName('st_down')[0].style.visibility = 'hidden';
            else
                new_element.getElementsByTagName('td')[0].getElementsByClassName('st_down')[0].removeAttribute('style');

            if (new_up == 'hidden')
                after.getElementsByTagName('td')[0].getElementsByClassName('st_up')[0].style.visibility = 'hidden';
            else
                after.getElementsByTagName('td')[0].getElementsByClassName('st_up')[0].removeAttribute('style');

            if (new_down == 'hidden')
                after.getElementsByTagName('td')[0].getElementsByClassName('st_down')[0].style.visibility = 'hidden';
            else
                after.getElementsByTagName('td')[0].getElementsByClassName('st_down')[0].removeAttribute('style');

            move_element.parentNode.removeChild(move_element);
            console.log(after);
        } else if (dir == -1) {
            var before = move_element.parentNode.children[id - 1];

            before.parentNode.insertBefore(new_element, before)

            af_up = before.getElementsByTagName('td')[0].getElementsByClassName('st_up')[0].style.visibility;
            af_down = before.getElementsByTagName('td')[0].getElementsByClassName('st_down')[0].style.visibility;

            new_up = new_element.getElementsByTagName('td')[0].getElementsByClassName('st_up')[0].style.visibility;
            new_down = new_element.getElementsByTagName('td')[0].getElementsByClassName('st_down')[0].style.visibility;

            if (af_up == 'hidden')
                new_element.getElementsByTagName('td')[0].getElementsByClassName('st_up')[0].style.visibility = 'hidden';
            else
                new_element.getElementsByTagName('td')[0].getElementsByClassName('st_up')[0].removeAttribute('style');

            if (af_down == 'hidden')
                new_element.getElementsByTagName('td')[0].getElementsByClassName('st_down')[0].style.visibility = 'hidden';
            else
                new_element.getElementsByTagName('td')[0].getElementsByClassName('st_down')[0].removeAttribute('style');

            if (new_up == 'hidden')
                before.getElementsByTagName('td')[0].getElementsByClassName('st_up')[0].style.visibility = 'hidden';
            else
                before.getElementsByTagName('td')[0].getElementsByClassName('st_up')[0].removeAttribute('style');

            if (new_down == 'hidden')
                before.getElementsByTagName('td')[0].getElementsByClassName('st_down')[0].style.visibility = 'hidden';
            else
                before.getElementsByTagName('td')[0].getElementsByClassName('st_down')[0].removeAttribute('style');

            move_element.parentNode.removeChild(move_element);
        }

    }

    function insertAfter(referenceNode, newNode) {
        referenceNode.parentNode.insertBefore(newNode, referenceNode.nextSibling);
    }

    this.deleteTopic = function (topicId) {
        var delete_element = document.getElementById('stickytopic_' + topicId);
        var parent = delete_element.parentNode;
        delete_element.parentNode.removeChild(delete_element);
        if (parent.children.length == 1) {
            parent.firstElementChild.getElementsByTagName('td')[0].getElementsByClassName('st_down')[0].style.visibility = 'hidden';
            parent.firstElementChild.getElementsByTagName('td')[0].getElementsByClassName('st_up')[0].style.visibility = 'hidden';
        } else if (parent.children.length == 0) {

        } else {
            parent.firstElementChild.getElementsByTagName('td')[0].getElementsByClassName('st_down')[0].removeAttribute('style');
            parent.firstElementChild.getElementsByTagName('td')[0].getElementsByClassName('st_up')[0].style.visibility = 'hidden';
            parent.lastElementChild.getElementsByTagName('td')[0].getElementsByClassName('st_up')[0].removeAttribute('style');
            parent.lastElementChild.getElementsByTagName('td')[0].getElementsByClassName('st_down')[0].style.visibility = 'hidden';
        }
    }

    this.init = function () {
    }

    return this;
}).call(ls.attachtopics ||
    {}, jQuery);

jQuery(document).ready(function () {
    ls.attachtopics.init();
});