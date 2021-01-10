$('#cmsInput').on('change', function (e) {
    var $input = $(this);
    var file = e.currentTarget.files[0];
    var opts = {
        lines: 13,
        length: 58,
        width: 14,
        radius: 22,
        scale: 1,
        corners: 1,
        color: '#007aff',
        opacity: 0.25,
        rotate: 0,
        direction: 1,
        speed: 2,
        trail: 60,
        fps: 20,
        zIndex: 2e9,
        className: 'spinner',
        top: '50%',
        left: '50%',
        shadow: true,
        hwaccel: false,
        position: 'absolute',
    },
        target = document.body,
        spinner = new Spinner(opts);
    var data = new FormData();
    data.append('csv_data', file);
    console.log(data);
    spinner.spin(target);
    $("input").prop('disabled', true);

    $.ajax({
        url: "channel/importChannels",
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
        data: data,
        type: 'POST',
        headers: {
            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (php_script_response) {
            $("input").prop('disabled', false);
            window.location.href = "/channel";
        },
        complete: function () {
            spinner.stop();
        },
        error: function () {
            $("input").prop('disabled', false);

        }
    });

});