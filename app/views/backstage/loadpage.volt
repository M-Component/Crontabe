<script>
window.onload = function(){
    $('<a href="'+location.href+'"></a>').appendTo($(document.body)).trigger('click').remove();
};
</script>
