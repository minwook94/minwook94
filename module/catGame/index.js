class Ga {
    constructor(id) {

        this._user_id = id;
        this._game_step = 0;

        this._body = document.querySelector("#content-item");
        this._mainBox = document.createElement("div");
        this._mainBox.className = "ga-mainbox";
        this._footerBox = document.createElement('div');
        this._footerBox.className = "ga-footerbox";
        this._catBox = document.createElement("div");
        this._catBox.className = "cat-box";
        this._lbox = document.createElement("div");
        this._lbox.className = "lcat-box subcatbox";
        this._rbox = document.createElement("div");
        this._rbox.className = "rcat-box subcatbox";
        this._btnLocation = document.createElement('div');
        this._timer = null;
        this._cat = { 'lbox': { lcat: document.createElement("div"), rcat: document.createElement("div") }, 'rbox': { lcat: document.createElement("div"), rcat: document.createElement("div") } };
        this.exStep = [
            { 'box': 'r', 'l': 'wsn', 'r': 'yay', 'answer': 'y' },
            { 'box': 'r', 'r': 'wan', 'l': 'ysy', 'answer': 'y' },
            { 'box': 'l', 'r': 'wsy', 'l': 'ysn', 'answer': 's' },
            { 'box': 'r', 'l': 'wsn', 'r': 'yan', 'answer': 'n' },
            { 'box': 'l', 'r': 'way', 'l': 'ysn', 'answer': 'a' },
            { 'box': 'l', 'l': 'way', 'r': 'ysy', 'answer': 'a' },
            { 'box': 'l', 'r': 'wan', 'l': 'yay', 'answer': 'a' },
            { 'box': 'r', 'l': 'wsy', 'r': 'ysn', 'answer': 'n' },
            { 'box': 'r', 'l': 'way', 'r': 'ysy', 'answer': 'y' },
            { 'box': 'l', 'r': 'way', 'l': 'ysy', 'answer': 'a' }
        ];
        this.realStep = [
            { 'box': 'l', 'l': 'wsy', 'r': 'ysy', 'answer': 's' },
            { 'box': 'l', 'r': 'wsy', 'l': 'ysy', 'answer': 's' },
            { 'box': 'l', 'l': 'wan', 'r': 'ysy', 'answer': 'a' },
            { 'box': 'r', 'r': 'wsy', 'l': 'yan', 'answer': 'n' },
            { 'box': 'r', 'r': 'wan', 'l': 'ysn', 'answer': 'n' },
            { 'box': 'r', 'l': 'way', 'r': 'ysy', 'answer': 'y' },
            { 'box': 'l', 'l': 'wsn', 'r': 'yay', 'answer': 's' },
            { 'box': 'r', 'r': 'wsy', 'l': 'ysy', 'answer': 'y' },
            { 'box': 'r', 'r': 'way', 'l': 'yan', 'answer': 'n' },
            { 'box': 'l', 'l': 'wsn', 'r': 'ysy', 'answer': 's' },
            { 'box': 'r', 'l': 'wan', 'r': 'ysn', 'answer': 'n' },
            { 'box': 'l', 'r': 'wsy', 'l': 'ysn', 'answer': 's' },
            { 'box': 'l', 'l': 'wan', 'r': 'ysn', 'answer': 'a' },
            { 'box': 'l', 'l': 'wsy', 'r': 'yan', 'answer': 's' },
            { 'box': 'r', 'r': 'way', 'l': 'yay', 'answer': 'y' },
            { 'box': 'r', 'r': 'wsn', 'l': 'ysn', 'answer': 'n' },
            { 'box': 'r', 'l': 'wsy', 'r': 'ysn', 'answer': 'n' },
            { 'box': 'l', 'r': 'wan', 'l': 'yay', 'answer': 'a' },
            { 'box': 'r', 'l': 'way', 'r': 'yan', 'answer': 'n' },
            { 'box': 'r', 'l': 'wsn', 'r': 'yay', 'answer': 'y' },
            { 'box': 'l', 'r': 'way', 'l': 'ysy', 'answer': 'a' },
            { 'box': 'l', 'r': 'wsn', 'l': 'ysn', 'answer': 's' },
            { 'box': 'r', 'l': 'wan', 'r': 'yay', 'answer': 'y' },
            { 'box': 'l', 'r': 'wan', 'l': 'ysn', 'answer': 'a' },
            { 'box': 'l', 'r': 'wsy', 'l': 'yan', 'answer': 's' },
            { 'box': 'l', 'l': 'wsn', 'r': 'ysn', 'answer': 's' },
            { 'box': 'r', 'r': 'wsn', 'l': 'ysy', 'answer': 'y' },
            { 'box': 'r', 'r': 'way', 'l': 'ysn', 'answer': 'n' },
            { 'box': 'l', 'l': 'way', 'r': 'yay', 'answer': 'a' },
            { 'box': 'r', 'r': 'wsy', 'l': 'ysn', 'answer': 'n' },
            { 'box': 'l', 'r': 'wsn', 'l': 'ysy', 'answer': 's' },
            { 'box': 'r', 'l': 'way', 'r': 'yay', 'answer': 'y' },
            { 'box': 'l', 'l': 'wan', 'r': 'yan', 'answer': 'a' },
            { 'box': 'r', 'r': 'wsn', 'l': 'yay', 'answer': 'y' },
            { 'box': 'l', 'l': 'wsy', 'r': 'ysn', 'answer': 's' },
            { 'box': 'r', 'l': 'wsn', 'r': 'ysy', 'answer': 'y' },
            { 'box': 'l', 'r': 'way', 'l': 'yay', 'answer': 'a' },
            { 'box': 'l', 'l': 'way', 'r': 'ysn', 'answer': 'a' },
            { 'box': 'l', 'l': 'way', 'r': 'ysy', 'answer': 'a' },
            { 'box': 'r', 'r': 'wan', 'l': 'yay', 'answer': 'y' },
            { 'box': 'r', 'r': 'wsn', 'l': 'yan', 'answer': 'n' },
            { 'box': 'l', 'l': 'way', 'r': 'yan', 'answer': 'a' },
            { 'box': 'l', 'l': 'wsy', 'r': 'yay', 'answer': 's' },
            { 'box': 'r', 'r': 'way', 'l': 'ysy', 'answer': 'y' },
            { 'box': 'r', 'l': 'wsy', 'r': 'ysy', 'answer': 'y' },
            { 'box': 'r', 'l': 'wan', 'r': 'ysy', 'answer': 'y' },
            { 'box': 'l', 'r': 'wsn', 'l': 'yay', 'answer': 's' },
            { 'box': 'l', 'r': 'way', 'l': 'yan', 'answer': 'a' },
            { 'box': 'r', 'l': 'wsn', 'r': 'ysn', 'answer': 'n' },
            { 'box': 'l', 'r': 'way', 'l': 'ysn', 'answer': 'a' },
            { 'box': 'r', 'l': 'way', 'r': 'ysn', 'answer': 'n' },
            { 'box': 'l', 'l': 'wan', 'r': 'yay', 'answer': 'a' },
            { 'box': 'r', 'r': 'wsy', 'l': 'yay', 'answer': 'y' },
            { 'box': 'r', 'r': 'wan', 'l': 'ysy', 'answer': 'y' },
            { 'box': 'r', 'l': 'wsy', 'r': 'yay', 'answer': 'y' },
            { 'box': 'l', 'r': 'wan', 'l': 'yan', 'answer': 'a' },
            { 'box': 'l', 'r': 'wan', 'l': 'ysy', 'answer': 'a' },
            { 'box': 'r', 'l': 'wsy', 'r': 'yan', 'answer': 'n' },
            { 'box': 'r', 'r': 'wan', 'l': 'yan', 'answer': 'n' },
            { 'box': 'r', 'l': 'wan', 'r': 'yan', 'answer': 'n' },
            { 'box': 'l', 'r': 'wsn', 'l': 'yan', 'answer': 's' },
            { 'box': 'l', 'r': 'wsy', 'l': 'yay', 'answer': 's' },
            { 'box': 'r', 'l': 'wsn', 'r': 'yan', 'answer': 'n' },
            { 'box': 'l', 'l': 'wsn', 'r': 'yan', 'answer': 's' }
        ];
        this._answer = new Array();
        this._rt_data = new Array();
        this._direction_text_1 = null;
        this._direction_text_2 = null;
        this._answer_text_1 = null;
        this._answer_text_2 = null;

        this._catCode = ["wsy.png",
            "wsn.png",
            "way.png",
            "wan.png",
            "ysy.png",
            "ysn.png",
            "yay.png",
            "yan.png"];
        var tm = new Object();
        this._catCode.forEach((code) => {
            var f = code.split(".");
            tm[f[0]] = document.createElement('img');
            tm[f[0]].src = "img/" + code;
        });
        this._catImg = tm;

        for (const [key, value] of Object.entries(this._cat)) {
            value.lcat.id = key + "-lcat-img";
            value.rcat.id = key + "-rcat-img";
            this['_' + key].append(value.lcat, value.rcat);
            this._catBox.appendChild(this['_' + key]);
        }
        this._test_time = null;
        this._test_wait_time = null;
        this._view_time = null;
    }

    //createElement
    help(el, option = null, direction = null) {
        let item = null;
        if (el == "button") {
            item = document.createElement("button");
            item.type = "button";
            if (direction != null) {
                item.className = "ga-direction-Btn";
            } else {
                item.className = "ga-Btn";
            }
        } else {
            item = document.createElement(el);
        }

        if (option != null) {
            for (const [key, value] of Object.entries(option)) {
                item[key] = value;
            }
        }
        return item;
    }
    // 키보드 버튼 만들기
    keyboard_btn(upBtn, leftBtn, downBtn, rightBtn, location = null) {

        document.onkeydown = function (e) {
            const keyCode = e.keyCode;

            if (keyCode == 37) {
                leftBtn.className = 'ga-direction-click-Btn';
            }
            if (keyCode == 38) {
                upBtn.className = 'ga-direction-click-Btn';
            }
            if (keyCode == 39) {
                rightBtn.className = 'ga-direction-click-Btn';
            }
            if (keyCode == 40) {
                downBtn.className = 'ga-direction-click-Btn';
            }
        }
        document.onkeyup = function (e) {
            const keyCode = e.keyCode;
            if (keyCode == 37) {
                leftBtn.className = 'ga-direction-Btn';
            }
            if (keyCode == 38) {
                upBtn.className = 'ga-direction-Btn';
            }
            if (keyCode == 39) {
                rightBtn.className = 'ga-direction-Btn';
            }
            if (keyCode == 40) {
                downBtn.className = 'ga-direction-Btn';
            }
        }

        var btnTable = ga.help('table');
        btnTable.classList.add('btnTable');

        var tbody = ga.help('tbody');
        var tr_1 = ga.help('tr');
        var tr_2 = ga.help('tr');

        var td_1 = ga.help('td');
        var td_2 = ga.help('td');
        var td_3 = ga.help('td');

        var td_4 = ga.help('td');
        var td_5 = ga.help('td');
        var td_6 = ga.help('td');

        td_2.appendChild(upBtn);
        td_4.appendChild(leftBtn);
        td_5.appendChild(downBtn);
        td_6.appendChild(rightBtn);

        tr_1.append(td_1, td_2, td_3);
        tr_2.append(td_4, td_5, td_6);
        tbody.append(tr_1, tr_2);
        btnTable.appendChild(tbody);

        if (location != null) {
            location.appendChild(btnTable);
        } else {
            this._mainBox.appendChild(btnTable);
        }
    }
    // 다 같은 키보드의 키를 제공하는 것이 아닌 8개를 제공하여 복잡성 증가
    keyboard_set(id, location = null) {

        switch (id % 8) {
            case 0:
                var upBtn = this.help("button", { innerText: "↑ 행복", value: "s", id: "upBtn" }, 'direction');
                var leftBtn = this.help("button", { innerText: "← 수염無", value: "n", id: "leftBtn" }, 'direction');
                var downBtn = this.help("button", { innerText: "↓ 화남", value: "a", id: "downBtn" }, 'direction');
                var rightBtn = this.help("button", { innerText: "→ 수염有", value: "y", id: "rightBtn" }, 'direction');
                this._direction_text_1 = "↑키(행복)와 ↓키(화남)";
                this._direction_text_2 = "→키(수염 있음)와 ←키(수염 없음)";
                this._answer_text_1 = "↓키(화남)";
                this._answer_text_2 = "→키(수염 있음)";
                ga.keyboard_btn(upBtn, leftBtn, downBtn, rightBtn, location);
                break;
            case 1:
                var upBtn = this.help("button", { innerText: "↑ 행복", value: "s", id: "upBtn" }, 'direction');
                var leftBtn = this.help("button", { innerText: "← 수염有", value: "y", id: "leftBtn" }, 'direction');
                var downBtn = this.help("button", { innerText: "↓ 화남", value: "a", id: "downBtn" }, 'direction');
                var rightBtn = this.help("button", { innerText: "→ 수염無", value: "n", id: "rightBtn" }, 'direction');
                this._direction_text_1 = "↑키(행복)와 ↓키(화남)";
                this._direction_text_2 = "←키(수염 있음)와 →키(수염 없음)";
                this._answer_text_1 = "↓키(화남)";
                this._answer_text_2 = "←키(수염 있음)";
                ga.keyboard_btn(upBtn, leftBtn, downBtn, rightBtn, location);

                break;
            case 2:
                var upBtn = this.help("button", { innerText: "↑ 화남", value: "a", id: "upBtn" }, 'direction');
                var leftBtn = this.help("button", { innerText: "← 수염無", value: "n", id: "leftBtn" }, 'direction');
                var downBtn = this.help("button", { innerText: "↓ 행복", value: "s", id: "downBtn" }, 'direction');
                var rightBtn = this.help("button", { innerText: "→ 수염有", value: "y", id: "rightBtn" }, 'direction');
                this._direction_text_1 = "↓키(행복)와 ↑키(화남)";
                this._direction_text_2 = "→키(수염 있음)와 ←키(수염 없음)";
                this._answer_text_1 = "↑키(화남)";
                this._answer_text_2 = "→키(수염 있음)";
                ga.keyboard_btn(upBtn, leftBtn, downBtn, rightBtn, location);
                break;
            case 3:
                var upBtn = this.help("button", { innerText: "↑ 화남", value: "a", id: "upBtn" }, 'direction');
                var leftBtn = this.help("button", { innerText: "← 수염有", value: "y", id: "leftBtn" }, 'direction');
                var downBtn = this.help("button", { innerText: "↓ 행복", value: "s", id: "downBtn" }, 'direction');
                var rightBtn = this.help("button", { innerText: "→ 수염無", value: "n", id: "rightBtn" }, 'direction');
                this._direction_text_1 = "↓키(행복)와 ↑키(화남)";
                this._direction_text_2 = "←키(수염 있음)와 →키(수염 없음)";
                this._answer_text_1 = "↑키(화남)";
                this._answer_text_2 = "←키(수염 있음)";
                ga.keyboard_btn(upBtn, leftBtn, downBtn, rightBtn, location);
                break;
            case 4:
                var upBtn = this.help("button", { innerText: "↑ 수염有", value: "y", id: "upBtn" }, 'direction');
                var leftBtn = this.help("button", { innerText: "← 화남", value: "a", id: "leftBtn" }, 'direction');
                var downBtn = this.help("button", { innerText: "↓ 수염無", value: "n", id: "downBtn" }, 'direction');
                var rightBtn = this.help("button", { innerText: "→ 행복", value: "s", id: "rightBtn" }, 'direction');
                this._direction_text_1 = "→키(행복)와 ←키(화남)";
                this._direction_text_2 = "↑키(수염 있음)와 ↓키(수염 없음)";
                this._answer_text_1 = "←키(화남)";
                this._answer_text_2 = "↑키(수염 있음)";
                ga.keyboard_btn(upBtn, leftBtn, downBtn, rightBtn, location);
                break;
            case 5:
                var upBtn = this.help("button", { innerText: "↑ 수염有", value: "y", id: "upBtn" }, 'direction');
                var leftBtn = this.help("button", { innerText: "← 행복", value: "s", id: "leftBtn" }, 'direction');
                var downBtn = this.help("button", { innerText: "↓ 수염無", value: "n", id: "downBtn" }, 'direction');
                var rightBtn = this.help("button", { innerText: "→ 화남", value: "a", id: "rightBtn" }, 'direction');
                this._direction_text_1 = "←키(행복)와 →키(화남)";
                this._direction_text_2 = "↑키(수염 있음)와 ↓키(수염 없음)";
                this._answer_text_1 = "→키(화남)";
                this._answer_text_2 = "↑키(수염 있음)";
                ga.keyboard_btn(upBtn, leftBtn, downBtn, rightBtn, location);
                break;
            case 6:
                var upBtn = this.help("button", { innerText: "↑ 수염無", value: "n", id: "upBtn" }, 'direction');
                var leftBtn = this.help("button", { innerText: "← 화남", value: "a", id: "leftBtn" }, 'direction');
                var downBtn = this.help("button", { innerText: "↓ 수염有", value: "y", id: "downBtn" }, 'direction');
                var rightBtn = this.help("button", { innerText: "→ 행복", value: "s", id: "rightBtn" }, 'direction');
                this._direction_text_1 = "→키(행복)와 ←키(화남)";
                this._direction_text_2 = "↓키(수염 있음)와 ↑키(수염 없음)";
                this._answer_text_1 = "←키(화남)";
                this._answer_text_2 = "↓키(수염 있음)";
                ga.keyboard_btn(upBtn, leftBtn, downBtn, rightBtn, location);
                break;
            case 7:
                var upBtn = this.help("button", { innerText: "↑ 수염無", value: "n", id: "upBtn" }, 'direction');
                var leftBtn = this.help("button", { innerText: "← 행복", value: "s", id: "leftBtn" }, 'direction');
                var downBtn = this.help("button", { innerText: "↓ 수염有", value: "y", id: "downBtn" }, 'direction');
                var rightBtn = this.help("button", { innerText: "→ 화남", value: "a", id: "rightBtn" }, 'direction');
                this._direction_text_1 = "←키(행복)와 →키(화남)";
                this._direction_text_2 = "↓키(수염 있음)와 ↑키(수염 없음)";
                this._answer_text_1 = "→키(화남)";
                this._answer_text_2 = "↓키(수염 있음)";
                ga.keyboard_btn(upBtn, leftBtn, downBtn, rightBtn, location);
                break;
        }
    }
    //초기 화면. 게임 설명
    tutorial() {

        this._mainBox.innerHTML = "";
        var timeleft = 599;

        function convertSeconds(s) {
            var min = Math.floor(s / 60);
            var sec = s % 60;
            return ("0" + min).slice(-2) + ':' + ("0" + sec).slice(-2);
        }
        // this._timer = setInterval(function () {
        //     if (timeleft <= 0) {
        //         ga.start_count();
        //     } else {
        //         document.getElementById("countdown-first").innerText = "검사 시작까지 " + convertSeconds(timeleft) + " 남았습니다.";
        //     }
        //     timeleft--;
        // }, 1000);

        var div1 = this.help("div", { innerHTML: "<h2 style=color:#0070C0;>고양이를 찾아라</h2>" });
        div1.classList.add('top');
        this._body.appendChild(div1);

        var table = this.help('table', { id: "info_table" });
        var tbody = this.help('tbody');

        ga.keyboard_set(ga._user_id, this._btnLocation);

        var tr = this.help('tr');
        var td_1 = this.help('td', {
            innerHTML: `<p style="font-weight:bold;">1.이 검사에서는 하얀색 고양이의 표정과 노란색 고양이의 수염 유무를 빠르고 정확하게 판별해야 합니다.</p>
            <p style=font-weight:bold;>
               2.<span style="color:blue;">키보드</span>를 이용해 응답하며, 조작법은 오른쪽 그림을 참고하십시오.<br>해당 키를 지금 눌러보십시오. 오른쪽 키보드 그림의 색이 변하면 정상 작동하는 것입니다.</p>
            <p>3.화면에 좌우로 두 개의 박스가 제시됩니다. 두 개의 박스 중 한 곳에 하얀색 고양이와 노란색 고양이 얼굴이 동시에 나타납니다.</p>
            <p>4.고양이들의 얼굴이 왼쪽 박스에 제시되는 경우, 하얀색 고양이의 표정을 `+ this._direction_text_1 + `로 판별 하십시오. 이 때 노란색 고양이의 수염 유무는 판별하지 않습니다(키보드 조작법은 검사 진행 중 화면 하단에서도 확인 가능).</p><br><p>※ 오른쪽 검사 화면 예시에서는 <span style=color:blue;>` + this._answer_text_1 + `</span>가 정답입니다.</p>
            <p>5.고양이들의 얼굴이 오른쪽 박스에 제시되는 경우, 노란색 고양이의 수염 유무를 `+ this._direction_text_2 + `로 판별하십시오. 이 때 하얀색 고양이의 표정은 판별하지 않습니다(키보드 조작법은 게임 진행 중 화면 하단에서도 확인 가능).</p><br><p>※ 오른쪽 검사 화면 예시에서는 <span style=color:blue;>` + this._answer_text_2 + `</span>가 정답입니다.</p>
            <p>6.화면마다 제한시간이 있으며, 제한시간 내 응답을 하지 못할 경우 오답 처리됩니다. 따라서 고양이들이 제시되면 바로 응답하십시오.</p>
            <p style=font-weight:bold;>7.연습검사를 한 뒤 실전검사가 이어지며 연습검사의 응답은 결과에 반영되지 않습니다.</p>
            `
        });
        td_1.style.width = "50%";

        var td_2 = this.help('td');
        td_2.appendChild(this._btnLocation);

        var imgs = this.help('div', {
            innerHTML: `<img class="infoImg" src=./img/directionImg/direction_` + this._user_id + `_first.jpg></img>
        <img class="infoImg" src=./img/directionImg/direction_`+ this._user_id + `_second.jpg></img>
        `});
        td_2.appendChild(this._btnLocation, imgs);
        td_2.appendChild(imgs);
        tr.append(td_1, td_2);

        tbody.append(tr);
        table.appendChild(tbody);
        this._mainBox.appendChild(table);

        var nextBtn = this.help("button", { id: 'countdown-first', innerText: '검사 시작' });
        nextBtn.addEventListener("click", () => {
            ga.start_count();
        });
        this._mainBox.appendChild(nextBtn);

        var bottom_text = this.help('div', { innerHTML: '<p style="color:red">※ 검사에서는 규정노트와 규정필기도구를 사용할 수 없습니다. 사용 시 부정행위로 간주되어 이에 상응한 불이익을 받게 됩니다.</p>' });
        bottom_text.classList.add('bottom_text');
        this._mainBox.appendChild(bottom_text);

        this._body.appendChild(this._mainBox);
    }
    // 게임 시작 전 카운트다운
    start_count(a = false) {

        this._mainBox.style.height = '100%';

        document.getElementsByClassName('top')[0].innerText = null;
        this._mainBox.innerHTML = "";
        clearInterval(this._timer);

        var count = this.help('div', { id: 'countdown' });

        var timeleft = 4;
        this._mainBox.appendChild(count);
        document.getElementById("countdown").innerHTML = "<p>" + (timeleft + 1) + "</p>";

        var timer = setInterval(function () {
            if (timeleft <= 0) {
                clearInterval(timer);
                if (a == true) {
                    ga.real_play();
                } else {
                    ga.ex_play();
                }
            } else {
                document.getElementById("countdown").innerHTML = "<p>" + timeleft + "</p>";
                timeleft--;
            }
        }, 1000);
    }
    // 연습게임 시작
    ex_play(a = false) {

        this._test_time = setTimeout(function () {
            ga.ex_result();
        }, 20000);

        if (!a) {
            this._mainBox.innerHTML = "";
            this._catBox.append(this._lbox, this._rbox);
            this._mainBox.appendChild(this._catBox);

            ga.keyboard_set(this._user_id);
        }
        document.querySelectorAll(".subcatbox > div").forEach((t) => {
            t.innerHTML = "";
        });

        if (ga._game_step >= 10) {
            clearTimeout(this._test_time);
            ga.real_game_rule();
        } else {
            this._cat[this.exStep[this._game_step].box + "box"]['lcat'].appendChild(this._catImg[this.exStep[this._game_step].l]);
            this._cat[this.exStep[this._game_step].box + "box"]['rcat'].appendChild(this._catImg[this.exStep[this._game_step].r]);
        }

        //키보드 방향키 이벤트
        document.onkeyup = function (e) {
            const keyCode = e.keyCode;
            if (ga._game_step < 10) {
                if (keyCode == 37 || keyCode == 38 || keyCode == 39 || keyCode == 40) {
                    ga.ex_result(keyCode);
                }
            }
        }
    }
    //연습게임 입력 결과 페이지
    ex_result(keycode) {

        document.onkeyup = null;
        clearTimeout(this._test_time);
        document.getElementById('lbox-lcat-img').innerHTML = null;
        document.getElementById('lbox-rcat-img').innerHTML = null;
        document.getElementById('rbox-lcat-img').innerHTML = null;
        document.getElementById('rbox-rcat-img').innerHTML = null;

        var ex_answer = null;

        switch (keycode) {
            case 37:
                document.getElementById('leftBtn').className = 'ga-direction-Btn';
                ex_answer = document.getElementById('leftBtn').value;
                break;
            case 38:
                document.getElementById('upBtn').className = 'ga-direction-Btn';
                ex_answer = document.getElementById('upBtn').value;
                break;
            case 39:
                document.getElementById('rightBtn').className = 'ga-direction-Btn';
                ex_answer = document.getElementById('rightBtn').value;
                break;
            case 40:
                document.getElementById('downBtn').className = 'ga-direction-Btn';
                ex_answer = document.getElementById('downBtn').value;
                break;
        }

        var ex_result = this.help('div', { id: 'ex_result' });
        this._mainBox.appendChild(ex_result);

        if (ga.exStep[ga._game_step].answer == ex_answer) {
            var result_text = ga.help('div', { id: "input-result", innerHTML: "<p>O</p>" });
        } else {
            var result_text = ga.help('div', { id: "input-result", innerHTML: "<p>X</p>" });
        }
        document.getElementById('ex_result').appendChild(result_text);

        ga._game_step++;

        this._test_wait_time = setTimeout(function () {
            ga.ex_play();
        }, 500);
    }
    //실전 게임 규칙
    real_game_rule() {
        this._mainBox.innerHTML = "";
        this._test_time = null;
        this._test_wait_time = null;
        var timeleft = 599;

        function convertSeconds(s) {
            var min = Math.floor(s / 60);
            var sec = s % 60;
            return ("0" + min).slice(-2) + ':' + ("0" + sec).slice(-2);
        }

        var div1 = this.help('div', {
            innerHTML: "<p>연습검사가 종료되었습니다.</p><p style=color:blue;>연습검사와 달리 실전검사에서는 응답에 대한 피드백을 제공하지 않습니다.</p><br>"
                + "<p>1. 하얀색 고양이와 노란색 고양이는 항상 동시에 나타납니다.</p><p>2. 고양이들의 얼굴이 왼쪽 박스에 제시되는 경우, 하얀색 고양이의 표정을 판별하십시오.</p>"
                + "<p>3. 고양이들의 얼굴이 오른쪽 박스에 제시되는 경우, 노란색 고양이의 수염 유무를 판별하십시오.</p><p>4. 제한시간 내 최대한 빨리 응답하십시오.</p>"
        });
        div1.style.marginLeft = '250px';
        div1.style.marginTop = '50px';
        this._mainBox.appendChild(div1);

        var nextBtn = this.help("button", { id: 'countdown-real', innerText: '실전 게임 시작' });
        nextBtn.addEventListener("click", () => {
            this._game_step = 0;
            ga.start_count(true);
        });
        this._mainBox.appendChild(nextBtn);
    }
    //실전 게임 페이지
    real_play(a = false) {
        this._test_time = setTimeout(function () {
            ga.real_result();
        }, 2000);

        if (!a) {
            this._mainBox.innerHTML = "";
            this._catBox.append(this._lbox, this._rbox);
            this._mainBox.appendChild(this._catBox);
            ga.keyboard_set(this._user_id);
        }
        document.querySelectorAll(".subcatbox > div").forEach((t) => {
            t.innerHTML = "";
        });

        if (ga._game_step >= 64) {
            clearTimeout(this._test_time);
            ga.quit_game();
        } else {
            this._cat[this.realStep[this._game_step].box + "box"]['lcat'].appendChild(this._catImg[this.realStep[this._game_step].l]);
            this._cat[this.realStep[this._game_step].box + "box"]['rcat'].appendChild(this._catImg[this.realStep[this._game_step].r]);

            let view_date = new Date();
            this._view_time = view_date.getTime();
        }
        //키보드 방향키 이벤트
        document.onkeyup = function (e) {
            const keyCode = e.keyCode;
            if (ga._game_step < 64) {
                if (keyCode == 37 || keyCode == 38 || keyCode == 39 || keyCode == 40) {
                    ga.real_result(keyCode);
                }
            }
        }
    }
    //실전 게임 결과 처리 및 빈 화면
    real_result(keycode) {

        document.onkeyup = null;
        clearTimeout(this._test_time);

        let answer_time = new Date();
        let rt = answer_time.getTime() - this._view_time;

        var real_answer = null;
        switch (keycode) {
            case 37:
                document.getElementById('leftBtn').className = 'ga-direction-Btn';
                real_answer = document.getElementById('leftBtn').value;
                break;
            case 38:
                document.getElementById('upBtn').className = 'ga-direction-Btn';
                real_answer = document.getElementById('upBtn').value;
                break;
            case 39:
                document.getElementById('rightBtn').className = 'ga-direction-Btn';
                real_answer = document.getElementById('rightBtn').value;
                break;
            case 40:
                document.getElementById('downBtn').className = 'ga-direction-Btn';
                real_answer = document.getElementById('downBtn').value;
                break;
        }
        document.getElementById('lbox-lcat-img').innerHTML = null;
        document.getElementById('lbox-rcat-img').innerHTML = null;
        document.getElementById('rbox-lcat-img').innerHTML = null;
        document.getElementById('rbox-rcat-img').innerHTML = null;

        // 정답 여부 체크
        if (ga.realStep[ga._game_step].answer == real_answer) {
            this._answer[ga._game_step] = 'O';
            this._rt_data[ga._game_step] = rt;
        } else {
            this._answer[ga._game_step] = 'X';
            this._rt_data[ga._game_step] = rt;
        }

        //다음 고양이 이미지 띄우기 위한 더하기
        ga._game_step++;

        this._test_wait_time = setTimeout(function () {
            ga.real_play();
        }, 500);
    }
    // 게임 종료
    quit_game() {
        this._mainBox.innerHTML = "";

        //결과 및 rt 데이터
        console.log(this._answer);
        console.log(this._rt_data);

        var score = 0;
        for (var i = 0; i < 64; i++) {
            if (this._answer[i] == 'O') {
                score++;
            }
        }

        var div = this.help('div', { id: "commit-text", innerHTML: "<p>검사가 종료되었습니다.</p> 점수 : " + score + "점" });
        this._mainBox.appendChild(div);
    }
}
let ga = null;
window.addEventListener("load", () => {
    ga = new Ga(8);
    ga.tutorial();
});