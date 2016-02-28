$(function () {
    $('input, textarea').placeholder();
    $('.tip').tooltip();
    $('.pop').popover();
    $('.block_toggle').click(function () {
        $(this).closest('.block_hd').next('.block_bd').slideToggle().next('.block_ft').slideToggle();
        return false;
    });
    $('form[name="search_form"]').submit(function () {
        if ($(this).find('input[name="keyword"]').val() == '') {
            alert('请输入关键词');
            $(this).find('input[name="keyword"]').focus();
            return false;
        }
    })
});
