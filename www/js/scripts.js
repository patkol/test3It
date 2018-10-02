$(document).ready(function () {
    console.log("Scripts has been loaded");
    $('#data tr').click(function () {
        $(this).toggleClass('highlighted');
    });
});
