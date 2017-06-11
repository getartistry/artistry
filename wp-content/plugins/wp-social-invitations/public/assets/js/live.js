jQuery(function($){
    $('.fields-wrapper, .friends-wrapper').hide();
});
jQuery(document).ready(function($) {
    $('#fileupload').fileupload({
        url: wsi_url + '/uploads/',
        dataType: 'json',
        start: function(){
            $('#progress .bar').fadeIn();
            $('.errors').hide();
        },
        done: function (e, data) {
            $.each(data.result.files, function (index, file) {
                if ( file.error)
                {
                    $('.errors').html(file.error).fadeIn();
                }
                else
                {
                    var html_inputs = '';
                    var counter = 0;
                    var classstr= '';
                    persons = file.data;
                    for( i in persons)
                    {
                        counter++;
                        classstr= '';
                        if( counter == 3)
                        {
                            classstr = 'last';
                            counter = 0;
                        }
                        if(persons[i]['E-mail Address']) {
                            html_inputs += '<tr><td class="checkbox-container"><input type="checkbox" value="' + persons[i]['E-mail Address'] + '" name="friend[]" checked="true"/></td><td class="user-img"></td><td class="last-child"> ' + persons[i]['First Name'] + ' ' + persons[i]['Last Name'] + '<em>' + persons[i]['E-mail Address'] + '</em></td></tr>';
                        }
                    }

                    $('.friends_container tbody').html(html_inputs);
                    $('#upload_container').hide();
                    $('.fields-wrapper, .friends-wrapper').fadeIn(function(){
                        $("#searchinput").fastLiveFilter('#FriendsList tbody', {
                            timeout: 10,
                            callback: function(total) { $('#FriendsList .lazy:visible').trigger('scroll') }
                        });
                    });
                }
                $('#progress .bar').fadeOut();
            });
        },
        dropZone: $('#dropzone'),
        progressall: function (e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('#progress .bar').css(
                'width',
                progress + '%'
            );
        }
    });
    $(document).bind('dragover', function (e) {
        var dropZone = $('#dropzone'),
            timeout = window.dropZoneTimeout;
        if (!timeout) {
            dropZone.addClass('in');
        } else {
            clearTimeout(timeout);
        }
        var found = false,
            node = e.target;
        do {
            if (node === dropZone[0]) {
                found = true;
                break;
            }
            node = node.parentNode;
        } while (node != null);
        if (found) {
            dropZone.addClass('hover');
        } else {
            dropZone.removeClass('hover');
        }
        window.dropZoneTimeout = setTimeout(function () {
            window.dropZoneTimeout = null;
            dropZone.removeClass('in hover');
        }, 100);
    });
});