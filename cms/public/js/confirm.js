function simple_timer(sec, block, direction) {
    var time = sec;
    direction = direction || false;

    var hour = parseInt(time / 3600);
    if (hour < 1) hour = 0;
    time = parseInt(time - hour * 3600);
    if (hour < 10) hour = '0' + hour;

    var minutes = parseInt(time / 60);
    if (minutes < 1) minutes = 0;
    time = parseInt(time - minutes * 60);
    if (minutes < 10) minutes = '0' + minutes;

    var seconds = time;
    if (seconds < 10) seconds = '0' + seconds;

    block.innerHTML = hour + ':' + minutes + ':' + seconds;

    if (direction) {
        sec++;

        setTimeout(function () {
            simple_timer(sec, block, direction);
        }, 1000);
    } else {
        sec--;

        if (sec > 0) {
            setTimeout(function () {
                simple_timer(sec, block, direction);
            }, 1000);
        } else {
            window.location.reload();
        }
    }
}


$(document).ready(function () {

    if($('#timer').length>0){
        var block = document.getElementById('timer');
        var timer = $('#timer').attr('data-time');
        simple_timer(timer, block);
    }

    if($('.phone').length>0) {
        $(".phone").mask("(+994 dd) ddd-dd-dd");
    }

    String.prototype.shuffle = function () {
        var a = this.split(""),
            n = a.length;


        for (var i = n - 1; i > 0; i--) {
            var j = Math.floor(Math.random() * (i + 1));
            var tmp = a[i];
            a[i] = a[j];
            a[j] = tmp;
        }
        return a.join("");
    }


    var small = Array("a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z");
    var big = Array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");
    var dec = Array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9");
    var sym = Array("~", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "-", "_", "=", "+");
    var pass = "";
    var check_disp = 1;

    $(document).on('click', '#generate', function () {

        pass += small[Math.floor(Math.random() * small.length)];
        pass += small[Math.floor(Math.random() * small.length)];
        pass += small[Math.floor(Math.random() * small.length)];
        pass += big[Math.floor(Math.random() * big.length)];
        pass += big[Math.floor(Math.random() * big.length)];
        pass += big[Math.floor(Math.random() * big.length)];
        pass += dec[Math.floor(Math.random() * dec.length)];
        pass += dec[Math.floor(Math.random() * dec.length)];
        pass += dec[Math.floor(Math.random() * dec.length)];
        pass += sym[Math.floor(Math.random() * sym.length)];
        pass += sym[Math.floor(Math.random() * sym.length)];
        pass += sym[Math.floor(Math.random() * sym.length)];

        check_disp = 0;
        var new_pass = pass.shuffle();
        $("input[name=pass]").attr("type", "text");
        $("#disp").attr("value", "hide");
        $("input[name=pass]").attr("value", new_pass);
        $("input[name=pass_confirm]").attr("value", new_pass);

        pass = "";
    });

    $(document).on('click', '#disp', function () {
        if (check_disp == 1) {
            check_disp = 0;
            $("input[name=pass]").attr("type", "text");
            $(this).text("Gizlət");
        } else {
            check_disp = 1;
            $("input[name=pass]").attr("type", "password");
            $(this).text("Göstər");
        }
    });

    $(".phone_internal").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
            // Allow: Ctrl+A, Command+A
            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
            // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
            // let it happen, don't do anything
            return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });


});
