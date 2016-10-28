/**
 * This is application script file.
 *
 */

/**
 * jQuery ready function
 * Function: Products, Categories, Suppliers, Owners
 * Page: index.ctp
 *
 */
$(function() {
  // write something ...
});


/**
 * Function: Common
 * Page: -
 *
 */

/**
 * description: 数値を通貨形式に変換する
 * 
 * @params val : value
 * @return formatted value
 */
function figureEncode(val) {
    if (!val) return val;
    return "\\" + val.toString().replace( /(\d)(?=(\d\d\d)+(?!\d))/g, '$1,' );
}

/**
 * description: 通貨形式の値を数値に変換する
 * 
 * @params val : formatted value
 * @return non formatted value
 */
function figureDecode(val) {
    if (!val) return val;
    return val.toString().replace( /,|\\/g, '' );
}

/**
 * Function: Products, Categories, Suppliers, Owners
 * Page: index.ctp
 *
 */

/**
 * description: ポップアップ表示のフォームを表示する
 * 
 * @params items  : entry form's data object
 */
function showForm(items)
{
    if (toString.call(items) !== "[object Object]") {
        console.log("parameter on showEntryWindow function is invalid.");
        return false;
    }

    Object.keys(items).forEach(function(key) {
        $('#' + key).val(items[key]);

        var inst = $('[data-remodal-id=modal]').remodal();
        inst.open();
    });
}

/**
 * description: ポップアップ表示のフォーム内容をサーバーに送信する
 * 
 * @params form_id  : entry form's id tag string
 * @params route    : url route name (ex. /owners/ -> 'owners')
 * @params is_delete: true: delete action, false: add or edit action
 */
function saveForm(form_id, route, is_delete)
{
    saveFormByKey(form_id, route, is_delete, $('#id').val());
}

/**
 * description: ポップアップ表示のフォーム内容をサーバーに送信する
 * 
 * @params form_id  : entry form's id tag string
 * @params route    : url route name (ex. /owners/ -> 'owners')
 * @params is_delete: true: delete action, false: add or edit action
 * @params key_id   : value of id field
 */
function saveFormByKey(form_id, route, is_delete, key_id)
{
    var action = "/" + route + "/add";

    if (key_id && key_id != "") {
        action = "/" + route + "/edit/" + key_id;

        if (is_delete) {
            action = "/" + route + "/delete/" + key_id;
        }
    }

    $('#' + form_id).attr('action', action);
    $('#' + form_id).submit();
}

/**
 * description: ポップアップ表示のフォーム内容をサーバーに送信する
 * 
 * @params form_id  : entry form's id tag string
 * @params route    : url route name (ex. /owners/ -> 'owners')
 * @params action   : action name
 * @params key_id   : value of id field
 */
function saveFormWithAction(form_id, route, action, key_id)
{
    var action = "/" + route + "/" + action + "/" + key_id;

    $('#' + form_id).attr('action', action);
    $('#' + form_id).submit();
}


