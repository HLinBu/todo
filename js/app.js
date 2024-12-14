var used = [];
var place = $(".data")
const app = {
    title: null,
    left: null,
}

function canvans() {
    app.title = $(".title")[0].getBoundingClientRect();
    place.css({
        position: "absolute",
        cursor: "pointer",
        top: app.title.top,
        left: app.title.left,
        width: app.title.width,
        height: app.title.height,
    })
}


function creatwork(e) {
    let icon_name = ["remove", "time", "ok"];
    let num = ["0", "90", "180"];
    let order = ["普通件", "速件", "最速件"];
    let mode = ["未處理", "處理中", "已完成"];

    let html = `
    <div title="${mode[e.mode]} ${order[e.order]}" class="work" id="${e.id}">
        <div class="time${e.id}">
            ${e.start_time.padStart(2, "0")}-${e.end_time.padStart(2, "0")}
        </div>
        ${e.name}<b class="icon-${icon_name[e.mode]}"></b>
    </div>`;

    place.append(html);

    let dom = $(`#${e.id}`)
    row = 0;
    while (true) {
        isusing = false;
        for (let i = e.start_time; i <= e.end_time - 1; i++) {
            let key = i + "-" + (parseInt(i, 10) + 1)
            if (used[row] && used[row][key]) {
                isusing = true;
            }
        }
        if (isusing) {
            row++
        } else {
            for (let i = e.start_time; i <= e.end_time - 1; i++) {
                let key = i + "-" + (parseInt(i, 10) + 1)
                if (!used[row]) {
                    used[row] = [];
                }
                used[row][key] = "1";
            }
            margin = (row > 0) ? (row * 10) : 0;
            app.left = (150 * row) + margin;
            break;
        }
    }

    dom.css({
        "height": ((e.end_time - e.start_time) * 20),
        top: (e.start_time * 20),
        left: (60 + app.left),
        filter: "hue-rotate(" + num[e.order] + "deg)",
    })

    let grid = [0, 20];
    dom.draggable({
        grid: grid,
        axis: "y",
        containment: place,
        drag: function () {
            updateUI($(this), "text")
        },
        stop: function () {
            updateUI($(this), "data")
        }
    }).resizable({
        containment: place,
        grid: grid,
        handles: "n,s",
        resize: function () {
            updateUI($(this), "text")
        },
        stop: function () {
            updateUI($(this), "data")

        }
    })

    dom.click(function () {
        $(`#work${e.id}`).modal("show")
    })

    dom.hover(function () {
        dom.tooltip("show")
    })
}

function updateUI(e, mode) {
    now = e[0].getBoundingClientRect();
    start = (now.top - (app.title.top)) / 20
    end = Math.floor(now.height / 20) + start
    if (mode == "text") {
        var _start = start.toString().padStart(2, "0")
        var _end = end.toString().padStart(2, "0")
        $(`.time${e[0].id}`).text(`${_start}-${_end}`)
    } else {
        fetch(`api.php?id=${e[0].id}&start=${start}&end=${end}`)
            .then(location.reload())
    }
}

function creat() {
    canvans();
    fetch("api.php")
        .then(res => res.json())
        .then(res => {
            res.forEach(e => {
                creatwork(e)
            })
        })
}

$(function () {
    creat();
    place.click(function (event) {
        if ((event.target.className) == "data") {
            $("#ins").modal("show")
        }
    })

    let file = $(`[name="file"]`)

    $(`[name="in"]`).on('click', function () {
        file.click();
    });

    file.on('change', function () {
        if (this.files.length > 0) {
            $(`form[action="file_in.php"]`).submit();
        }
        $(this).val('');
    })
})