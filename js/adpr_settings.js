(function( $ ) {

    // Add Color Picker to all inputs that have 'color-field' class
    $(function() {
        $('.adrp-color-field').wpColorPicker();
    });

})( jQuery );

window.onload = function(){
    var editor = ace.edit("adpr_editor");
    var textarea = jQuery('#adpr_css_field').hide();
    var beautified = cssbeautify(textarea.val());
    editor.getSession().setValue(beautified);
    editor.setTheme("ace/theme/monokai");
    editor.getSession().setMode("ace/mode/css");

    editor.getSession().on('change', function(e){
       textarea.val(editor.getValue());
    });

};
