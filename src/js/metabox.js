/*
 * Metabox JavaScript code.
 */

(function($) {
    $("document").ready(function() {
        $("#makigas-metabox-_video_id").change( function() {
            var text = $("#makigas-metabox-_video_id").val();
            var youtube_url = /(youtu\.be\/|youtube\.com\/(watch\?(.*&)?v=|(embed|v)\/))([^\?&"'>]+)/;
            if ( youtube_url.test( text ) ) {
                var matches = youtube_url.exec( text );
                $("#makigas-metabox-_video_id").val( matches[5] );
            }
        });
    });
})(jQuery);